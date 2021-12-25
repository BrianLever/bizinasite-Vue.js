<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as Admin;


Route::group(['as'=>'admin.', 'prefix'=>'admin', 'namespace'=>'Admin','middleware'=>['auth','role:admin']], function() {
    Route::get('/todo', 'TodoController@index')->name('todo.index');
    Route::get('/getTodoCount', 'TodoController@getTodoCount')->name('todo.getTodoCount');
    Route::get('/todo/{type}', 'TodoController@detail')->name('todo.detail');

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/test/{type}', 'DashboardController@test')->name('test');
    Route::get('/selectUser', 'DashboardController@selectUser')->name('selectUser');
    Route::get('/dashboard/analytics', 'DashboardController@analytics')->name('analytics');

    Route::get('userManage', 'UserManageController@index')->name('userManage.index');
    Route::get('userManage/create', 'UserManageController@create')->name('userManage.create');
    Route::post('userManage/create', 'UserManageController@store')->name('userManage.store');
    Route::get('userManage/detail/{id}', [\App\Http\Controllers\Admin\UserManageController::class,'detail'])->name('userManage.detail');
    Route::get('userManage/edit/{id}', 'UserManageController@edit')->name('userManage.edit');
    Route::post('userManage/updateProfile/{id}', 'UserManageController@updateProfile')->name('userManage.updateProfile');
    Route::post('userManage/updatePassword/{id}', 'UserManageController@updatePassword')->name('userManage.updatePassword');

    Route::group(['namespace'=>'Setting', 'prefix'=>'setting', 'as'=>'setting.'], function() {
        Route::get('basic', 'BasicController@index')->name('basic.index');
        Route::post('basic', 'BasicController@store')->name('basic.store');

        Route::get('social', 'SocialController@index')->name('social.index');
        Route::post('social', 'SocialController@store')->name('social.store');

        Route::get('seo', 'SeoController@index')->name('seo.index');
        Route::post('seo', 'SeoController@store')->name('seo.store');
        Route::get('seo/generateSitemap', 'SeoController@generateSitemap')->name('seo.generateSitemap')->middleware('throttle:sitemap');
        Route::get('seo/downloadSitemap', 'SeoController@downloadSitemap')->name('seo.downloadSitemap')->middleware('throttle:sitemap');

        Route::get('script', 'ScriptController@index')->name('script.index');
        Route::post('script', 'ScriptController@store')->name('script.store');

        Route::get('analytics', 'AnalyticsController@index')->name('analytics.index');
        Route::post('analytics', 'AnalyticsController@store')->name('analytics.store');

        Route::get('color', 'ColorController@index')->name('color.index');
        Route::get('color/create/{type}', 'ColorController@create')->name('color.create');
        Route::post('color/create/{type}', 'ColorController@store')->name('color.store');
        Route::get('color/edit/{id}', 'ColorController@edit')->name('color.edit');
        Route::post('color/edit/{id}', 'ColorController@update')->name('color.update');
        Route::get('color/switchItem', 'ColorController@switchItem')->name('color.switchItem');

        Route::get('payment', 'PaymentController@index')
            ->name('payment.index')
            ->middleware("password.confirm");

        Route::post('payment', 'PaymentController@store')->name('payment.store');
    });


    Route::group(['namespace'=>'Purchase', 'prefix'=>'purchase', 'as'=>'purchase.'], function() {
        Route::get('order', 'OrderController@index')->name('order.index');
        Route::get('order/detail/{id}', 'OrderController@detail')->name('order.detail');

        Route::get('subscription', 'SubscriptionController@index')->name('subscription.index');
        Route::get('subscription/detail/{id}', 'SubscriptionController@detail')->name('subscription.detail');
        Route::post('subscription/cancel', 'SubscriptionController@cancel')->name('subscription.cancel');

        Route::get('transaction', 'TransactionController@index')->name('transaction.index');
        Route::get('transaction/invoice/{id}', 'TransactionController@invoice')->name('transaction.invoice');
        Route::get('transaction/invoice/{id}/download', 'TransactionController@invoiceDownload')->name('transaction.invoiceDownload');

        Route::get('form', 'FormController@index')->name('form.index');
        Route::get('form/detail/{id}', 'FormController@detail')->name('form.detail');
        Route::get('form/edit/{id}', 'FormController@edit')->name('form.edit');
        Route::post('form/edit/{id}', 'FormController@update')->name('form.update');
        Route::get('form/switch', 'FormController@switchForm')->name('form.switch');

        Route::get('blog', 'ProductController@blog')->name('blog.index');
        Route::get('blog/detail/{id}', 'ProductController@blogDetail')->name('blog.detail');

        Route::get('directory', 'ProductController@directory')->name('directory.index');
        Route::get('directory/detail/{id}', 'ProductController@directoryDetail')->name('directory.detail');

        Route::get('ecommerce', 'ProductController@ecommerce')->name('ecommerce.index');
        Route::get('ecommerce/detail/{id}', 'ProductController@ecommerceDetail')->name('ecommerce.detail');

    });

    Route::group(['namespace'=>'PurchaseFollowup', 'prefix'=>'purchasefollowup', 'as'=>'purchasefollowup.'], function() {
        Route::get('email', 'EmailController@index')->name('email.index');
        Route::post('email', 'EmailController@store')->name('email.store');
        Route::get('email/switch', 'EmailController@switch')->name('email.switch');

        Route::get('form', 'FormController@index')->name('form.index');
        Route::get('form/create', 'FormController@create')->name('form.create');
        Route::post('form/create', 'FormController@store')->name('form.store');
        Route::get('form/show/{id}', 'FormController@show')->name('form.show');
        Route::get('form/edit/{id}', 'FormController@edit')->name('form.edit');
        Route::get('form/switch', 'FormController@switch')->name('form.switch');
    });

//    Route::group(['prefix'=>'coupon', 'as'=>'coupon.'], function() {
//        Route::get('/', 'CouponController@index')->name('index');
//        Route::post('/', 'CouponController@store')->name('store');
//        Route::get('/product', 'CouponController@product')->name('product');
//        Route::get('/edit', 'CouponController@edit')->name('edit');
//        Route::get('/switch', 'CouponController@switch')->name('switch');
//    });
    Route::group(['namespace'=>'Blog', 'prefix'=>'blog', 'as'=>'blog.', 'middleware'=>'module_active:simple_blog|advanced_blog'], function() {

        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::group(['middleware'=>'module_active:advanced_blog'], function() {

            Route::get('setting', 'SettingController@index')->name('setting.index');
            Route::post('setting', 'SettingController@store')->name('setting.store');

            Route::get('package', 'PackageController@index')->name('package.index');
            Route::get('package/switch', 'PackageController@switch')->name('package.switch');
            Route::get('package/sort', 'PackageController@getSort')->name('package.sort');
            Route::post('package/sort', 'PackageController@updateSort');

            Route::get('package/create', 'PackageController@create')->name('package.create');
            Route::post('package/create', 'PackageController@store')->name('package.store');
            Route::get('package/edit/{id}', 'PackageController@edit')->name('package.edit');
            Route::post('package/edit/{id}', 'PackageController@update')->name('package.update');

            Route::post('package/updateMeetingForm/{id}', 'PackageController@updateMeetingForm')->name('package.updateMeetingForm');
            Route::post('package/createPrice/{id}', 'PackageController@createPrice')->name('package.createPrice');
            Route::delete('package/deletePrice/{id}', 'PackageController@deletePrice')->name('package.deletePrice');

            Route::get('author', 'AuthorController@index')->name('author.index');
        });

        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('tag', 'TagController@index')->name('tag.index');
        Route::post('tag', 'TagController@store')->name('tag.store');
        Route::get('tag/switch', 'TagController@switch')->name('tag.switch');

        Route::get('post', 'PostController@index')->name('post.index');
        Route::get('post/create', 'PostController@create')->name('post.create');
        Route::post('post/create', 'PostController@store')->name('post.store');
        Route::get('post/show/{id}', 'PostController@show')->name('post.show');
        Route::get('post/edit/{id}', 'PostController@edit')->name('post.edit');
        Route::post('post/edit/{id}', 'PostController@update')->name('post.update');
        Route::get('post/switchPost', 'PostController@switchPost')->name('post.switchPost');

        Route::get('comment', 'CommentController@index')->name('comment.index');
        Route::get('comment/show/{id}', 'CommentController@show')->name('comment.show');
        Route::get('comment/edit/{id}', 'CommentController@edit')->name('comment.edit');
        Route::post('comment/edit/{id}', 'CommentController@update')->name('comment.update');
        Route::get('comment/switchComment', 'CommentController@switchComment')->name('comment.switchComment');

    });

    Route::group(['namespace'=>'BlogAds', 'prefix'=>'blogAds', 'as'=>'blogAds.', 'middleware'=>'module_active:blogAds'], function() {

        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::get('type', 'TypeController@index')->name('type.index');
        Route::post('type', 'TypeController@store')->name('type.store');
        Route::get('type/switch', 'TypeController@switch')->name('type.switch');

        Route::get('position', 'PositionController@index')->name('position.index');
        Route::post('position', 'PositionController@store')->name('position.store');
        Route::get('position/switch', 'PositionController@switchPosition')->name('position.switch');

        Route::get('spot', 'SpotController@index')->name('spot.index');
        Route::get('spot/create', 'SpotController@create')->name('spot.create');
        Route::post('spot/create', 'SpotController@store')->name('spot.store');
        Route::get('spot/switch', 'SpotController@switchSpot')->name('spot.switch');
        Route::get('spot/getPosition', 'SpotController@getPosition')->name('spot.getPosition');
        Route::get('spot/edit/{id}', 'SpotController@edit')->name('spot.edit');
        Route::post('spot/edit/{id}', 'SpotController@update')->name('spot.update');
        Route::post('spot/createPrice/{id}', 'SpotController@createPrice')->name('spot.createPrice');
        Route::delete('spot/deletePrice/{id}', 'SpotController@deletePrice')->name('spot.deletePrice');
        Route::post('spot/updateListing/{id}', 'SpotController@updateListing')->name('spot.updateListing');

        Route::get('listing', 'ListingController@index')->name('listing.index');
        Route::get('listing/select', 'ListingController@select')->name('listing.select');
        Route::get('listing/create/{slug}', 'ListingController@create')->name('listing.create');
        Route::post('listing/create/{slug}', 'ListingController@store')->name('listing.store');
        Route::get('listing/show/{id}', 'ListingController@show')->name('listing.show');
        Route::get('listing/edit/{id}', 'ListingController@edit')->name('listing.edit');
        Route::post('listing/edit/{id}', 'ListingController@update')->name('listing.update');
        Route::get('listing/tracking/{id}', 'ListingController@tracking')->name('listing.tracking');
        Route::get('listing/getChart/{id}', 'ListingController@getChart')->name('listing.getChart');
        Route::post('listing/updateStatus/{id}', 'ListingController@updateStatus')->name('listing.updateStatus');
        Route::get('listing/switch', 'ListingController@switchListing')->name('listing.switch');

        Route::get('calendar', 'CalendarController@index')->name('calendar.index');
        Route::get('calendar/spot/{id}', 'CalendarController@spot')->name('calendar.spot');
        Route::get('calendar/events', 'CalendarController@events')->name('calendar.events');
    });

    Route::group(['namespace'=>'SiteAds', 'prefix'=>'siteAds', 'as'=>'siteAds.', 'middleware'=>'module_active:siteAds'], function() {
        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::get('type', 'TypeController@index')->name('type.index');
        Route::post('type', 'TypeController@store')->name('type.store');
        Route::get('type/switch', 'TypeController@switch')->name('type.switch');

        Route::get('spot', 'SpotController@index')->name('spot.index');
        Route::get('spot/page/{page_id}/{type_id}', 'SpotController@page')->name('spot.page');
        Route::get('spot/create/{page_id}/{type_id}', 'SpotController@create')->name('spot.create');
        Route::post('spot/create/{page_id}/{type_id}', 'SpotController@store')->name('spot.store');
        Route::get('spot/edit/{id}', 'SpotController@edit')->name('spot.edit');
        Route::post('spot/edit/{id}', 'SpotController@update')->name('spot.update');
        Route::get('spot/editPosition/{id}', 'SpotController@editPosition')->name('spot.editPosition');
        Route::post('spot/editPosition/{id}', 'SpotController@updatePosition')->name('spot.updatePosition');
        Route::post('spot/createPrice/{id}', 'SpotController@createPrice')->name('spot.createPrice');
        Route::delete('spot/deletePrice/{id}', 'SpotController@deletePrice')->name('spot.deletePrice');
        Route::post('spot/updateListing/{id}', 'SpotController@updateListing')->name('spot.updateListing');
        Route::get('spot/switchSpot', 'SpotController@switchSpot')->name('spot.switchSpot');
        Route::get('spot/getAds', 'SpotController@getAds')->name('spot.getAds');

        Route::get('listing', 'ListingController@index')->name('listing.index');
        Route::get('listing/select', 'ListingController@select')->name('listing.select');
        Route::get('listing/create/{slug}', 'ListingController@create')->name('listing.create');
        Route::post('listing/create/{slug}', 'ListingController@store')->name('listing.store');
        Route::get('listing/show/{id}', 'ListingController@show')->name('listing.show');
        Route::get('listing/edit/{id}', 'ListingController@edit')->name('listing.edit');
        Route::post('listing/edit/{id}', 'ListingController@update')->name('listing.update');
        Route::get('listing/tracking/{id}', 'ListingController@tracking')->name('listing.tracking');
        Route::get('listing/getChart/{id}', 'ListingController@getChart')->name('listing.getChart');
        Route::post('listing/updateStatus/{id}', 'ListingController@updateStatus')->name('listing.updateStatus');
        Route::get('listing/switch', 'ListingController@switchListing')->name('listing.switch');
    });

    Route::group(['namespace'=>'Portfolio', 'prefix'=>'portfolio', 'as'=>'portfolio.', 'middleware'=>'module_active:portfolio'], function() {
        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('item', 'ItemController@index')->name('item.index');
        Route::get('item/create', 'ItemController@create')->name('item.create');
        Route::post('item/create', 'ItemController@store')->name('item.store');
        Route::get('item/edit/{id}', 'ItemController@edit')->name('item.edit');
        Route::post('item/edit/{id}', 'ItemController@update')->name('item.update');
        Route::get('item/switch', 'ItemController@switch')->name('item.switch');

    });

    Route::group(['namespace'=>'Directory', 'prefix'=>'directory', 'as'=>'directory.', 'middleware'=>'module_active:directory'], function() {
        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::get('setting', 'SettingController@index')->name('setting.index');
        Route::post('setting', 'SettingController@store')->name('setting.store');

        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('tag', 'TagController@index')->name('tag.index');
        Route::post('tag', 'TagController@store')->name('tag.store');
        Route::get('tag/switch', 'TagController@switch')->name('tag.switch');

        Route::get('package', 'PackageController@index')->name('package.index');
        Route::get('package/switch', 'PackageController@switch')->name('package.switch');
        Route::get('package/sort', 'PackageController@getSort')->name('package.sort');
        Route::post('package/sort', 'PackageController@updateSort');

        Route::get('package/create', 'PackageController@create')->name('package.create');
        Route::post('package/create', 'PackageController@store')->name('package.store');
        Route::get('package/edit/{id}', 'PackageController@edit')->name('package.edit');
        Route::post('package/edit/{id}', 'PackageController@update')->name('package.update');

        Route::post('package/updateMeetingForm/{id}', 'PackageController@updateMeetingForm')->name('package.updateMeetingForm');
        Route::post('package/createPrice/{id}', 'PackageController@createPrice')->name('package.createPrice');
        Route::delete('package/deletePrice/{id}', 'PackageController@deletePrice')->name('package.deletePrice');

        Route::get('listing', 'ListingController@index')->name('listing.index');
        Route::get('listing/create', 'ListingController@create')->name('listing.create');
        Route::post('listing/create', 'ListingController@store')->name('listing.store');
        Route::get('listing/show/{id}', 'ListingController@edit')->name('listing.show');
        Route::get('listing/edit/{id}', 'ListingController@edit')->name('listing.edit');
    });

    Route::group(['namespace'=>'DirectoryAds', 'prefix'=>'directoryAds', 'as'=>'directoryAds.', 'middleware'=>'module_active:directoryAds'], function() {
        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::get('type', 'TypeController@index')->name('type.index');
        Route::post('type', 'TypeController@store')->name('type.store');
        Route::get('type/switch', 'TypeController@switch')->name('type.switch');

        Route::get('position', 'PositionController@index')->name('position.index');
        Route::post('position', 'PositionController@store')->name('position.store');
        Route::get('position/switch', 'PositionController@switchPosition')->name('position.switch');

        Route::get('spot', 'SpotController@index')->name('spot.index');
        Route::get('spot/create', 'SpotController@create')->name('spot.create');
        Route::post('spot/create', 'SpotController@store')->name('spot.store');
        Route::get('spot/switch', 'SpotController@switchSpot')->name('spot.switch');
        Route::get('spot/getPosition', 'SpotController@getPosition')->name('spot.getPosition');
        Route::get('spot/edit/{id}', 'SpotController@edit')->name('spot.edit');
        Route::post('spot/edit/{id}', 'SpotController@update')->name('spot.update');
        Route::post('spot/createPrice/{id}', 'SpotController@createPrice')->name('spot.createPrice');
        Route::delete('spot/deletePrice/{id}', 'SpotController@deletePrice')->name('spot.deletePrice');
        Route::post('spot/updateListing/{id}', 'SpotController@updateListing')->name('spot.updateListing');


        Route::get('listing', 'ListingController@index')->name('listing.index');
        Route::get('listing/select', 'ListingController@select')->name('listing.select');
        Route::get('listing/create/{slug}', 'ListingController@create')->name('listing.create');
        Route::post('listing/create/{slug}', 'ListingController@store')->name('listing.store');
        Route::get('listing/show/{id}', 'ListingController@show')->name('listing.show');
        Route::get('listing/edit/{id}', 'ListingController@edit')->name('listing.edit');
        Route::post('listing/edit/{id}', 'ListingController@update')->name('listing.update');
        Route::get('listing/tracking/{id}', 'ListingController@tracking')->name('listing.tracking');
        Route::get('listing/getChart/{id}', 'ListingController@getChart')->name('listing.getChart');
        Route::post('listing/updateStatus/{id}', 'ListingController@updateStatus')->name('listing.updateStatus');
        Route::get('listing/switch', 'ListingController@switchListing')->name('listing.switch');

    });

    Route::group(['namespace'=>'Ecommerce', 'prefix'=>'ecommerce', 'as'=>'ecommerce.', 'middleware'=>'module_active:ecommerce'], function() {
        Route::get('front', 'FrontController@index')->name('front.index');
        Route::post('front', 'FrontController@store')->name('front.store');

        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('product', 'ProductController@index')->name('product.index');
        Route::get('product/create', 'ProductController@create')->name('product.create');
        Route::post('product/create', 'ProductController@store')->name('product.store');
        Route::get('product/edit/{id}', 'ProductController@edit')->name('product.edit');
        Route::post('product/updateProduct/{id}', 'ProductController@updateProduct')->name('product.updateProduct');
        Route::get('product/getPrice/{id}', 'ProductController@getPrice')->name('product.getPrice');
        Route::post('product/createPrice/{id}', 'ProductController@createPrice')->name('product.createPrice');
        Route::post('product/updatePrice/{id}', 'ProductController@updatePrice')->name('product.updatePrice');
        Route::delete('product/delPrice/{id}', 'ProductController@delPrice')->name('product.delPrice');
        Route::get('product/switch', 'ProductController@switch')->name('product.switch');

    });
    Route::group(['namespace'=>'Email', 'prefix'=>'email', 'as'=>'email.', 'middleware'=>'module_active:email'], function() {

        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('/template', 'TemplateController@index')->name('template.index');
        Route::get('/template/onlineTemplate', 'TemplateController@onlineTemplate')->name('template.onlineTemplate');
        Route::get('/template/create', 'TemplateController@create')->name('template.create');
        Route::post('/template/create', 'TemplateController@store')->name('template.store');
        Route::get('/template/edit/{id}', 'TemplateController@edit')->name('template.edit');
        Route::get('/template/show/{id}', 'TemplateController@show')->name('template.show');
        Route::post('/template/updateBody/{id}', 'TemplateController@updateBody')->name('template.updateBody');
        Route::get('/template/switch', 'TemplateController@switch')->name('template.switch');
        Route::get('/template/viewOnlineTemplate/{slug}', 'TemplateController@viewOnlineTemplate')->name('template.viewOnlineTemplate');
        Route::post('/template/saveOnlineTemplate', 'TemplateController@saveOnlineTemplate')->name('template.saveOnlineTemplate');

        Route::get('/campaign', 'CampaignController@index')->name('campaign.index');
        Route::get('/campaign/create', 'CampaignController@create')->name('campaign.create');
        Route::post('/campaign/create', 'CampaignController@store')->name('campaign.store');
        Route::get('/campaign/edit/{id}', 'CampaignController@edit')->name('campaign.edit');
        Route::get('/campaign/show/{id}', 'CampaignController@show')->name('campaign.show');
        Route::get('/campaign/switch', 'CampaignController@switch')->name('campaign.switch');
        Route::get('/campaign/sendNow', 'CampaignController@sendNow')->name('campaign.sendNow');
        Route::get('/campaign/getCategory', 'CampaignController@getCategory')->name('campaign.getCategory');
        Route::get('/campaign/getTemplate', 'CampaignController@getTemplate')->name('campaign.getTemplate');

        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/switch', 'SubscriberController@switch')->name('subscriber.switch');
    });

    Route::group(['namespace'=>'Appointment', 'prefix'=>'appointment', 'as'=>'appointment.', 'middleware'=>'module_active:appointment'], function() {
        Route::get('setting', 'SettingController@index')->name('setting.index');
        Route::post('setting', 'SettingController@store')->name('setting.store');

        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::get('category/create', 'CategoryController@create')->name('category.create');
        Route::post('category/create', 'CategoryController@store')->name('category.store');
        Route::get('category/edit/{id}', 'CategoryController@edit')->name('category.edit');
        Route::post('category/edit/{id}', 'CategoryController@update')->name('category.update');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('unavailable-dates/{type}/{id}', 'BlockDateController@index')->name('blockDate.index');
        Route::get('unavailable-dates/edit/{type}/{id}', 'BlockDateController@edit')->name('blockDate.edit');
        Route::post('unavailable-dates/{type}/{id}', 'BlockDateController@store')->name('blockDate.store');
        Route::post('unavailable-dates/delete/{type}/{id}', 'BlockDateController@delete')->name('blockDate.delete');

        Route::get('/listing', 'ListingController@index')->name('listing.index');
        Route::get('/listing/getData', 'ListingController@getData')->name('listing.getData');
        Route::get('/listing/edit/{id}', 'ListingController@edit')->name('listing.edit');
        Route::post('/listing/approve/{id}', 'ListingController@update')->name('listing.update');
        Route::get('/listing/detail/{id}', 'ListingController@detail')->name('listing.detail');
        Route::get('/listing/switch', 'ListingController@switchListing')->name('listing.switch');
        Route::get('/listing/allListing', 'ListingController@allListing')->name('listing.allListing');

    });

    Route::group(['namespace'=>'Content', 'prefix'=>'content', 'as'=>'content.'], function() {

        Route::group(['prefix'=>'review', 'as'=>'review.', 'middleware'=>'module_active:review'], function() {
            Route::get('/', 'ReviewController@index')->name('index');
            Route::get('/show/{id}', 'ReviewController@show')->name('show');
            Route::get('/edit', 'ReviewController@edit')->name('edit');
            Route::post('/edit', 'ReviewController@update')->name('update');
            Route::get('/switch', 'ReviewController@switch')->name('switch');
        });

        Route::group(['prefix'=>'page', 'as'=>'page.'], function() {
            Route::get('/', 'PageController@index')->name('index');
            Route::get('/create', 'PageController@create')->name('create');
            Route::post('/create', 'PageController@store')->name('store');
            Route::get('/edit/{id}', 'PageController@edit')->name('edit');
            Route::post('/edit/{id}', 'PageController@update')->name('update');
            Route::get('/editContent/{id}/{type}', 'PageController@editContent')->name('editContent');
            Route::post('/editContent/{id}/{type}', 'PageController@updateContent')->name('updateContent');
            Route::get('/switch', 'PageController@switch')->name('switch');

            Route::post('/upload/cover/{page_id}', 'UploadController@uploadCover')->name('uploadCover')->middleware("storage_check");
            Route::post('/upload/largeImage/{page_id}', 'UploadController@uploadLarageImage')->name('largeImage')->middleware("storage_check");
            Route::post('/upload/moduleImage/{page_id}', 'UploadController@uploadModuleImage')->name('moduleImage')->middleware("storage_check");
            Route::post('/upload/moduleVideo/{page_id}', 'UploadController@uploadModuleVideo')->name('moduleVideo')->middleware("storage_check");
            Route::post('/upload/saveImage/{page_id}', 'UploadController@storeImage')->name('saveImage')->middleware("storage_check");

        });
        Route::group(['prefix'=>'legalPage', 'as'=>'legalPage.'], function() {
            Route::get('/', 'LegalPageController@index')->name('index');
            Route::get('/edit/{id}', 'LegalPageController@edit')->name('edit');
            Route::post('/edit/{id}', 'LegalPageController@update')->name('update');
        });
        Route::group(['prefix'=>'header', 'as'=>'header.'], function() {
            Route::get('/', 'HeaderController@index')->name('index');
            Route::post('/store', 'HeaderController@store')->name('store');
            Route::get('/edit', 'HeaderController@edit')->name('edit');
            Route::delete('/delete', 'HeaderController@delete')->name('delete');
            Route::get('/switchItem', 'HeaderController@switchItem')->name('switchItem');
            Route::get('/updateOrder', 'HeaderController@updateOrder')->name('updateOrder');
        });
        Route::group(['prefix'=>'footer', 'as'=>'footer.'], function() {
            Route::get('/', 'FooterController@index')->name('index');
            Route::post('/store', 'FooterController@store')->name('store');
            Route::get('/edit', 'FooterController@edit')->name('edit');
            Route::delete('/delete', 'FooterController@delete')->name('delete');
            Route::get('/switchItem', 'FooterController@switchItem')->name('switchItem');
            Route::get('/updateOrder', 'FooterController@updateOrder')->name('updateOrder');
        });
    });

    Route::group(['namespace'=>'Ticket', 'prefix'=>'ticket', 'as'=>'ticket.'], function() {
        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category', 'CategoryController@store')->name('category.store');
        Route::get('category/switch', 'CategoryController@switch')->name('category.switch');
        Route::get('category/sort', 'CategoryController@getSort')->name('category.sort');
        Route::post('category/sort', 'CategoryController@updateSort');

        Route::get('item', 'ItemController@index')->name('item.index');
        Route::get('item/reply/{id}', 'ItemController@edit')->name('item.edit');
        Route::get('item/show/{id}', 'ItemController@show')->name('item.show');
        Route::post('item/reply/{id}', 'ItemController@update')->name('item.update');
        Route::get('item/switch', 'ItemController@switchTicket')->name('item.switch');

    });

    Route::group(['prefix'=>'storage', 'as'=>'storage.'], function() {
        Route::get('/', 'StorageController@index')->name('index');
        Route::get('/getFrame', 'StorageController@getFrame')->name('getFrame');
        Route::get('/loadSize', 'StorageController@loadSize')->name('loadSize');
    });
    Route::group(['prefix'=>'module', 'as'=>'module.'], function() {
        Route::get('/', 'ModuleController@index')->name('index');
        Route::get('/getAllModules', 'ModuleController@getAllModules')->name('getAllModules');
        Route::get('/getMyModules', 'ModuleController@getMyModules')->name('getMyModules');
        Route::get('/getModule', 'ModuleController@getModule')->name('getModule');
        Route::get('/switchModule', [Admin\ModuleController::class,'switchModule'])->name('switchModule');
    });

    Route::group(['namespace'=>'Notification', 'prefix'=>'notification', 'as'=>'notification.'], function() {
        Route::get('template', 'TemplateController@index')->name('template.index');
        Route::get('template/create', 'TemplateController@create')->name('template.create');
        Route::post('template/create', 'TemplateController@store')->name('template.store');
        Route::get('template/edit/{id}', 'TemplateController@edit')->name('template.edit');
        Route::get('template/show/{id}', 'TemplateController@show')->name('template.show');
        Route::get('template/switch', 'TemplateController@switch')->name('template.switch');

    });
});
