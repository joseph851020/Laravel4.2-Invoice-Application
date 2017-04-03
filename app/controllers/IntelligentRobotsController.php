<?php

use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Preference\Reader as PreferenceReader;
use IntegrityInvoice\Services\Preference\OnetimeUpdater as OnetimeUpdater;
use IntegrityInvoice\Services\User\Reader as UserReader;
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;

class IntelligentRobotsController extends BaseController {
	
	public $account_plan;
	public $tenant_verification;
	public $preference;
	public $tenantID;
	public $userID;
	private $mailer;
	
	function __construct(TenantRepositoryInterface $tenant, UserRepositoryInterface $user, PreferenceRepositoryInterface $preference, AppMailer $mailer)
    {
    	$this->tenant = $tenant;
    	$this->user = $user;
		$this->preference = $preference;
		$this->tenantID = Session::get('tenantID');
		$this->userID = Session::get('user_id');
		$this->mailer = $mailer;
	 
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
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }
		
		$preferenceReaderService = new PreferenceReader($this->preference, $this);		
		$preferenceReaderService->read();
		
		$data = array(
			'jan' => array(2, 6, 7, 3),
			'feb' => array(2, 4, 3, 4),
			'mar' => array(3, 4, 4, 1),
			'apr' => array(4, 5, 1, 7),
			'may' => array(2, 6, 4, 4),
			'jun' => array(4, 2, 5, 6),
			'jul' => array(2, 5, 1, 1),
			'aug' => array(5, 4, 4, 6),
			'sep' => array(7, 4, 6, 5),
			'oct' => array(6, 3, 2, 9),
			'nov' => array(4, 5, 0, 3),
			'dec' => array(6, 3, 5, 3)
		);
	  
		$yearRangeStart = date('Y', time());
		$yearRangeEnd = date('Y', time());
		$monthRangeStart = date('n', time());
		$monthRangeEnd = date('n', time());
		$monthStart = 1;
	 
	 	// Everything / All
	 	
	 	$all_invoice_balance = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 0)
		->sum('balance_due');
	   
		$all_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)
		->sum('invoice_payments.amount');
		
		$all_value_outstanding = $all_invoice_balance - $all_value_paid;
		 
		$all_value_quote = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 1)
		->sum('balance_due');
		
		if($all_value_quote == NULL){
			$all_value_quote = 0;
		}
		
		if($all_value_paid == NULL){
		    $all_value_paid = 0;
		}
		
		$all_expenses = Expense::where('tenantID', '=', $this->tenantID)->sum('amount');
	 	
	 	
	 	// THIS YEAR
	 	
	 	$yearly_invoice_balance = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 0)
		->whereBetween('invoices.created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))	 
		->sum('balance_due');
	 
		$yearly_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('amount');
		
		$yearly_invoice_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)		
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('amount');
	  
		
		$yearly_value_outstanding = $yearly_invoice_balance - $yearly_invoice_value_paid;
		 
		$yearly_value_quote = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 1)
		->whereBetween('invoices.created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('balance_due');
		
		if($yearly_value_quote == NULL){
			$yearly_value_quote = 0;
		}
		
		
		if($yearly_value_paid == NULL){
		    $yearly_value_paid = 0;
		}
		 
	 
	 	// THIS MONTH 
	 	
	 	$monthly_invoice_balance = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 0)
		->whereBetween('invoices.created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('balance_due');
		 
	 
		$monthly_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)		
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('amount');
		
		$monthly_invoice_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)		
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		))
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('amount');
	 
		$monthly_value_outstanding = $monthly_invoice_balance - $monthly_invoice_value_paid;
		 
		$monthly_value_quote = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 1)
		->whereBetween('invoices.created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfMonth(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfMonth()
		)) 
		->sum('balance_due');
		
		if($monthly_value_quote == NULL){
			$monthly_value_quote = 0;
		}
		
		if($monthly_value_paid == NULL){
		    $monthly_value_paid = 0;
		}
	 	
	 	
	 	// LAST SEVEN DAYS / WEEK
	 	
	 	$weekly_invoice_balance = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 0)
		->whereBetween('invoices.created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfWeek(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfWeek()
		)) 
		->sum('balance_due');
	 
		$weekly_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)	
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfWeek(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfWeek()
		)) 
		->sum('amount');
		
		$weekly_invoice_value_paid = DB::table('invoice_payments')
		->where('tenantID', '=', $this->tenantID)		
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfWeek(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfWeek()
		))
		->whereBetween('created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfWeek(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfWeek()
		)) 
		->sum('amount');
		
		$weekly_value_outstanding = $weekly_invoice_balance - $weekly_invoice_value_paid;
		
		 // WEEKLY QUOTES 
		$weekly_value_quote = DB::table('invoices')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 1)
		->whereBetween('invoices.created_at', array(
		    Carbon::createFromDate($yearRangeStart, $monthRangeStart)->startOfWeek(),
		    Carbon::createFromDate($yearRangeEnd, $monthRangeEnd)->endOfWeek()
		)) 
		->sum('balance_due');
		
		if($weekly_value_paid == NULL){
		    $weekly_value_paid = 0;
		}
		
		if($weekly_value_quote == NULL){
			$weekly_value_quote = 0;
		}
	 
        return View::make('robots.index')
		->with('tenant_email_verified', $this->tenant->find($this->tenantID)->verified)
		->with('total_outstanding', 1)
		->with('total_paid', 8)
		->with('total_invoiced', 3)
		->with('total_draft', 2)
		->with(compact('all_expenses'))
		->with(compact('all_value_outstanding'))
		->with(compact('all_value_paid'))
		->with(compact('all_value_quote'))
		->with(compact('weekly_value_outstanding'))
		->with(compact('weekly_value_paid'))
		->with(compact('weekly_value_quote'))
		->with(compact('monthly_value_outstanding'))
		->with(compact('monthly_value_paid'))
		->with(compact('monthly_value_quote'))
		->with(compact('yearly_value_outstanding'))
		->with(compact('yearly_value_paid'))
		->with(compact('yearly_value_quote'))
		->with('title', 'Reports')
		->with('firsttime', $this->user->find($this->tenantID, $this->userID)->firsttimer)
		->with(compact('data'));
		
	}
  
}