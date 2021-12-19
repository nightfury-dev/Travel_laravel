<?php
use App\Http\Controllers\LanguageController;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::domain('itinerary.quotes4.co.uk')->group(function () {
    Route::get('/planId/{itinerary_id}', 'ShowCaseController@index');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'namespace' => 'Admin'], function () {

    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    Route::get('/read_message/{id}', 'DashboardController@read_message')->name('admin.read_message');
    Route::post('/mark_allmessage', 'DashboardController@mark_allmessage')->name('admin.mark_allmessage');
    Route::post('/fetch_message', 'DashboardController@fetch_message')->name('admin.fetch_message');

    //Profile
    Route::get('/profile', 'DashboardController@profile')->name('admin.profile');
    Route::post('/profile/update', 'DashboardController@update_profile')->name('admin.profile.update');
    Route::post('/profile/password', 'DashboardController@update_password')->name('admin.profile.password');

    //Enquriy
    Route::get('/enquiry', 'EnquiryController@index')->name('admin.enquiry');
    Route::get('/enquiry/create', 'EnquiryController@create')->name('admin.enquiry.create');
    Route::post('/enquiry/add', 'EnquiryController@add')->name('admin.enquiry.add');
    Route::get('/enquiry/edit/{enquiry_id}', 'EnquiryController@edit')->name('admin.enquiry.edit');
    Route::post('/enquiry/update', 'EnquiryController@update')->name('admin.enquiry.update');
    Route::get('/enquiry/delete', 'EnquiryController@delete')->name('admin.enquiry.delete');
    Route::get('/enquiry/delete_some_enquiry', 'EnquiryController@delete_some_enquiry')->name('admin.enquiry.delete_some_enquiry');
    Route::post('/enquiry/filter_by_status', 'EnquiryController@filter_by_status')->name('admin.enquiry.filter_by_status');
    Route::post('/enquiry/get_messages', 'EnquiryController@get_messages')->name('admin.enquiry.get_messages');
    Route::get('/enquiry/delete_message', 'EnquiryController@delete_message')->name('admin.enquiry.delete_message');
    Route::post('/enquiry/send_message', 'EnquiryController@send_message')->name('admin.enquiry.send_message');

    //CRM
    Route::get('/crm', 'CrmController@index')->name('admin.crm');
    Route::get('/crm/create', 'CrmController@create')->name('admin.crm.create');
    Route::get('/crm/edit/{user_id}', 'CrmController@edit')->name('admin.crm.edit');
    Route::post('/crm/add', 'CrmController@add')->name('admin.crm.add');
    Route::post('/crm/update', 'CrmController@update')->name('admin.crm.update');
    Route::get('/crm/delete', 'CrmController@delete')->name('admin.crm.delete');
    Route::post('/crm/password', 'CrmController@reset_password')->name('admin.crm.password');
    Route::post('/crm/avatar', 'CrmController@avatar_upload')->name('admin.crm.avatar');

    //Product
    Route::get('/product', 'ProductController@index')->name('admin.product');
    Route::post('/product/detail', 'ProductController@detail')->name('admin.product.detail');
    Route::get('/product/add/{flag?}', 'ProductController@add')->name('admin.product.add');
    Route::get('/product/edit/{product_id?}', 'ProductController@edit')->name('admin.product.edit');
    Route::get('/product/delete/{product_id?}', 'ProductController@delete')->name('admin.product.delete');
    Route::post('/product/tag', 'ProductController@tag')->name('admin.product.tag');
    Route::post('/product/save', 'ProductController@save')->name('admin.product.save');
    Route::post('/product/description', 'ProductController@product_description')->name('admin.product.description');
    Route::post('/product/gallery/upload', 'ProductController@upload_gallery')->name('admin.product.gallery.upload');
    Route::post('/product/gallery/delete', 'ProductController@delete_gallery')->name('admin.product.gallery.delete');
    Route::post('/product/pricing', 'ProductController@product_pricing')->name('admin.product.pricing');

    //Itinerary
    Route::get('/itinerary', 'ItineraryController@index')->name('admin.itinerary');
    Route::get('/itinerary/build', 'ItineraryController@build')->name('admin.itinerary.build');
    Route::post('/itinerary/save_basic_info', 'ItineraryController@save_basic_info')->name('admin.itinerary.save_basic_info');
    Route::get('/itinerary/itinerary_add_daily_search', 'ItineraryController@product_search')->name('admin.itinerary.product_search');
    Route::get('/itinerary/itinerary_template_search', 'ItineraryController@template_search')->name('admin.itinerary.template_search');
    Route::get('/itinerary/itinerary_add_daily_filter', 'ItineraryController@filter')->name('admin.itinerary.filter');
    Route::post('/itinerary/del_itinerary', 'ItineraryController@delete_itinerary')->name('admin.itinerary.delete_itinerary');
    Route::get('/itinerary/itinerary_send', 'ItineraryController@send_itinerary')->name('admin.itinerary.send_itinerary');
    Route::post('/itinerary/send_email', 'ItineraryController@send_email_itinerary')->name('admin.itinerary.itinerary_send_email');
    Route::post('/itinerary/itinerary_daily_save', 'ItineraryController@saveDailyItinerary')->name('admin.itinerary.save_itinerary');
    Route::post('/itinerary/itinerary_template_save', 'ItineraryController@saveTemplateItinerary')->name('admin.itinerary.save_template');
    Route::post('/itinerary/get_product_pricing_tag', 'ItineraryController@get_product_pricing_tag')->name('admin.itinerary.get_product_pricing_tag');
    Route::post('/itinerary/get_product_pricing_and_tag', 'ItineraryController@get_product_pricing_and_tag')->name('admin.itinerary.get_product_pricing_and_tag');
    Route::post('/itinerary/check_itinerary_product_season', 'ItineraryController@check_itinerary_product_season')->name('admin.itinerary.check_itinerary_product_season');
    Route::post('/itinerary/itinerary_complete_with_budget', 'ItineraryController@itinerary_complete_with_budget')->name('admin.itinerary.itinerary_complete_with_budget');
    Route::post('/itinerary/delete_template_itinerary', 'ItineraryController@delete_template_itinerary')->name('admin.itinerary.delete_template_itinerary');
    Route::post('/itinerary/preview_template_itinerary', 'ItineraryController@preview_template_itinerary')->name('admin.itinerary.preview_template_itinerary');
    Route::post('/itinerary/currency_converter', 'ItineraryController@currency_converter')->name('admin.itinerary.currency_converter');
    Route::get('/itinerary/confirm', 'ItineraryController@confirm_itinerary')->name('admin.itinerary.confirm');
    Route::get('/itinerary/contact', 'ItineraryController@contact')->name('admin.itinerary.contact');
    Route::post('/itinerary/delete_message', 'ItineraryController@delete_message')->name('admin.itinerary.delete_message');
    Route::post('/itinerary/send_message', 'ItineraryController@send_message')->name('admin.itinerary.send_message');
    Route::get('/itinerary/invoice', 'ItineraryController@invoice')->name('admin.itinerary.invoice');
    Route::post('/itinerary/send_invoice', 'ItineraryController@send_invoice')->name('admin.itinerary.send_invoice');
    Route::get('/itinerary/preview_itinerary', 'ItineraryController@preview_itinerary')->name('admin.itinerary.preview_itinerary');

    //Task
    Route::get('/task', 'TaskController@index')->name('admin.task');
    Route::post('/task/edit', 'TaskController@edit')->name('admin.task.edit');
    Route::post('/task/delete', 'TaskController@delete')->name('admin.task.delete');
    Route::post('/task/detail_delete', 'TaskController@detail_delete')->name('admin.task.detail_delete');
    Route::post('/task/save', 'TaskController@save')->name('admin.task.save');
    Route::get('task/detail', 'TaskController@detail')->name('admin.task.detail');
    Route::get('task/contact', 'TaskController@contact')->name('admin.task.contact');
    Route::post('task/contact/send', 'TaskController@send_message')->name('admin.task.contact.send');
    Route::post('task/contact/delete', 'TaskController@delete_message')->name('admin.task.contact.delete');

    //Booking
    Route::get('/booking', 'BookingController@index')->name('admin.booking');
    Route::get('/booking/preview', 'BookingController@preview')->name('admin.booking.preview');

    //Setting
    Route::get('/settings', 'SettingController@index')->name('admin.setting');
    Route::get('/settings/detail_account_type/{account_type_id?}', 'SettingController@detail_account_type')->name('admin.setting.detail_account_type');
    Route::post('/settings/save_account_type', 'SettingController@save_account_type')->name('admin.setting.save_account_type');
    Route::post('/settings/account_type_del', ['uses' => 'SettingController@account_type_del'])->name('admin.setting.account_type_del');
    Route::get('/settings/detail_currency/{currency_id?}', 'SettingController@detail_currency')->name('admin.setting.detail_currency');
    Route::post('/settings/save_currency', 'SettingController@save_currency')->name('admin.setting.save_currency');
    Route::post('/settings/currency_del', ['uses' => 'SettingController@currency_del'])->name('admin.setting.currency_del');
    Route::get('/settings/detail_language/{language_id?}', 'SettingController@detail_language')->name('admin.setting.detail_language');
    Route::post('/settings/save_language', 'SettingController@save_language')->name('admin.setting.save_language');
    Route::post('/settings/language_del', ['uses' => 'SettingController@language_del'])->name('admin.setting.language_del');
    Route::get('/settings/detail_category/{category_id?}', 'SettingController@detail_category')->name('admin.setting.detail_category');
    Route::post('/settings/save_category', 'SettingController@save_category')->name('admin.setting.save_category');
    Route::post('/settings/category_del', ['uses' => 'SettingController@category_del'])->name('admin.setting.category_del');
    Route::get('/settings/detail_category_tag/{category_tag_id?}', 'SettingController@detail_category_tag')->name('admin.setting.detail_category_tag');
    Route::post('/settings/save_category_tag', 'SettingController@save_category_tag')->name('admin.setting.ave_category_tag');
    Route::post('/settings/category_tag_del', ['uses' => 'SettingController@category_tag_del'])->name('admin.setting.category_tag_del');
    Route::post('/settings/save_default_settings', ['uses' => 'SettingController@save_default_settings'])->name('admin.setting.save_default_settings');
});

Route::group(['prefix' => 'vendor', 'middleware' => ['auth', 'vendor'], 'namespace' => 'Vendor'], function () {
    Route::get('/', 'DashboardController@index')->name('vendor.dashboard');
    Route::get('/read_message/{id}', 'DashboardController@read_message')->name('vendor.read_message');
    Route::post('/mark_allmessage', 'DashboardController@mark_allmessage')->name('vendor.mark_allmessage');
    Route::post('/fetch_message', 'DashboardController@fetch_message')->name('vendor.fetch_message');

    //Profile
    Route::get('/profile', 'DashboardController@profile')->name('vendor.profile');
    Route::post('/profile/update', 'DashboardController@update_profile')->name('vendor.profile.update');
    Route::post('/profile/password', 'DashboardController@update_password')->name('vendor.profile.password');
    Route::post('/profile/avatar', 'DashboardController@avatar_upload')->name('vendor.profile.avatar');

    //Itinerary
    Route::get('/itinerary', 'ItineraryController@index')->name('vendor.itinerary');
    Route::get('itinerary/detail', 'ItineraryController@detail')->name('vendor.itinerary.detail');
    Route::get('itinerary/confirm', 'ItineraryController@confirm')->name('vendor.itinerary.confirm');
    Route::get('itinerary/decline', 'ItineraryController@decline')->name('vendor.itinerary.decline');
    Route::get('itinerary/contact', 'ItineraryController@contact')->name('vendor.itinerary.contact');
    Route::post('itinerary/contact/send', 'ItineraryController@send_message')->name('vendor.itinerary.contact.send');
    Route::post('itinerary/contact/delete', 'ItineraryController@delete_message')->name('vendor.itinerary.contact.delete');

    //Product
    Route::get('/product', 'ProductController@index')->name('vendor.product');
    Route::post('/product/detail', 'ProductController@detail')->name('vendor.product.detail');
    Route::get('/product/add', 'ProductController@add')->name('vendor.product.add');
    Route::get('/product/edit', 'ProductController@edit')->name('vendor.product.edit');
    Route::get('/product/delete', 'ProductController@delete')->name('vendor.product.delete');
    Route::post('/product/tag', 'ProductController@tag')->name('vendor.product.tag');
    Route::post('/product/save', 'ProductController@save')->name('vendor.product.save');
    Route::post('/product/description', 'ProductController@product_description')->name('vendor.product.description');
    Route::post('/product/gallery/upload', 'ProductController@upload_gallery')->name('vendor.product.gallery.upload');
    Route::post('/product/gallery/delete', 'ProductController@delete_gallery')->name('vendor.product.gallery.delete');
    Route::post('/product/pricing', 'ProductController@product_pricing')->name('vendor.product.pricing');
});

Route::group(['prefix' => 'customer', 'middleware' => ['auth', 'customer'], 'namespace' => 'Customer'], function () {
    Route::get('/', 'DashboardController@index')->name('customer.dashboard');
    Route::get('/read_message/{id}', 'DashboardController@read_message')->name('customer.read_message');
    Route::post('/mark_allmessage', 'DashboardController@mark_allmessage')->name('customer.mark_allmessage');
    Route::post('/fetch_message', 'DashboardController@fetch_message')->name('customer.fetch_message');

    //Profile
    Route::get('/profile', 'DashboardController@profile')->name('customer.profile');
    Route::post('/profile/update', 'DashboardController@update_profile')->name('customer.profile.update');
    Route::post('/profile/password', 'DashboardController@update_password')->name('customer.profile.password');
    Route::post('/profile/avatar', 'DashboardController@avatar_upload')->name('customer.profile.avatar');

    //Enquiry
    Route::get('/enquiry', 'EnquiryController@index')->name('customer.enquiry');
    Route::get('/enquiry/create', 'EnquiryController@create')->name('customer.enquiry.create');
    Route::post('/enquiry/add', 'EnquiryController@add')->name('customer.enquiry.add');
    Route::get('/enquiry/edit/{enquiry_id}', 'EnquiryController@edit')->name('customer.enquiry.edit');
    Route::post('/enquiry/update', 'EnquiryController@update')->name('customer.enquiry.update');
    Route::get('/enquiry/delete', 'EnquiryController@delete')->name('customer.enquiry.delete');
    Route::get('/enquiry/delete_some_enquiry', 'EnquiryController@delete_some_enquiry')->name('customer.enquiry.delete_some_enquiry');
    Route::post('/enquiry/filter_by_status', 'EnquiryController@filter_by_status')->name('customer.enquiry.filter_by_status');
    Route::post('/enquiry/get_messages', 'EnquiryController@get_messages')->name('customer.enquiry.get_messages');
    Route::get('/enquiry/delete_message', 'EnquiryController@delete_message')->name('customer.enquiry.delete_message');
    Route::post('/enquiry/send_message', 'EnquiryController@send_message')->name('customer.enquiry.send_message');

    //Itinerary
    Route::get('/itinerary', 'ItineraryController@index')->name('customer.itinerary');
    Route::get('/itinerary/messages', 'ItineraryController@messages')->name('customer.itinerary.messages');
    Route::post('/itinerary/delete_message', 'ItineraryController@delete_message')->name('customer.itinerary.delete_message');
    Route::post('/itinerary/send_message', 'ItineraryController@send_message')->name('customer.itinerary.send_message');
    Route::get('/itinerary/customer_info', 'ItineraryController@customer_info')->name('customer.itinerary.customer_info');
    Route::post('/itinerary/send_customer_info', 'ItineraryController@send_customer_info')->name('customer.itinerary.send_customer_info');
    Route::get('/itinerary/invoice', 'ItineraryController@invoice')->name('customer.itinerary.invoice');
    Route::get('/itinerary/invoice_detail', 'ItineraryController@invoice_detail')->name('customer.itinerary.invoice_detail');
    Route::post('/itinerary/invoice_buy', 'ItineraryController@invoice_buy')->name('customer.itinerary.invoice_buy');
    Route::post('/itinerary/invoice_pay', 'ItineraryController@invoice_pay')->name('customer.itinerary.invoice_pay');
    Route::get('/itinerary/preview_itinerary', 'ItineraryController@preview_itinerary')->name('customer.itinerary.preview_itinerary');
});