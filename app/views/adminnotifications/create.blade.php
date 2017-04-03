@extends('layouts.admin')

	@section('content')
	 
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo;  Create Notification</h1>	  
	  
	{{ Form::open(array('url' => 'admin/notifications/store', 'method' => 'POST')) }}
	<div id="" class="more_space">
 
	    <div class="longbox">

				<label>Titile<span class="mand">*</span></label>
		          <input type="text" name="title" class="txt" id="title" value="{{ Input::old('title')}}" />
		       <label>Type <span class="mand">*</span></label>
	           <select id="type" name="type" class="sel">
                <option value="" selected="selected">- select -</option>
                <option value="1">- Maintenance -</option>
                <option value="2">- New Feature Update -</option>
                <option value="3">- Other -</option>
               </select>	
                
		        <label>Info / Description <span class="mand">*</span></label>
		         <textarea id="info" name="info" class="txtarea">{{ Input::old('info')}}</textarea>  
		            
		       <label>Start Date <span class="mand">*</span> <span>(select)</span></label>
		       <input type="text" id="display_start_date" name="display_start_date" class="txt startdate" value="{{ Input::old('display_start_date')}}" />
		       
		       <label>End Date <span class="mand">*</span> <span>(select)</span></label>
		       <input type="text" id="display_end_date" name="display_end_date" class="txt enddate" value="{{ Input::old('display_end_date')}}" />
		       
		       <label class="active" for="active"><input id="active" name="active" class="element checkbox" type="checkbox" value="yes" /> Active</label>
   			 
    	   <br /> 
	      <input type="submit" id="addnotification" class="gen_btn" name="addnotification" value="Create" />
		          
		   </div><!-- END Long box -->	
		  
		</div><!-- END new_user_form form -->
	{{ Form::close() }}
	  	 
	@stop
	

	@section('footer')
 
		<script src="{{ URL::asset('assets/js/picker.js') }}"></script>
		<script src="{{ URL::asset('assets/js/picker.date.js') }}"></script>
		<script src="{{ URL::asset('assets/js/legacy.js') }}"></script>
		
		
		<script>
       		$(document).ready(function() {
      
				$('#addnotification').click(function(){	
				 
					if($('#title').val() == ""){						
						alert('Please enter the title');						
						return false;
					}
					
					if($('#type').val() == ""){						
						alert('Please select the notification type');						
						return false;
					}
					
					if($('#info').val() == ""){						
						alert('Please enter the description');						
						return false;
					}
					
					if($('#display_start_date').val() == ""){						
						alert('Please select the display start date');						
						return false;
					}
					
					if($('#display_end_date').val() == ""){						
						alert('Please select the display end date');						
						return false;
					}
					 
				 
				});
				
			});
 
        </script>
 
	@stop