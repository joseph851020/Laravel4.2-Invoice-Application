@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('services') }}">Services</a> &raquo; Edit </h1>
	
	{{ Form::open(array('url' => 'service/update', 'method' =>'PUT')) }}
	
	<div id="edit_item_form">
		@include('common.item_errors')
	  
	    	<label>Name <span class="mand">*</span></label>
	            <input type="text" name="item_name" class="txt" id="item_name" value="{{ $service->item_name }}" />
	        <label>Rate<span> (e.g 478.61 or 400) </span></label>
	            <input type="text" name="unit_price" class="txt" id="unit_price" value="{{ $service->unit_price }}" />
	        
	            <input type="hidden" name="item_type" class="" id="" value="{{ $service->item_type }}" />
	            
	         <label>Tax type</label>
	            <select id="tax_type" name="tax_type" class="sel">
	            	<option <?php echo $service->tax_type == 0 ? "selected='selected'" : "" ;?> value="0"> - </option>
	            	<?php if($preferences->tax_perc1 != 0 && $preferences->tax_perc1 != ""): ?>
	                <option <?php echo $service->tax_type == 1 ? "selected='selected'" : "" ;?> value="1">{{ $preferences->tax_1name }}</option>
	                <?php endif; ?>
	                
	                <?php if($preferences->tax_perc2 != 0 && $preferences->tax_perc2 != ""): ?>	                
	                <option <?php echo $service->tax_type == 2 ? "selected='selected'" : "" ;?> value="2">{{ $preferences->tax_2name }}</option>
	                <?php endif; ?>
	            </select>
	            {{ Form::hidden('id', $service->id) }}
	            {{ Form::hidden('tenantID', Session::get('tenantID')) }}
	           
	          <br /> <br /> <input type="submit" id="edit_item" class="gen_btn" name="edit_item" value="Save Service" />
	            @include('common.mandatory_field_message')
	   
	</div><!-- END Add item-->

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

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                $('#tax_type').select2({ width: 'element' });
            }
		});
		
	</script>
 
	@stop