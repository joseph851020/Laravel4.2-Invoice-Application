@extends('layouts.default')

	@section('content')
	
	<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a> &raquo; Profit &amp; Loss</h1>
 		
 	<div class="profit_and_loss">
 		
 		<div class="pl_headers">
 			<p>Please select date range below</p>
 			{{ Form::open(array('url' => 'reports/profit_and_loss', 'method' => 'POST')) }}
 			
 			<div class="date_cover">
 				
 			   <div class="start_side">
 			   <label>Start date<span class="mand">*</span></label>
	            <input type="text" name="startdate" class="txt" id="startdate" value="{{ Input::old('startdate') }}" placeholder="Select" autocomplete="off" />	
	           </div><!-- END start_side --> 
	          
	           <div class="end_side"> 
	            <label>End date<span class="mand">*</span></label>
	            <input type="text" name="enddate" class="txt" id="enddate" value="{{ Input::old('enddate') }}" placeholder="Select" autocomplete="off" />
	           </div><!-- END end_side -->
	           
	          </div><!-- END date_cover -->
	          
	            <?php $dateformat ="d/m/Y";
					// British dateformat
					if($preferences->date_format == "dd/mm/yyyy"){ $dateformat ="d/m/Y"; }
					
					// America dateformat
					if($preferences->date_format == "mm/dd/yyyy"){ $dateformat = "m/d/Y"; }	?>	
	            <input type="hidden" class="pref_dateformat" value="{{ $dateformat }}" ><br />
	            
	            <input type="submit" class="btn" name="" value="Show">
 			{{ Form::close() }}
 		</div><!-- END pl_headers -->
 	 
 		
 	</div><!-- END profit_and_loss -->
	
	@stop
	
	@section('footer')
	<script src="{{ URL::asset('assets/js/jquery.datetimepicker.js') }}"></script>
	<script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
				   
				  $('.report_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_profit_loss').addClass('selected');		  		
		  		  $('.report_all_menu ul').css({'display': 'block'});
			 }
			
		    $('#startdate, #enddate').datetimepicker({			 
					lang:'en',
					timepicker:false,
					format: $('.pref_dateformat').val(),
					formatDate:'Y/m/d',
					closeOnDateSelect:true					  
			 }); 
		  
		});
		
	</script>
	
@stop