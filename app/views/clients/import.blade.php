@extends('layouts.default')

	@section('content')
	
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a class="" href="{{ URL::to('clients') }}">Clients</a> &raquo; Import </h1>
	 
	  {{  Form::open(array('url' => 'clients/process_import', 'files' => true)) }}
	  
	  	
	  	@if($errors->has())
	  	  <div class="flash error">
			<ul>
				{{ $errors->first('file', '<li>:message</li>') }}
			</ul>
		  </div>
		@endif 
	   
	   <div id="import">
	        <div class="longbox">
	            <label>File:
	            <span class="small">(Must be a CSV file and in the correct format as shown in the example template) </span>
	            </label>	            
	            {{ Form::file('clientscsv') }}
	            
	            <input type="submit" id="importclient" class="gen_btn" name="importclient" value="Upload file" />
	            
	            <br /><a class="alink" href="{{ URL::asset('sample_data/clients.csv') }}">Download clients csv template</a>
	        	 
	        </div><!-- END LONG BOX -->
	      </div><!-- END Upload  -->
	
	{{ Form::close() }}  
   @stop
   

  @section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_clients').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			  }		 
		});
		
	</script>
		
  @stop