<?php
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Preference\Reader as PreferenceReader;
use IntegrityInvoice\Services\Preference\OnetimeUpdater as OnetimeUpdater;
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;

use IntegrityInvoice\Services\Tenant\Reader as TenantReader;

class DashboardController extends BaseController {

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

        $count_data = array(
            'total_clients' => Client::where('tenantID', '=', $this->tenantID)->count(),
            'total_merchants' => Merchant::where('tenantID', '=', $this->tenantID)->count(),
            'total_products' => Item::where('tenantID', '=', $this->tenantID)->where('item_type', '=', 'product')->count(),
            'total_services' => Item::where('tenantID', '=', $this->tenantID)->where('item_type', '=', 'service')->count(),
            'total_invoices' => Invoice::where('tenantID', '=', $this->tenantID)->where('quote', '=', 0)->count(),
            'total_expenses' => Expense::where('tenantID', '=', $this->tenantID)->count(),
            'total_payments' => InvoicePayment::where('tenantID', '=', $this->tenantID)->count()
        );


        $total_invoices_value = $this->getInvoiceBalanceInDefaultCurrency();		
        $total_payments_value = $this->getPaymentBalanceInDefaultCurrency();				
        $total_invoices_unpaid_value =  $total_invoices_value - $total_payments_value;		
        $total_expenses_value = $this->getExpenseBalanceInDefaultCurrency();

        $calculated = array(
            'total_invoices_value' => $total_invoices_value,
            'total_expenses_value' => $total_expenses_value,
            'total_payments_value' => $total_payments_value,
            'total_invoices_unpaid_value' => $total_invoices_unpaid_value
        );

        $total_part_paid = Invoice::where('tenantID', '=', $this->tenantID)->where('payment', '=', 1)->where('quote', '=', 0)->count();		
        $total_unpaid = Invoice::where('tenantID', '=', $this->tenantID)->where('payment', '=', 0)->where('quote', '=', 0)->count();


        ////////////////////////////////////////////////////////////////
        /////// EXPENSE VS INCOME

        $startof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->startOfMonth();
        $endof_current_month = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->endOfMonth();

        /*------- THIS MONTH ---------*/
        $payment_received_thismonth = $this->getPaymentReceivedThisMonthInDefaultCurrency();  
        $expense_balance_thismonth = $this->getExpenseThisMonthInDefaultCurrency();		
        $month_thismonth = $startof_current_month->format("M Y");

        /*------- LAST MONTH ---------*/
        $payment_received_lastmonth = $this->getPaymentReceivedLastMonthInDefaultCurrency();  
        $expense_balance_lastmonth = $this->getExpenseLastMonthInDefaultCurrency();		
        $month_lastmonth = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonth()->startOfMonth()->format("M Y");

        /*------- LAST 2 MONTH ---------*/  
        $payment_received_last2months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(2);  
        $expense_balance_last2months = $this->getExpense2t06MonthsInDefaultCurrency(2);		
        $month_last2months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(2)->startOfMonth()->format("M Y");

        /*------- LAST 3 MONTH ---------*/
        $payment_received_last3months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(3);  
        $expense_balance_last3months = $this->getExpense2t06MonthsInDefaultCurrency(3);		
        $month_last3months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(3)->startOfMonth()->format("M Y");

        /*------- LAST 4 MONTH ---------*/
        $payment_received_last4months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(4);  
        $expense_balance_last4months = $this->getExpense2t06MonthsInDefaultCurrency(4);		
        $month_last4months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(4)->startOfMonth()->format("M Y");

        /*------- LAST 5 MONTH ---------*/
        $payment_received_last5months = $this->getPaymentReceived2t06MonthsInDefaultCurrency(5);  
        $expense_balance_last5months = $this->getExpense2t06MonthsInDefaultCurrency(5);		
        $month_last5months = Carbon::createFromDate($this->yearRangeStart, $this->monthRangeStart)->subMonths(5)->startOfMonth()->format("M Y");


        ////// MONTHLY INCOME AND EXPENSES FOR THE PAST 6 MONTH
        $income_and_expenses = array(
            'thismonth' => array(
                'monthtitle' => "'".$month_thismonth."'", 
                'monthexpense' => $expense_balance_thismonth,
                'monthincome' => $payment_received_thismonth
            ),
            'lastmonth' => array(
                'monthtitle' => "'".$month_lastmonth."'", 
                'monthexpense' => $expense_balance_lastmonth,
                'monthincome' => $payment_received_lastmonth
            ),
            'last2months' => array(
                'monthtitle' => "'".$month_last2months."'", 
                'monthexpense' => $expense_balance_last2months,
                'monthincome' => $payment_received_last2months
            ),
            'last3months' => array(
                'monthtitle' => "'".$month_last3months."'", 
                'monthexpense' => $expense_balance_last3months,
                'monthincome' => $payment_received_last3months
            ),
            'last4months' => array(
                'monthtitle' => "'".$month_last4months."'", 
                'monthexpense' => $expense_balance_last4months,
                'monthincome' => $payment_received_last4months
            ),
            'last5months' => array(
                'monthtitle' => "'".$month_last5months."'", 
                'monthexpense' => $expense_balance_last5months,
                'monthincome' => $payment_received_last5months
            )		 
        );


        $cur_symbol = AppHelper::dumCurrencyCode($preferences->currency_code);

        return View::make('dashboard.index')
            ->with('tenant_email_verified', $this->tenant->find($this->tenantID)->verified)
            ->with(compact('preferences'))	 
            ->with('title', 'Dashboard')
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

        $tenant_currencies = DB::table('invoices')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');		
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


    public function improvements()
    {
        return View::make('dashboard.improvements')->with('title', 'Improvements');
    }

    public function invite()
    {
        return View::make('dashboard.invites')->with('title', 'Invites');
    }

    public function send_invite()
    {

    }

    public function support()
    {
        return View::make('dashboard.support')->with('title', 'Contact Us');

    }


    public function support_send()
    {

        $user = User::find(Session::get('user_id'));

        // Use Javascript for validation

        // Send email
        $priority = Input::get('priority');
        $feedback_type = Input::get('feedback_type');
        $senttime = AppHelper::date_to_text(strftime("%Y-%m-%d %H:%M:%S", time()));
        $ip = $_SERVER['REMOTE_ADDR'];
        $email_subject = '#'.$feedback_type.', '.Input::get('subject').', Priority - '.Input::get('priority');

        $issues = Input::get('issues');			
        $issues = str_replace("\r\n","<br />",$issues); // Replaces Blank lines with <br />
        $issues = str_replace("\n","<br />",$issues);

        if(Input::get('subject') == "" || Input::get('subject') == NULL){
            return Redirect::to('support')->with('failed_flash_message', 'Message not sent. Subject is missing.')->withInput();
        } 

        if($issues == "" || $issues == NULL){
            return Redirect::to('support')->with('failed_flash_message', 'Message not sent. Description missing.')->withInput();
        }


        $email_body = 'Feedback type: <br />'.$feedback_type;
        $email_body .= '<br /> <br /> Description: <br />'. $issues;		
        $email_body .= '<br /> Priority: '.$priority;
        $email_body .= '<br />  Sent: '.$senttime;
        $email_body .= '<br />  IP Address: '.$ip;

        $from_email = $user->email;
        $from_name = Company::where('tenantID', '=', $this->tenantID)->pluck('company_name');


        if($this->mailer->send_support($from_name, $from_email, $email_subject, $email_body))
        { 
            return Redirect::to('support')->with('flash_message', 'Your message has been sent. We\'ll review shortly. Thank you.');
        }
        else 
        {
            return Redirect::to('support')->with('failed_flash_message', 'Message not sent.');

        }


    }

    public function process_invites()
    {

    } // END


    public function search()
    {
        return View::make('dashboard.search')->with('title', 'Search Results');
    }

    public function import()
    {
        return View::make('dashboard.import')->with('title', 'Import Data');
    }


    public function export()
    {
        return View::make('dashboard.export')->with('title', 'Export Data');
    }

    public function archive()
    {
        return View::make('dashboard.archive')->with('title', 'Invoice archive');

        // Send Date picker too
    }

    public function download_archive()
    {
        $preference = new PreferenceReader($this->preference, $this);

        // $startdate = AppHelper::convert_to_mysql_yyyymmdd(Input::get('startdate'), $preference->read()->date_format);
        // $enddate = AppHelper::convert_to_mysql_yyyymmdd(Input::get('enddate'),  $preference->read()->date_format);


        $invoice_root_dir = public_path(). '/te_da/'.$this->tenantID.'/invoices';

        $zip = new ZipArchiveEx();

        $zip->open(public_path(). '/te_da/'.$this->tenantID.'/user_data/invoice_archive.zip', ZIPARCHIVE::OVERWRITE);
        // Add whole directory including contents:
        $zip->addDir($invoice_root_dir);

        $zip->close();

        // generate filename to add to zip
        $filepath = public_path(). '/te_da/'.$this->tenantID. '/user_data/invoice_archive.zip';
        return Response::download($filepath);

        //return Redirect::route('invoice_archives')->with('flash_message','Archived downloaded successfully.');
    }



    public function close_forever(){
                /*
                if($this->input->post('docloseforever') == 1){
                        $this->User_model->update_record_by_id(array('firsttimer'=> 0), $this->user_id, $this->tenant_id);
                } */
    }

    public function close_temporarily(){

                /*
                if($this->input->post('doclosetemporarily') == 1){
                        $this->session->set_userdata(array('closenew_infobox' => true));
                }
                 */
    }

    public function remove_tenant_verify(){
        Session::put('remove_tenant_verify', Input::get('tenant_verify_remind'));
    }

    public function resend_account_verification_mail() {
            $user = Auth::user()->get();
            $tenantReader = new TenantReader($this->tenant, $this);
            $tenant = $tenantReader->read($this->tenantID);
            $selected_plan = Session::get('account_plan');
            $activationUrl = Config::get('app.app_domain').'signup/verify/'.$tenant->activation_key.'/'.$selected_plan;
            Event::fire('user.resend_account_verification_mail', ['user' => $user, 'activationCode' => $activationUrl]);
            $reverification_mail_sent = TRUE;
            return Redirect::to('dashboard');

    }
}
