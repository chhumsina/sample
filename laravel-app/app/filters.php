<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

/*Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});*/
Route::filter('auth', function()
{
	if (Auth::guest())
		return Redirect::guest('/')
			->with('flash_notice', 'You must be logged in to view this page!');
	// if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

/*Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});*/
Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/home');
	// if (Auth::check()) return Redirect::to('/');
});

/*Route::filter('ohadmin', function()
{
	if (Auth::check()) return Redirect::to('/');
});*/
Route::filter('ohadmin', function()
{
	if (Auth::check()) {
		return Redirect::to('backend');
	}
	// if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


//-----------------Fillter Role-Permission--------------------------------
Route::filter('dashboard', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index')) {
		if (Entrust::can('view_graphic_report_dealer_status')) $access = true;
	}
	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('announcements', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_announcement')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_announcement')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_announcement')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_announcement')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('dealers', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search') || str_contains($action, 'show')) {
		if (Entrust::can('view_dealer')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_dealer')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_dealer')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_dealer')) $access = true;
	} else if (str_contains($action, 'getDealerByKhanCode')) {
		if (Entrust::can('add_sale_staff') || Entrust::can('edit_sale_staff')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('dealer-terminals', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_dealer_link_terminal')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_dealer_link_terminal')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_dealer_link_terminal')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_dealer_link_terminal')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('dealer-banks', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_dealer_link_bank')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_dealer_link_bank')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_dealer_link_bank')) $access = true;
	} /*else if (str_contains($action, 'destroy')) {
    	if (Entrust::can('delete_dealer_link_terminal')) $access = true;
    }*/

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});


Route::filter('terminals', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_terminal')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_terminal')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_terminal')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_terminal')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('currency-conversion-rules', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_currency_conversion_rule')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_currency_conversion_rule')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('sales', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search') || str_contains($action, 'show')) {
		if (Entrust::can('view_sale_staff')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_sale_staff')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_sale_staff')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_sale_staff')) $access = true;
	} else if (str_contains($action, 'target')) {
		if (Entrust::can('view_dragon_warrior_target')) $access = true;
	} else if (str_contains($action, 'createTarget') || str_contains($action, 'storeTarget')) {
		if (Entrust::can('add_dragon_warrior_target')) $access = true;
	} else if (str_contains($action, 'editTarget') || str_contains($action, 'updateTarget')) {
		if (Entrust::can('edit_dragon_warrior_target')) $access = true;
	}else if (str_contains($action, 'uploadSaleTarget') || str_contains($action, 'storeTarget')) {
		if (Entrust::can('upload_dragon_warrior_target')) $access = true;
	}



	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('customer-calls', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	/*if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_sale_staff')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_sale_staff')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_sale_staff')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_sale_staff')) $access = true;
	}*/

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('tickits', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	/*if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_sale_staff')) $access = true;
	} else if (str_contains($action, 'create') || str_contains($action, 'store')) {
		if (Entrust::can('add_sale_staff')) $access = true;
	} else if (str_contains($action, 'edit') || str_contains($action, 'update')) {
		if (Entrust::can('edit_sale_staff')) $access = true;
	} else if (str_contains($action, 'destroy')) {
		if (Entrust::can('delete_sale_staff')) $access = true;
	}*/

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('sys-audits', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action, 'search')) {
		if (Entrust::can('view_sys_audittrail')) $access = true;
	}
	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('reports', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'saleVisit')) {
		if (Entrust::can('view_sale_visit_report')) $access = true;
	} else if (str_contains($action, 'dealerSummaryBalanceReport')) {
		if (Entrust::can('view_dealer_summary_balance_report')) $access = true;
	} else if (str_contains($action, 'transactionReport')) {
		if (Entrust::can('transaction-report')) $access = true;
	}else if (str_contains($action, 'bankReport')) {
		if (Entrust::can('view_bank_report')) $access = true;
	}else if (str_contains($action, 'masterSubdealerReport')) {
		if (Entrust::can('transaction_master_subdealer_report')) $access = true;
	}else if (str_contains($action, 'summaryPinCodeStock')) {
		if (Entrust::can('summary_pin_code_stock')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('pin-stocks', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;

	if (str_contains($action, 'summaryPinCodeStock')) {
		if (Entrust::can('summary_pin_code_stock')) $access = true;
	} else if (str_contains($action, 'viewPinStock')) {
		if (Entrust::can('view_pin_stock')) $access = true;
	} else if (str_contains($action, 'uploadPin') || str_contains($action, 'storeUploadPin')) {
		if (Entrust::can('upload_pin')) $access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});


Route::filter('finances', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'deposit') || str_contains($action, 'storeDeposit')) {
		if (Entrust::can('deposit')) $access = true;
	} else if (str_contains($action, 'promotion') || str_contains($action, 'storePromotion')) {
		if (Entrust::can('promotion')) $access = true;
	} else if (str_contains($action, 'commissionFree') || str_contains($action, 'storeCommissionFree')) {
		if (Entrust::can('commission_free')) $access = true;
	} else if (str_contains($action, 'masterDepositToSubDealer') || str_contains($action, 'storeMasterDepositToSubDealer')) {
		if (Entrust::can('master-deposit-tosub-dealer')) $access = true;
	} else if (str_contains($action, 'masterWithdrawFromSubDealer') || str_contains($action, 'storeMasterWithdrawFromSubDealer')) {
		if (Entrust::can('master-withdraw-fromsub-dealer')) $access = true;
	} else if (str_contains($action, 'dealerRefund') || str_contains($action, 'storeDealerRefund')) {
		if (Entrust::can('dealer_refund')) $access = true;
	} else if (str_contains($action, 'dealerPayback') || str_contains($action, 'storeDealerPayback')) {
		if (Entrust::can('dealer_payback')) $access = true;
	} else if (str_contains($action, 'getDealerBankAccountNames') || str_contains($action, 'getDealerBankAccountNumber')
		|| str_contains($action, 'getDealerWalletByCondition')) {
		$access = true;
	}



	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('lucky', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action,'printTicket')) {
		//if (Entrust::can('print-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action,'printStoreTicket')) {
		//if (Entrust::can('print-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'cancelTicket')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'cancelStoreTicket')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'payout')) {
		//if (Entrust::can('payout'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'showPayout')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'result')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'showResult')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'lucky') || str_contains($action, 'showReport')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

Route::filter('ticket', function($route, $request)
{
	$action = $route->getAction();
	$action = $action['controller'];
	$access = false;
	if (str_contains($action, 'index') || str_contains($action,'printTicket')) {
		//if (Entrust::can('print-ticket'))
		$access = true;
	}else if (str_contains($action, 'ticket') || str_contains($action,'printStoreTicket')) {
		//if (Entrust::can('print-ticket'))
		$access = true;
	}else if (str_contains($action, 'ticket') || str_contains($action, 'cancelTicket')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'ticket') || str_contains($action, 'cancelStoreTicket')) {
		//if (Entrust::can('cancel-ticket'))
		$access = true;
	}else if (str_contains($action, 'ticket') || str_contains($action, 'payout')) {
		//if (Entrust::can('payout'))
		$access = true;
	}else if (str_contains($action, 'ticket') || str_contains($action, 'showPayout')) {
		//if (Entrust::can('payout'))
		$access = true;
	}

	if (!$access) {
		return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
	}
});

//----------------End Filter Role-Permission--------------------------------

/*Route::filter('view_dealer', function()
{
    if (! Entrust::can('view_dealer'))
    	return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
});
Route::filter('add_dealer', function()
{
    if (! Entrust::can('add_dealer'))
    	return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
});
Route::filter('edit_dealer', function()
{
    if (! Entrust::can('edit_dealer'))
    	return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
});
Route::filter('delete_dealer', function()
{
    if (! Entrust::can('delete_dealer'))
    	return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
});
Route::filter('view_sale_visit_report', function()
{
    if (! Entrust::can('view_sale_visit_report'))
    	return Redirect::to('/permission-deny')->with('flash_notice_error', Config::get('globle.deny'));
});*/

