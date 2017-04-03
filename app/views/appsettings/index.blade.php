@extends('layouts.admin')

	@section('content')
	 
	  <h1>Maintenance notifications</h1>
	  
	  
	   <?php if($totalRecords >= 1):  ?>
      
		<table class="table">
       		<thead>
       			<tr>        
       			  
       				<th class="sorting"><i class=""></i>Title</th>   
       				<th class="sorting"><i class=""></i>Info</th>
       				<th class="sorting"><i class=""></i>Start Date</th>
       				<th class="sorting"><i class=""></i>End Date</th>
       				<th class="sorting"><i class=""></i>Created Date</th>            				 
       				<th class="sorting"><i class="fa fa-cogs"></i> Action</th>
       			</tr>
       		</thead>
       		
	        <tbody>
				<?php $row = 2; foreach($maintenances as $maintenance): ?>
				<?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
				<tr class="{{ $colour }}">
					<td><strong>{{ $maintenance->title; }}</strong></td>				 
					<td>{{ $maintenance->info; }}</td>	
					<td>{{ $maintenance->display_start_date->diffForHumans() }}</td>
					<td>{{ $maintenance->display_end_date->diffForHumans() }}</td>			 
					<td>{{ $maintenance->created_at->diffForHumans() }}</td>
					 <td class="">	               					
	   					<a class="btn-edit" href="{{ URL::to('admin/notification/'.$appsettings->id.'/edit') }}">
						<i class="fa fa-edit"></i>Edit</a>
				 
						<a class="btn-edit btn-danger do_delete_account" href="{{ URL::to('admin/accounts/'.$tenant->tenantID.'/delete') }}">
						<i class="fa fa-trash-o"></i> Delete</a>
					  </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
       		
       	 </table>
      
	 
	  <?php else: ?>	
	 	
	 	 
	 	
	  <!-- NO Notification yet -->
     <div class="no_item">
  
 		<h4>No Notifications yet</h4>    
    
   	</div><!-- End no client -->
   
  
 
 <?php endif; ?> 

		{{ $tenants->links() }}		
	  	 
	@stop