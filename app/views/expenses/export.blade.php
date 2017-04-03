@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('expenses') }}">Expenses</a> &raquo; Export</h1>
 		 
	       {{ Form::open(array('url' => 'expenses/process_export', 'method' => 'POST')) }}
	           <input type="submit" id="expenses_download" class="gen_btn" name="expense_download" value="Download CSV" />  
	       {{ Form::close() }}
	  
	@stop
 
 @section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_expenses').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			    }
		 
		});
		
	</script>
	
@stop