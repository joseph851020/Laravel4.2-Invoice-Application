<?php namespace IntegrityInvoice\Providers;
 
use Illuminate\Support\ServiceProvider;
 
class BackendServiceProvider extends ServiceProvider {
 
  public function register()
  {
	$this->app->bind(
      'IntegrityInvoice\Repositories\ItemRepositoryInterface',
      'IntegrityInvoice\Repositories\DbItemRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\ClientRepositoryInterface',
      'IntegrityInvoice\Repositories\DbClientRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\CurrencyRateRepositoryInterface',
      'IntegrityInvoice\Repositories\DbCurrencyRateRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\MerchantRepositoryInterface',
      'IntegrityInvoice\Repositories\DbMerchantRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\ExpenseRepositoryInterface',
      'IntegrityInvoice\Repositories\DbExpenseRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\TenantRepositoryInterface',
      'IntegrityInvoice\Repositories\DbTenantRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\InvoiceRepositoryInterface',
      'IntegrityInvoice\Repositories\DbInvoiceRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\InvoicePaymentsRepositoryInterface',
      'IntegrityInvoice\Repositories\DbInvoicePaymentsRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\UserRepositoryInterface',
      'IntegrityInvoice\Repositories\DbUserRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\PreferenceRepositoryInterface',
      'IntegrityInvoice\Repositories\DbPreferenceRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface',
      'IntegrityInvoice\Repositories\DbCompanyDetailsRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\ExpenseCategoriesRepositoryInterface',
      'IntegrityInvoice\Repositories\DbExpenseCategoriesRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\CurrencyRepositoryInterface',
      'IntegrityInvoice\Repositories\DbCurrencyRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\PaymentGatewaysRepositoryInterface',
      'IntegrityInvoice\Repositories\DbPaymentGatewaysRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface',
      'IntegrityInvoice\Repositories\DbPaymentsHistoryRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\CancelQueueRepositoryInterface',
      'IntegrityInvoice\Repositories\DbCancelQueueRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\AccountPlanRepositoryInterface',
      'IntegrityInvoice\Repositories\DbAccountPlanRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\DiscountRepositoryInterface',
      'IntegrityInvoice\Repositories\DbDiscountRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\CouponRepositoryInterface',
      'IntegrityInvoice\Repositories\DbCouponRepository'
    );
	
	$this->app->bind(
      'IntegrityInvoice\Repositories\AdminNotificationRepositoryInterface',
      'IntegrityInvoice\Repositories\DbAdminNotificationRepository'
    );
	
  }
 
}