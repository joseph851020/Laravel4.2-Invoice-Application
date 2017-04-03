@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a class="" href="{{ URL::to('merchants') }}">Merchants</a> &raquo; Export</h1>
 		 
	       {{ Form::open(array('url' => 'merchants/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="merchant_download" class="gen_btn" name="merchant_download" value="Download CSV" />  
	       {{ Form::close() }}
  
	@stop
	

		@section('footer')
	
	 <script>
	
		$(document).ready(function(){
		 
			 if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Expenses');				 
				  $('.menu_all_merchants').addClass('selected');
			  }
			 
		});
		
	</script>
 
	@stop
	

	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
		 
			 if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Expenses');				 
				  $('.menu_all_merchants').addClass('selected');
			  }
			 
		});
		
	</script>
 
	@stop