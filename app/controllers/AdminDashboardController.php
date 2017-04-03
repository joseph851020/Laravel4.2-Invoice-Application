<?php

use Carbon\Carbon;

class AdminDashboardController extends BaseController {
 
 
	function __construct()
    {
		 
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$yearRangeStart = date('Y', time());
		$yearRangeEnd = date('Y', time());
		$monthRangeStart = date('n', time());
		$monthRangeEnd = date('n', time());
		$monthStart = 1;
		
		// Subscriptions
		$total_tenants = Tenant::where('verified', '>', '0')->count();
		$total_starter = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->count();
		$total_premium = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->count();
		$total_super_premium = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->count();
		
		// This month's subscription
		$total_tenants_this_month = Tenant::where('verified', '>', '0')->count();		
		$total_starter_this_month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->count();	
			
		$total_premium_this_month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->count();
				
		$total_super_premium_this_month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->count();
				
	  
		// Sales
		$total_sales = PaymentsHistory::all()->sum('amount');
		  
		$total_sales_this_week = PaymentsHistory::whereBetween('created_at', array(
		   Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfWeek(),
		   Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfWeek()
		))->sum('amount');
		
		$total_sales_this_month = PaymentsHistory::whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->sum('amount');
		
		
		$total_sales_this_year = PaymentsHistory::whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->sum('amount');
		
		 	 
        	return View::make('admindashboard.index')		 
		->with('title', 'Admin Dashboard')	
		->with(compact('total_tenants'))
		->with(compact('total_starter'))
		->with(compact('total_premium'))
		->with(compact('total_super_premium'))
	 
		->with(compact('total_tenants_this_month'))
		->with(compact('total_starter_this_month'))
		->with(compact('total_premium_this_month'))
		->with(compact('total_super_premium_this_month'))
		
		->with(compact('total_sales'))
		->with(compact('total_sales_this_week'))
		->with(compact('total_sales_this_month'))
		->with(compact('total_sales_this_year'))	 
		->with('script', 'dashboard');
	}

	
	public function report()
	{
		$yearRangeStart = date('Y', time());
		$yearRangeEnd = date('Y', time());
		$monthRangeStart = date('n', time());
		$monthRangeEnd = date('n', time());
		$monthStart = 1;
		
		// This month's subscription
		$total_starter_this_month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->count();	
			
		$total_premium_this_month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->count();
				
		$total_super_premium_this_month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))->count();
		$month_thismonth = Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth()->format("M Y");
		
		// last month
		$total_starter_lastmonth = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonth()->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonth()->endOfMonth()
		))->count();	
			
		$total_premium_lastmonth = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonth()->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonth()->endOfMonth()
		))->count();
				
		$total_super_premium_lastmonth = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonth()->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonth()->endOfMonth()
		))->count();
		$month_lastmonth = Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonth()->startOfMonth()->format("M Y");
		
		// last 2 month
		$total_starter_last2month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(2)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(2)->endOfMonth()
		))->count();	
			
		$total_premium_last2month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(2)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(2)->endOfMonth()
		))->count();
				
		$total_super_premium_last2month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(2)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(2)->endOfMonth()
		))->count();
		$month_last2month = Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(2)->startOfMonth()->format("M Y");
		
		// last 3 month
		$total_starter_last3month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(3)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(3)->endOfMonth()
		))->count();	
		
		$total_premium_last3month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(3)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(3)->endOfMonth()
		))->count();
				
		$total_super_premium_last3month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(3)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(3)->endOfMonth()
		))->count();
		
		$month_last3month = Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(3)->startOfMonth()->format("M Y");
		
		// last 4 month
		$total_starter_last4month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(4)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(4)->endOfMonth()
		))->count();	
		
		$total_premium_last4month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(4)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(4)->endOfMonth()
		))->count();
		
				
		$total_super_premium_last4month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(4)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(4)->endOfMonth()
		))->count();
		
		$month_last4month = Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(4)->startOfMonth()->format("M Y");
		
		// last 5 month
		$total_starter_last5month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '1')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(5)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(5)->endOfMonth()
		))->count();	
			
		$total_premium_last5month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '2')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(5)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(5)->endOfMonth()
		))->count();
				
		$total_super_premium_last5month = Tenant::where('verified', '>', '0')->where('account_plan_id', '=', '3')->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(5)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->subMonths(5)->endOfMonth()
		))->count();
		$month_last5month = Carbon::createFromDate($yearRangeStart, $monthRangeStart)->subMonths(5)->startOfMonth()->format("M Y");
		
		$income_and_expenses = array
		(

	            'thismonth' => array(
	                'monthtitle' => "'".$month_thismonth."'",
	                'monthstart' => $total_starter_this_month,
	                'monthpremium' => $total_premium_this_month,
	                'monthsuperpremium' => $total_super_premium_this_month
	                
	            ),
	            'lastmonth' => array(
	                'monthtitle' => "'".$month_lastmonth."'",
	                'monthstart' => $total_starter_lastmonth,
	                'monthpremium' => $total_premium_lastmonth,
	                'monthsuperpremium' => $total_super_premium_lastmonth
	            ),
	            'last2months' => array(
	                'monthtitle' => "'".$month_last2month."'",
	                'monthstart' => $total_starter_last2month,
	                'monthpremium' => $total_premium_last2month,
	                'monthsuperpremium' => $total_super_premium_last2month
	            ),
	            'last3months' => array(
	                'monthtitle' => "'".$month_last3month."'",
	                'monthstart' => $total_starter_last3month,
	                'monthpremium' => $total_premium_last3month,
	                'monthsuperpremium' => $total_super_premium_last3month
	            ),
	            'last4months' => array(
	               	'monthtitle' => "'".$month_last4month."'",
	                'monthstart' => $total_starter_last4month,
	                'monthpremium' => $total_premium_last4month,
	                'monthsuperpremium' => $total_super_premium_last4month
	            ),
	            'last5months' => array(
	                'monthtitle' => "'".$month_last5month."'",
	                'monthstart' => $total_starter_last5month,
	                'monthpremium' => $total_premium_last5month,
	                'monthsuperpremium' => $total_super_premium_last5month
	            )
	        );
		
			
		return View::make('admindashboard.report')->with('title', 'Admin Report')
		->with(compact('income_and_expenses'))
		;
		 
		 
		 
	}

	 
	public function search()
	{
		return View::make('dashboard.search')->with('title', 'Search Results');
	}

}