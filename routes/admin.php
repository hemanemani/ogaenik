<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BusinessSettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProducDescriptionController;
use App\Http\Controllers\GiftProductController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SpecialController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ContactController;


/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register admin routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


Route::get('/admin', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard')->middleware(['auth', 'admin']);
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::controller(AdminController::class)->group(function () {
        Route::post('/dashboard/top-category-products-section', 'top_category_products_section')->name('dashboard.top_category_products_section');
        Route::post('/dashboard/inhouse-top-brands', 'inhouse_top_brands')->name('dashboard.inhouse_top_brands');
        Route::post('/dashboard/inhouse-top-categories', 'inhouse_top_categories')->name('dashboard.inhouse_top_categories');
        Route::post('/dashboard/top-sellers-products-section', 'top_sellers_products_section')->name('dashboard.top_sellers_products_section');
        Route::post('/dashboard/top-brands-products-section', 'top_brands_products_section')->name('dashboard.top_brands_products_section');
    });

    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products/admin', 'admin_products')->name('products.admin');
        Route::get('/products/all', 'all_products')->name('products.all');
        Route::get('/products/create', 'create')->name('products.create');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::get('/products/admin/{id}/edit', 'admin_product_edit')->name('products.admin.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::post('/products/todays_deal', 'updateTodaysDeal')->name('products.todays_deal');
        Route::get('/products/temdestroy/{id}', 'temdestroy')->name('products.temdestroy');
        Route::post('/products/featured', 'updateFeatured')->name('products.featured');
        Route::post('/products/published', 'updatePublished')->name('products.published');
        Route::post('/products/approved', 'updateProductApproval')->name('products.approved');
        Route::post('/products/get_products_by_subcategory', 'get_products_by_subcategory')->name('products.get_products_by_subcategory');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::post('/bulk-product-delete', 'bulk_product_delete')->name('bulk-product-delete');

        Route::post('/products/sku_combination', 'sku_combination')->name('products.sku_combination');
        Route::post('/products/sku_combination_edit', 'sku_combination_edit')->name('products.sku_combination_edit');
        Route::post('/products/add-more-choice-option', 'add_more_choice_option')->name('products.add-more-choice-option');
        Route::post('/product-search', 'product_search')->name('product.search');
        Route::post('/get-selected-products', 'get_selected_products')->name('get-selected-products');
        Route::post('/set-product-discount', 'setProductDiscount')->name('set_product_discount');

        //added newly

        Route::get('bulk-product-remove', 'deleteMultiple')->name('product.multiple_delete');
        Route::get('bulk-product-update', 'updateMultiple')->name('product.multiple_update');
        Route::get('/product-notifications', 'productNotify')->name('product.notifylist');
        Route::get('/products/temporary-deleted-product','temporary_delete_product')->name('products.temporary_delete_product');
        Route::get('product-list-admin', 'product_list_admin')->name('products.product_list_admin');
        Route::post('product-upload-status-admin', 'upload_status_listing')->name('product.upload_status');
        Route::post('/product_bulk_update/delete', 'product_listing_delete')->name('product_listing.delete');
        Route::get('/products/editor', 'editor')->name('products.editor');


    });

    // Product Description

    Route::resource('product-description', ProducDescriptionController::class);

    // Gift Products

    Route::controller(GiftProductController::class)->group(function () {
	Route::get('/giftproducts/admin','admin_products')->name('giftproducts.admin');
	Route::get('/giftproducts/seller','seller_products')->name('giftproducts.seller');
	Route::get('/giftproducts/all','all_products')->name('giftproducts.all');
	Route::get('/giftproducts/create','create')->name('giftproducts.create');
	Route::get('/giftproducts/admin/{id}/edit','admin_product_edit')->name('giftproducts.admin.edit');
	Route::get('/giftproducts/seller/{id}/edit','seller_product_edit')->name('giftproducts.seller.edit');
	Route::post('/giftproducts/todays_deal', 'updateTodaysDeal')->name('giftproducts.todays_deal');
	Route::post('/giftproducts/get_products_by_subcategory', 'get_products_by_subcategory')->name('giftproducts.get_products_by_subcategory');
	Route::post('/giftproducts/store/','store')->name('giftproducts.store');
	Route::post('/giftproducts/update/{id}','update')->name('giftproducts.update');
	Route::get('/giftproducts/destroy/{id}', 'destroy')->name('giftproducts.destroy');
	Route::get('/giftproducts/temdestroy/{id}', 'temdestroy')->name('giftproducts.temdestroy');
	Route::get('/giftproducts/duplicate/{id}', 'duplicate')->name('giftproducts.duplicate');
	Route::post('/giftproducts/sku_combination', 'sku_combination')->name('giftproducts.sku_combination');
	Route::post('/giftproducts/sku_combination_edit', 'sku_combination_edit')->name('giftproducts.sku_combination_edit');
	Route::post('/giftproducts/featured', 'updateFeatured')->name('giftproducts.featured');
	Route::post('/giftproducts/published', 'updatePublished')->name('giftproducts.published');

    });

    //origin

    Route::resource('origins',OriginController::class);
    Route::controller(OriginController::class)->group(function () {
        Route::get('/origins/edit/{id}', 'edit')->name('origins.edit');
        Route::get('/origins/destroy/{id}', 'destroy')->name('origins.destroy');
        Route::post('/origins/featured', 'updateFeatured')->name('origins.featured');
        Route::get('/originsdestroy/destroy/{id}', 'temdestroy')->name('origins.temdestroy');
    });

    //Department

    Route::resource('departments',DepartmentController::class);
    Route::controller(DepartmentController::class)->group(function () {
	Route::get('/departments/edit/{id}', 'edit')->name('departments.edit');
	Route::get('/departments/destroy/{id}', 'destroy')->name('departments.destroy');	
	Route::get('/departmentsdestroy/destroy/{id}', 'temdestroy')->name('departments.temdestroy');
    });

    //On Special

    Route::resource('specials',SpecialController::class);
    Route::controller(SpecialController::class)->group(function () {
        Route::get('/specials/edit/{id}', 'edit')->name('specials.edit');
        Route::get('/specials/destroy/{id}', 'destroy')->name('specials.destroy');	
        Route::get('/specialsdestroy/destroy/{id}', 'temdestroy')->name('specials.temdestroy');
        Route::post('/special/published', 'updatePublished')->name('special.published');

    });

    // Brand

    Route::resource('brands',BrandController::class);
    Route::controller(BrandController::class)->group(function () {
	Route::get('/brands/edit/{id}', 'edit')->name('brands.edit');
	Route::get('/brands/destroy/{id}', 'destroy')->name('brands.destroy');
	Route::get('/brandsdestroy/destroy/{id}', 'temdestroy')->name('brands.temdestroy');
	Route::post('/brand/published', 'updateFeatured')->name('brands.published');
	Route::get('/brand/inactive_brnds', 'inactive_brands')->name('brands.inactive_brnds');
    });

    // Attributes

    Route::resource('attributes',AttributeController::class);
    Route::controller(AttributeController::class)->group(function () {
        Route::get('/attributes/edit/{id}', 'edit')->name('attributes.edit');
        Route::get('/attributes/destroy/{id}', 'destroy')->name('attributes.destroy');
        Route::get('/attributesdestroy/destroy/{id}', 'temdestroy')->name('attributes.temdestroy');
    });

    // Product Reviews
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews', 'index')->name('reviews.index');
        Route::post('/reviews/published', 'updatePublished')->name('reviews.published');
    });

    // All Orders
    Route::controller(OrderController::class)->group(function () {
	Route::get('/inhouse-orders', 'admin_orders')->name('inhouse_orders.index');
	Route::get('/inhouse-orders/{id}/show', 'show')->name('inhouse_orders.show');

    // Inactive Orders
	Route::get('/inactive-inhouse-orders', 'inactive_order_index')->name('inhouse_orders.inactive_order_index');
	Route::get('/inactive-inhouse-orders/{id}/show', 'inactive_order_show')->name('inhouse_orders.inactive_order_show');

    Route::get('invoice/customer/{order_id}', 'customer_invoice_download')->name('customer.invoice.download');


    });
    
    // Categories
    Route::controller(HomeController::class)->group(function () {
        Route::get('/admin-categories', 'all_categories_admin')->name('admincategories.all');
        Route::get('/shop-by-ethics/{department}', 'search')->name('department.category');
    });

	Route::resource('categories',CategoryController::class);
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/edit/{id}', 'edit')->name('categories.edit');
        Route::get('/categories/destroy/{id}', 'destroy')->name('categories.destroy');
        Route::post('/categories/featured', 'updateFeatured')->name('categories.featured');
        Route::get('/categoriesdestroy/destroy/{id}', 'temdestroy')->name('categories.temdestroy');
        Route::get('/product-categories', 'product_category')->name('product.categories');
        Route::post('/categories/export-bulk-product-category', 'export_bulk_product_category')->name('export.bulk.product.categories');
        Route::get('/orders/destroy/{id}', 'destroy')->name('orders.destroy');
	    Route::get('/temdestroy/destroy/{id}', 'temdestroy')->name('orders.temdestroy');
        Route::post('/categories/export-bulk-category-product', 'export_bulk_category_product')->name('export.bulk.category.products');
        Route::post('/export-selected-category-products', 'export_selected_category_products')->name('export.selected.category.products');
      

    });


    // // Sub Sub Categories
    // Route::resource('subcategories','SubCategoryController');
	// Route::get('/subcategories/edit/{id}', 'SubCategoryController@edit')->name('subcategories.edit');
	// Route::get('/subcategories/destroy/{id}', 'SubCategoryController@destroy')->name('subcategories.destroy');

    // // Sub Sub Categories
	// Route::resource('subsubcategories','SubSubCategoryController');
	// Route::get('/subsubcategories/edit/{id}', 'SubSubCategoryController@edit')->name('subsubcategories.edit');
	// Route::get('/subsubcategories/destroy/{id}', 'SubSubCategoryController@destroy')->name('subsubcategories.destroy');

   

    Route::resource('profile', ProfileController::class);

    // Business Settings
    Route::controller(BusinessSettingsController::class)->group(function () {
        Route::post('/business-settings/update', 'update')->name('business_settings.update');
        Route::post('/business-settings/update/activation', 'updateActivationSettings')->name('business_settings.update.activation');
        Route::post('/payment-activation', 'updatePaymentActivationSettings')->name('payment.activation');
        Route::get('/general-setting', 'general_setting')->name('general_setting.index');
        Route::get('/activation', 'activation')->name('activation.index');
        Route::get('/payment-method', 'payment_method')->name('payment_method.index');
        Route::get('/file_system', 'file_system')->name('file_system.index');
        Route::get('/social-login', 'social_login')->name('social_login.index');
        Route::get('/smtp-settings', 'smtp_settings')->name('smtp_settings.index');
        Route::get('/google-analytics', 'google_analytics')->name('google_analytics.index');
        Route::get('/google-recaptcha', 'google_recaptcha')->name('google_recaptcha.index');
        Route::get('/google-map', 'google_map')->name('google-map.index');
        Route::get('/google-firebase', 'google_firebase')->name('google-firebase.index');

        //Facebook Settings
        Route::get('/facebook-chat', 'facebook_chat')->name('facebook_chat.index');
        Route::post('/facebook_chat', 'facebook_chat_update')->name('facebook_chat.update');
        Route::get('/facebook-comment', 'facebook_comment')->name('facebook-comment');
        Route::post('/facebook-comment', 'facebook_comment_update')->name('facebook-comment.update');
        Route::post('/facebook_pixel', 'facebook_pixel_update')->name('facebook_pixel.update');

        Route::post('/env_key_update', 'env_key_update')->name('env_key_update.update');
        Route::post('/payment_method_update', 'payment_method_update')->name('payment_method.update');
        Route::post('/google_analytics', 'google_analytics_update')->name('google_analytics.update');
        Route::post('/google_recaptcha', 'google_recaptcha_update')->name('google_recaptcha.update');
        Route::post('/google-map', 'google_map_update')->name('google-map.update');
        Route::post('/google-firebase', 'google_firebase_update')->name('google-firebase.update');

        Route::get('/verification/form', 'seller_verification_form')->name('seller_verification_form.index');
        Route::post('/verification/form', 'seller_verification_form_update')->name('seller_verification_form.update');
        Route::get('/vendor_commission', 'vendor_commission')->name('business_settings.vendor_commission');

        //Shipping Configuration
        Route::get('/shipping_configuration', 'shipping_configuration')->name('shipping_configuration.index');
        Route::post('/shipping_configuration/update', 'shipping_configuration_update')->name('shipping_configuration.update');

        // Order Configuration
        Route::get('/order-configuration', 'order_configuration')->name('order_configuration.index');
    });

    Route::resource('contact-details', ContactController::class);

    Route::resource('notification-type', NotificationTypeController::class);
    Route::controller(NotificationTypeController::class)->group(function () {
        Route::get('/notification-type/edit/{id}', 'edit')->name('notification-type.edit');
        Route::post('/notification-type/update-status', 'updateStatus')->name('notification-type.update-status');
        Route::get('/notification-type/destroy/{id}', 'destroy')->name('notification-type.destroy');
        Route::post('/notification-type/bulk_delete', 'bulkDelete')->name('notifications-type.bulk_delete');
        Route::post('/notification-type.get-default-text', 'getDefaulText')->name('notification_type.get_default_text');
    });

    Route::get('/clear-cache', [AdminController::class, 'clearCache'])->name('cache.clear');

    Route::get('/admin-permissions', [RoleController::class, 'create_admin_permissions']);
});
