@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Download invoices in zip file</h1>
	<div class="" class="group">
	  
	    {{ Form::open(array('url' => 'download/invoices', 'method' => 'POST')) }}
	       <input type="submit" id="download_archive" class="gen_btn" name="download_archive" value="Download" />  
	     {{ Form::close() }}
	  
	</div><!-- END for_help -->
	  
			 
	@stop
	

	@section('footer')
	  
	  <script>
	
		$(function(){
		 
		 	  if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Invoices');				 
				  $('.menu_archive_invoices').addClass('selected');
			  }
		 
		});
		
	  </script>
	 
	@stop