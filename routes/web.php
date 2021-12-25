<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\Front as Front;

Auth::routes(['verify' => true]);

Route::get('/uploads/{disk}/{path}', 'FileController@get')->where('path', '(.*)')->name('file.get');
Route::get('/sitemap.xml', 'FileController@sitemap')->name('sitemap.get');

Route::get('/cart', 'Front\CartController@index')->name('cart.index');
Route::post('/cart', 'Front\CartController@update')->name('cart.update');
Route::get('/cart/getData', 'Front\CartController@getData')->name('cart.getData');
Route::get('/cart/remove', 'Front\CartController@remove')->name('cart.remove');
Route::get('/cart/empty', 'Front\CartController@empty')->name('cart.empty');
Route::get('/cart/checkout', 'Front\CartController@checkout')->name('cart.checkout');

Route::get('/cart/login', 'Front\PaymentController@login')->name('cart.login');
Route::post('/cart/paypal/getUrl', 'Front\PaymentController@paypalGetUrl')->name('cart.paypal.getUrl')
    ->middleware(ProtectAgainstSpam::class);
Route::get('/cart/paypal/execute', 'Front\PaymentController@paypalExecute')->name('cart.paypal.execute');
Route::post('/cart/stripe/execute', 'Front\PaymentController@stripeExecute')->name('cart.stripe.execute')
    ->middleware(ProtectAgainstSpam::class);

Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider')->name('social.login');
Route::get('auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback')->name('social.redirect');

Route::get('/home', 'HomeController@index')->name('dashboard');

Route::group(['middleware'=>['auth','verified']], function(){
    Route::get('/{role}/profile', 'HomeController@profile')->name('profile');
    Route::get('/{role}/subscribed', 'HomeController@subscribed')->name('subscribed');
    Route::post('/{role}/subscribed', 'HomeController@subscribedUpdate')->name('subscribed.update')->middleware(ProtectAgainstSpam::class);
    Route::get('/{role}/subscribed/switch', 'HomeController@subscribedSwitch')->name('subscribed.switch');
    Route::post('/account/profileUpdate', 'HomeController@profileUpdate')->name('account.profile.update');
    Route::post('/account/passwordUpdate', 'HomeController@passwordUpdate')->name('account.password.update');
    Route::get('/{role}/notifications', 'HomeController@notifications')->name('notification');
    Route::get('/{role}/notifications/{id}/detail', 'HomeController@notificationDetail')->name('notification.detail');
    Route::get('/{role}/notifications/switch', 'HomeController@notificationSwitch')->name('notification.switch');

    Route::post('/uploadImage/{folder?}', 'HomeController@uploadImage')->name('uploadImage');
    Route::post('/uploadImages/{id}', 'HomeController@uploadImages')->name('uloapdImages');
});

Route::group(['namespace'=>'Front'], function() {

    Route::group(['middleware'=>'module_publish:email'], function() {
        Route::get('/getSubscribeForm', 'HomeController@getSubscribeForm')->name('getSubscribeForm');
        Route::get('/closeSubscribeForm', 'HomeController@closeSubscribeForm')->name('closeSubscribeForm');
        Route::post('/subscribe', 'HomeController@subscribe')->name('subscribe')->middleware(ProtectAgainstSpam::class);
        Route::get('/subscribe/{token}', 'HomeController@subscribeConfirm')->name('subscribe.confirm');
    });

    Route::get('/unsubscribe', 'HomeController@unsubscribe')->name('unsubscribe');
    Route::post('/unsubscribe', 'HomeController@unsubscribeConfirm')->name('unsubscribe.confirm')->middleware(ProtectAgainstSpam::class);
    Route::get('/mail/{id}', 'HomeController@mail')->name('mail.view');

    Route::group(['middleware'=>'module_publish:simple_blog|advanced_blog', 'as'=>'blog.', 'prefix'=>config("custom.route.blog")], function() {

        Route::get('/', 'BlogController@index')->name('index');
        Route::get('/ajaxPage', [Front\BlogController::class,'ajaxPage'])->name('ajaxPage');
        Route::get('/ajaxCategory/{id}', 'BlogController@ajaxCategory')->name('ajaxCategory');
        Route::get('/ajaxTag/{id}', 'BlogController@ajaxTag')->name('ajaxTag');
        Route::get('/ajaxAuthor/{username}', 'BlogController@ajaxAuthor')->name('ajaxAuthor');
        Route::get('/ajaxComment/{slug}', 'BlogController@ajaxComment')->name('ajaxComment');
        Route::get('/detail/{slug}', 'BlogController@detail')->name('detail');
        Route::get('/tag/{slug}', 'BlogController@tag')->name('tag');
        Route::get('/category/{slug}', 'BlogController@category')->name('category');
        Route::get('/all-posts', 'BlogController@allPosts')->name('allPosts');
        Route::get('/search', 'BlogController@search')->name('search');
        Route::get('/getCommentForm/{id}', 'BlogController@getCommentForm')->name('getCommentForm');
        Route::get('/author/@{username}', 'BlogController@author')->name('author');
        Route::get('/favoriteComment/add', 'BlogController@favoriteComment')->name('favoriteComment');
        Route::post('/postComment/{id}', 'BlogController@postComment')->name('postComment')->middleware(ProtectAgainstSpam::class);

        Route::group(['middleware'=>'module_publish:advanced_blog'], function() {
            Route::get('/packages', 'BlogController@package')->name('package');
            Route::get('/packages/{slug}', 'BlogController@packageDetail')->name('package.detail');
            Route::get('/packages/{id}/addtocart', 'BlogController@addtocart')->name('package.addtocart');
        });
    });
    Route::group(['middleware'=>'module_publish:blogAds', 'as'=>'blogAds.', 'prefix'=>config("custom.route.blogAds")], function() {
        Route::get('/', 'BlogAdsController@index')->name('index');
        Route::post('/getData', 'BlogAdsController@getData')->name('getData');
        Route::post('/addtocart/{id}', 'BlogAdsController@addtocart')->name('addtocart');
        Route::get('/spot/{slug}', 'BlogAdsController@spot')->name('spot');
        Route::post('/impClick', 'BlogAdsController@impClick')->name('impClick');
    });
    Route::group(['middleware'=>'module_publish:siteAds', 'as'=>'siteAds.', 'prefix'=>config("custom.route.siteAds")], function() {
        Route::get('/', 'SiteAdsController@index')->name('index');
        Route::post('/getData', 'SiteAdsController@getData')->name('getData');
        Route::post('/addtocart/{id}', 'SiteAdsController@addtocart')->name('addtocart');
        Route::get('/spot/{slug}', 'SiteAdsController@spot')->name('spot');
        Route::post('/impClick', 'SiteAdsController@impClick')->name('impClick');
    });
    Route::group(['middleware'=>'module_publish:portfolio', 'as'=>'portfolio.', 'prefix'=>config("custom.route.portfolio")], function() {
        Route::get('/', 'PortfolioController@index')->name('index');
        Route::get('/{slug}', 'PortfolioController@detail')->name('detail');
    });
    Route::group(['middleware'=>'module_publish:ecommerce', 'as'=>'ecommerce.', 'prefix'=>config("custom.route.ecommerce")], function() {
        Route::get('/', 'EcommerceController@index')->name('index');
        Route::get('/{slug}', 'EcommerceController@detail')->name('detail');
        Route::get('/{slug}/addtocart', 'EcommerceController@addtocart')->name('addtocart');
    });
    Route::group(['middleware'=>'module_publish:directory', 'as'=>'directory.', 'prefix'=>config("custom.route.directory")], function() {
        Route::get('/', 'DirectoryController@index')->name('index');
        Route::get('/category/{slug}', 'DirectoryController@category')->name('category');
        Route::get('/category/{category}/{subCategory}', 'DirectoryController@subCategory')->name('subCategory');
        Route::get('/tag/{slug}', 'DirectoryController@tag')->name('tag');
        Route::get('/detail/{slug}', 'DirectoryController@detail')->name('detail');
        Route::get('/packages', 'DirectoryController@package')->name('package');
        Route::get('/packages/{slug}', 'DirectoryController@packageDetail')->name('package.detail');
        Route::get('/packages/{id}/addtocart', 'DirectoryController@addtocart')->name('package.addtocart');
    });
    Route::group(['middleware'=>'module_publish:directoryAds', 'as'=>'directoryAds.', 'prefix'=>config("custom.route.directoryAds")], function() {
        Route::get('/', 'DirectoryAdsController@index')->name('index');
        Route::post('/getData', 'DirectoryAdsController@getData')->name('getData');
        Route::post('/addtocart/{id}', 'DirectoryAdsController@addtocart')->name('addtocart');
        Route::get('/spot/{slug}', 'DirectoryAdsController@spot')->name('spot');
        Route::post('/impClick', 'DirectoryAdsController@impClick')->name('impClick');
    });
    Route::group(['middleware'=>'module_publish:review', 'as'=>'review.', 'prefix'=>config("custom.route.review")], function() {
        Route::get('/', 'ReviewController@index')->name('index');
        Route::post('/', 'ReviewController@store')->name('store')->middleware(ProtectAgainstSpam::class);
    });

    Route::get('/{url?}', [\App\Http\Controllers\Front\PageController::class,'index'])->where('url', '([A-Za-z0-9\-\/]+)')->name('home');
});
