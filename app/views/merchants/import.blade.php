@extends('layouts.default')

	@section('content')
	
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a class="" href="{{ URL::to('merchants') }}">Merchants</a> &raquo; Import</h1>
	 
	  {{  Form::open(array('url' => 'merchants/process_import', 'files' => true)) }}
	  
	  	
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
	            {{ Form::file('merchantscsv') }}
	            
	            <input type="submit" id="importmerchant" class="gen_btn" name="importmerchant" value="Upload file" />
	            
	            <br /><a class="alink" href="{{ URL::asset('sample_data/merchants.csv') }}">Download merchants csv format</a>
	        	 
	        </div><!-- END LONG BOX -->
	      </div><!-- END company logo  -->
	
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