<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Frontend\{
    HomeController,
    PostController,
    ProductController,
    LoginController,
    JoinController,
    ActivityController,
    FaqController,
    SealKnowledgeController,
    SealKnowledgeCategoryController,
    ProfileController,
    LogisticsController,
    SearchController,
    ForgetController,
    NavigationController,
    FeedbackController,
};
use App\Http\Controllers\Frontend\{
    CartController,
    CheckoutController,
    PaymentController,
    OrderController,
};

use App\Http\Controllers\Frontend\TestController;

// 首頁路由
Route::get('/', [HomeController::class, 'index'])->name('home');

// 登入路由
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginProcess'])->name('login.process');

// 註冊路由
Route::get('/join', [JoinController::class, 'index'])->name('join');
Route::post('/join', [JoinController::class, 'joinProcess'])->name('join.process');

// 忘記密碼路由
Route::get('/forget', [ForgetController::class, 'forget'])->name('forget');
Route::post('/forget', [ForgetController::class, 'forgetProcess'])->name('forget.process');

// 登出路由
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('get.logout');

// 文章路由
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

// 產品路由
Route::get('/product/{id}', [ProductController::class, 'index'])->name('product.index');

// 活動訊息路由
Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
Route::get('/activity/{id}', [ActivityController::class, 'detail'])->name('activity.detail');

// 常見問題路由
Route::get('/faqs/{category?}', [FaqController::class, 'index'])->name('faqs.index');

// 產品分類路由
Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('category/{id}', [ProductController::class, 'index'])->name('category');
    Route::get('show/{id}', [ProductController::class, 'show'])->name('show');
});

// 印章知識路由
Route::group(['prefix' => 'seal-knowledge', 'as' => 'seal-knowledge.'], function () {
    Route::get('/', [SealKnowledgeController::class, 'index'])->name('index');
    Route::get('/category/{id}', [SealKnowledgeCategoryController::class, 'show'])->name('category');
    Route::get('/{id}', [SealKnowledgeController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('show');
});

// 驗證碼路由
Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha.generate');
Route::post('/captcha/refresh', [CaptchaController::class, 'generate'])->name('captcha.refresh');

// 購物車和結帳路由
Route::group(['middleware' => 'auth:member'], function () {
    // 購物車
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    // Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    // Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');

    // 需要添加的路由
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');

    // 結帳流程
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'addToCart'])->name('checkout.add');

    // 結帳驗證
    Route::post('/payment/process', [PaymentController::class, 'paymentProcess'])->name('payment.process');
    // 訂單
    Route::post('/orders/{product}', [OrderController::class, 'store'])->name('products.order');
    Route::get('/orders/list/{order}', [OrderController::class, 'orderShow'])->name('orders.show');
    Route::get('/orders/list', [OrderController::class, 'orderList'])->name('orders.list');

    // 會員資料
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// 付款結果
Route::get('/payment/callback', [PaymentController::class, 'paymentATMCallback'])->name('payment.atm.callback');
Route::post('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
Route::post('/payment/notify', [PaymentController::class, 'notify'])->name('payment.notify');

// 門市地圖
Route::get('/checkout/map/711-store/{shippmentType}', [CheckoutController::class, 'openSevenMap'])->name('checkout.map.711');
Route::get('/checkout/map/family-store/{shippmentType}', [CheckoutController::class, 'openFamilyMap'])->name('checkout.map.family');

// 重寫門市地圖
Route::post('/checkout/map/rewrite', [CheckoutController::class, 'rewriteMap'])->name('checkout.map.rewrite');
// 獲取已選擇的門市資訊
Route::get('/checkout/get-store', [CheckoutController::class, 'getSelectedStore'])->name('checkout.get.store');


// 物流通知路由
Route::post('logistics/notify', [LogisticsController::class, 'notify'])->name('logistics.notify');
Route::post('logistics/store/notify', [LogisticsController::class, 'storeNotify'])->name('logistics.store.notify');

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

// 會員中心
Route::get('/member/agreement', [HomeController::class, 'memberAgreement'])->name('member.agreement');


// TODO: 測試路由，記得刪除
Route::group(['prefix' => 'tester', 'as' => 'tester.'], function () {
    Route::get('/mail', [TestController::class, 'testMail'])->name('test.mail');
    Route::get('/update-atm-status', [TestController::class, 'updateShippingStatus'])->name('update.atm.status');
});

Route::post('/checkout/validate-invoice-number', [CheckoutController::class, 'validateInvoiceNumber'])
    ->name('checkout.validate-invoice-number');

Route::get('/cart/count', [NavigationController::class, 'getCartCount'])->name('cart.count');

// 問題回饋
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
