<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private $merchantID;
    private $hashKey;
    private $hashIV;
    private $shipmentMerchantID;
    private $shipmentHashKey;
    private $shipmentHashIV;

    public function __construct()
    {
        $this->merchantID = config('config.app_run') === 'production' ? config('config.ecpay_merchant_id') : config('config.ecpay_stage_merchant_id');
        $this->hashKey = config('config.app_run') === 'production' ? config('config.ecpay_hash_key') : config('config.ecpay_stage_hash_key');
        $this->hashIV = config('config.app_run') === 'production' ? config('config.ecpay_hash_iv') : config('config.ecpay_stage_hash_iv');

        $this->shipmentMerchantID = config('config.app_run') === 'production' ? config('config.ecpay_shipment_merchant_id') : config('config.ecpay_stage_shipment_merchant_id');
        $this->shipmentHashKey = config('config.app_run') === 'production' ? config('config.ecpay_shipment_hash_key') : config('config.ecpay_stage_shipment_hash_key');
        $this->shipmentHashIV = config('config.app_run') === 'production' ? config('config.ecpay_shipment_hash_iv') : config('config.ecpay_stage_shipment_hash_iv');
    }

    public function index()
    {
        // 使用 Order 模型的 scope 方法來處理運送方式文字
        $orders = Order::with(['member', 'items.product', 'items.spec', 'items.productImage'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                $order['shipment_method_text'] = match ($order->shipment_method) {
                    'family_b2c' => '全家店到店',
                    '711_b2c' => '7-11 店到店',
                    'mail_send' => '郵寄',
                    default => ''
                };
                $order['payment_method_text'] = match ($order->payment_method) {
                    'credit' => '信用卡',
                    'atm' => 'ATM 虛擬帳號',
                    default => ''
                };
                return $order;
            });

        return view('admin.orders.index_order', compact('orders'));
    }

    public function show(Order $order)
    {

        $order->load(['member', 'items.product', 'items.spec', 'items.productImage']);

        switch ($order->shipment_method) {
            case 'family_b2c':
                $shipmentMethodName = '全家店到店';
                break;

            case '711_b2c':
                $shipmentMethodName = '7-11 店到店';
                break;

            default:
                $shipmentMethodName = '郵寄';
                break;
        }


        if (!empty($order->logistics_id)) {
            // try {
            // 準備請求參數
            $params = [
                'MerchantID' => $this->shipmentMerchantID,
                'AllPayLogisticsID' => $order->logistics_id,
                'TimeStamp' => time(),
            ];

            // 加入檢查碼
            $params['CheckMacValue'] = $this->generateEcpayCheckMacValue($params);

            $api_url = config('config.app_run') === 'production'
                ? 'https://logistics.ecpay.com.tw/Helper/QueryLogisticsTradeInfo/V4'
                : 'https://logistics-stage.ecpay.com.tw/Helper/QueryLogisticsTradeInfo/V4';

            // 發送請求到綠界 API
            $response = Http::asForm()->post($api_url, $params);

            if ($response->status() == 200) {
                // 解析回應
                parse_str($response->body(), $result);

                $this->updateOrderShippingStatus($order, $result);
            } else {
                Log::error('綠界物流查詢失敗', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }

        return view(
            'admin.orders.show',
            compact('order', 'shipmentMethodName')
        );
    }

    /**
     * 產生綠界檢查碼
     */
    private function generateEcpayCheckMacValue($params)
    {
        // 移除 CheckMacValue
        unset($params['CheckMacValue']);

        // 參數依照 A-Z 排序
        ksort($params);

        // 組合字串
        $str = "HashKey=" . $this->shipmentHashKey;
        foreach ($params as $key => $value) {
            $str .= "&{$key}={$value}";
        }
        $str .= "&HashIV=" . $this->shipmentHashIV;

        // URL encode
        $str = urlencode($str);

        // 轉小寫
        $str = strtolower($str);

        // MD5 加密
        return strtoupper(md5($str));
    }

    private function updateOrderShippingStatus($order, $result)
    {

        // 參考綠界物流狀態代碼：https://developers.ecpay.com.tw/?p=7418
        switch ($result['LogisticsStatus']) {
            case '300':  // 訂單建立
                $order->shipping_status = Order::SHIPPING_STATUS_PROCESSING;
                break;

            case '2063': // 退貨完成
                $order->shipping_status = Order::SHIPPING_STATUS_RETURNED;
                break;

            case '3001': // 貨物已到達門市
            case '3002': // 貨物已到達門市（退貨）
                $order->shipping_status = Order::SHIPPING_STATUS_ARRIVED_STORE;
                break;

            case '2030': // 貨物已送達
            case '3003': // 貨物已取貨
                $order->shipping_status = Order::SHIPPING_STATUS_DELIVERED;
                break;

            case '2067': // 門市關轉
            case '2068': // 門市倒閉
                $order->shipping_status = Order::SHIPPING_STATUS_STORE_CLOSED;
                // 可以在這裡添加通知管理員的邏輯
                break;

            case '2065': // 退貨中
                $order->shipping_status = Order::SHIPPING_STATUS_RETURNING;
                break;

            case '2066': // 拒收
                $order->shipping_status = Order::SHIPPING_STATUS_REJECTED;
                break;

            default:
                // 記錄未處理的狀態碼
                Log::info('未處理的物流狀態碼：' . $logisticsStatus, [
                    'order_id' => $order->id,
                    'logistics_id' => $order->logistics_id
                ]);
                return;
        }

        $order->shipment_no = $result['ShipmentNo'] ?? '';
        $order->save();
    }

    // 更新運送狀態
    public function updateShippingStatus(Request $request)
    {

        $order = Order::findOrFail($request->order_id);
        $order->update([
            'shipping_status' => $request->status
        ]);

        if ($order->payment_status == 'paid' && $order->shipping_status == 'shipped') {
            $order->update([
                'status' => Order::STATUS_COMPLETED
            ]);
        } else {
            $order->update([
                'status' => Order::STATUS_PROCESSING
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => '運送狀態更新成功'
        ]);
    }

    /**
     * 更新訂單狀態
     */
    public function updateStatus(Request $request, Order $order)
    {

        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,shipping'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // 可以在這裡添加其他邏輯，比如發送通知等

        return redirect()->route('admin.orders.show', $order)->with('success', '訂單狀態更新成功');
    }


    public function updateInvoiceNumber(Request $request)
    {
        $order = Order::where('order_number', $request->order_number)->first();
        $order->update([
            'issued_invoice_number' => $request->invoice_number,
            'issued_invoice_date' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => '發票號碼更新成功'
        ]);
    }
}
