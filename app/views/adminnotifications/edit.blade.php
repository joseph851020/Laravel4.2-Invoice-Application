@extends('layouts.admin')

	@section('content')
	 
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo;  Edit Notification</h1>	  
	 
	{{ Form::open(array('url' => 'admin/notifications/update', 'method' => 'PUT')) }}
	<div id="" class="more_space">
 
	    <div class="longbox">

				<label>Titile<span class="mand">*</span></label>
		          <input type="text" name="title" class="txt" id="title" value="{{ $notification->title }}" />
		       <label>Type <span class="mand">*</span></label>
	           <select id="type" name="type" class="sel">            
                <option <?php echo $notification->type == 1 ? "selected=\"selected\"" : ""; ?> value="1">- Maintenance -</option>
                <option <?php echo $notification->type == 2 ? "selected=\"selected\"" : ""; ?>value="2">- New Feature Update -</option>
                <option <?php echo $notification->type == 3 ? "selected=\"selected\"" : ""; ?>value="3">- Other -</option>
               </select>	
                
		        <label>Info / Description <span class="mand">*</span></label>
		         <textarea id="info" name="info" class="txtarea">{{ $notification->info }}</textarea>  
		       <?php $display_start_date_raw = new DateTime($notification->display_start_date);
					 $display_start_date = $display_start_date_raw->format("d/m/Y"); ?>
		       <label>Start Date <span class="mand">*</span> <span>(select)</span></label>
		       <input type="text" id="display_start_date" name="display_start_date" class="txt startdate" value="{{ $display_start_date }}" />
		       <?php $display_end_date_raw = new DateTime($notification->display_end_date);
					 $display_end_date = $display_end_date_raw->format("d/m/Y"); ?>
		       <label>End Date <span class="mand">*</span> <span>(select)</span></label>
		       <input type="text" id="display_end_date" name="display_end_date" class="txt enddate" value="{{ $display_end_date }}" />
		       
		       <label class="active" for="active"><input id="active" name="active" class="checkbox" type="checkbox" <?php echo $notification->active == 1 ? "checked=\"checked\"" : ""; ?> value="yes" /> Active</label>
   			 	<input type="hidden" name="notification_id" value="{{ $notification->id }}">
    	   <br /> 
	      <input type="submit" id="editNotification" class="gen_btn" name="editNotification" value="Save" />
		          
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
      
				$('#editNotification').click(function(){	
				 
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