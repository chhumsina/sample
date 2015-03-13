<?php

/*
 |--------------------------------------------------------------------------
 | Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Laravel the URIs it should respond to
 | and give it the Closure to execute when that URI is requested.
 |
 */

/*Route::get('/', function()
 {
 return View::make('hello');
 });*/

Route::get('/', 'HomeController@welcome');

// check login
Route::get('login', 'HomeController@login');
Route::post('login', 'HomeController@validate');

// Admin login
Route::get('ohadmin', 'OhadminController@login');
Route::post('ohadmin', 'OhadminController@validate');

// logout route
Route::get('logout', array('before' => 'auth', function() {
	Auth::logout();
	return Redirect::to('/') -> with('flash_notice', 'You are successfully logged out.');
}));

//Route::resource('announcements', 'AnnouncementController');
Route::group(array('before' => 'auth'), function() {
	Route::get('home', 'HomeController@welcome');
//	Route::get('home/profile', 'HomeController@profile');
//	Route::get('home/change-password', 'HomeController@changePassword');
//	Route::post('home/update-password', 'HomeController@updatePassword');
//
//	Route::get('permission-deny', 'HomeController@permissionDeny');
//
//	Route::get('announcements/search', 'AnnouncementController@search');
//	Route::post('announcements/search', 'AnnouncementController@search');
//	Route::resource('announcements', 'AnnouncementController');
//	Route::when('announcements*', 'announcements');
//
//	Route::get('sales/search', 'SaleController@search');
//	Route::post('sales/search', 'SaleController@search');
//
//	Route::get('sales/target', 'SaleController@target');
//	Route::post('sales/target', 'SaleController@target');
//
//	Route::get('sales/create-target', 'SaleController@createTarget');
//	Route::post('sales/create-target', 'SaleController@storeTarget');
//
//	Route::get('sales/upload-sale-target', 'SaleController@uploadSaleTarget');
//	Route::post('sales/store-upload-sale-target', 'SaleController@storeUploadSaleTarget');
//
//	Route::get('sales/{id}/edit-target', 'SaleController@editTarget');
//	Route::post('sales/edit-target', 'SaleController@updateTarget');
//
//
//	Route::resource('sales', 'SaleController');
//	Route::when('sales*', 'sales');
//
//	//Route::resource('locations', 'LocationController');
//	//Route::controller('dealers', 'DealerController');
//	Route::controller('locations', 'LocationController');
//
//	Route::get('dealers/search', 'DealerController@search');
//	Route::post('dealers/search', 'DealerController@search');
//	Route::resource('dealers', 'DealerController');
//	Route::when('dealers*', 'dealers');
//	Route::controller('dealers', 'DealerController');
//
//	Route::get('dealer-banks/search', 'DealerBankController@search');
//	Route::post('dealer-banks/search', 'DealerBankController@search');
//	Route::resource('dealer-banks', 'DealerBankController');
//	Route::when('dealer-banks*', 'dealer-banks');
//
//	Route::get('dealer-terminals/search', 'DealerTerminalController@search');
//	Route::post('dealer-terminals/search', 'DealerTerminalController@search');
//	Route::resource('dealer-terminals', 'DealerTerminalController');
//	Route::when('dealer-terminals*', 'dealer-terminals');
//
//	Route::get('terminals/search', 'TerminalController@search');
//	Route::post('terminals/search', 'TerminalController@search');
//	Route::resource('terminals', 'TerminalController');
//	Route::when('terminals*', 'terminals');
//
//	Route::resource('currency-conversion-rules', 'CurrencyConversionRuleController');
//	Route::when('currency-conversion-rules*', 'currency-conversion-rules');
//
//	Route::get('reports/sale_visit', 'ReportController@saleVisit');
//	Route::post('reports/sale_visit', 'ReportController@saleVisit');
//	//Route::get('reports/dealer-summary-balance-report', 'ReportController@dealerSummaryBalanceReport');
//	//Route::post('reports/dealer-summary-balance-report', 'ReportController@dealerSummaryBalanceReport');
//	Route::get('reports/dealer-summary-balance-report', 'ReportController@dealerSummaryBalanceReportNewStructure');
//	Route::post('reports/dealer-summary-balance-report', 'ReportController@dealerSummaryBalanceReportNewStructure');
//	Route::get('reports/transaction-report', 'ReportController@transactionReport');
//	Route::post('reports/transaction-report', 'ReportController@transactionReport');
//
//	Route::get('reports/bank-report', 'ReportController@bankReport');
//	Route::post('reports/bank-report', 'ReportController@bankReport');
//
//	Route::get('reports/transaction-master-subdealer-report', 'ReportController@masterSubdealerReport');
//	Route::post('reports/transaction-master-subdealer-report', 'ReportController@masterSubdealerReport');
//
//	Route::when('reports*', 'reports');
//
//	Route::resource('customer-calls', 'CustomerCallController');
//	Route::when('customer-calls*', 'customer-calls');
//
//	Route::resource('tickits', 'TikitController');
//	Route::when('tickits*', 'tickits');
//
//	Route::get('sys-audits/search', 'SysAuditController@search');
//	Route::post('sys-audits/search', 'SysAuditController@search');
//	Route::resource('sys-audits', 'SysAuditController');
//	Route::when('sys-audits*', 'sys-audits');
//
//	Route::get('finances/deposit', 'FinanceController@deposit');
//	Route::post('finances/deposit', 'FinanceController@storeDeposit');
//
//	Route::get('finances/promotion', 'FinanceController@promotion');
//	Route::post('finances/promotion', 'FinanceController@storePromotion');
//
//	Route::get('finances/commission-free', 'FinanceController@commissionFree');
//	Route::post('finances/commission-free', 'FinanceController@storeCommissionFree');
//
//
//	Route::get('finances/dealer-refund', 'FinanceController@dealerRefund');
//	Route::post('finances/dealer-refund', 'FinanceController@storeDealerRefund');
//
//	Route::get('finances/master-deposit-tosub-dealer', 'FinanceController@masterDepositToSubDealer');
//	Route::post('finances/master-deposit-tosub-dealer', 'FinanceController@storeMasterDepositToSubDealer');
//
//	Route::get('finances/master-withdraw-fromsub-dealer', 'FinanceController@masterWithdrawFromSubDealer');
//	Route::post('finances/master-withdraw-fromsub-dealer', 'FinanceController@storeMasterWithdrawFromSubDealer');
//
//	Route::get('finances/dealer-payback', 'FinanceController@dealerPayback');
//	Route::post('finances/dealer-payback', 'FinanceController@storeDealerPayback');
//
//	Route::resource('finances', 'DealerRefundController');
//	Route::resource('finances', 'FinanceController');
//	Route::controller('finances', 'FinanceController');
//	Route::when('finances*', 'finances');
//
//	Route::get('stocks/index', 'StockController@index');
//	Route::post('stocks/index', 'StockController@index');
//	Route::get('stocks/stock-initiate', 'StockController@stockInitiate');
//	Route::post('stocks/stock-initiate', 'StockController@storeStockInitiate');
//	Route::get('stocks/stock-initiate-view', 'StockController@stockInitiateView');
//	Route::post('stocks/stock-initiate-view', 'StockController@stockInitiateView');
//	Route::get('stocks/{id}/stock-initiate-detail', 'StockController@stockInitiateDetail');
//
//	Route::get('stocks/{id}/stock-approval', 'StockController@stockApproval');
//	Route::post('stocks/stock-approval', 'StockController@storeStockApproval');
//	Route::get('stocks/stock-delete-initiate', 'StockController@stockDeleteInititae');
//	Route::post('stocks/stock-delete-initiate', 'StockController@storeStockDeleteInititae');
//	Route::get('stocks/stock-delete-initiate-view', 'StockController@storeStockDeleteInitiateView');
//	Route::get('stocks/stock-delete-approval', 'StockController@stockDeleteApproval');
//	Route::post('stocks/stock-delete-approval', 'StockController@storeStockDeleteApproval');
//	Route::resource('stocks', 'StockController');
//	Route::when('stocks*', 'stocks');
//
//	Route::get('transactions/do-action-from-detail', 'TransactionController@doActionFromDetail');
//	Route::post('transactions/do-action-from-detail', 'TransactionController@doActionFromDetail');
//	Route::get('transactions/index', 'TransactionController@index');
//	Route::post('transactions/index', 'TransactionController@index');
//	Route::get('transactions/{id}/detail', 'TransactionController@detail');
//	Route::post('transactions/{id}/detail', 'TransactionController@detail');
//	Route::get('transactions/{id}/edit', 'TransactionController@edit');
//	Route::post('transactions/update', 'TransactionController@update');
//	Route::get('transactions/cancel-game', 'TransactionController@cancelGame');
//	Route::post('transactions/cancel-game', 'TransactionController@storeCancelGame');
//	Route::resource('transactions', 'TransactionController');
//	Route::when('transactions*', 'transactions');
//
//	Route::get('wallets/index', 'WalletController@index');
//	Route::post('wallets/index', 'WalletController@index');
//	Route::resource('wallets', 'WalletController');
//	Route::when('wallets*', 'wallets');
//
//	Route::get('pin-stocks/upload-pin', 'PinStockController@uploadPin');
//	Route::post('pin-stocks/upload-pin', 'PinStockController@storeUploadPin');
//	Route::get('pin-stocks/view-pin-stock', 'PinStockController@viewPinStock');
//	Route::post('pin-stocks/view-pin-stock', 'PinStockController@viewPinStock');
//
//	Route::get('pin-stocks/summary-pin-code-stock', 'PinStockController@summaryPinCodeStock');
//	Route::post('pin-stocks/summary-pin-code-stock', 'PinStockController@summaryPinCodeStock');
//
//	Route::resource('pin-stocks', 'PinStockController');
//	Route::when('pin-stocks*', 'pin-stocks');
//
//	Route::get('service-charges/user_profile_service_charge', 'ServiceChargeController@userProfileServiceCharge');
//	Route::post('service-charges/user_profile_service_charge', 'ServiceChargeController@userProfileServiceCharge');
//	Route::get('service-charges/create_user_profile_service_charge', 'ServiceChargeController@createUserProfileServiceCharge');
//	Route::post('service-charges/store_user_profile_service_charge', 'ServiceChargeController@storeUserProfileServiceCharge');
//
//
//
//
//	Route::resource('service-charges', 'ServiceChargeController');
//	Route::when('service-charges*', 'service-charges');
//
//	Route::get('dashboard/dashboard', 'DashboardController@index');
//	Route::post('dashboard/dashboard', 'DashboardController@index');
//
//	Route::get('sale-performance/sales_performance', 'DashboardController@salePerformances');
//	Route::post('sale-performance/sales_performance', 'DashboardController@salePerformances');
//
//	Route::get('dragon-warrior-quota-graphic/dragon_warrior_quota_graphic', 'DashboardController@saleTargetGraphic');
//	Route::post('dragon-warrior-quota-graphic/dragon_warrior_quota_graphic', 'DashboardController@saleTargetGraphic');
//
//	Route::when('dashboard*', 'dashboard');
//
//	Route::get('sale-actual/index', 'SaleActualController@index');
//	Route::post('sale-actual/index', 'SaleActualController@index');
//
//	Route::get('sale-actual/create-sale-actual', 'SaleActualController@create');
//	Route::post('sale-actual/create-sale-actual', 'SaleActualController@create');
//
//	Route::get('sale-actual/upload-sale-actual', 'SaleActualController@uploadSaleActual');
//	Route::post('sale-actual/upload-sale-actual', 'SaleActualController@storeUploadSaleActual');
//
//	Route::resource('sale-actual', 'SaleActualController');
//	Route::when('sale-actual*', 'sale-actual');
//
//	Route::get('upload-sale-actuals/index', 'UploadActualChannelSale@index');
//	Route::post('upload-sale-actuals/index', 'UploadActualChannelSale@index');
//	Route::resource('upload-sale-actuals', 'UploadActualChannelSale');
//	Route::when('upload-sale-actuals*', 'upload-sale-actual');
//
//	Route::get('upload-sale-target/index', 'UploadSaleTarget@index');
//	Route::post('upload-sale-target/index', 'UploadSaleTarget@index');
//	Route::resource('upload-sale-target', 'UploadSaleTarget');
//	Route::when('upload-sale-target*', 'upload-sale-target');

	Route::get('lucky/print-ticket', 'LuckyController@printTicket');
	Route::post('lucky/print-ticket', 'LuckyController@printStoreTicket');
	Route::get('lucky/cancel-ticket', 'LuckyController@cancelTicket');
	Route::post('lucky/cancel-ticket', 'LuckyController@cancelStoreTicket');
	Route::get('lucky/payout', 'LuckyController@payout');
	Route::post('lucky/payout', 'LuckyController@showPayout');
	Route::get('lucky/result', 'LuckyController@result');
	Route::post('lucky/result', 'LuckyController@showResult');
	Route::get('lucky/show-report', 'LuckyController@showReport');
	Route::post('lucky/show-report', 'LuckyController@showReport');

	Route::resource('lucky', 'LuckyController');
	Route::when('lucky*', 'lucky');


	Route::get('ticket/print-ticket', 'TicketController@printTicket');
	Route::post('ticket/print-ticket', 'TicketController@printStoreTicket');
	Route::get('ticket/cancel-ticket', 'TicketController@cancelTicket');
	Route::post('ticket/cancel-ticket', 'TicketController@cancelStoreTicket');
	Route::get('ticket/payout', 'TicketController@payout');
	Route::post('ticket/payout', 'TicketController@showPayout');

	Route::resource('ticket', 'TicketController');
	Route::when('ticket*', 'ticket');

});
Route::controller('sys-automates', 'SysAutomateReportController');

?>