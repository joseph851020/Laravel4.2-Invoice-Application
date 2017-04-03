<?php

use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Preference\Reader as PreferenceReader;
use IntegrityInvoice\Services\Preference\OnetimeUpdater as OnetimeUpdater;
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;

class ReportsController extends BaseController {

    public $account_plan;
    public $tenant_verification;
    public $preference;
    public $tenantID;
    public $tenant;
    public $userID;
    private $mailer;
    public $yearRangeStart;
    public $yearRangeEnd;
    public $monthRangeStart;
    public $monthRangeEnd;
    public $monthStart;

    function __construct(TenantRepositoryInterface $tenant, UserRepositoryInterface $user, PreferenceRepositoryInterface $preference, AppMailer $mailer)
    {
        $this->tenant = $tenant;
        $this->user = $user;
        $this->preference = $preference;
        $this->tenantID = Session::get('tenantID');
        $this->userID = Session::get('user_id');
        $this->mailer = $mailer;
        $this->yearRangeStart = date('Y', time());
        $this->yearRangeEnd = date('Y', time());
        $this->monthRangeStart = date('n', time());
        $this->monthRangeEnd = date('n', time());
        $this->monthStart = 1;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index()
    {
        // One-time setup check
        $onetimecheck = new OnetimeUpdater($this->preference, $this);
        if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please complete the setings below'); }

        $preferenceReaderService = new PreferenceReader($this->preference, $this);
        $preferences = $preferenceReaderService->read();

        $yearRangeStart = date('Y', time());
        $yearRangeEnd = date('Y', time());
        $monthRangeStart = date('n', time());
        $monthRangeEnd = date('n', time());
        $monthStart = 1;

        ////////////////////////////////////////////////////////////////
        /////// EXPENSE VS INCOME

        $startof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->startOfMonth();
        $endof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->endOfMonth();


        /*------- THIS MONTH ---------*/
        $ei_payment_received_thismonth = $this->getPaymentReceivedThisMonthInDefaultCurrency();
        $ei_expense_balance_thismonth = $this->getExpenseThisMonthInDefaultCurrency();
        $month_thismonth = $startof_current_month->format("M Y");


        /*------- LAST MONTH ---------*/
        $ei_payment_received_lastmonth = $this->getPaymentReceivedLastMonthInDefaultCurrency();
        $ei_expense_balance_lastmonth = $this->getExpenseLastMonthInDefaultCurrency();
        $month_lastmonth = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->startOfMonth()->format("M Y");


        /*------- LAST 2 MONTH ---------*/
        $ei_payment_received_last2months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(2);
        $ei_expense_balance_last2months = $this->getExpense2t06MonthsInDefaultCurrency(2);
        $month_last2months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(2)->startOfMonth()->format("M Y");

        /*------- LAST 3 MONTH ---------*/
        $ei_payment_received_last3months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(3);
        $ei_expense_balance_last3months = $this->getExpense2t06MonthsInDefaultCurrency(3);
        $month_last3months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(3)->startOfMonth()->format("M Y");


        /*------- LAST 4 MONTH ---------*/
        $ei_payment_received_last4months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(4);
        $ei_expense_balance_last4months = $this->getExpense2t06MonthsInDefaultCurrency(4);
        $month_last4months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(4)->startOfMonth()->format("M Y");


        /*------- LAST 5 MONTH ---------*/
        $ei_payment_received_last5months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(5);
        $ei_expense_balance_last5months = $this->getExpense2t06MonthsInDefaultCurrency(5);
        $month_last5months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(5)->startOfMonth()->format("M Y");
	 
		 
        ////// MONTHLY INCOME AND EXPENSES FOR THE PAST 6 MONTH
         
        $income_and_expenses = array(

            'thismonth' => array(
                'monthtitle' => "'".$month_thismonth."'",
                'monthexpense' => $ei_expense_balance_thismonth,
                'monthincome' => $ei_payment_received_thismonth
            ),
            'lastmonth' => array(
                'monthtitle' => "'".$month_lastmonth."'",
                'monthexpense' => $ei_expense_balance_lastmonth,
                'monthincome' => $ei_payment_received_lastmonth
            ),
            'last2months' => array(
                'monthtitle' => "'".$month_last2months."'",
                'monthexpense' => $ei_expense_balance_last2months,
                'monthincome' => $ei_payment_received_last2months
            ),
            'last3months' => array(
                'monthtitle' => "'".$month_last3months."'",
                'monthexpense' => $ei_expense_balance_last3months,
                'monthincome' => $ei_payment_received_last3months
            ),
            'last4months' => array(
                'monthtitle' => "'".$month_last4months."'",
                'monthexpense' => $ei_expense_balance_last4months,
                'monthincome' => $ei_payment_received_last4months
            ),
            'last5months' => array(
                'monthtitle' => "'".$month_last5months."'",
                'monthexpense' => $ei_expense_balance_last5months,
                'monthincome' => $ei_payment_received_last5months
            )
        );


        $cur_symbol = AppHelper::dumCurrencyCode($preferences->currency_code);



        ////////////////////////////////////////////////////////////////
        /////// PROFIT / LOSS 

        /*------- PL THIS MONTH ---------*/
        $profit_loss_thismonth = AppHelper::two_decimal($ei_payment_received_thismonth - $ei_expense_balance_thismonth);
	 
        /*------- PL LAST MONTH ---------*/
        $profit_loss_lastmonth = AppHelper::two_decimal($ei_payment_received_lastmonth - $ei_expense_balance_lastmonth);


        /*------- PL LAST 2 MONTH ---------*/
        $profit_loss_last2months = AppHelper::two_decimal($ei_payment_received_last2months - $ei_expense_balance_last2months);


        /*------- PL LAST 3 MONTH ---------*/
        $profit_loss_last3months = AppHelper::two_decimal($ei_payment_received_last3months - $ei_expense_balance_last3months);


        /*------- PL LAST 4 MONTH ---------*/
        $profit_loss_last4months = AppHelper::two_decimal($ei_payment_received_last4months - $ei_expense_balance_last4months);


        /*------- PL LAST 5 MONTH ---------*/
        $profit_loss_last5months = AppHelper::two_decimal($ei_payment_received_last5months - $ei_expense_balance_last5months);
 

        $profit_loss_data = array(

            'thismonth' => array(
                'monthtitle' => "'".$month_thismonth."'",
                'monthvalue' => $profit_loss_thismonth
            ),
            'lastmonth' => array(
                'monthtitle' => "'".$month_lastmonth."'",
                'monthvalue' => $profit_loss_lastmonth
            ),
            'last2months' => array(
                'monthtitle' => "'".$month_last2months."'",
                'monthvalue' => $profit_loss_last2months
            ),
            'last3months' => array(
                'monthtitle' => "'".$month_last3months."'",
                'monthvalue' => $profit_loss_last3months
            ),
            'last4months' => array(
                'monthtitle' => "'".$month_last4months."'",
                'monthvalue' => $profit_loss_last4months
            ),
            'last5months' => array(
                'monthtitle' => "'".$month_last5months."'",
                'monthvalue' => $profit_loss_last5months
            )
        );

        return View::make('reports.index')
            ->with('tenant_email_verified', $this->tenant->find($this->tenantID)->verified)
            ->with(compact('preferences'))
            ->with('title', 'Summary Report')
            ->with(compact('profit_loss_data'))
            ->with(compact('activity_data'))
            ->with(compact('count_data'))
            ->with(compact('calculated'))
            ->with(compact('total_part_paid'))
            ->with(compact('total_unpaid'))
            ->with(compact('income_and_expenses'))
            ->with(compact('cur_symbol'))
            ->with('firsttimer', $this->user->find($this->tenantID, $this->userID)->firsttimer);
    }

    public function getInvoiceBalanceInDefaultCurrency()
    {
        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('invoices')->where('tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)->where('currency_code','=', $preferences->currency_code)->sum('balance_due');
        $group_totals = DB::table('invoices')->select(DB::raw("currency_code, sum(balance_due) as total"))->where('tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)->groupBy('currency_code')->get();
        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency = round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getExpenseBalanceInDefaultCurrency()
    {
        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)->where('currency_code','=', $preferences->currency_code)->sum('amount');
        $group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))->where('tenantID','=', $this->tenantID)->groupBy('currency_code')->get();

        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency = round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getPaymentBalanceInDefaultCurrency()
    {

        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->where('invoice_payments.tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)->where('invoices.currency_code','=', $preferences->currency_code)->sum('invoice_payments.amount');

        $tenant_currencies = DB::table('expenses')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }

        $group_totals = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->select(DB::raw("invoices.currency_code, sum(invoice_payments.amount) as total"))
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->where('invoices.currency_code','<>', $preferences->currency_code)
            ->whereIn('invoices.currency_code', $tenant_currencies)
            ->where('invoices.quote', '=', 0)->groupBy('invoices.currency_code')->distinct()->get();


        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }


        return $all_totals_home_currency = round($total_default_currency_amount + $total_other_currencies_amount, 2);

    }


    public function getPaymentReceivedThisMonthInDefaultCurrency()
    {
        $startof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->startOfMonth();
        $endof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->endOfMonth();

        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->whereBetween('invoice_payments.created_at', array(
                $startof_current_month,
                $endof_current_month
            ))
            ->where('invoices.quote', '=', 0)->where('invoices.currency_code','=', $preferences->currency_code)->sum('invoice_payments.amount');


        $tenant_currencies = DB::table('invoices')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }


        $group_totals = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->select(DB::raw("invoices.currency_code, sum(invoice_payments.amount) as total"))
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->whereBetween('invoice_payments.created_at', array(
                $startof_current_month,
                $endof_current_month
            ))
            ->whereIn('invoices.currency_code', $tenant_currencies)
            ->where('invoices.quote', '=', 0)->groupBy('invoices.currency_code')->get();
        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency = round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getExpenseThisMonthInDefaultCurrency()
    {
        $startof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->startOfMonth();
        $endof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->endOfMonth();

        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)
            ->whereBetween('expenses.expense_date', array(
                $startof_current_month,
                $endof_current_month
            ))
            ->where('currency_code','=', $preferences->currency_code)->sum('amount');

        $tenant_currencies = DB::table('expenses')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }


        $group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))->where('tenantID','=', $this->tenantID)
            ->whereBetween('expenses.expense_date', array(
                $startof_current_month,
                $endof_current_month
            ))
            ->whereIn('expenses.currency_code', $tenant_currencies)
            ->groupBy('currency_code')->get();

        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency =  round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getPaymentReceivedLastMonthInDefaultCurrency()
    {
        $startof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->startOfMonth();
        $endof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->endOfMonth();

        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->whereBetween('invoice_payments.created_at', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->endOfMonth()
            ))
            ->where('invoices.quote', '=', 0)->where('invoices.currency_code','=', $preferences->currency_code)->sum('invoice_payments.amount');

        $tenant_currencies = DB::table('invoices')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }

        $group_totals = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->select(DB::raw("invoices.currency_code, sum(invoice_payments.amount) as total"))
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->whereBetween('invoice_payments.created_at', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->endOfMonth()
            ))
            ->whereIn('invoices.currency_code', $tenant_currencies)
            ->where('invoices.quote', '=', 0)->groupBy('invoices.currency_code')->get();

        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency =  round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getExpenseLastMonthInDefaultCurrency()
    {
        $startof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->startOfMonth();
        $endof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->endOfMonth();

        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)
            ->whereBetween('expenses.expense_date', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->endOfMonth()
            ))
            ->where('currency_code','=', $preferences->currency_code)->sum('amount');

        $tenant_currencies = DB::table('expenses')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }

        $group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))->where('tenantID','=', $this->tenantID)
            ->whereBetween('expenses.expense_date', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->endOfMonth()
            ))
            ->whereIn('expenses.currency_code', $tenant_currencies)
            ->groupBy('currency_code')->get();

        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency =  round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getExpense2t06MonthsInDefaultCurrency($pastmonths)
    {

        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)
            ->whereBetween('expense_date', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->endOfMonth()
            ))
            ->where('currency_code','=', $preferences->currency_code)->sum('amount');

        $tenant_currencies = DB::table('expenses')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }

        $group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))->where('tenantID','=', $this->tenantID)
            ->whereBetween('expense_date', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->endOfMonth()
            ))
            ->whereIn('expenses.currency_code', $tenant_currencies)
            ->groupBy('currency_code')->get();

        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

        return $all_totals_home_currency =  round($total_default_currency_amount + $total_other_currencies_amount, 2);
    }


    public function getPaymentReceived2t06MonthsInDefaultCurrency($pastmonths)
    {
        $preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();

        $total_default_currency_amount = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->whereBetween('invoice_payments.created_at', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->endOfMonth()
            ))
            ->where('invoices.quote', '=', 0)->where('invoices.currency_code','=', $preferences->currency_code)->sum('invoice_payments.amount');

        $tenant_currencies = DB::table('invoices')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');
        if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }
		
		
		// dd($total_default_currency_amount);

        $group_totals = DB::table('invoice_payments')
            ->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
            ->select(DB::raw("invoices.currency_code, sum(invoice_payments.amount) as total"))
            ->where('invoice_payments.tenantID','=', $this->tenantID)
            ->whereBetween('invoice_payments.created_at', array(
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->startOfMonth(),
                Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths($pastmonths)->endOfMonth()
            ))
            ->whereIn('invoices.currency_code', $tenant_currencies)
            ->where('invoices.quote', '=', 0)->groupBy('invoices.currency_code')->get();
        $total_other_currencies_amount = 0;

        foreach($group_totals as $group_total){
            $total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
        }

       return $all_totals_home_currency = round($total_default_currency_amount + $total_other_currencies_amount, 2);        
         
    }


    public function download(){

		$activities = array(
			
			'last7days' => array(
				'invoice_balance' => $invoice_balance_last7days,
				'payment_received' => $payment_received_last7days,
				'outstanding' => $outstanding_last7days,
				'expense_balance' => $expense_balance_last7days
			),
			
			'last30days' => array(
				'invoice_balance' => $invoice_balance_last30days,
				'payment_received' => $payment_received_last30days,
				'outstanding' => $outstanding_last30days,
				'expense_balance' => $expense_balance_last30days
			),
			
			'last3months' => array(
				'invoice_balance' => $invoice_balance_last3months,
				'payment_received' => $payment_received_last3months,
				'outstanding' => $outstanding_last3months,
				'expense_balance' => $expense_balance_last3months
			),
			
			'last6months' => array(
				'invoice_balance' => $invoice_balance_last6months,
				'payment_received' => $payment_received_last6months,
				'outstanding' => $outstanding_last6months,
				'expense_balance' => $expense_balance_last6months
			)
		
		);
		
		$preferenceReaderService = new PreferenceReader($this->preference, $this);		
		$preferenceReaderService->read();
		
 		/*
		if($download_mode == false){
	   	 
		 	// return $pdf->generateFromHtml(View::make('quotes.download'.$preferences->invoice_template, $data), $pdf_file_loc);
			
	   	}else{
	   	 
		  $pdf->generateFromHtml(View::make('reports.download', $data), $pdf_file_loc, array(), true);				  
		  return Response::download($pdf_file_loc);
	 
	  	} */
	 
	}//
	
	
	
	public function profit_and_loss_start()
	{
		$preferenceReaderService = new PreferenceReader($this->preference, $this);		
		$preferences = $preferenceReaderService->read();
		
		return View::make('reports.profit_and_loss_start')		 
				->with('title', 'Profit and Loss Report')
				->with(compact('preferences'));	 
	 
	}
	
	public function profit_and_loss()
	{
		
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }
	  
		$preferenceReaderService = new PreferenceReader($this->preference, $this);		
		$preferences = $preferenceReaderService->read();
	 
	    if(Input::get('startdate') != "" && Input::get('enddate') != "")
		{
		  	$from_date = AppHelper::convert_to_mysql_yyyymmdd(Input::get('startdate'), $preferences->date_format);
			$to_date = AppHelper::convert_to_mysql_yyyymmdd(Input::get('enddate'), $preferences->date_format);
		 
		}
		else
		{
			return Redirect::to('reports/profit_and_loss')->with('failed_flash_message', 'Invalid date range');
		}
	 
		
		$raw_startdate = $from_date;
		$raw_enddate = $to_date;
	 
		//$to_date = Carbon::now();
		//$from_date = $to_date->subMonths(6);	
		
		//$from_date = '2014-01-01 00:00:00';	
		//$to_date = '2014-07-09 00:00:00';
		
		$format = 'Y-m-d';
		if($preferences->date_format == "dd/mm/yyy"){
			$format = 'Y-m-d';
		}
		
		if($preferences->date_format == "mm/dd/yyy"){
			$format = 'Y-d-m';
		}
		 
		$sdt = Carbon::createFromFormat($format, $from_date);
		$edt = Carbon::createFromFormat($format, $to_date);
		 
		$startdate = $sdt->format('jS \\of F Y');		
		$enddate = $edt->format('jS \\of F Y'); 
		
		 if($sdt->diffInDays($edt, false) < 0){		  
		 	return Redirect::to('reports/profit_and_loss')->with('failed_flash_message', 'Start date cannot be greater than end date.')->withInput();
		 }
		 
	 
		$todate_plus1 = datetime::createfromformat('Y-m-d',$to_date);
		$todate_plus1->add(new DateInterval('P1D'));	 
		$to_date = $todate_plus1;
		 
	  
		$income = $this->getPlIncome($from_date, $to_date); 
		$total_expenses = $this->getPlExpenses($from_date, $to_date);		
		$total_tax = $this->getPlTax($from_date, $to_date);	
		$expenses_with_category = $this->getPlExpenseWithCategory($from_date, $to_date);		
		$currencyExchangeRates = $this->getCurrencyRate();

		return View::make('reports.profit_and_loss')		 
		->with('title', 'Profit and Loss Report')		 
		->with(compact('startdate'))
		->with(compact('enddate'))
		->with(compact('raw_startdate'))
		->with(compact('raw_enddate'))
		->with(compact('income'))
		->with(compact('expenses_with_category'))
		->with(compact('currencyExchangeRates'))
		->with(compact('total_tax'))
		->with(compact('total_expenses'))
		->with(compact('preferences'));
	}

	public function getPlTax($from_date, $to_date)
	{
		 
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		 
		$tax_default_currency_amount = DB::table('invoices')->where('tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)
		->where('currency_code','=', $preferences->currency_code)
		->where('payment','>', 0)
		->whereBetween('created_at', array(
		    $from_date,
		    $to_date
		))->sum('tax_val');
		
		
		$group_totals = DB::table('invoices')->select(DB::raw("currency_code, sum(tax_val) as total"))
		->where('tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)
		->where('payment','>', 0)
		->whereBetween('created_at', array(
		    $from_date,
		    $to_date
		))->groupBy('currency_code')->get();
		
		$total_tax_other_currencies_amount = 0;
		
		foreach($group_totals as $group_total){
			$total_tax_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
		}
	  
		return round($tax_default_currency_amount + $total_tax_other_currencies_amount, 2);
	}


	public function getPlIncome($from_date, $to_date)
	{
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		
		$income_default_currency_amount = DB::table('invoice_payments')
		->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
		->where('invoice_payments.tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)->where('invoices.currency_code','=', $preferences->currency_code)
		->whereBetween('invoice_payments.created_at', array(
		    $from_date,
		    $to_date
		))
		->sum('invoice_payments.amount');
		 
		$tenant_currencies = DB::table('invoices')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');		
		if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }	 
	 	 
		$group_totals = DB::table('invoice_payments')
		->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
		->select(DB::raw("invoices.currency_code, sum(invoice_payments.amount) as total"))
		->where('invoice_payments.tenantID','=', $this->tenantID)
		->where('invoices.currency_code','<>', $preferences->currency_code)
		->whereIn('invoices.currency_code', $tenant_currencies)
		->where('invoices.quote', '=', 0)
		->whereBetween('invoice_payments.created_at', array(
		    $from_date,
		    $to_date
		))->groupBy('invoices.currency_code')->distinct()->get();
		 
		$total_other_currencies_amount = 0;		 
		
		foreach($group_totals as $group_total){
			$total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
		}
		 
	  
		$all_income_home_currency = round($income_default_currency_amount + $total_other_currencies_amount, 2);
		
	   return $all_income_home_currency;
		
	}


	public function getPlExpenses($from_date, $to_date)
	{
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		 
		$total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)
		->where('currency_code','=', $preferences->currency_code)
		->whereBetween('created_at', array(
		    $from_date,
		    $to_date
		))
		->sum('amount');
		
		
		$group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))
		->where('tenantID','=', $this->tenantID)
		->whereBetween('created_at', array(
		    $from_date,
		    $to_date
		))->groupBy('currency_code')->get();
		
		$total_other_currencies_amount = 0;
		
		foreach($group_totals as $group_total){
			$total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
		}
	  
		return $all_totals_home_currency = round($total_default_currency_amount + $total_other_currencies_amount, 2);
	 
	}


	public function getPlExpenseWithCategory($from_date, $to_date)
	{
		
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
	  
		$expense_with_cat_currencies_amount = DB::table('expenses')->select('expense_categories.expense_name',DB::raw("currency_code, sum(amount) as total_expense_in_category"))
		->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
		->where('tenantID','=', $this->tenantID)
		->whereBetween('expenses.created_at', array(
		    $from_date,
		    $to_date
		))
		->orderBy('expense_name', 'asc')
		->groupBy('expenses.note')->get();
	  
		return $expense_with_cat_currencies_amount;
		
	}


	public function getCurrencyRate()
	{
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		
		return CurrencyRate::where('tenantID', '=', $this->tenantID)
			->where('currency_code','<>', $preferences->currency_code)
			->lists('unit_exchange_rate', 'currency_code');
	}
	
	
	
	public function profit_and_loss_download()
	{
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }

		$preferenceReaderService = new PreferenceReader($this->preference, $this);		
		$preferences = $preferenceReaderService->read();
		
		$company = Company::where('tenantID', '=', $this->tenantID)->first();
		 
		if(Input::get('startdate') != "" && Input::get('enddate') != "")
		{
		  	$from_date = Input::get('startdate');
			$to_date = Input::get('enddate');
		}
		else
		{
			return Redirect::to('reports/profit_and_loss')->with('failed_flash_message', 'Invalid date range');
		}

	 
		 
		$format = 'Y-m-d';
		if($preferences->date_format == "dd/mm/yyy"){
			$format = 'Y-m-d';
		}
		
		if($preferences->date_format == "mm/dd/yyy"){
			$format = 'Y-d-m';
		}
		 
		$sdt = Carbon::createFromFormat($format, $from_date);
		$edt = Carbon::createFromFormat($format, $to_date);
		 
		$startdate = $sdt->format('jS \\of F Y');		
		$enddate = $edt->format('jS \\of F Y'); 
		
		 if($sdt->diffInDays($edt, false) < 0){		  
		 	return Redirect::to('reports/profit_and_loss')->with('failed_flash_message', 'Start date cannot be greater than end date.')->withInput();
		 }
		 
		
		$todate_plus1 = datetime::createfromformat('Y-m-d',$to_date);
		$todate_plus1->add(new DateInterval('P1D'));	 
		$to_date = $todate_plus1;
	 
		
		$income = $this->getPlIncome($from_date, $to_date); 
		$total_expenses = $this->getPlExpenses($from_date, $to_date);		
		$total_tax = $this->getPlTax($from_date, $to_date);	
		$expenses_with_category = $this->getPlExpenseWithCategory($from_date, $to_date);

		$currencyExchangeRates = $this->getCurrencyRate();
		
		 
			$pdf = new Pdf();
			$pdf->setBinary(Config::get('app.binary_dir').Config::get('app.binary'));
			 
	
			//$ts = strtotime($company->created_at);		 
			//$mytoday = date('Y-m-d', $ts);	
			
			$pdf_file = 'Profit_and_loss_report'.'_'.$company->company_name.'.pdf'; 
			 
	 
			$pdf_file_loc = public_path(). '/te_da/'.$this->tenantID.'/user_data/'.$pdf_file;
			
			$pdf_file_loc = str_replace(' ', '_', $pdf_file_loc);
		  
		    $data = array(
   				'title'                  => 'Profit and Loss Report',
   				'company'                => $company,
   				'preferences'   		 => $preferences,
   				'startdate'    			 => $startdate,
   				'enddate'     			 => $enddate,
   				'income'    			 => $income,
   				'expenses_with_category' => $expenses_with_category,
   				'total_tax'    			 => $total_tax,
   				'currencyExchangeRates'  => $currencyExchangeRates,
   				'total_expenses'     	 => $total_expenses
			); 
		   
			$pdf->generateFromHtml(View::make('reports.download_profit_and_loss', $data), $pdf_file_loc, array(), true);				  
		    return Response::download($pdf_file_loc);
		  
	}
	
  
}