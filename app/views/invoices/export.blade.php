@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::route('invoices') }}">Invoices</a> &raquo; Export</h1>
 		
 		   {{ Form::open(array('url' => 'invoices/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="invoices_download" class="gen_btn" name="invoice_download" value="Download CSV" />  
	       {{ Form::close() }}
	 
	@stop
	

	@section('footer')
	
	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
  				// Check if ULR Contain invoice
  				if(window.location.href.indexOf("invoice") > -1){
  					
  					$('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_invoices').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
  					 
			  		
  				}else if(window.location.href.indexOf("quote") > -1){
  					
  					$('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_quotes').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			   		
  				}				   
		     }
		 
		});
		
	</script>
 
	@stop