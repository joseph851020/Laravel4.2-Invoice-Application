@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Export Data</h1>
 		
 		   {{ Form::open(array('url' => 'invoices/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="invoices_download" class="gen_btn" name="invoice_download" value="Download Invoices record CSV" />  
	       {{ Form::close() }}
	       
	       {{ Form::open(array('url' => 'expenses/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="expenses_download" class="gen_btn" name="expense_download" value="Download Expenses record CSV" />  
	       {{ Form::close() }}
	       
 			
	       {{ Form::open(array('url' => 'products/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="products_download" class="gen_btn" name="product_download" value="Download Products record CSV" />  
	       {{ Form::close() }}
	       
	       {{ Form::open(array('url' => 'services/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="services_download" class="gen_btn" name="service_download" value="Download Services record CSV" />  
	       {{ Form::close() }}
	       
	       {{ Form::open(array('url' => 'clients/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="clients_download" class="gen_btn" name="client_download" value="Download Clients record CSV" />  
	       {{ Form::close() }}
	       
	       {{ Form::open(array('url' => 'merchants/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="merchant_download" class="gen_btn" name="merchant_download" value="Download Merchants record CSV" />  
	       {{ Form::close() }}
  
	@stop
	

	 @section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_export_data').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			    }
		 
		});
		
	</script>
	
@stop