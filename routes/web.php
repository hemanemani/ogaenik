<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GiftProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\OrderController;


Route::get('/refresh-csrf', function(){
    return csrf_token();
});

Auth::routes(['verify' => true]);

Route::controller(RegisterController::class)->group(function () {
    Route::get('varifycontact','varifyphone')->name('auth.verifyphone');
    Route::get('varifyemail','varifyemail')->name('auth.verifyemail');
    Route::get('create_varifyemail','create_varifyemail')->name('auth.create_varifyemail');
    Route::get('varifyloginemail','varifyloginemail')->name('auth.varifyloginemail');
    Route::get('varifypassword','varifypassword')->name('auth.varifypassword');

});

Route::get('/logout',[LoginController::class,'logout']);

Route::controller(VerificationController::class)->group(function () {

    Route::get('/email/resend', 'resend')->name('verification.resend');
    Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});

Route::post('/language', [LanguageController::class,'changeLanguage'])->name('language.change');
Route::post('/currency', [CurrencyController::class,'changeCurrency'])->name('currency.change');

Route::controller(HomeController::class)->group(function () {
    Route::get('/users/login', 'login')->name('user.login');
    Route::get('/users/registration', 'registration')->name('user.registration');
    Route::get('/', 'index')->name('home');
    Route::get('/email_change/callback', 'email_change_callback')->name('email_change.callback');
    Route::get('/brands', 'all_brands')->name('brands.all');
    Route::get('/categories', 'all_categories')->name('categories.all');
    Route::get('/category={category}', 'search')->name('products.category');
    Route::get('/subcategory={subcategory}', 'search')->name('products.subcategory');
    Route::get('/category/{subsubcategory}', 'search')->name('products.subsubcategory');
    Route::get('/brand/{brand}', 'search')->name('products.brand');
    Route::get('/search', 'search_bar')->name('search');
    Route::get('/search?q={search}', 'search_bar')->name('suggestion.search');
    Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
    Route::get('/track_your_order', 'trackOrder')->name('orders.track');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/privacypolicy', 'privacypolicy')->name('privacypolicy');
    Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit');
    Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');
    Route::post('/product/variant_price', 'variant_price')->name('products.variant_price');

});


Route::middleware(['user', 'verified', 'unbanned'])
    ->controller(HomeController::class)
    ->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
        Route::post('/new-user-email', 'update_email')->name('user.change.email');
        Route::post('/customer/update-profile', 'customer_update_profile')->name('customer.profile.update');
        Route::post('/seller/update-profile', 'seller_update_profile')->name('seller.profile.update');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('contact', 'form')->name('contact');
        Route::post('contact-submit','submit')->name('contact.submit');
    });

    Route::resource('addresses',AddressController::class);

    Route::controller(AddressController::class)->group(function () {
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
        Route::post('/addresses/update/{id}', 'update')->name('addresses.update');

    });



Route::get('/sitemap.xml', function(){
	return base_path('sitemap.xml');
});


Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::post('/cart/nav-cart-items', 'updateNavCart')->name('cart.nav_cart');
    Route::post('/cart/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
    Route::post('/cart/addtocart', 'addToCart')->name('cart.addToCart');
    Route::post('/cart/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
    Route::post('/cart/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
    Route::post('/cart/updateQuantitys', 'updateQuantitys')->name('cart.updateQuantitys');
    Route::post('/cart/removeFromCarts', 'removeFromCarts')->name('cart.removeFromCarts');
});

//Checkout Routes 

Route::middleware(['checkout'])
    ->controller(CheckoutController::class)
    ->group(function () {
	Route::get('/checkout', 'get_shipping_info')->name('checkout.shipping_info');
	Route::post('/checkout/payments', 'store_shipping_info')->name('checkout.store_shipping_infostore');
	Route::post('/checkout/payment_select', 'store_delivery_info')->name('checkout.store_delivery_info');
});

Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout/order-confirmed', 'order_confirmed')->name('order_confirmed');
    Route::post('/checkout/payment', 'checkout')->name('payment.checkout');
    Route::get('/checkout/payment_select', 'get_payment_info')->name('checkout.payment_info');
    Route::post('/checkout/apply_coupon_code', 'apply_coupon_code')->name('checkout.apply_coupon_code');
    Route::post('/checkout/remove_coupon_code', 'remove_coupon_code')->name('checkout.remove_coupon_code');
});

Route::resource('purchase_history',PurchaseHistoryController::class);

Route::controller(PurchaseHistoryController::class)->group(function () {
    Route::post('/purchase_history/details', 'purchase_history_details')->name('purchase_history.details');
    Route::get('/purchase_history/destroy/{id}', 'destroy')->name('purchase_history.destroy');

});

Route::resource('orders',OrderController::class);
Route::controller(OrderController::class)->group(function () {
Route::get('/orders/destroy/{id}', 'destroy')->name('orders.destroy');
Route::post('/orders/details', 'order_details')->name('orders.details');
Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
Route::post('/orders/seller_update_payment_status', 'seller_update_payment_status')->name('orders.seller_update_payment_status');
Route::post('/orders/seller_update_order_status', 'seller_update_order_status')->name('orders.seller_update_order_status');
});


Route::get('/gifting',[GiftProductController::class,'gift_list'])->name('customer_gift');
