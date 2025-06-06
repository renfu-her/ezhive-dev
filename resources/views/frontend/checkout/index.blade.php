@extends('frontend.layouts.app')

@push('app-content')
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12 px-md-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent justify-content-md-end">
                            <li class="breadcrumb-item"><a href="./">首頁</a></li>
                            <li class="breadcrumb-item active" aria-current="page">購物車</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </header>


    <!-- Page Content Start -->
    <article class="page-wrapper my-3">
        <div class="container">
            <div class="page-title">
                <h2 class="text-black text-center font-weight-bold mb-0" data-aos="zoom-in-up">我要結帳</h2>
                <h4 class="text-center text-gold mb-4" data-aos="zoom-in-up" data-aos-delay="150">Checkout</h4>
            </div>

            <section class="mx-md-5 my-md-2 m-sm-1" data-aos="fade-up" data-aos-delay="450">
                <div class="shopping-cart">
                    <div class="table-responsive">
                        <table class="table text-center cart-items">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">圖片</th>
                                    <th scope="col">商品名稱</th>
                                    <th scope="col">優惠價</th>
                                    <th scope="col">數量</th>
                                    <th scope="col">小計</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($cart as $key => $item)
                                    <tr class="cart-item">
                                        <td scope="row"></td>
                                        <td class="thumb-img align-middle">
                                            <img class="item-img" src="{{ $item['primary_image'] }}" width="106px">
                                        </td>
                                        <td class="align-middle border-sm-top">
                                            <span class="cart-tag d-block d-sm-none text-muted" disable>規格</span>
                                            <div class="product-details text-md-left text-sm-center">
                                                <p class="mb-0">{{ $item['product_name'] }}</p>
                                                <p class="mb-0">規格：{{ $item['spec_name'] }}</p>
                                            </div>
                                        </td>
                                        <td class="align-middle border-sm-top">
                                            <span class="cart-tag d-block d-sm-none text-muted" disable>單價</span>
                                            <p class="mb-0">NT${{ number_format($item['price']) }}</p>
                                        </td>
                                        <td class="quantity align-middle border-sm-top">
                                            <span class="cart-tag d-block d-sm-none text-muted" disable>數量</span>
                                            <p class="mb-0">{{ $item['quantity'] }}</p>
                                        </td>
                                        <td class="total align-middle border-sm-top">
                                            <span class="cart-tag d-block d-sm-none text-muted" disable>小計</span>
                                            <p class="text-danger mb-0 money">
                                                NT${{ number_format($item['price'] * $item['quantity']) }}</p>
                                        </td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">購物車是空的</td>
                                    </tr>
                                @endforelse

                                <!-- 運費列 -->
                                <tr class="shipping-fee" style="display: none;">
                                    <td colspan="4"></td>
                                    <td class="text-center align-middle">運費</td>
                                    <td class="align-middle">
                                        <p class="text-danger mb-0 money">NT$0</p>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12 my-3">
                        <div class="row">
                            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                                <h2 class="text-black mb-0">
                                    總計
                                    <span class="pl-3">NT${{ number_format($total + $shippingFee) }}</span>
                                </h2>
                            </div>
                        </div>
                    </div>

                    <form class="w-100" method="post" action="{{ route('payment.process') }}"
                        enctype="multipart/form-data">
                        <div class="col-sm-12 border rounded mt-3">
                            <div class="form-group row m-3">
                                <legend
                                    class="col-form-label col-sm-3 text-md-right text-sm-left text-danger align-self-center">
                                    <h3 class="mb-0">付款方式</h3>
                                </legend>
                                <div class="col-sm-9 align-self-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment" id="inlineRadio1"
                                            value="Credit" checked>
                                        <label class="form-check-label" for="inlineRadio1">線上刷卡</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment" id="inlineRadio2"
                                            value="ATM">
                                        <label class="form-check-label" for="inlineRadio2">ATM 虛擬帳號繳費</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment" id="inlineRadio3"
                                            value="COD">
                                        <label class="form-check-label" for="inlineRadio3">貨到付款 (超商取貨付款)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 border rounded mt-3">

                            <div class="form-group row m-3">
                                <div class="col-md-3 col-sm-12 align-self-center">
                                    <h3 class="text-md-right text-sm-left text-danger mb-0">收貨人資訊</h3>
                                </div>
                                <div class="col-md-9 col-sm-12 align-self-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck1" name="sameAsMember">
                                        <label class="form-check-label" for="gridCheck1">同訂購人資料</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-3">
                                <div class="col-md-9 offset-md-3 col-sm-12">
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left align-self-center"
                                            for="name">
                                            <span class="text-danger">*</span>收貨姓名
                                        </label>
                                        <div class="col-sm-6 align-self-center">
                                            <input ref="test" type="text" class="form-control" id="name"
                                                placeholder="請填真實姓名" required name="username">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left align-self-center"><span
                                                class="text-danger">*</span>性別</label>
                                        <div class="col-sm-6 align-self-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    id="male" value="1" checked>
                                                <label class="form-check-label" for="male">男</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    id="female" value="2">
                                                <label class="form-check-label" for="female">女</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left align-self-center"
                                            for="phone"><span class="text-danger">*</span>聯絡電話</label>
                                        <div class="col-sm-6 align-self-center">
                                            <input type="text" class="form-control" id="phone" placeholder=""
                                                required name="phone" ref="">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left align-self-center"
                                            for="shipment">
                                            <span class="text-danger">*</span>寄送方式
                                        </label>
                                        <div class="col-sm-6 align-self-center">
                                            <select id="shipment" class="form-control" name="shipment" required>
                                                <option value="">請選擇</option>
                                                @foreach ($shippingSettings as $key => $shipping)
                                                    <option value="{{ $key }}" data-fee="{{ $shipping['fee'] }}">
                                                        {{ $shipping['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-primary mt-2 map-btn"
                                                style="display: none;">
                                                <i class="fas fa-map-marker-alt"></i> 選擇門市
                                            </button>
                                            <input type="hidden" name="store_id" value="">
                                            <input type="hidden" name="store_name" value="">
                                            <input type="hidden" name="store_address" value="">
                                            <input type="hidden" name="store_telephone" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row addr" style="display:none;">
                                        <label class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left"><span
                                                class="text-danger">*</span>寄送地址</label>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div id="twzipcode" class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6 mb-2">
                                                            <select data-role="county" class="form-control"
                                                                name="county"></select>
                                                        </div>
                                                        <div class="col-12 col-md-6 mb-2">
                                                            <select data-role="district" class="form-control"
                                                                name="district"></select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" data-role="zipcode" />
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" placeholder="請入詳細地址"
                                                        value="{{ Auth::guard('member')->user()->address }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left"
                                            for="note">訂購備註</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" rows="5" id="note" name="info"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->


                        <div class="col-sm-12 border rounded mt-3">

                            <div class="form-group row m-3">
                                <legend
                                    class="col-form-label col-sm-3 text-md-right text-sm-left text-danger align-self-center">
                                    <h3 class="mb-0">開立發票</h3>
                                </legend>
                                <div class="col-sm-9 align-self-center">
                                    <div class="form-check form-check-inline mx-1">
                                        <input class="form-check-input" type="radio" name="receipt" id="receipt2"
                                            checked value="2">
                                        <label class="form-check-label" for="receipt2">二聯式</label>
                                    </div>
                                    <div class="form-check form-check-inline mr-0">
                                        <input class="form-check-input" type="radio" name="receipt" id="receipt3"
                                            value="3">
                                        <label class="form-check-label" for="receipt3">三聯式</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-3 invoiceArea" style="display:none;">
                                <div class="col-md-9 offset-md-3 col-sm-12">
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label text-md-right text-sm-left align-self-center pr-0"
                                            for="note">發票寄送地址</label>
                                        <div class="col-sm-6 align-self-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="idCheck1"
                                                    name="invoiceSameAsMember">
                                                <label class="form-check-label" for="idCheck1">同訂購人資料</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div id="invoice_twzipcode" class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6 mb-2">
                                                            <select data-role="county" class="form-control"
                                                                name="invoice_county"></select>
                                                        </div>
                                                        <div class="col-12 col-md-6 mb-2">
                                                            <select data-role="district" class="form-control"
                                                                name="invoice_district"></select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" data-role="zipcode" />
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="address"
                                                        name="invoice_address" placeholder="請輸入詳細地址" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row invoiceTitleArea">
                                        <label
                                            class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left align-self-center"
                                            for="invoice_taxid">發票統編</label>
                                        <div class="col-sm-6 align-self-center">
                                            <input type="text" class="form-control" id="invoice_taxid"
                                                placeholder="發票統編" name="invoice_taxid" maxlength="8">
                                        </div>
                                    </div>
                                    <div class="form-group row invoiceTitleArea">
                                        <label class="col-sm-10 offset-sm-2 col-form-label align-self-center"
                                            for="invoice_taxid_check" style="color: red;">請輸入 8
                                            位數統編，如果正確，發票抬頭會自動帶入</label>

                                    </div>

                                    <div class="form-group row invoiceTitleArea">
                                        <label
                                            class="col-sm-2 col-form-label pr-0 text-md-right text-sm-left align-self-center"
                                            for="invoice_title">發票抬頭</label>
                                        <div class="col-sm-6 align-self-center">
                                            <input type="text" class="form-control" id="invoice_title"
                                                placeholder="發票抬頭" name="invoice_title" readonly>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 sum-row">
                            <div class="form-group row mt-4">
                                <div class="col-md-9 offset-md-3 col-sm-12">
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-2 col-form-label text-md-right px-0 align-self-center">
                                            <span class="text-danger">*</span>輸入右方驗證碼
                                        </label>
                                        <div class="col-sm-6 align-self-center">
                                            <div class="input-group">
                                                <input type="text" class="form-control align-self-center"
                                                    id="verify" placeholder="" required name="captcha"
                                                    style="text-transform: uppercase"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <div class="d-flex pl-2 align-self-center">
                                                    <img src="{{ route('captcha.generate') }}" width="140"
                                                        height="60" class="captchaImg" />
                                                </div>
                                                <div class="input-group-append">
                                                    <label class="refresh mn-0">
                                                        <a class="btn btn-refresh hvr-icon-spin"
                                                            style="cursor: pointer;">更換 <i
                                                                class="fas fa-sync-alt hvr-icon px-1"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 offset-sm-2">
                                            <button type="submit"
                                                class="btn btn-danger btn-purchase w-100 rounded-pill mb-3 px-5 py-3 shoppingFinish">訂購完成</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
            </section>


        </div>
    </article>
@endpush

@push('scripts')
    <script src="{{ asset('frontend/js/jquery.twzipcode.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#twzipcode').twzipcode({
                "zipcodeSel": "{{ Auth::guard('member')->user()->zipcode }}",
            });

            $('#invoice_twzipcode').twzipcode({
                "countyName": "invoice_county",
                "districtName": "invoice_district",
                "zipcodeName": "invoice_zipcode",
                "zipcodeSel": "{{ Auth::guard('member')->user()->invoice_zipcode }}",
            });

            // 監聽"同訂購人資料"選框的變化
            $('input[name="sameAsMember"]').change(function() {
                if ($(this).is(':checked')) {
                    // 如果勾選，填入會員資料
                    $('input[name="username"]').val('{{ Auth::guard('member')->user()->name }}');
                    $('input[name="phone"]').val('{{ Auth::guard('member')->user()->phone }}');

                    // 設置性別
                    if ('{{ Auth::guard('member')->user()->gender }}' === '1') {
                        $('#male').prop('checked', true);
                    } else {
                        $('#female').prop('checked', true);
                    }

                    // 如果地址區塊是顯示的，則填入地址資料
                    if ($('.addr').is(':visible')) {
                        // 修正地址欄位的選擇器，使用 twzipcode 的方法
                        $('select[data-role="county"]').val('{{ Auth::guard('member')->user()->county }}')
                            .trigger('change');
                        $('select[data-role="district"]').val(
                            '{{ Auth::guard('member')->user()->district }}');
                        $('input[name="address"]').val('{{ Auth::guard('member')->user()->address }}');
                    }
                } else {
                    // 如果取消勾選，清空所有欄位
                    $('input[name="username"]').val('');
                    $('input[name="phone"]').val('');
                    $('select[data-role="county"]').val('');
                    $('select[data-role="district"]').val('');
                    $('input[name="address"]').val('');
                }
            });

            // 寄送方式變更處理
            $('#shipment').change(function() {
                const selectedOption = $(this).find('option:selected');
                const shippingFee = parseInt(selectedOption.data('fee')) || 0;
                const selectedValue = $(this).val();
                const freeShippings = {{ $freeShippings }};

                // 更新運費顯示
                if (freeShippings > 0) {
                    $('.shipping-fee').hide();
                } else if (shippingFee > 0) {
                    $('.shipping-fee').show();
                    $('.shipping-fee .money').text('NT$' + numberFormat(shippingFee));
                } else {
                    $('.shipping-fee').hide();
                }

                // 更新總金額
                const subtotal = {{ $total }};
                const newTotal = subtotal + (freeShippings > 0 ? 0 : shippingFee);
                $('h2.text-black span').text('NT$' + numberFormat(newTotal));

                // 根據選擇顯示不同內容
                switch (selectedValue) {
                    case 'mail_send':
                        $('.addr').show(); 
                        $('.map-btn').hide();
                        break;
                    case '711_b2c':
                    case 'family_b2c':
                        $('.addr').hide(); 
                        $('.map-btn').show(); 
                        break;
                    default:
                        $('.addr').hide();
                        $('.map-btn').hide();
                        break;
                }
            });

            // 門市地圖按鈕點擊事件
            $('.map-btn').click(function() {
                const shipmentType = $('#shipment').val();
                // 根據不同超商打開對應地圖
                if (shipmentType === '711_b2c') {
                    openSevenMap(shipmentType);
                } else if (shipmentType === 'family_b2c') {
                    openFamilyMap(shipmentType);
                }
            });

            // 監聽來自子視窗的消息
            window.addEventListener('message', function(event) {
                if (event.data.type === 'STORE_SELECTED') {
                    const storeData = event.data.data;

                    // 在寄送方式下方顯示選擇的門市資訊
                    const storeInfoHtml = `
                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-2">
                                <div class="store-info mt-2">
                                    <div class="alert alert-info mb-0">
                                        <strong>已選擇門市：</strong><br>
                                        門市：${storeData.store_name}<br>
                                        地址：${storeData.store_address}<br>
                                        電話：${storeData.store_telephone}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // 移除舊的門市資訊（如果有的話）
                    $('.store-info').closest('.form-group').remove();

                    // 添加新的門市資訊 - 改為添加在寄送方式的 form-group 後面
                    $('#shipment').closest('.form-group').after(storeInfoHtml);

                    // 將門市資訊存入隱藏欄位（方便表單提交）
                    if (!$('input[name="store_id"]').length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'store_id',
                            value: storeData.store_id
                        }).appendTo('form');
                    } else {
                        $('input[name="store_id"]').val(storeData.store_id);
                    }
                }
            });

            // 表單提交前的驗證
            $('form').submit(function(e) {
                e.preventDefault(); // 先阻止預設提交

                const form = $(this);
                const shipmentType = $('#shipment').val();
                const paymentMethod = $('input[name="payment"]:checked').val();

                // 驗證寄送方式
                if (!shipmentType) {
                    window.showToast('請選擇寄送方式', 'error');
                    return false;
                }

                // 驗證超商取貨門市
                if ((shipmentType === '711_b2c' || shipmentType === 'family_b2c') &&
                    !$('input[name="store_id"]').val()) {
                    window.showToast('請選擇取貨門市', 'error');
                    return false;
                }

                // 驗證郵寄地址
                if (shipmentType === 'mail_send') {
                    if (!$('select[name="county"]').val() ||
                        !$('select[name="district"]').val()) {
                        window.showToast('請選擇縣市及區域', 'error');
                        return false;
                    }
                }

                // 驗證三聯式發票
                if ($('input[name="receipt"]:checked').val() === '3') {
                    // 檢查統一編號
                    if (!$('input[name="invoice_taxid"]').val()) {
                        window.showToast('請填寫統一編號', 'error');
                        $('input[name="invoice_taxid"]').focus();
                        return false;
                    }

                    // 檢查發票抬頭
                    if (!$('input[name="invoice_title"]').val()) {
                        window.showToast('請填寫發票抬頭', 'error');
                        $('input[name="invoice_title"]').focus();
                        return false;
                    }

                    // 檢查發票寄送地址
                    if (!$('select[name="invoice_county"]').val() ||
                        !$('select[name="invoice_district"]').val() ||
                        !$('input[name="invoice_address"]').val()) {
                        window.showToast('請填寫完整的發票寄送地址', 'error');
                        return false;
                    }
                }

                // 如果是 ATM 付款，則在新視窗中提交
                if (paymentMethod === 'ATM') {
                    // 計算視窗位置，使其置中
                    const width = window.innerWidth;
                    const height = window.innerHeight;
                    const left = (window.screen.width - width) / 2;
                    const top = (window.screen.height - height) / 2;

                    // 開啟新視窗
                    const atmWindow = window.open(
                        'about:blank',
                        'atmWindow',
                        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes`
                    );

                    // 創建一個臨時表單進行 POST
                    const tempForm = document.createElement('form');
                    tempForm.setAttribute('method', 'post');
                    tempForm.setAttribute('action', form.attr('action'));
                    tempForm.setAttribute('target', 'atmWindow');

                    // 複製原表單的所有資料
                    const formData = new FormData(form[0]);
                    for (let pair of formData.entries()) {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', pair[0]);
                        input.setAttribute('value', pair[1]);
                        tempForm.appendChild(input);
                    }

                    // 添加 ATM 標記
                    const atmInput = document.createElement('input');
                    atmInput.setAttribute('type', 'hidden');
                    atmInput.setAttribute('name', 'is_atm', '1');
                    tempForm.appendChild(atmInput);

                    // 添加 CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.setAttribute('type', 'hidden');
                    csrfInput.setAttribute('name', '_token');
                    csrfInput.setAttribute('value', '{{ csrf_token() }}');
                    tempForm.appendChild(csrfInput);

                    // 添加到 body，提交後移除
                    document.body.appendChild(tempForm);
                    tempForm.submit();
                    document.body.removeChild(tempForm);

                    // 設置輪詢檢查視窗是否關閉
                    const timer = setInterval(function() {
                        if (atmWindow.closed) {
                            clearInterval(timer);
                            window.location.href = '{{ route('orders.list') }}';
                        }
                    }, 500);

                    return false;
                }

                // 如果不是 ATM 付款，則正常提交表單
                form.off('submit').submit();
            });

            // 發票類型變更處理
            $('input[name="receipt"]').change(function() {
                const selectedValue = $(this).val();
                const invoiceArea = $('.invoiceArea'); // 假設三聯式發票區塊的 class 是 invoice-area
                console.log(invoiceArea);
                console.log(selectedValue);

                // 如果選擇三聯式發票(value=3)，顯示發票資訊區域
                if (selectedValue === '3') {
                    invoiceArea.show();
                    // 當顯示時，設置必填欄位
                    $('input[name="invoiceTaxid"]').prop('required', true);
                    $('input[name="invoiceTitle"]').prop('required', true);
                } else {
                    invoiceArea.hide();
                    // 當隱藏時，移除必填屬性
                    $('input[name="invoiceTaxid"]').prop('required', false);
                    $('input[name="invoiceTitle"]').prop('required', false);
                    // 清空欄位值
                    $('input[name="invoiceTaxid"]').val('');
                    $('input[name="invoiceTitle"]').val('');
                }
            });

            // 初始檢查發票類型
            $('input[name="receipt"]:checked').trigger('change');

            // 監聽發票區域的"同訂購人資料"複選框
            $('input[name="invoiceSameAsMember"]').change(function() {
                if ($(this).is(':checked')) {
                    // 如果勾選，填入會員資料
                    $('#invoice_twzipcode select[data-role="county"]')
                        .val('{{ Auth::guard('member')->user()->county }}')
                        .trigger('change');

                    $('#invoice_twzipcode select[data-role="district"]')
                        .val('{{ Auth::guard('member')->user()->district }}');

                    $('input[name="invoice_address"]')
                        .val('{{ Auth::guard('member')->user()->address }}');

                    // 如果是三聯式發票，也可以填入公司資訊（如果有的話）
                    // if ($('input[name="receipt"]:checked').val() === '3') {
                    //     $('input[name="invoice_title"]')
                    //         .val('{{ Auth::guard('member')->user()->company_name ?? '' }}');
                    //     $('input[name="invoice_taxid"]')
                    //         .val('{{ Auth::guard('member')->user()->tax_id ?? '' }}');
                    // }
                } else {
                    // 如果取消勾選，清空所有欄位
                    $('#invoice_twzipcode select[data-role="county"]').val('');
                    $('#invoice_twzipcode select[data-role="district"]').val('');
                    $('input[name="invoice_address"]').val('');
                    $('input[name="invoice_title"]').val('');
                    $('input[name="invoice_taxid"]').val('');
                }
            });

            // 當收貨人資訊的"同訂購人資料"被勾選時，如果發票區域的"同訂購人資料"也被勾選，
            // 則同步更新發票地址
            $('input[name="sameAsMember"]').change(function() {
                if ($('input[name="invoiceSameAsMember"]').is(':checked')) {
                    if ($(this).is(':checked')) {
                        // 同步更新發票地址為會員資料
                        $('#invoice_twzipcode select[data-role="county"]')
                            .val('{{ Auth::guard('member')->user()->county }}')
                            .trigger('change');
                        $('#invoice_twzipcode select[data-role="district"]')
                            .val('{{ Auth::guard('member')->user()->district }}');
                        $('input[name="invoice_address"]')
                            .val('{{ Auth::guard('member')->user()->address }}');
                    }
                }
            });

            // 當發票類型改變時，重置發票地址區域
            $('input[name="receipt"]').change(function() {
                const selectedValue = $(this).val();
                const invoiceArea = $('.invoiceArea');

                if (selectedValue === '3') {
                    invoiceArea.show();
                    // 如果"同訂購人資料"已勾選，則自動填入地址
                    if ($('input[name="invoiceSameAsMember"]').is(':checked')) {
                        $('#invoice_twzipcode select[data-role="county"]')
                            .val('{{ Auth::guard('member')->user()->county }}')
                            .trigger('change');
                        $('#invoice_twzipcode select[data-role="district"]')
                            .val('{{ Auth::guard('member')->user()->district }}');
                        $('input[name="invoice_address"]')
                            .val('{{ Auth::guard('member')->user()->address }}');
                    }
                } else {
                    invoiceArea.hide();
                    // 清空所有發票相關欄位
                    $('#invoice_twzipcode select[data-role="county"]').val('');
                    $('#invoice_twzipcode select[data-role="district"]').val('');
                    $('input[name="invoice_address"]').val('');
                    $('input[name="invoice_title"]').val('');
                    $('input[name="invoice_taxid"]').val('');
                }
            });

            // 統一編號驗證
            $('#invoice_taxid').blur(function() {
                const taxId = $(this).val();
                if (taxId.length === 8) {
                    $.ajax({
                        url: '{{ route('checkout.validate-invoice-number') }}',
                        method: 'POST',
                        data: {
                            invoice_taxid: taxId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#invoice_title').val(response.company_name);
                                window.showToast('統一編號驗證成功', 'success');
                            } else {
                                window.showToast(response.message || '統一編號驗證失敗', 'error');
                                $('#invoice_taxid').val('').focus();
                            }
                        },
                        error: function(xhr) {
                            window.showToast('驗證過程發生錯誤，請稍後再試', 'error');
                            $('#invoice_taxid').val('').focus();
                        }
                    });
                } else if (taxId.length > 0) {
                    window.showToast('統一編號必須為8碼數字', 'error');
                    $('#invoice_taxid').val('').focus();
                }
            });

            // 監聽付款方式變更
            $('input[name="payment"]').change(function() {
                const paymentMethod = $(this).val();
                const shipmentSelect = $('#shipment');
                
                // 重置寄送方式選項
                shipmentSelect.find('option').remove();
                shipmentSelect.append('<option value="">請選擇</option>');
                
                if (paymentMethod === 'COD') {
                    // 貨到付款只顯示超商取貨選項
                    shipmentSelect.append(`
                        <option value="711_b2c" data-fee="{{ $shippingSettings['711_b2c']['fee'] }}">
                            {{ $shippingSettings['711_b2c']['name'] }}
                        </option>
                        <option value="family_b2c" data-fee="{{ $shippingSettings['family_b2c']['fee'] }}">
                            {{ $shippingSettings['family_b2c']['name'] }}
                        </option>
                    `);
                } else {
                    // 其他付款方式顯示所有選項
                    @foreach ($shippingSettings as $key => $shipping)
                        shipmentSelect.append(`
                            <option value="{{ $key }}" data-fee="{{ $shipping['fee'] }}">
                                {{ $shipping['name'] }}
                            </option>
                        `);
                    @endforeach
                }
                
                // 觸發寄送方式變更事件以更新顯示
                shipmentSelect.trigger('change');
            });

            // 初始觸發付款方式變更事件
            $('input[name="payment"]:checked').trigger('change');
        });

        // 開 7-11 地圖
        function openSevenMap(shipment) {
            // 計算視窗位置，使其置中
            const width = window.innerWidth;
            const height = window.innerHeight;
            const left = (window.screen.width - width) / 2;
            const top = (window.screen.height - height) / 2;

            // 獲取當前選擇的付款方式
            const payment = $('input[name="payment"]:checked').val();

            const mapWindow = window.open(
                `{{ url('checkout/map/711-store/') }}/${shipment}?payment=${payment}`,
                'mapWindow',
                `width=${width},height=${height},left=${left},top=${top},scrollbars=yes`
            );

            // 設置輪詢檢查視窗是否關閉
            const timer = setInterval(function() {
                if (mapWindow.closed) {
                    clearInterval(timer);
                    checkStoreSelection();
                }
            }, 500);
        }

        // 開啟全家地圖
        function openFamilyMap(shipment) {
            // 計算視窗位置，使其置中
            const width = window.innerWidth;
            const height = window.innerHeight;
            const left = (window.screen.width - width) / 2;
            const top = (window.screen.height - height) / 2;

            // 獲取當前選擇的付款方式
            const payment = $('input[name="payment"]:checked').val();

            const mapWindow = window.open(
                `{{ url('checkout/map/family-store/') }}/${shipment}?payment=${payment}`,
                'mapWindow',
                `width=${width},height=${height},left=${left},top=${top},scrollbars=yes`
            );

            // 設置輪詢檢查視窗是否關閉
            const timer = setInterval(function() {
                if (mapWindow.closed) {
                    clearInterval(timer);
                    checkStoreSelection();
                }
            }, 500);
        }

        // 檢查門市選擇
        function checkStoreSelection() {
            $.ajax({
                url: '{{ route('checkout.get.store') }}', // 需要新增這個路由
                method: 'GET',
                success: function(response) {
                    if (response.store) {
                        updateStoreInfo(response.store);
                    }
                }
            });
        }

        // 更新門市資訊顯示
        function updateStoreInfo(storeData) {
            const storeInfoHtml = `
                <div class="form-group row">
                    <div class="col-sm-6 offset-sm-2">
                        <div class="store-info mt-2">
                            <div class="alert alert-info mb-0">
                                <strong>已選擇門市：</strong><br>
                                門市：${storeData.store_name}<br>
                                地址：${storeData.store_address}<br>
                                電話：${storeData.store_telephone}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('.store-info').closest('.form-group').remove();
            $('#shipment').closest('.form-group').after(storeInfoHtml);

            $('input[name="store_name"]').val(storeData.store_name);
            $('input[name="store_address"]').val(storeData.store_address);
            $('input[name="store_telephone"]').val(storeData.store_telephone);

            // 更新隱藏欄位
            if (!$('input[name="store_id"]').length) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'store_id',
                    value: storeData.store_id
                }).appendTo('form');
            } else {
                $('input[name="store_id"]').val(storeData.store_id);
            }
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('zh-TW').format(number);
        }
    </script>
@endpush
