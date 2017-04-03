@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a class="" href="{{ URL::to('services') }}">Services</a> &raquo; Export</h1>
 		 
       {{ Form::open(array('url' => 'services/process_export', 'method' => 'POST')) }}
           <input type="submit" id="services_download" class="gen_btn" name="service_download" value="Download CSV" />  
       {{ Form::close() }}
	 
	@stop
	

	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
		 		$('.manage_all_menu').addClass('selected_group'); 		 
		  		$('.menu_all_services').addClass('selected');		  		
		  		$('.manage_all_menu ul').css({'display': 'block'});
		 	}
			 
		});
		
	</script>
 
	@stop