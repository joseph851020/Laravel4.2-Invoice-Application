@extends('layouts.admin')

	@section('content')
	 
	  <h1>Notifications</h1>	  
	  
	   <?php use Carbon\Carbon as Carbon; ?>
	   <?php if($totalRecords >= 1):  ?>
      
		<table class="table">
       		<thead>
       			<tr>        
       		 
       				<th class="sorting"><i class=""></i>Title</th>       				    
       				<th class="sorting" width="10%"><i class=""></i>Start Date</th>
       				<th class="sorting" width="10%"><i class=""></i>End Date</th>  
       				<th class="sorting"><i class=""></i>Updated</th>           				 
       				<th class="sorting" width="15%"><i class="fa fa-cogs"></i> Action</th>
       			</tr>
       		</thead>
       		
	        <tbody>
				<?php $row = 2; foreach($notifications as $notification): ?>
				<?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
				<tr class="{{ $colour }}">					 
					 <td><strong class="makeBold">{{ $notification->title }}</strong> @if($notification->active == 1)
					 	<span class="isActive">Active</span>
					 	@else
					 	<span class="notActive">Not active</span>
					 	@endif <br />{{ $notification->info }}</td>
					 <td><?php echo Carbon::createFromFormat('Y-m-d H:i:s', $notification->display_start_date)->diffForHumans(); ?></td>
					 <td><?php echo Carbon::createFromFormat('Y-m-d H:i:s', $notification->display_end_date)->diffForHumans(); ?></td>
					 <td>{{ $notification->updated_at->diffForHumans() }}</td>					
					 <td class="">	               					
	   					<a class="btn-edit" href="{{ URL::to('admin/notifications/'.$notification->id.'/edit') }}">
						<i class="fa fa-edit"></i>Edit</a>
				 
						<a class="btn-edit btn-danger do_delete_notification" href="{{ URL::to('admin/notifications/'.$notification->id.'/delete') }}">
						<i class="fa fa-trash-o"></i> Delete</a>
					  </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
       		
       	 </table>
      
		<div class="simple_search">
			{{ Form::open(array('url' => 'admin/notifcations', 'method' => 'GET')) }}
				<input type="text" class="search_term" name="q" value="search..." />
				<input type="submit" class="search_submit" value="Go" />			 
			{{ Form::close() }}				
		</div><!-- END search -->
		
		<a class="btn" href="{{ URL::route('create_notification') }}">New Notification</a>
	
	 <?php else: ?>	
	 	
	 	 <?php if(isset($searchquery) && is_null($searchquery) || $searchquery ==  ""): ?>
	 	
	  <!-- NO Client yet -->
     <div class="no_item">
  
 		<h4>No Accounts yet</h4>   
 		
 		<a class="btn" href="{{ URL::route('create_notification') }}">New Notification</a> 
    
   	</div><!-- End no client -->
   
   <?php else: ?> 
    	<p>No records found in the search result.</p>
    	
    	<a class="btn" href="{{ URL::to('admin_notifications') }}">Back</a>
    <?php endif; ?> 
 
 <?php endif; ?> 

		{{ $notifications->links() }}		
	  	 
	@stop