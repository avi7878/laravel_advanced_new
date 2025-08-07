<?php

use Illuminate\Support\Facades\Route;

/* User routes =========================================================================== */

Route::get('cron/schedule/run', function () {
    $artisan = new \Illuminate\Support\Facades\Artisan();
    $artisan::call("schedule:run");
    return $artisan::output();
})->name('cron/schedule/run');


Route::group(['middleware' => ['web']], function () {
    Route::get('/', '\App\Http\Controllers\FrontController@index')->name('home');
    Route::get('page/{slug}', '\App\Http\Controllers\FrontController@page')->name('page');
    Route::get('contact', '\App\Http\Controllers\FrontController@contact')->name('contact');
    Route::post('contact-process', '\App\Http\Controllers\FrontController@contactProcess')->name('contact-process');

    Route::get('register', '\App\Http\Controllers\SiteController@register')->name('register');
    Route::post('site/register-process', '\App\Http\Controllers\SiteController@registerProcess')->name('site/register-process');
    Route::get('site/verify-account', '\App\Http\Controllers\SiteController@verifyAccount')->name('site/verify-account');
    Route::post('site/verify-account-process', '\App\Http\Controllers\SiteController@verifyAccountProcess')->name('site/verify-account-process');
    Route::get('site/password-forgot', '\App\Http\Controllers\SiteController@passwordForgot')->name('site/password-forgot');
    Route::post('site/password-forgot-process', '\App\Http\Controllers\SiteController@passwordForgotProcess')->name('site/password-forgot-process');

    Route::get('login', '\App\Http\Controllers\AuthController@login')->name('login');
    Route::post('auth/login-process', '\App\Http\Controllers\AuthController@loginProcess')->name('auth/login-process');
    Route::get('logout', '\App\Http\Controllers\AuthController@logout')->name('logout');
    Route::get('auth/login-otp', '\App\Http\Controllers\AuthController@loginOtp')->name('auth/login-otp');
    Route::post('auth/login-otp-process', '\App\Http\Controllers\AuthController@loginOtpProcess')->name('auth/login-otp-process');
    Route::get('auth/verify', '\App\Http\Controllers\AuthController@verify')->name('auth/verify');
    Route::post('auth/verify-process', '\App\Http\Controllers\AuthController@verifyProcess')->name('auth/verify-process');
    Route::post('auth/resend-otp', '\App\Http\Controllers\AuthController@resendOTP')->name('auth/resend-otp');
    Route::post('/remove-totp', '\App\Http\Controllers\AuthController@removeTotp')->name('remove-totp');
    Route::get('oauth/login/{type}', '\App\Http\Controllers\AuthController@socialLogin')->name('oauth/login');
    Route::get('oauth/callback/{type}', '\App\Http\Controllers\AuthController@socialLoginCallback')->name('oauth/callback');
    
    
    Route::get('blog', '\App\Http\Controllers\BlogController@index')->name('blog');
    Route::post('blog/list', '\App\Http\Controllers\BlogController@list')->name('blog/list');
    Route::get('blog/{slug}', 'App\Http\Controllers\BlogController@view')->name('blog/view');
    Route::post('/blog/list', '\App\Http\Controllers\BlogController@list');
    
    Route::post('stripe/webhook', 'App\Http\Controllers\SubscriptionController@handleStripeWebhook')->name('stripe/webhook');
    Route::get('/admin/page/show', '\App\Http\Controllers\Admin\PageController@show')->name('admin/page/show');

});


Route::group(['middleware' => ['web', 'user']], function () {
    Route::get('dashboard', '\App\Http\Controllers\SiteController@dashboard')->name('dashboard');
    Route::get('billing-portal', '\App\Http\Controllers\BillingController@createBillingPortal')->name('billing-portal');
    Route::get('note', '\App\Http\Controllers\NoteController@index')->name('note');
    Route::post('note/list', '\App\Http\Controllers\NoteController@list')->name('note/list');
    Route::get('note/create', '\App\Http\Controllers\NoteController@create')->name('note/create');
    Route::get('note/update', '\App\Http\Controllers\NoteController@update')->name('note/update');
    Route::post('note/save', '\App\Http\Controllers\NoteController@save')->name('note/save');
    Route::post('note/delete', '\App\Http\Controllers\NoteController@delete')->name('note/delete');
    Route::get('note/view', '\App\Http\Controllers\NoteController@view')->name('note/view');
    
    Route::get('account/update', '\App\Http\Controllers\AccountController@update')->name('account/update');
    Route::post('account/update-process', '\App\Http\Controllers\AccountController@updateProcess')->name('account/update-process');
    Route::get('account/image', '\App\Http\Controllers\AccountController@image')->name('account/image');
    Route::post('account/image-save', '\App\Http\Controllers\AccountController@imageSave')->name('account/image-save');
    Route::post('account/image-delete', '\App\Http\Controllers\AccountController@deleteImage')->name('account/image-delete');
    Route::get('account/password-change', '\App\Http\Controllers\AccountController@passwordChange')->name('account/password-change');
    Route::post('account/password-change-process', '\App\Http\Controllers\AccountController@changePasswordProcess')->name('account/password-change-process');
    Route::get('account/tfa', '\App\Http\Controllers\AccountController@tfa')->name('account/tfa');
    Route::post('account/tfa-status-change', '\App\Http\Controllers\AccountController@tfaStatusChange')->name('account/tfa-status-change');
    Route::post('account/revoke-all', '\App\Http\Controllers\AccountController@revokeAll')->name('account/revoke-all');
    Route::get('/get-qr-modal', '\App\Http\Controllers\AuthController@getTotpModel')->name('get-qr-modal');
    Route::get('otp.verify', '\App\Http\Controllers\AuthController@verifyOtpModal')->name('otp.verify');
    Route::post('otp.confirm', '\App\Http\Controllers\AuthController@optVerifyProcess')->name('otp.confirm');
    Route::get('backup-code', '\App\Http\Controllers\AuthController@backupCode')->name('backup-code');
    Route::get('backup-codes.regenerate', '\App\Http\Controllers\AuthController@optVerifyProcess')->name('backup-codes.regenerate');
    Route::get('account/device', '\App\Http\Controllers\AccountController@device')->name('account/device');
    Route::post('account/device-list', '\App\Http\Controllers\AccountController@deviceList')->name('account/device-list');
    Route::post('account/device-logout', '\App\Http\Controllers\AccountController@deviceLogout')->name('account/device-logout');

    Route::get('account/user-activity', '\App\Http\Controllers\AccountController@userActivity')->name('account/user-activity');
    Route::post('account/user-activity-list', '\App\Http\Controllers\AccountController@userActivityList')->name('account/user-activity-list');
    Route::post('account/deactivate', '\App\Http\Controllers\Admin\AccountController@accountDeactivate')->name('account/deactivate');

    // Chat Routes
    Route::get('chat', '\App\Http\Controllers\ChatController@index')->name('chat');
    Route::post('/chat/send-message', '\App\Http\Controllers\ChatController@sendMessage')->name('chat.sendMessage')->middleware('auth');


    Route::post('chat/list', '\App\Http\Controllers\ChatController@list')->name('chat/list');
    Route::post('chat/detail', '\App\Http\Controllers\ChatController@detail')->name('chat/detail');
    Route::any('chat/message-list', '\App\Http\Controllers\ChatController@messageList')->name('chat/message-list');
    Route::post('chat/message-send', '\App\Http\Controllers\ChatController@messageSend')->name('chat/message-send');

    Route::get('plan', '\App\Http\Controllers\PlanController@index')->name('plan');
    Route::post('plan-select','App\Http\Controllers\SubscriptionController@planSelect')->name('plan-select');
    Route::get('checkout/success','App\Http\Controllers\SubscriptionController@checkoutSuccess')->name('checkout/success');
    Route::get('checkout/cancel','App\Http\Controllers\SubscriptionController@checkoutCancel')->name('checkout/cancel');

    Route::post('chat/list', '\App\Http\Controllers\ChatController@list')->name('chat/list');
    Route::any('chat-message/list', '\App\Http\Controllers\ChatController@list')->name('chat-message/list');
    Route::post('chat-message/send', '\App\Http\Controllers\ChatController@send')->name('chat-message/send');

    Route::get('chat/group/create_modal', '\App\Http\Controllers\ChatController@groupCreateModal')->name('chat/group/create_modal');
    Route::post('chat/group/create', '\App\Http\Controllers\ChatController@groupCreate')->name('chat/group/create');
    Route::post('/chat/read_status', '\App\Http\Controllers\ChatController@readStatus')->name('chat/read_status');
    Route::post('/chat/upload_file', '\App\Http\Controllers\ChatController@uploadFile')->name('chat/upload_file');
    Route::get('chat/test', '\App\Http\Controllers\ChatController@test')->name('chat/test');
    Route::get('chat/userdata/{id}', '\App\Http\Controllers\ChatController@userdata')->name('chat/userdata');
    Route::post('chat/delete', '\App\Http\Controllers\ChatController@delete')->name('chat/delete');
    Route::get('chat/adduser', '\App\Http\Controllers\ChatController@addUser')->name('chat/adduser');
    Route::get('chat/usersuggestion', '\App\Http\Controllers\ChatController@userSuggestion')->name('chat/usersuggestion');
    Route::post('chat/addusers', '\App\Http\Controllers\ChatController@addUsers')->name('chat/addusers');
    Route::post('chat/remove', '\App\Http\Controllers\ChatController@remove')->name('chat/remove');
    Route::any('chat/user_chat_list', '\App\Http\Controllers\ChatController@chat_user_list')->name('chat/user_chat_list');
    
    Route::get('support', '\App\Http\Controllers\SupportController@index')->name('support');
    Route::get('support/tickets','App\Http\Controllers\SupportController@tickets');
    Route::get('support/new-ticket','App\Http\Controllers\SupportController@newTickets');
    Route::get('support/ticket-detail/{id}','App\Http\Controllers\SupportController@ticketDetail'); 
    Route::post('support/create','App\Http\Controllers\SupportController@create');
    Route::post('support/comment-create','App\Http\Controllers\SupportController@comment_create');
});


/* Admin routes =========================================================================== */
Route::group(['prefix' => 'admin', 'middleware' => 'web'], function () {
    Route::get('auth/login', '\App\Http\Controllers\Admin\AuthController@login')->name('admin/auth/login');
    Route::post('auth/login-process', '\App\Http\Controllers\Admin\AuthController@loginProcess')->name('admin/auth/login-process');
    Route::get('auth/logout', '\App\Http\Controllers\Admin\AuthController@logout')->name('admin/auth/logout');

    Route::get('auth/verify', '\App\Http\Controllers\Admin\AuthController@verify')->name('admin/auth/verify');
    Route::post('auth/verify-process', '\App\Http\Controllers\Admin\AuthController@verifyProcess')->name('admin/auth/verify-process');
    Route::post('auth/resend-otp', '\App\Http\Controllers\Admin\AuthController@resendOTP')->name('admin/auth/resend-otp');


    Route::get('site/password-forgot', '\App\Http\Controllers\Admin\SiteController@passwordForgot')->name('admin/site/password-forgot');
    Route::post('site/password-forgot-process', '\App\Http\Controllers\Admin\SiteController@passwordForgotProcess')->name('admin/site/password-forgot-process');
});

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin']], function () {
    Route::get('dashboard', '\App\Http\Controllers\Admin\SiteController@dashboard')->name('admin/dashboard');
    Route::post('site/get-chart-user', '\App\Http\Controllers\Admin\SiteController@getChartUser')->name('admin/site/get-chart-user');

    Route::get('account/tfa', '\App\Http\Controllers\Admin\AccountController@tfa')->name('admin/account/tfa');
    Route::post('account/tfa-status-change', '\App\Http\Controllers\Admin\AccountController@tfaStatusChange')->name('admin/account/tfa-status-change');
    Route::post('account/revoke-all', '\App\Http\Controllers\Admin\AccountController@revokeAll')->name('admin/account/revoke-all');

    Route::get('account/update', '\App\Http\Controllers\Admin\AccountController@update')->name('admin/account/update');
    Route::post('account/save', '\App\Http\Controllers\Admin\AccountController@save')->name('admin/account/save');
    Route::get('account/image', '\App\Http\Controllers\Admin\AccountController@image')->name('admin/account/image');
    Route::post('account/image-save', '\App\Http\Controllers\Admin\AccountController@imageSave')->name('admin/account/image-save');
    Route::post('account/image-delete', '\App\Http\Controllers\Admin\AccountController@deleteImage')->name('admin/account/image-delete');
    Route::get('account/password-change', '\App\Http\Controllers\Admin\AccountController@passwordChange')->name('admin/account/password-change');

    Route::post('account/password-change-process', '\App\Http\Controllers\Admin\AccountController@changePasswordProcess')->name('admin/account/password-change-process');

    Route::get('account/device', '\App\Http\Controllers\Admin\AccountController@device')->name('admin/account/device');
    Route::any('account/device-list', '\App\Http\Controllers\Admin\AccountController@deviceList')->name('admin/account/device-list');
    Route::any('account/device-logout', '\App\Http\Controllers\Admin\AccountController@deviceLogout')->name('admin/account/device-logout');

    Route::get('account/user-activity', '\App\Http\Controllers\Admin\AccountController@userActivity')->name('admin/account/user-activity');
    Route::any('account/user-activity-list', '\App\Http\Controllers\Admin\AccountController@userActivityList')->name('admin/account/user-activity-list');
    Route::post('account/deactivate', '\App\Http\Controllers\Admin\AccountController@accountDeactivate')->name('admin/account/deactivate');


    Route::get('user', '\App\Http\Controllers\Admin\UserController@index')->name('admin/user');
    Route::any('user/list', '\App\Http\Controllers\Admin\UserController@list')->name('admin/user/list');
    Route::get('user/create', '\App\Http\Controllers\Admin\UserController@create')->name('admin/user/create');
    Route::get('user/update', '\App\Http\Controllers\Admin\UserController@update')->name('admin/user/update');
    Route::post('user/save', '\App\Http\Controllers\Admin\UserController@save')->name('admin/user/save');
    Route::get('user/view', '\App\Http\Controllers\Admin\UserController@view')->name('admin/user/view');
    Route::post('user/delete', '\App\Http\Controllers\Admin\UserController@delete')->name('admin/user/delete');
    Route::post('user/change_status', '\App\Http\Controllers\Admin\UserController@changeStatus')->name('admin/user/change_status');
    Route::get('user/autologin', '\App\Http\Controllers\Admin\UserController@autoLogin')->name('admin/user/autologin');
    Route::get('user/send-tfa-mail', '\App\Http\Controllers\Admin\UserController@sendTfaMail')->name('admin/user/send-tfa-mail');
    Route::post('user/autologin', '\App\Http\Controllers\Admin\UserController@autoLogin')->name('admin/user/autologin');
    Route::post('user/change_status', '\App\Http\Controllers\Admin\UserController@changeStatus')->name('admin/user/change_status');
    
    Route::get('plan', '\App\Http\Controllers\Admin\PlanController@index')->name('admin/plan');
    Route::any('plan/list', '\App\Http\Controllers\Admin\PlanController@list')->name('admin/plan/list');
    Route::get('plan/create', '\App\Http\Controllers\Admin\PlanController@create')->name('admin/plan/create');
    Route::get('plan/update', '\App\Http\Controllers\Admin\PlanController@update')->name('admin/plan/update');
    Route::post('plan/save', '\App\Http\Controllers\Admin\PlanController@save')->name('admin/plan/save');
    Route::get('plan/view', '\App\Http\Controllers\Admin\PlanController@view')->name('admin/plan/view');
    Route::post('plan/delete', '\App\Http\Controllers\Admin\PlanController@delete')->name('admin/plan/delete');
    Route::post('plan/change_status', '\App\Http\Controllers\Admin\PlanController@changeStatus')->name('admin/plan/change_status');

    Route::get('product', '\App\Http\Controllers\Admin\ProductController@index')->name('admin/product');
    Route::any('product/list', '\App\Http\Controllers\Admin\ProductController@list')->name('admin/product/list');
    Route::get('product/create', '\App\Http\Controllers\Admin\ProductController@create')->name('admin/product/create');
    Route::get('product/update', '\App\Http\Controllers\Admin\ProductController@update')->name('admin/product/update');
    Route::post('product/save', '\App\Http\Controllers\Admin\ProductController@save')->name('admin/product/save');
    Route::get('product/view', '\App\Http\Controllers\Admin\ProductController@view')->name('admin/product/view');
    Route::post('product/delete', '\App\Http\Controllers\Admin\ProductController@delete')->name('admin/product/delete');
    Route::get('product/export', '\App\Http\Controllers\Admin\ProductController@productExportAll')->name('admin/product/export');
    Route::post('product/change_status', '\App\Http\Controllers\Admin\ProductController@changeStatus')->name('admin/product/change_status');

    Route::get('transaction', '\App\Http\Controllers\Admin\TransactionController@index')->name('admin/transaction');
    Route::any('transaction/list', '\App\Http\Controllers\Admin\TransactionController@list')->name('admin/transaction/list');
    Route::get('transaction/create', '\App\Http\Controllers\Admin\TransactionController@create')->name('admin/transaction/create');
    Route::get('transaction/update', '\App\Http\Controllers\Admin\TransactionController@update')->name('admin/transaction/update');
    Route::post('transaction/save', '\App\Http\Controllers\Admin\TransactionController@save')->name('admin/transaction/save');
    Route::get('transaction/view', '\App\Http\Controllers\Admin\TransactionController@view')->name('admin/transaction/view');

    Route::get('order', '\App\Http\Controllers\Admin\OrderController@index')->name('admin/order');
    Route::any('order/list', '\App\Http\Controllers\Admin\OrderController@list')->name('admin/order/list');
    Route::get('order/create', '\App\Http\Controllers\Admin\OrderController@create')->name('admin/order/create');
    Route::get('order/update', '\App\Http\Controllers\Admin\OrderController@update')->name('admin/order/update');
    Route::post('order/save', '\App\Http\Controllers\Admin\OrderController@save')->name('admin/order/save');
    Route::get('order/view', '\App\Http\Controllers\Admin\OrderController@view')->name('admin/order/view');


    Route::get('admin', '\App\Http\Controllers\Admin\AdminController@index')->name('admin/admin');
    Route::post('admin/list', '\App\Http\Controllers\Admin\AdminController@list')->name('admin/admin/list');
    Route::get('admin/create', '\App\Http\Controllers\Admin\AdminController@create')->name('admin/admin/create');
    Route::get('admin/update', '\App\Http\Controllers\Admin\AdminController@update')->name('admin/admin/update');
    Route::post('admin/save', '\App\Http\Controllers\Admin\AdminController@save')->name('admin/admin/save');
    Route::get('admin/view', '\App\Http\Controllers\Admin\AdminController@view')->name('admin/admin/view');
    Route::post('admin/delete', '\App\Http\Controllers\Admin\AdminController@delete')->name('admin/admin/delete');
    Route::post('admin/status-save', '\App\Http\Controllers\Admin\AdminController@statusSave')->name('admin/admin/status-save');
    
    /* Blog_category */
    Route::get('/blog/index', '\App\Http\Controllers\Admin\BlogController@index')->name('admin/blog/index');
    Route::post('blog/list', '\App\Http\Controllers\Admin\BlogController@list')->name('admin/blog/list');
    Route::get('blog/create', '\App\Http\Controllers\Admin\BlogController@create')->name('admin/blog/create');
    Route::get('blog/update', '\App\Http\Controllers\Admin\BlogController@update')->name('admin/blog/update');
    Route::get('blog/view', '\App\Http\Controllers\Admin\BlogController@view')->name('admin/blog/view');
    Route::post('blog/save', '\App\Http\Controllers\Admin\BlogController@save')->name('admin/blog/save');
    Route::post('blog/save-editor-file', '\App\Http\Controllers\Admin\BlogController@saveEditorFile')->name('admin/blog/save-editor-file');
    Route::post('blog/delete', '\App\Http\Controllers\Admin\BlogController@delete')->name('admin/blog/delete');

   Route::get('/blog_category/index', '\App\Http\Controllers\Admin\BlogCategoryController@index')->name('admin/blog_category/index');
    Route::post('/blog_category/list', '\App\Http\Controllers\Admin\BlogCategoryController@list')->name('admin/blog_category/list');
    Route::get('blog_category/create', '\App\Http\Controllers\Admin\BlogCategoryController@create')->name('admin/blog_category/create');
    Route::post('blog_category/save', '\App\Http\Controllers\Admin\BlogCategoryController@save')->name('admin/blog_category/save');
    Route::get('blog_category/update', '\App\Http\Controllers\Admin\BlogCategoryController@update')->name('admin/blog_category/update');
    Route::any('blog_category/delete', '\App\Http\Controllers\Admin\BlogCategoryController@delete')->name('admin/blog_category/delete');
    
      Route::get('notes', '\App\Http\Controllers\Admin\NoteController@index')->name('admin/notes');
    Route::post('notes/list', '\App\Http\Controllers\Admin\NoteController@list')->name('admin/notes/list');
    Route::get('notes/create', '\App\Http\Controllers\Admin\NoteController@create')->name('admin/notes/create');
    Route::get('notes/update', '\App\Http\Controllers\Admin\NoteController@update')->name('admin/notes/update');
    Route::post('notes/save', '\App\Http\Controllers\Admin\NoteController@save')->name('admin/notes/save');
    Route::post('notes/delete', '\App\Http\Controllers\Admin\NoteController@delete')->name('admin/notes/delete');
    Route::get('notes/view', '\App\Http\Controllers\Admin\NoteController@view')->name('admin/notes/view');

    Route::get('seo/meta', '\App\Http\Controllers\Admin\SeoController@index')->name('admin/seo/meta');
    Route::post('seo/list', '\App\Http\Controllers\Admin\SeoController@list')->name('admin/seo/list');
    Route::get('seo/create', '\App\Http\Controllers\Admin\SeoController@create')->name('admin/seo/create');
    Route::get('seo/update', '\App\Http\Controllers\Admin\SeoController@update')->name('admin/seo/update');
    Route::post('seo/save', '\App\Http\Controllers\Admin\SeoController@save')->name('admin/seo/save');
    Route::post('seo/delete', '\App\Http\Controllers\Admin\SeoController@delete')->name('admin/seo/delete');
    Route::get('seo/sitemap-update', '\App\Http\Controllers\Admin\SeoController@sitemapUpdate')->name('admin/seo/sitemap-update');

    Route::get('page', '\App\Http\Controllers\Admin\PageController@index')->name('admin/page');
    Route::post('page/list', '\App\Http\Controllers\Admin\PageController@list')->name('admin/page/list');
    Route::get('page/update', '\App\Http\Controllers\Admin\PageController@update')->name('admin/page/update');
    Route::post('page/save', '\App\Http\Controllers\Admin\PageController@save')->name('admin/page/save');
    Route::post('page/save-image', '\App\Http\Controllers\Admin\PageController@saveImage')->name('admin/page/save-image');
    Route::get('page/create', '\App\Http\Controllers\Admin\PageController@create')->name('admin/page/create');
    Route::post('page/create', '\App\Http\Controllers\Admin\PageController@create')->name('admin/page/create');
    Route::get('page/preview', '\App\Http\Controllers\Admin\PageController@preview')->name('admin/page/preview');


    Route::get('setting/update', '\App\Http\Controllers\Admin\SettingController@update')->name('admin/setting/update');
    Route::post('setting/save', '\App\Http\Controllers\Admin\SettingController@save')->name('admin/setting/save');
    Route::post('setting/save-logo', '\App\Http\Controllers\Admin\SettingController@saveLogo')->name('admin/setting/save-logo');
    Route::get('setting/cache-clear', '\App\Http\Controllers\Admin\SettingController@cacheClear')->name('admin/setting/cache-clear');
    Route::post('setting/mailprocess', '\App\Http\Controllers\Admin\SettingController@mailprocess')->name('admin/setting/mailprocess');
    Route::post('setting/smtp', '\App\Http\Controllers\Admin\SettingController@smtp')->name('admin/setting/smtp');
    Route::post('setting/captcha', '\App\Http\Controllers\Admin\SettingController@captcha')->name('admin/setting/captcha');
    Route::post('setting/social', '\App\Http\Controllers\Admin\SettingController@social')->name('admin/setting/social');
    Route::post('setting/content', '\App\Http\Controllers\Admin\SettingController@content')->name('admin/setting/content');


    Route::get('user-activity', '\App\Http\Controllers\Admin\UserActivityController@index')->name('admin/user-activity');
    Route::post('user-activity/list', '\App\Http\Controllers\Admin\UserActivityController@list')->name('admin/user-activity/list');

    Route::get('device', '\App\Http\Controllers\Admin\DeviceController@index')->name('admin/device');
    Route::post('device/list', '\App\Http\Controllers\Admin\DeviceController@list')->name('admin/device/list');
    Route::post('device/logout', '\App\Http\Controllers\Admin\DeviceController@logout')->name('admin/device/logout');

    Route::get('email-template', '\App\Http\Controllers\Admin\EmailTemplateController@index')->name('admin/email-template');
    Route::get('email-template/list', '\App\Http\Controllers\Admin\EmailTemplateController@list')->name('admin/email-template/list');
    Route::get('email-template/update', '\App\Http\Controllers\Admin\EmailTemplateController@update')->name('admin/email-template/update');
    Route::get('email-template/view', '\App\Http\Controllers\Admin\EmailTemplateController@view')->name('admin/email-template/view');
    Route::post('email-template/save', '\App\Http\Controllers\Admin\EmailTemplateController@save')->name('admin/email-template/save');
    Route::post('email-template/save-file', '\App\Http\Controllers\Admin\EmailTemplateController@saveFile')->name('admin/email-template/save-file');
    Route::get('email-template/create', '\App\Http\Controllers\Admin\EmailTemplateController@create')->name('admin/email-template/create');

    Route::get('ip-info','\App\Http\Controllers\Admin\IpInfoController@index')->name('admin/ip-info');
});