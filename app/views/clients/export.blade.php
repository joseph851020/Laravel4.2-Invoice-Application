@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a class="" href="{{ URL::to('clients') }}">Clients</a> &raquo; Export</h1>
 		 
	       {{ Form::open(array('url' => 'clients/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="clients_download" class="gen_btn" name="client_download" value="Download CSV" />  
	       {{ Form::close() }}
	  
	@stop
	

	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_clients').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			  }	
			 
		});
		
	</script>
 
	@stop