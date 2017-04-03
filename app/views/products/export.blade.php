@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Export Products Data (CSV)</h1>
 		 
       {{ Form::open(array('url' => 'products/process_export', 'method' => 'POST')) }}
           <input type="submit" id="products_download" class="gen_btn" name="product_download" value="Download CSV" />  
       {{ Form::close() }}
	    
	@stop
	

	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
				
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_products').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			 }
			 
		});
		
	</script>
 
	@stop