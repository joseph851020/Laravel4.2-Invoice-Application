@extends('layouts.default')

	@section('content')
	
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('expenses') }}">Expenses</a> &raquo; Import</h1>
	 
	  {{  Form::open(array('url' => 'expenses/process_import', 'files' => true)) }}
	  
	  	
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
	            {{ Form::file('expensescsv') }}
	            
	            <input type="submit" id="importexpense" class="gen_btn" name="importexpense" value="Upload file" />
	            
	            <p>If your date format is "Day / Month / Year". <a class="alink" href="{{ URL::asset('sample_data/expenses.csv') }}">Download this expense csv template</a> </p><p>&nbsp;</p>
	            <p>If your date format is "Month / Day / Year". <a class="alink" href="{{ URL::asset('sample_data/expenses2.csv') }}">Download this expense csv template</a> </p>
	       
	        </div><!-- END LONG BOX -->
	      </div><!-- END company logo  -->
	
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