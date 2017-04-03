<?php

// API
// Route::get('integrityinvoice_api', array('uses' => Request::query('controller').'@'. Request::query('action')));
//Route::post('integrityinvoice_api', array('uses' => Input::get('controller').'@'. Input::get('action')));
//Route::post('integrityinvoice_api', array('uses' => Input::get('controller').'@'.Input::get('action')));

// Public transactions
Route::get('payinvoicebycard/{invoiceID}/{token}/{tenantID}', array('as' => 'payinvoicebycard', 'uses' => 'PublicTransactionController@payInvoiceByCard'));
Route::get('payinvoicebycard/error', array('as' => 'payinvoicebycard_error', 'uses' => 'PublicTransactionController@error'));
Route::get('view_invoice/{md5token}/{tenantID}/{tenant_invoice_id}/{sha1token}', array('as' => 'view_invoice_public', 'uses' => 'PublicTransactionController@view_invoice'));
Route::post('public_download/{tenantID}/{tenant_invoice_id}', array('as' => 'download_invoice_public', 'uses' => 'PublicTransactionController@public_download_invoice'));
Route::post('secure/process_card/{tenantID}/{tenant_invoice_id}', array('as' => 'secure_card_process', 'uses' => 'PublicTransactionController@secure_card_process'));
Route::get('invoice_payments/card_success', array('as' => 'invoice_card_success', 'uses' => 'PublicTransactionController@invoice_card_success'));

Route::post('client_payment/paypal', array('as' => 'client_payment_paypal', 'uses' => 'PublicTransactionController@paypal'));
Route::post('client_payment/paypal_success', array('as' => 'client_paypal_successful', 'uses' => 'PublicTransactionController@client_paypal_successful'));
Route::post('client_payment/paypal_cancel', array('as' => 'client_paypal_cancel', 'uses' => 'PublicTransactionController@cancel'));
Route::post('client_payment/ipn', array('as' => 'client_paypal_ipn', 'uses' => 'ClientIpnController@store'));

Route::get('public_invoices/{tenantID}/{tenant_invoice_id}/download_file', array('as' => 'invoice_download_file', 'uses' => 'PublicTransactionController@download_invoice_file'));

// Admin Login
Route::get('admin', array('uses' => 'AdminLoginController@create'));
Route::get('admin/login', array('as' => 'adminsuperlogin', 'uses' => 'AdminLoginController@create'));
Route::post('admin/login', array('uses' => 'AdminLoginController@store'));
Route::get('admin/logout', array('as' => 'adminsuperlogout', 'uses' => 'AdminLoginController@destroy'));

Route::group(array('before' => 'admin.auth'), function () {

    //Route::get('dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
    Route::get('admin/dashboard', array('as' => 'admin_dashboard', 'uses' => 'AdminDashboardController@index'));
    Route::get('admin/report', array('as' => 'admin_dashboard', 'uses' => 'AdminDashboardController@report'));
    Route::get('admin/accounts', array('as' => 'admin_accounts', 'uses' => 'AdminAccountsController@index'));
    Route::get('admin/accounts/{tenantID}/status', array('as' => 'account_status', 'uses' => 'AdminAccountsController@status'));
    Route::get('admin/accounts/{tenantID}/edit', array('as' => 'account_edit', 'uses' => 'AdminAccountsController@edit'));

    Route::get('admin/accounts/{tenantID}/ImpersonateUser', array('as' => 'ImpersonateUser', 'uses' => 'AdminAccountsController@ImpersonateUser'));

    Route::put('admin/accounts/{tenantID}/update_status', array('as' => 'account_update_status', 'uses' => 'AdminAccountsController@update_status'));

    Route::put('admin/accounts/{tenantID}/update_level', array('as' => 'account_update_level', 'uses' => 'AdminAccountsController@update_level'));
    Route::put('admin/accounts/{tenantID}/verify', array('as' => 'account_verify', 'uses' => 'AdminAccountsController@verify'));
    Route::put('admin/accounts/update', array('uses' => 'AdminAccountsController@update'));
    //Route::put('admin/accounts/update', array('as' => 'account_update','uses' => 'AdminAccountsController@update'));

    Route::get('admin/accounts/{tenantID}/delete', array('as' => 'admin_delete_account', 'uses' => 'TenantsController@finallyCancel'));

    Route::get('admin/accounts/{tenantID}/extend-subscription', array('as' => 'extend_subscription', 'uses' => 'AdminAccountsController@extendSubscription'));
    Route::post('admin/account/{tenantID}/process-extend-subscription', array('uses' => 'AdminAccountsController@processExtendSubscription'));

    Route::get('admin/notifications', array('as' => 'admin_notifications', 'uses' => 'AdminNotificationsController@index'));
    Route::get('admin/notifications/create', array('as' => 'create_notification', 'uses' => 'AdminNotificationsController@create'));
    Route::post('admin/notifications/store', array('as' => 'store_notification', 'uses' => 'AdminNotificationsController@store'));
    Route::get('admin/notifications/{id}/delete', array('as' => 'admin_delete_notification', 'uses' => 'AdminNotificationsController@destroy'));
    Route::get('admin/notifications/{id}/edit', array('as' => 'edit_admin_notification', 'uses' => 'AdminNotificationsController@edit'));
    Route::put('admin/notifications/update', array('uses' => 'AdminNotificationsController@update'));
});

// Account Signup
Route::get('signup', array('as' => 'signup', 'uses' => 'SignupController@create'));
Route::get('signup/verify/{verifystring}/{plan}', array('as' => 'verify', 'uses' => 'SignupController@verify'));
Route::get('canceled', array('uses' => 'SignupController@canceled'));

/* Route::get('signup/{id}', array('as' => 'signup', 'uses' => 'SignupController@create')); */
Route::post('register', array('as' => 'register', 'uses' => 'SignupController@store'));

// Login
Route::get('login', array('as' => 'login', 'uses' => 'LoginController@create'));
Route::post('login', array('uses' => 'LoginController@store'));
Route::get('logout', array('as' => 'logout', 'uses' => 'LoginController@destroy'));

// Password Reminder
Route::get('passwordresets', array('as' => 'passwordresets', 'uses' => 'PasswordResetsController@create'));
Route::post('passwordresets', array('uses' => 'PasswordResetsController@store'));
Route::get('/passwordresets/reset/{type}/{token}', 'PasswordResetsController@reset');
Route::post('/passwordresets/reset/{type}/{token}', 'PasswordResetsController@postReset');

Route::get('dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
Route::get('resend_account_verification_mail', array('as' => 'dashboard', 'uses' => 'DashboardController@resend_account_verification_mail'));
Route::group(array('before' => 'auth'), function () {

    // Dashboard
    Route::get('/', function () {return Redirect::to('dashboard');});
    /* Dashboard */

    Route::get('improvements', array('as' => 'improvements', 'uses' => 'DashboardController@improvements'));
    Route::get('support', array('as' => 'support', 'uses' => 'DashboardController@support'));
    Route::post('support/send', array('as' => 'support_send', 'uses' => 'DashboardController@support_send'));
    Route::post('dashboard/remove_tenant_verify', array('as' => 'remove_tenant_verify', 'uses' => 'DashboardController@remove_tenant_verify'));

    Route::get('export', array('as' => 'export', 'uses' => 'DashboardController@export'));
    Route::get('import', array('as' => 'import', 'uses' => 'DashboardController@import'));
    Route::get('invite', array('as' => 'invite', 'uses' => 'DashboardController@invite'));
    Route::post('send_invite', array('as' => 'send_invite', 'uses' => 'DashboardController@send_invite'));

    // Report
    Route::get('reports', array('as' => 'reports', 'uses' => 'ReportsController@index'));
    Route::get('reports/summary', array('as' => 'reports', 'uses' => 'ReportsController@index'));
    Route::get('reports/download', array('as' => 'download_report', 'uses' => 'ReportsController@download'));
    Route::get('reports/profit_and_loss', array('as' => 'profit_and_loss_start', 'uses' => 'ReportsController@profit_and_loss_start'));
    Route::post('reports/profit_and_loss', array('as' => 'profit_and_loss', 'uses' => 'ReportsController@profit_and_loss'));
    Route::post('reports/profit_and_loss_download', array('as' => 'profit_and_loss_download', 'uses' => 'ReportsController@profit_and_loss_download'));

    Route::get('download/invoices', array('as' => 'invoice_archives', 'uses' => 'DashboardController@archive'));
    Route::post('download/invoices', array('as' => 'download_invoice_archives', 'uses' => 'DashboardController@download_archive'));

    // Intelligent Robots
    Route::get('robot', array('as' => 'robot', 'uses' => 'IntelligentRobotsController@index'));

    // Company profile
    Route::get('company', array('before' => 'powerUser', 'as' => 'company', 'uses' => 'CompanyController@index'));
    Route::get('company/logo', array('before' => 'powerUser', 'as' => 'logo', 'uses' => 'CompanyController@logo'));
    Route::post('company/uploadlogo', array('as' => 'uploadLogo', 'uses' => 'CompanyController@uploadlogo'));
    Route::put('company/update', array('uses' => 'CompanyController@update'));

    Route::get('company/cancel', array('before' => 'powerUser', 'as' => 'removeAccount', 'uses' => 'CompanyController@cancel'));
    Route::post('account/cancel', array('before' => 'powerUser', 'as' => 'cancelAccount', 'uses' => 'TenantsController@cancel'));

    /* Labels */
    Route::resource('labels', 'LabelsController');

    /* Settings */
    Route::get('settings', array('before' => 'powerUser', 'as' => 'settings', 'uses' => 'SettingsController@index'));
    Route::get('settings/invoice_template', array('before' => 'powerUser', 'as' => 'invoice_template', 'uses' => 'SettingsController@invoice_template'));
    Route::get('settings/apply_invoice_template/{id}', array('before' => 'powerUser', 'as' => 'apply_invoice_template', 'uses' => 'SettingsController@apply_invoice_template'));
    Route::put('setting/update', array('before' => 'powerUser', 'uses' => 'SettingsController@update'));
    Route::get('settings/onetime', array('as' => 'onetime', 'uses' => 'SettingsController@onetime'));
    Route::put('settings/onetime', array('uses' => 'SettingsController@onetime_update'));
    Route::get('settings/apptheme', array('as' => 'app_theme', 'uses' => 'UsersController@apptheme'));
    Route::put('settings/apptheme', array('as' => 'app_theme_update', 'uses' => 'UsersController@apptheme_update'));
    Route::put('settings/invoice_update', array('as' => 'invoice_update_settings', 'uses' => 'SettingsController@invoice_update_settings'));

    /* Payment Gateway */
    Route::get('paymentgateways', array('as' => 'paymentgateways', 'uses' => 'SettingsController@paymentgateways'));
    Route::put('paymentgateways', array('as' => 'store_paymentgateway', 'uses' => 'SettingsController@store_paymentgateway'));

    /* Users  */
    Route::get('users', array('as' => 'users', 'uses' => 'UsersController@index'));
    Route::get('users/create', array('as' => 'create_user', 'uses' => 'UsersController@create'));
    Route::post('users/store', array('as' => 'store_user', 'uses' => 'UsersController@store'));
    Route::get('users/{id}/edit', array('as' => 'edit_user', 'uses' => 'UsersController@edit'));
    Route::put('user/update', array('uses' => 'UsersController@update'));
    Route::get('users/{id}/delete', array('as' => 'delete_user', 'uses' => 'UsersController@destroy'));
    Route::get('users/{id}', array('as' => 'user', 'uses' => 'UsersController@show'));
    Route::get('user/password', array('as' => 'password', 'uses' => 'UsersController@password'));
    Route::put('user/password/update', array('as' => 'password_update', 'uses' => 'UsersController@password_update'));

    Route::post('firsttimer/remove', array('as' => 'firsttimer_remove', 'uses' => 'UsersController@remove_firsttimer'));
    Route::post('notification/close', array('as' => 'close_notification', 'uses' => 'UsersController@close_notification'));

    /* Invoices  */

    Route::get('invoices/export', array('as' => 'exportInvoices', 'uses' => 'InvoicesController@export'));
    Route::post('invoices/process_export', array('as' => 'exportInvoicesProcess', 'uses' => 'InvoicesController@process_export'));

    Route::get('invoices', array('as' => 'invoices', 'uses' => 'InvoicesController@index'));
    Route::get('invoices/create', array('as' => 'create_invoice', 'uses' => 'InvoicesController@create'));
    Route::post('invoices/store', array('as' => 'invoice_store', 'uses' => 'InvoicesController@store'));
    Route::get('invoices/clients_select_list', array('as' => 'create_invoice_clients_select', 'uses' => 'InvoicesController@clients_select_list'));
    Route::get('invoices/items_select_list', array('as' => 'create_invoice_items_select', 'uses' => 'InvoicesController@items_select_list'));

    Route::get('invoices/enable_discount', array('as' => 'enable_discount', 'uses' => 'InvoicesController@enable_discount'));
    Route::get('invoices/disable_discount', array('as' => 'disable_discount', 'uses' => 'InvoicesController@disable_discount'));
    Route::get('invoices/enable_tax', array('as' => 'enable_tax', 'uses' => 'InvoicesController@enable_tax'));
    Route::get('invoices/disable_tax', array('as' => 'disable_tax', 'uses' => 'InvoicesController@disable_tax'));

    Route::get('invoices/{tenant_invoice_id}/enable_discount', array('as' => 'enable_discount_edit', 'uses' => 'InvoicesController@enable_discount_edit'));
    Route::get('invoices/{tenant_invoice_id}/disable_discount', array('as' => 'disable_discount_edit', 'uses' => 'InvoicesController@disable_discount_edit'));
    Route::get('invoices/{tenant_invoice_id}/enable_tax', array('as' => 'enable_tax_edit', 'uses' => 'InvoicesController@enable_tax_edit'));
    Route::get('invoices/{tenant_invoice_id}/disable_tax', array('as' => 'disable_tax_edit', 'uses' => 'InvoicesController@disable_tax_edit'));

    Route::get('invoices/{tenant_invoice_id}/enable_discount_copy', array('as' => 'enable_discount_copy', 'uses' => 'InvoicesController@enable_discount_copy'));
    Route::get('invoices/{tenant_invoice_id}/disable_discount_copy', array('as' => 'disable_discount_copy', 'uses' => 'InvoicesController@disable_discount_copy'));
    Route::get('invoices/{tenant_invoice_id}/enable_tax_copy', array('as' => 'enable_tax_copy', 'uses' => 'InvoicesController@enable_tax_copy'));
    Route::get('invoices/{tenant_invoice_id}/disable_tax_copy', array('as' => 'disable_tax_copy', 'uses' => 'InvoicesController@disable_tax_copy'));

    Route::get('invoices/{tenant_invoice_id}', array('as' => 'invoice', 'uses' => 'InvoicesController@show'));
    Route::get('invoices/{tenant_invoice_id}/delete', array('uses' => 'InvoicesController@destroy'));
    Route::get('invoices/{tenant_invoice_id}/download', array('uses' => 'InvoicesController@download'));
    Route::get('invoices/{tenant_invoice_id}/edit', array('as' => 'edit_invoice', 'uses' => 'InvoicesController@edit'));
    Route::post('invoices/{tenant_invoice_id}/update', array('uses' => 'InvoicesController@update'));
    Route::get('invoices/{tenant_invoice_id}/copy', array('as' => 'copy_invoice', 'uses' => 'InvoicesController@copy'));
    Route::put('invoices/{tenant_invoice_id}', array('uses' => 'InvoicesController@update'));
    Route::get('invoices/{tenant_invoice_id}/send', array('as' => 'send_invoice', 'uses' => 'InvoicesController@send'));
    Route::get('invoices/{tenant_invoice_id}/sent', array('uses' => 'InvoicesController@offline_send'));
    Route::post('invoice/{tenant_invoice_id}/email_invoice', array('as' => 'email_invoice', 'uses' => 'InvoicesController@email_invoice'));

    Route::get('invoices/{tenant_invoice_id}/reminder', array('as' => 'invoice_reminder', 'uses' => 'InvoicesController@reminder'));
    Route::post('invoice/{tenant_invoice_id}/send_reminder', array('as' => 'send_reminder', 'uses' => 'InvoicesController@send_reminder'));
    Route::post('invoices/check_invoice_id', array('as' => 'check_invoice_id', 'uses' => 'InvoicesController@check_invoice_id'));

    Route::post('invoices/{tenant_invoice_id}/attachment', array('as' => 'invoice_attachment', 'uses' => 'InvoicesController@invoice_attachment'));
    Route::get('invoices/{tenant_invoice_id}/download_file', array('as' => 'invoice_download_file', 'uses' => 'InvoicesController@download_file'));
    Route::get('invoices/{tenant_invoice_id}/remove_file', array('as' => 'invoice_remove_file', 'uses' => 'InvoicesController@remove_file'));
    Route::post('invoices/{tenant_invoice_id}/recurring', array('as' => 'invoice_recurring', 'uses' => 'InvoicesController@invoice_recurring'));
    Route::get('invoices/{tenant_invoice_id}/remove_recurring', array('as' => 'remove_invoice_recurring', 'uses' => 'InvoicesController@remove_recurring'));

    /* Quotes  */
    Route::get('quotes', array('as' => 'quotes', 'uses' => 'InvoicesController@index'));
    Route::get('quotes/create', array('as' => 'create_quote', 'uses' => 'InvoicesController@create'));
    Route::post('quotes/store', array('as' => 'quote_store', 'uses' => 'InvoicesController@store'));
    Route::get('quotes/enable_discount', array('as' => 'enable_discount', 'uses' => 'InvoicesController@enable_discount'));
    Route::get('quotes/disable_discount', array('as' => 'disable_discount', 'uses' => 'InvoicesController@disable_discount'));
    Route::get('quotes/enable_tax', array('as' => 'enable_tax', 'uses' => 'InvoicesController@enable_tax'));
    Route::get('quotes/disable_tax', array('as' => 'disable_tax', 'uses' => 'InvoicesController@disable_tax'));
    Route::get('quotes/{tenant_quote_id}/copy', array('as' => 'copy_quote', 'uses' => 'InvoicesController@copy'));
    Route::get('quotes/{tenant_quote_id}/edit', array('as' => 'edit_quote', 'uses' => 'InvoicesController@edit'));
    Route::post('quotes/{tenant_quote_id}/update', array('uses' => 'InvoicesController@update'));
    Route::get('quotes/{tenant_quote_id}', array('as' => 'quote', 'uses' => 'InvoicesController@show'));
    Route::get('quotes/{tenant_quote_id}/delete', array('uses' => 'InvoicesController@destroy'));
    Route::get('quotes/{tenant_quote_id}/convert', array('uses' => 'InvoicesController@convert_to_invoice'));
    Route::get('quotes/{tenant_quote_id}/download', array('uses' => 'InvoicesController@download'));
    Route::get('quotes/{tenant_quote_id}/send', array('as' => 'send_quote', 'uses' => 'InvoicesController@send'));
    Route::post('quote/{tenant_quote_id}/email_quote', array('as' => 'email_quote', 'uses' => 'InvoicesController@email_quote'));
    Route::post('quotes/check_quote_id', array('as' => 'check_quote_id', 'uses' => 'InvoicesController@check_quote_id'));

    /* Invoice Payment */
    Route::get('payments/{tenant_invoice_id}', array('as' => 'create_payment', 'uses' => 'PaymentsController@index'));
    Route::post('payments/{tenant_invoice_id}/store', array('as' => 'payments', 'uses' => 'PaymentsController@store'));
    Route::post('payments/{tenant_invoice_id}/paid', array('as' => 'mark_paid', 'uses' => 'PaymentsController@mark_paid'));
    Route::get('payments/{tenant_invoice_id}/{payment_id}/delete', array('as' => 'delete_payment', 'uses' => 'PaymentsController@destroy'));
    Route::get('payments/{tenant_invoice_id}/{payment_id}/send', array('as' => 'payment_acknowledgement', 'uses' => 'PaymentsController@payment_acknowledgement'));
    Route::post('payments/{tenant_invoice_id}/{payment_id}/email_acknowledgement', array('as' => 'payment_acknowledgement_email', 'uses' => 'PaymentsController@payment_acknowledgement_email'));
    Route::get('payments/{tenant_invoice_id}/send_receipt/{mode}', array('as' => 'payment_receipt', 'uses' => 'PaymentsController@payment_receipt'));

    /* Expenses  */
    Route::get('expenses', array('as' => 'expenses', 'uses' => 'ExpensesController@index'));
    Route::get('expenses/create', array('as' => 'create_expense', 'uses' => 'ExpensesController@create'));
    Route::post('expenses/store', array('as' => 'store_expense', 'uses' => 'ExpensesController@store'));
    Route::get('expenses/{id}/edit', array('as' => 'edit_expense', 'uses' => 'ExpensesController@edit'));
    Route::put('expense/update', array('uses' => 'ExpensesController@update'));
    Route::get('expenses/export', array('as' => 'exportExpenses', 'uses' => 'ExpensesController@export'));
    Route::post('expenses/process_export', array('as' => 'exportExpensesProcess', 'uses' => 'ExpensesController@process_export'));
    Route::get('expenses/{id}/delete', array('as' => 'delete_expense', 'uses' => 'ExpensesController@destroy'));
    Route::delete('expenses/deletebulk', array('as' => 'delete_bulk_expenses', 'uses' => 'ExpensesController@deletebulk'));
    Route::get('expenses/import', array('as' => 'importExpenses', 'uses' => 'ExpensesController@import'));
    Route::post('expenses/process_import', array('as' => 'processExpensesImport', 'uses' => 'ExpensesController@processImport'));
    Route::get('expenses/{id}/download_file', array('as' => 'expense_download_file', 'uses' => 'ExpensesController@download_file'));
    Route::get('expenses/{id}/remove_file', array('as' => 'expense_remove_file', 'uses' => 'ExpensesController@remove_file'));
    Route::post('expenses/{id}/recurring', array('as' => 'expense_recurring', 'uses' => 'ExpensesController@expense_recurring'));
    Route::get('expenses/{id}/remove_recurring', array('as' => 'remove_expense_recurring', 'uses' => 'ExpensesController@remove_recurring'));

    /* Services  */
    Route::get('services/json_list', array('as' => 'service_json_list', 'uses' => 'ServicesController@json_list'));
    Route::get('services', array('as' => 'services', 'uses' => 'ServicesController@index'));
    Route::get('services/create', array('as' => 'create_service', 'uses' => 'ServicesController@create'));
    Route::post('services/store', array('as' => 'store_service', 'uses' => 'ServicesController@store'));
    Route::get('services/{id}/edit', array('as' => 'edit_service', 'uses' => 'ServicesController@edit'));
    Route::put('service/update', array('uses' => 'ServicesController@update'));
    Route::get('services/{id}/delete', array('as' => 'delete_service', 'uses' => 'ServicesController@destroy'));
    Route::delete('services/deletebulk', array('as' => 'delete_bulk_services', 'uses' => 'ServicesController@deletebulk'));
    Route::get('services/import', array('as' => 'importServices', 'uses' => 'ServicesController@import'));
    Route::post('services/process_import', array('as' => 'processServicesImport', 'uses' => 'ServicesController@processImport'));
    Route::get('services/export', array('as' => 'exportServices', 'uses' => 'ServicesController@export'));
    Route::post('services/process_export', array('as' => 'exportServicesProcess', 'uses' => 'ServicesController@process_export'));
    Route::get('services/{id}', array('as' => 'service', 'uses' => 'ServicesController@show'));

    /* Products  */
    Route::get('products/json_list', array('as' => 'product_json_list', 'uses' => 'ProductsController@json_list'));
    Route::get('products', array('as' => 'products', 'uses' => 'ProductsController@index'));
    Route::get('products/create', array('as' => 'create_product', 'uses' => 'ProductsController@create'));
    Route::post('products/store', array('as' => 'store_product', 'uses' => 'ProductsController@store'));
    Route::get('products/{id}/edit', array('as' => 'edit_product', 'uses' => 'ProductsController@edit'));
    Route::put('product/update', array('uses' => 'ProductsController@update'));
    Route::get('products/{id}/delete', array('as' => 'delete_product', 'uses' => 'ProductsController@destroy'));
    Route::delete('products/deletebulk', array('as' => 'delete_bulk_products', 'uses' => 'ProductsController@deletebulk'));
    Route::get('products/import', array('as' => 'importProducts', 'uses' => 'ProductsController@import'));
    Route::post('products/process_import', array('as' => 'processProductsImport', 'uses' => 'ProductsController@processImport'));
    Route::get('products/export', array('as' => 'exportProducts', 'uses' => 'ProductsController@export'));
    Route::post('products/process_export', array('as' => 'exportProductsProcess', 'uses' => 'ProductsController@process_export'));
    Route::get('products/{id}', array('as' => 'product', 'uses' => 'ProductsController@show'));

    /* Clients  */
    Route::get('clients', array('as' => 'clients', 'uses' => 'ClientsController@index'));
    Route::get('clients/create', array('as' => 'create_client', 'uses' => 'ClientsController@create'));
    Route::post('clients/store', array('as' => 'store_client', 'uses' => 'ClientsController@store'));
    Route::post('clients/ajaxy_store', array('as' => 'ajaxy_store_client', 'uses' => 'ClientsController@ajaxy_store'));
    Route::get('clients/{id}/edit', array('as' => 'edit_client', 'uses' => 'ClientsController@edit'));
    Route::put('client/update', array('uses' => 'ClientsController@update'));
    Route::get('clients/{id}/delete', array('before' => 'powerUser', 'as' => 'delete_client', 'uses' => 'ClientsController@destroy'));
    Route::delete('clients/deletebulk', array('as' => 'delete_bulk_clients', 'uses' => 'ClientsController@deletebulk'));
    Route::get('clients/import', array('as' => 'importClients', 'uses' => 'ClientsController@import'));
    Route::post('clients/process_import', array('as' => 'processImportClients', 'uses' => 'ClientsController@processImport'));
    Route::get('clients/export', array('as' => 'exportClients', 'uses' => 'ClientsController@export'));
    Route::post('clients/process_export', array('as' => 'exportClientsProcess', 'uses' => 'ClientsController@process_export'));
    Route::get('clients/{id}', array('as' => 'client', 'uses' => 'ClientsController@show'));

    /* Merchants  */
    Route::get('merchants', array('as' => 'merchants', 'uses' => 'MerchantsController@index'));
    Route::get('merchants/create', array('as' => 'create_merchant', 'uses' => 'MerchantsController@create'));
    Route::post('merchants/store', array('as' => 'store_merchant', 'uses' => 'MerchantsController@store'));
    Route::get('merchants/{id}/edit', array('as' => 'edit_merchant', 'uses' => 'MerchantsController@edit'));
    Route::put('merchant/update', array('uses' => 'MerchantsController@update'));
    Route::get('merchants/{id}/delete', array('as' => 'delete_merchant', 'uses' => 'MerchantsController@destroy'));
    Route::delete('merchants/deletebulk', array('as' => 'delete_bulk_merchants', 'uses' => 'MerchantsController@deletebulk'));
    Route::get('merchants/import', array('as' => 'importMerchants', 'uses' => 'MerchantsController@import'));
    Route::post('merchants/process_import', array('as' => 'processImportMerchants', 'uses' => 'MerchantsController@processImport'));
    Route::get('merchants/export', array('as' => 'exportMerchants', 'uses' => 'MerchantsController@export'));
    Route::post('merchants/process_export', array('as' => 'exportMerchantsProcess', 'uses' => 'MerchantsController@process_export'));
    Route::get('merchants/{id}', array('as' => 'merchant', 'uses' => 'MerchantsController@show'));

    /* Currency rate */
    Route::get('currency-rates', array('as' => 'currency_rates', 'uses' => 'CurrencyRatesController@index'));
    Route::get('currency-rates/create', array('as' => 'create_currency_rate', 'uses' => 'CurrencyRatesController@create'));
    Route::post('currency-rates/store', array('as' => 'store_currency_rate', 'uses' => 'CurrencyRatesController@store'));
    Route::get('currency-rates/{id}/edit', array('as' => 'edit_currency_rate', 'uses' => 'CurrencyRatesController@edit'));
    Route::put('currency-rates/update', array('uses' => 'CurrencyRatesController@update'));
    Route::get('currency-rates/{id}/delete', array('as' => 'delete_currency_rate', 'uses' => 'CurrencyRatesController@destroy'));
    Route::post('currency-rates/api', array('as' => 'currency_rate_api', 'uses' => 'CurrencyRatesController@get_currency_exchange_rate'));

    /* Subscription */
    Route::get('subscription', array('as' => 'subscription', 'uses' => 'SubscriptionController@index'));
    Route::post('subscription/cart', array('as' => 'subscription_cart', 'uses' => 'SubscriptionController@cart'));

    Route::post('subscription/paypal', array('as' => 'subscription_process', 'uses' => 'SubscriptionController@paypal'));
    Route::post('subscription/success', array('as' => 'subscription_successful', 'uses' => 'SubscriptionController@success'));

    Route::post('subscription/card', array('as' => 'subscription_by_card', 'uses' => 'SubscriptionController@card'));
    Route::post('subscription/process_card', array('as' => 'process_card', 'uses' => 'SubscriptionController@process_card'));
    Route::get('subscription/card_success', array('as' => 'card_success', 'uses' => 'SubscriptionController@card_success'));
    // Route::post('subscription/cancel', array('as' => 'subscription_cancel', 'uses' => 'SubscriptionController@cancel'));
    Route::match(array('GET', 'POST'), 'subscription/cancel', array('as' => 'subscription_cancel', 'uses' => 'SubscriptionController@cancel'));
    Route::get('subscription/history', array('as' => 'subscription_history', 'uses' => 'SubscriptionController@history'));

    /* Help Centre */

    Route::get('help/expenses', array('as' => 'help_expenses', 'uses' => 'HelpController@expenses'));
    Route::get('help/clients', array('as' => 'help_clients', 'uses' => 'HelpController@clients'));
    Route::get('help/merchants', array('as' => 'help_merchants', 'uses' => 'HelpController@merchants'));
    Route::get('help/invoices', array('as' => 'help_invoices', 'uses' => 'HelpController@invoices'));
    Route::get('help/quotes', array('as' => 'help_quotes', 'uses' => 'HelpController@quotes'));
    Route::get('help/settings', array('as' => 'help_settings', 'uses' => 'HelpController@settings'));
    Route::get('help/users', array('as' => 'help_users', 'uses' => 'HelpController@users'));
    Route::get('help/reports', array('as' => 'help_reports', 'uses' => 'HelpController@reports'));
    Route::get('help/services', array('as' => 'help_services', 'uses' => 'HelpController@services'));
    Route::get('help/products', array('as' => 'help_products', 'uses' => 'HelpController@products'));

    Route::get('help', array('as' => 'help', 'uses' => 'HelpController@index'));
    Route::get('help/getting-started', array('as' => 'getting-started', 'uses' => 'HelpController@getting_started'));
    Route::get('help/videos', array('as' => 'videos', 'uses' => 'HelpController@videos'));

    Route::get('help/introduction/about-sighted', array('as' => 'about_sighted', 'uses' => 'HelpController@about_sighted'));
    Route::get('help/introduction/why-use-sighted', array('as' => 'why_use_sighted', 'uses' => 'HelpController@why_use_sighted'));
    Route::get('help/introduction/frequently-asked-questions', array('as' => 'faq', 'uses' => 'HelpController@faq'));
    Route::get('help/introduction/glossary-of-terms', array('as' => 'glossary', 'uses' => 'HelpController@glossary'));

    // Help - invoices
    Route::get('help/invoices/how-to-create-and-send-invoice', array('as' => 'help_invoice_create', 'uses' => 'HelpController@invoice_create'));

    // Help - quotes
    Route::get('help/quotes/how-to-create-a-quote', array('as' => 'help_quote_create', 'uses' => 'HelpController@quote_create'));

    // Help - clients
    Route::get('help/clients/how-to-create-a-client', array('as' => 'help_client_create', 'uses' => 'HelpController@client_create'));

    // Help - expenses
    Route::get('help/expenses/how-to-create-an-expense', array('as' => 'help_expense_create', 'uses' => 'HelpController@expense_create'));

    // Help - products
    Route::get('help/products/how-to-create-a-product', array('as' => 'help_product_create', 'uses' => 'HelpController@product_create'));

    // Help - services
    Route::get('help/services/how-to-create-a-service', array('as' => 'help_service_create', 'uses' => 'HelpController@service_create'));

    // Help - settings
    Route::get('help/settings/account', array('as' => 'help_settings_general', 'uses' => 'HelpController@settings_general'));

    // Help - security and privacy
    Route::get('help/security-and-privacy-of-data', array('as' => 'help_security_privacy', 'uses' => 'HelpController@security_privacy'));

    // PAYPAL
    //Route::post('subscription/ipn', array('uses' => 'IpnController@store', 'as' => 'ipn'));

}); // End Route group

Route::post('subscription/ipn', array('as' => 'subscription_ipn', 'uses' => 'IpnController@store'));


Route::group(array('prefix' => 'api'), function() {
    Route::resource('clients', 'APIClientsController');
    Route::resource('invoices', 'APIInvoicesController');
});

