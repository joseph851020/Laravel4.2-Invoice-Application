<div id='appmenu'>
<ul>
   <li class="home_menu"><a href="{{ URL::to('dashboard') }}"><span><i class="fa fa-home"></i>Home</span></a></li>
   <li class="create_all_menu"><a href='#'><span><i class="fa fa-plus-circle"></i>Create</span></a>
      <ul>                         
		 	<li class="menu_create_invoice newinvoice_dropdown">
		        <a class="menu_newinvoice" data="invoice" href="{{ URL::to('invoices/create') }}"><i class="fa fa-file-text"></i>New Invoice</a>		       				
					<div class="menu_newinvoice_submenu page_popup">
						  <?php $preferences = Preference::where('tenantID', '=', Session::get('tenantID'))->first();
                                $tenant = Tenant::where('tenantID', '=', Session::get('tenantID'))->first();
                            ?>
							
						<form action="/settings/invoice_update" method="POST">
							  	<input name="_token" type="hidden" value="{{ csrf_token() }}">
							  	<input type="hidden" name="_method" value="PUT">
							  	<div class="option_section">
									<p><strong>What is this <span class="form_type"></span> for?</strong> <br />
									<label for="option_service"><input id="option_service" type="radio" class="" name="business_model" value="1" {{ $preferences->business_model == 1 ? 'checked' : '' }}> services</label> &nbsp;&nbsp;
									<label for="option_product"><input id="option_product" type="radio" class="" name="business_model" value="0" {{ $preferences->business_model == 0 ? 'checked' : '' }}> products</label> <br>
									</p>
								</div>
								
								<div id="bill_option" class="option_section {{ $preferences->business_model == 0 ? 'hide_section' : '' }}">
									<p><strong>Billing method (for services)</strong> <br />
									<label for="option_bill_project"><input id="option_bill_project" type="radio" name="bill_option" value="1" {{ $preferences->bill_option == 1 ? 'checked' : '' }}> per project</label> &nbsp;&nbsp;
									<label for="option_bill_hour"><input id="option_bill_hour" type="radio" name="bill_option" value="0" {{ $preferences->bill_option == 0 ? 'checked' : '' }}> per hour</label><br>
									</p>
								</div>
								
								<div class="option_section">
								<p> 
								<label for="option_disc"> <input id="option_disc" type="checkbox" name="enable_discount" value="1" {{ $preferences->enable_discount == 1 ? 'checked' : '' }}> enable discount</label> &nbsp;&nbsp;			  
								 @if($preferences->tax_perc1 > 0 || $preferences->tax_perc2 > 0)
								<label for="option_tax"> <input id="option_tax" type="checkbox" name="enable_tax" value="1" {{ $preferences->enable_tax == 1 ? 'checked' : '' }}> enable tax</label>
								 @endif
							    </p>
							    </div>
							    <input type="hidden" id="form_type" name="form_type" value="">
						     <br />	         
						    <button class="btn btn-default cancelBtn cancel_newinvoce">Cancel</button> <button id="go_invoice_form" class="gen_btn" name="" value="Next step" />Next step</button>
						 
				   		</form>
						 
					</div><!-- END newinvoice_dropdown -->
		        
		    </li>
		    	 
		    <li class="menu_create_expense">
		        <a href="{{ URL::to('expenses/create') }}"><i class="fa fa-money" title="Expenses"></i>New Expense</a>
		    </li>
		    
		     <li class="menu_create_quote newinvoice_dropdown">
		        <a class="menu_newinvoice" data="quote" href="{{ URL::to('quotes/create') }}"><i class="fa fa-file-text-o"></i>New Quote</a>
		    </li> 		    
			 
		    <li class="menu_create_client">
		        <a href="{{ URL::to('clients/create') }}"><i class="fa fa-users"></i>New Client</a>
		    </li>
		   
		    <li class="menu_create_service">
		        <a href="{{ URL::to('services/create') }}"><i class="fa fa-suitcase"></i>New Service</a>
		    </li> 
		    
		    <li class="menu_create_product">
		        <a href="{{ URL::to('products/create') }}"><i class="fa fa-cube"></i>New Product</a>
		    </li>	                             
       </ul>
   </li>
   
   
   <li class="manage_all_menu"><a href='#'><span><i class="fa fa-folder-open"></i>Manage</span></a>
      <ul>
         <li class="menu_all_invoices">
		        <a href="{{ URL::to('invoices') }}"><i class="fa fa-file-text"></i>Invoices</a>
		    </li> 
		    
		    <li class="menu_all_expenses">
		        <a href="{{ URL::to('expenses') }}"><i class="fa fa-money"></i>Expenses</a>
		    </li>
		    
		    <li class="menu_all_quotes">
		        <a href="{{ URL::to('quotes') }}"><i class="fa fa-file-text-o"></i>Quotes</a>
		    </li> 
			 
		    <li class="menu_all_clients">
		        <a href="{{ URL::to('clients') }}"><i class="fa fa-users"></i>Clients</a>
		    </li>
		   
		    <li class="menu_all_services">
		        <a href="{{ URL::to('services') }}"><i class="fa fa-suitcase"></i>Services</a>
		    </li> 
		    
		    <li class="menu_all_products">
		        <a href="{{ URL::to('products') }}"><i class="fa fa-cube"></i>Products</a>
		    </li>
      </ul>
   </li>
   
   
   <li class="report_all_menu"><a href='#'><span><i class="fa fa-line-chart"></i>Reports</span></a>
   		<ul>        	  
		    <li class="menu_financial_summary">
		        <a href="{{ URL::to('reports/summary') }}"><i class="fa fa-area-chart"></i>Financial Summary</a>
		    </li>
            
		    <li class="menu_profit_loss">
		        <a href="{{ URL::to('reports/profit_and_loss') }}"><i class="fa fa-book"></i>Profit &amp; Loss</a>
		    </li>
            
       </ul>
   </li>
   
   
   <li class="settings_all_menu"><a href='#'><span><i class="fa fa-cog"></i>Settings</span></a>
        <ul>
        	<li class="menu_company">
		        <a href="{{ URL::to('company') }}"><i class="fa fa-building-o"></i>Business Profile</a>
		    </li>
		                           
		 	<li class="menu_general_settings">
		        <a href="{{ URL::to('settings') }}"><i class="fa fa-cogs"></i>Account Settings</a>
		    </li> 
		    
		    <li class="menu_currency_rate_settings">
		        <a href="{{ URL::to('currency-rates') }}"><i class="fa fa-money"></i>Currency Rates</a>
		    </li> 
		    
		    <li class="menu_all_users">
		        <a href="{{ URL::to('users') }}"><i class="fa fa-user"></i>User Accounts</a>
		    </li>
            
		    <li class="menu_payment_gateway">
		 	  	<a href="{{ URL::to('paymentgateways') }}"><i class="fa fa-credit-card"></i>Payment Gateway</a>
		    </li>
            
			<li class="menu_invoice_template">
                <a href="{{ URL::to('settings/invoice_template') }}"><i class="fa fa-file-pdf-o"></i>Invoice Designs</a>
            </li>
		    
		    <li class="menu_app_theme">
		        <a href="{{ URL::to('settings/apptheme') }}"><i class="fa fa-picture-o"></i>App Themes</a>
		    </li>		                         
       </ul>
   </li>
   
   <li class="more_all_menu"><a href='#'><span><i class="fa fa-ellipsis-h"></i>More</span></a>
        <ul>        	  
		    <li class="menu_help">
		        <a class="gethelp" href="{{ URL::to('help') }}"><i class="fa fa-life-ring"></i> Get Help</a>
		    </li> 
		    <li class="menu_subscription">
		        <a class="getsubscription" href="{{ URL::to('subscription') }}"><i class="fa fa-level-up"></i>Subscription</a>
		    </li> 
		    <li class="menu_export_data">
		        <a href="{{ URL::to('export') }}"><i class="fa fa-cloud-download"></i>Export Data</a>
		    </li> 		     
		    <li class="menu_improvements">
		        <a href="{{ URL::to('improvements') }}"><i class="fa fa-info"></i>Latest Updates</a>
		    </li>                       
       </ul>
   </li>
   
</ul>
</div>