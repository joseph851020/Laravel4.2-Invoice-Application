@extends('layouts.default')

	@section('content')
	
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Import services</h1>
	 
	  {{  Form::open(array('url' => 'services/process_import', 'files' => true)) }}
	  
	  	
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
	            <span class="small">(Must be a CSV file and in the correct format as shown in the example template)</span>
	            </label>	            
	            {{ Form::file('itemscsv') }}
	            
	            <input type="submit" id="importservice" class="gen_btn" name="importservice" value="Upload file" />
	             <br /><a class="alink" href="{{ URL::asset('sample_data/services.csv') }}">Download services csv template</a>
	        	 
	        </div><!-- END LONG BOX -->
	      </div><!-- END company logo  -->
	
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