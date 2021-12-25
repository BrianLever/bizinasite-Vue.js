<?php

use Illuminate\Support\Facades\Route;


Route::group(['as'=>'user.', 'prefix'=>'account', 'namespace'=>'User','middleware'=>['auth','verified']], function() {
    Route::get('/todo', 'TodoController@index')->name('todo.index');
    Route::get('/getTodoCount', 'TodoController@getTodoCount')->name('todo.getTodoCount');
    Route::get('/todo/{type}', 'TodoController@detail')->name('todo.detail');

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix'=>'blog', 'as'=>'blog.', 'middleware'=>'module_publish:advanced_blog'], function() {
        Route::get('/', 'BlogController@index')->name('index');
        Route::get('create', 'BlogController@create')->name('create');
        Route::post('create', 'BlogController@store')->name('store');
        Route::get('detail/{slug}', 'BlogController@detail')->name('detail');
        Route::get('edit/{slug}', 'BlogController@edit')->name('edit');
        Route::post('edit/{slug}', 'BlogController@update')->name('update');
    });

    Route::group(['prefix'=>'blogAds', 'as'=>'blogAds.', 'middleware'=>'module_publish:blogAds'], function() {
        Route::get('/', 'BlogAdsController@index')->name('index');
        Route::get('/detail/{id}', 'BlogAdsController@detail')->name('detail');
        Route::get('/edit/{id}', 'BlogAdsController@edit')->name('edit');
        Route::post('/edit/{id}', 'BlogAdsController@update')->name('update');
        Route::get('/tracking/{id}', 'BlogAdsController@tracking')->name('tracking');
        Route::get('/getChart/{id}', 'BlogAdsController@getChart')->name('getChart');
    });

    Route::group(['prefix'=>'siteAds', 'as'=>'siteAds.', 'middleware'=>'module_publish:siteAds'], function() {
        Route::get('/', 'SiteAdsController@index')->name('index');
        Route::get('/detail/{id}', 'SiteAdsController@detail')->name('detail');
        Route::get('/edit/{id}', 'SiteAdsController@edit')->name('edit');
        Route::post('/edit/{id}', 'SiteAdsController@update')->name('update');
        Route::get('/tracking/{id}', 'SiteAdsController@tracking')->name('tracking');
        Route::get('/getChart/{id}', 'SiteAdsController@getChart')->name('getChart');
    });

    Route::group(['prefix'=>'directory', 'as'=>'directory.', 'middleware'=>'module_publish:directory'], function() {
        Route::get('/', 'DirectoryController@index')->name('index');
        Route::get('select', 'DirectoryController@select')->name('select');
        Route::get('/create/{id}', 'DirectoryController@create')->name('create');
        Route::post('create/{id}', 'DirectoryController@store')->name('store');
        Route::get('show/{slug}', 'DirectoryController@show')->name('show');
        Route::get('edit/{slug}', 'DirectoryController@edit')->name('edit');
        Route::post('edit/{slug}', 'DirectoryController@update')->name('update');
    });

    Route::group(['prefix'=>'directoryAds', 'as'=>'directoryAds.', 'middleware'=>'module_publish:directoryAds'], function() {
        Route::get('/', 'DirectoryAdsController@index')->name('index');
        Route::get('/detail/{id}', 'DirectoryAdsController@detail')->name('detail');
        Route::get('/edit/{id}', 'DirectoryAdsController@edit')->name('edit');
        Route::post('/edit/{id}', 'DirectoryAdsController@update')->name('update');
        Route::get('/tracking/{id}', 'DirectoryAdsController@tracking')->name('tracking');
        Route::get('/getChart/{id}', 'DirectoryAdsController@getChart')->name('getChart');
    });

    Route::group(['prefix'=>'ecommerce', 'as'=>'ecommerce.', 'middleware'=>'module_publish:ecommerce'], function() {
        Route::get('/', 'EcommerceController@index')->name('index');
        Route::get('detail/{id}', 'EcommerceController@detail')->name('detail');
        Route::get('edit/{slug}', 'EcommerceController@edit')->name('edit');
        Route::post('edit/{slug}', 'EcommerceController@update')->name('update');
    });
    Route::group(['prefix'=>'ticket', 'as'=>'ticket.', 'middleware'=>'module_publish:ticket'], function() {
        Route::get('/', 'TicketController@index')->name('index');
        Route::get('create', 'TicketController@create')->name('create');
        Route::post('create', 'TicketController@store')->name('store');
        Route::get('reply/{id}', 'TicketController@edit')->name('edit');
        Route::get('show/{id}', 'TicketController@show')->name('show');
        Route::post('reply/{id}', 'TicketController@update')->name('update');
        Route::get('switch', 'TicketController@switch')->name('switch');
    });
    Route::group(['prefix'=>'appointment', 'as'=>'appointment.', 'middleware'=>'module_publish:appointment'], function() {
        Route::get('/', 'AppointmentController@index')->name('index');
        Route::get('create', 'AppointmentController@create')->name('create');
        Route::get('detail/{id}', 'AppointmentController@detail')->name('detail');
        Route::get('edit/{id}', 'AppointmentController@edit')->name('edit');
        Route::get('cancel/{id}', 'AppointmentController@cancel')->name('cancel');
        Route::get('selectProduct', 'AppointmentController@selectProduct')->name('selectProduct');
        Route::get('selectCategory', 'AppointmentController@selectCategory')->name('selectCategory');
        Route::post('store', 'AppointmentController@store')->name('store');
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

//        Route::get('blog', 'ProductController@blog')->name('blog.index');
//        Route::get('blog/detail/{id}', 'ProductController@blogDetail')->name('blog.detail');

        Route::get('form', 'FormController@index')->name('form.index');
        Route::get('form/detail/{id}', 'FormController@detail')->name('form.detail');
        Route::get('form/edit/{id}', 'FormController@edit')->name('form.edit');
        Route::post('form/edit/{id}', 'FormController@update')->name('form.update');
        Route::get('form/switch', 'FormController@switchForm')->name('form.switch');

    });

});
