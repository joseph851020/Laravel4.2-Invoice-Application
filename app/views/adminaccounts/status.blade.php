@extends('layouts.admin')

	@section('content')
	 
	  <h1><a class="do_previous" href="{{ URL::previous() }}">&nbsp; <i class="fa fa-chevron-left">&nbsp;&nbsp; </i> </a> <strong>{{ $tenant->company->company_name }}</strong></h1>
	  <p>{{  $tenant->tenantID }}</p>
	  
	{{ Form::open(array('url' => 'admin/accounts/'.$tenant->tenantID.'/update_status', 'method' => 'put')) }}
  
	<label>Status</label><br />
	<select id="status" name="status" class="sel"> 
		<option <?php echo $tenant->status == 0 || $tenant->status == NULL || $tenant->status == "" ? "selected" : ""; ?> value="">Inactive</option>          
        <option <?php echo $tenant->status == 1 ? "selected" : ""; ?> value="1">Active</option>
        <option <?php echo $tenant->status == -1 ? "selected" : ""; ?> value="-1">Suspended</option>
        <option <?php echo $tenant->status == -2 ? "selected" : ""; ?> value="-2">Deactivated</option>
                      
	</select>
	  
    <div class="btn-submit">
       <input type="submit" id="" class="gen_btn button btn" name="theme" value="Update" />
    </div><!-- END btn-submit -->
      {{ Form::close() }}
      
     <div class="">
    	
    	<h2>Change Level</h2>
    		{{ Form::open(array('url' => 'admin/accounts/'.$tenant->tenantID.'/update_level', 'method' => 'put')) }}
    		<label>User level</label>
	        	<select id="level" name="level" class="sel"> 
				<option <?php echo $tenant->account_plan_id== 1 ? "selected" : ""; ?> value="1">Free</option>
				<option <?php echo $tenant->account_plan_id== 2 ? "selected" : ""; ?> value="2">Premium</option>
				<option <?php echo $tenant->account_plan_id== 3 ? "selected" : ""; ?> value="3">Super Premium</option>
			</select>
		 <div class="btn-submit">
       			<input type="submit" id="" class="gen_btn button btn" name="theme" value="Update" />
    		</div><!-- END btn-submit -->
      		{{ Form::close() }}		
	
    </div>
     
    <div class="">
    	
    	<h2>Verification status</h2>
    	
		<?php if($tenant->verified == 0): ?>
			{{ Form::open(array('url' => 'admin/accounts/'.$tenant->tenantID.'/verify', 'method' => 'put')) }}
			
			  <div class="btn-submit">
		        <input type="submit" id="" class="fa fa-edit gen_btn button btn" name="theme" value="Verify" />
		      </div><!-- END btn-submit -->
				 
		  	{{ Form::close() }}
		<?php else: ?>
         <p class="stat_green">Verified</p>

         <?php if($tenant->status == 1): ?>
            <p><a class="btn btn-primary" href="{{ URL::route('extend_subscription', $tenant->tenantID) }}">Extend Subscription</a></p>

            <?php endif; ?>

		<?php endif; ?>
    </div>
    
@stop