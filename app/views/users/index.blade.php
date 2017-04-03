@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <?php if(isset($searchquery) && $searchquery != ""){ echo "Search results: "; } ?> User Accounts  &raquo; <span><?php echo $total_records; ?> record<?php echo (int)$total_records == 0 || (int)$total_records > 1 ? "s": ""; ?></span></h1>

    <?php if($total_records >= 1):  ?> 
   
		<table class="table">
       		<thead>
       			<tr>
       				<th class="sorting"><i class=""></i>Full name</th>
       				<th class="sorting"><i class=""></i>Level</th>  
       				<th class="sorting"><i class=""></i>Email</th>          				 
       				<th class="sorting"><i class="fa fa-cogs"></i> Action</th>
       			</tr>
       		</thead>
       		
	        <tbody>
				<?php $row = 2; foreach($users as $user): ?>
				<?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
				<tr class="{{ $colour }}">
					<td><a class="" href="{{ URL::to('users/'.$user->id.'/edit') }}">{{ $user->firstname . " ". $user->lastname }}</a></td>
					<td><?php if($user->level  == null || $user->level < 2) { echo "User"; } else {echo "Power User"; } ?></td>
					<td>{{ $user->email; }}</td>
					 <td class="">
					 	<?php  if(Auth::user()->get()->id == $user->id): ?>		               					
	   					<a class="btn-edit" href="{{ URL::to('users/'.$user->id.'/edit') }}">
						<i class="fa fa-edit"></i> Edit</a>	
						<?php endif; ?>
						<?php  if(Auth::user()->get()->level > 1): ?>	
							<?php if(Auth::user()->get()->id != $user->id): ?>
								<a class="btn-edit" href="{{ URL::to('users/'.$user->id.'/edit') }}">
								<i class="fa fa-edit"></i> Edit</a>	
										
								<a class="btn-edit btn-danger do_delete_user" href="{{ URL::to('users/'.$user->id.'/delete') }}">
								<i class="fa fa-trash-o"></i> Delete</a>
							<?php endif; ?>
						<?php endif; ?>
					  </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
       		
       	 </table>
           	 
         
         <div class="">
         	<a class="gen_btn" href="{{ URL::to('users/create') }}">Create New User Account</a>
         </div>
 
	
		<div class="simple_search">
			{{ Form::open(array('url' => 'users', 'method' => 'GET')) }}
			<input type="text" class="search_term" name="q" value="search..." />
			<input type="submit" class="search_submit" value="Go" />
			<!-- <p class="tinytext">Invoice ID, user name or item name.</p> -->
			{{ Form::close() }}				
		</div><!-- END search -->
	
	
	 <?php else: ?>	
	   
	   	<?php if(isset($searchquery) && is_null($searchquery)): ?>
    	  
			  <!-- NO Items yet -->
		     <div class="no_item">
		 	  <div class="msg">
		 		<h4>You're the only Admin</h4>
		    	 
		    	<p><a href="" class="gen_btn">Watch quick How-to video</a> &nbsp;&nbsp; <a href="{{ URL::to('users/create') }}" class="gen_btn">New Admin User</a></p>
		    </div>    
		    </div><!-- End no admin user --> 
		    
	 	 <?php else: ?> 
	    	<p>No records found in the search result.</p>
	    	<a class="btn" href="{{ URL::previous() }}">Back</a>
	    <?php endif; ?> 
 	
 	 <?php endif; ?> 
  

		{{  $users->links() }}
	
  @stop
  
	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){				 
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_all_users').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			 }
			 
		});
		
	</script>
 
	@stop