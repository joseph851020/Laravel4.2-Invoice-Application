@extends('layouts.default')

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('services', 'Services', array('class' => 'to_all')) }} &raquo; New</h1>
	<?php if($limitReached == FALSE): ?>
		
	{{ Form::open(array('url' => 'services/store', 'method' => 'POST')) }}
	
		<div id="add_item_form">
			@include('common.item_errors')
	    <div class="longbox">
	    	<label>Name<span class="mand">*</span></label>
	         <input type="text" name="item_name" class="txt" id="item_name" value="{{ Input::old('item_name')}}" />

	   	    <label><?php echo $preferences->bill_option == 0 ? 'Hourly rate' : 'Amount'; ?> <span class="mand">*</span><span> (e.g. 40 or 47.61) </span></label>
	          <input type="text" name="unit_price" class="txt" id="unit_price" value="{{ Input::old('unit_price')}}" />
	            
	        <label>Tax type / rate</label>
            <select id="tax_type" name="tax_type" class="sel">
            	<option value="0"> - </option>
            	<?php if($preferences->tax_perc1 != 0 && $preferences->tax_perc1 != ""): ?>
                <option value="1"><?php echo $preferences->tax_1name; ?></option>
                <?php endif; ?>
                <?php if($preferences->tax_perc2 != 0 && $preferences->tax_perc2 != ""): ?>
                <option value="2"><?php echo $preferences->tax_2name; ?></option>
                <?php endif; ?>
            </select>
            
             <input type="hidden" name="item_type" class="" id="item_type" value="service" />
             
             <?php if(($preferences->tax_perc1 == 0  || $preferences->tax_perc1 == "") && ($preferences->tax_perc2 == 0  || $preferences->tax_perc2 == "")): ?>
             	<p> For Tax rates see <a class="ordinary_link" href="{{ URL::to('settings') }}">settings</a></p>
             <?php endif; ?>
           
            <br /><br /><input type="submit" id="additem" class="gen_btn" name="add_item" value="Create Service" />
            
            @include('common.mandatory_field_message')

	   </div><!-- END longbox -->
   
</div><!-- END Add item-->
{{ Form::close() }}

<?php else: ?>
	<h3>You have reached your limit. Please consider upgrading your account if you wish to create more services. 
		{{ HTML::linkRoute('subscription', 'UPGRADE NOW', array('class' => 'to_all')) }}
	</h3>
	
<?php endif; ?>

   @stop
   

	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){				  
				   $('.create_all_menu').addClass('selected_group'); 		 
		  		   $('.menu_create_service').addClass('selected');		  		
		  		   $('.create_all_menu ul').css({'display': 'block'});
			 }

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                $('#tax_type').select2({ width: 'element' });
            }
			 
		});
		
	</script>
 
	@stop