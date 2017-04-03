<?php $tenant2 = Tenant::where('tenantID', '=', Session::get('tenantID'))->first();
?>
<nav id="mobile">
    <div id="toggle-bar">
        <strong class="company-name mtoggle"><i class="fa fa-bars"></i> {{ Company::where('tenantID','=', Session::get('tenantID'))->pluck('company_name') }}</strong>
        <a class="mtoggle" href="#"></a>
    </div>

    <ul id="mmenu">
        <li><a href="{{ URL::to('dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
        <li class=""><a href="#"><i class="fa fa-plus-circle"></i> Create</a>
            <ul class="sub-menu">
                <li><a href="{{ URL::to('invoices/create') }}"><i class="fa fa-file-text"></i> New Invoice</a></li>
                <li><a href="{{ URL::to('expenses/create') }}">New Expenses</a></li>
                <li><a href="{{ URL::to('quotes/create') }}">New Quote</a></li>
                <li><a href="{{ URL::to('clients/create') }}">New Client</a></li>
                <li><a href="{{ URL::to('services/create') }}">New Service</a></li>
                <li><a href="{{ URL::to('products/create') }}">New Product</a></li>
            </ul>
        </li>
        <li class=""><a href="#"><i class="fa fa-folder-open"></i> Manage</a>
            <ul class="sub-menu">
                <li><a href="{{ URL::to('invoices') }}">Invoices</a></li>
                <li><a href="{{ URL::to('expenses') }}">Expenses</a></li>
                <li><a href="{{ URL::to('quotes') }}">Quotes</a></li>
                <li><a href="{{ URL::to('clients') }}">Clients</a></li>
                <li><a href="{{ URL::to('services') }}">Services</a></li>
                <li><a href="{{ URL::to('products') }}">Products</a></li>
            </ul>
        </li>
        <li class=""><a href="#"><i class="fa fa-bar-chart-o"></i> Reports</a>
            <ul class="sub-menu">
                <li><a href="{{ URL::to('reports/summary') }}">Financial summary</a></li>
                <?php if($tenant2->account_plan_id > 1): ?>
                <li><a href="{{ URL::to('reports/profit_and_loss') }}">Profit &amp; Loss</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <li class=""><a href="#"><i class="fa fa-cog"></i> Settings</a>
            <ul class="sub-menu">
                <li><a href="{{ URL::to('company') }}">Business Profile</a></li>
                <li><a href="{{ URL::to('users/'.Session::get('user_id').'/edit') }}">My Profile</a></li>
                <li><a href="{{ URL::to('settings') }}">Account Settings</a></li>
                <li><a href="{{ URL::to('currency-rates') }}">Currency Rates</a></li>
                <?php if($tenant2->account_plan_id > 1): ?>
                <li><a href="{{ URL::to('paymentgateways') }}">Payment Gateway</a></li>
                <?php endif; ?>
                <li><a href="{{ URL::to('settings/invoice_template') }}">Invoice Designs</a></li>
                <li><a href="{{ URL::to('subscription') }}">Subscription</a></li>
                <li><a href="{{ URL::to('improvements') }}">Latest Updates</a></li>
            </ul>
        </li>
        <li><a href="{{ URL::route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>

</nav>