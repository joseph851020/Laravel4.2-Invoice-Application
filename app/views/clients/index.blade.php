@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a> <?php if(isset($searchquery) && $searchquery != ""){ echo "Search results: "; } ?>Clients - <span><?php echo $total_records; ?> record<?php echo (int)$total_records == 0 || (int)$total_records > 1 ? "s": ""; ?></span></h1>
	
	<a class="btn" href="{{ URL::Route('create_client') }}"><?php echo $total_records >= 1 ? "Create client" : "Create your first client"; ?></a>
	<?php if($account_plan_id > 1): ?>
    <a class="btn" href="{{ URL::Route('importClients') }}">Import</a> <a class="btn" href="{{ URL::Route('exportClients') }}">Export</a>
	<?php endif; ?>
    <?php if($total_records >= 1):  ?>
   {{ Form::open(array('url' => 'clients/deletebulk', 'method' => 'DELETE')) }}
   
		<table class="table">
       		<thead>
       			<tr>
       				<th class="sorting"><input type="checkbox" name="" id="selectAll" /></th>
       				<th width="30%" class="sorting client_name_width"><i class=""></i>Company</th>
       				<th class="sorting"><i class=""></i>Contact person</th>   
       				<th class="sorting dislayNone"><i class=""></i>Email</th>          				 
       				<th width="20%"class="sorting"><i class="fa fa-cogs"></i> Action</th>
       			</tr>
       		</thead>
       		
	        <tbody>
				<?php $row = 2; foreach($clients as $client): ?>
					 
				<?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
				<tr class="{{ $colour }}">
					<td><input class="checkbox" name="checkbox[]" type="checkbox" id="checkbox[]" value="{{ $client->id }}"></td>
					<td><a href="{{ URL::to('clients/'.$client->id.'/edit') }}"><strong class="itemname">{{ $client->company }}</strong></a><br /> <span class="client_meta">
						    @if($client->invoices->first() != null)
						    (Invoiced {{ $client->invoices->count() }} time{{ $client->invoices->count() != 1 ? "s" : ""}}, {{ IntegrityInvoice\Utilities\AppHelper::dumCurrencyCode($client->invoices->first()->currency_code).$client->invoices->sum('balance_due') }})
							@endif	
						</span> </td>
					<td>{{ $client->firstname . " ". $client->lastname; }}</td>
					<td class="dislayNone">{{ $client->email; }}</td>
					 <td class="">	               					
	   					<a title="Edit" class="btn-edit" href="{{ URL::to('clients/'.$client->id.'/edit') }}">
						<i class="fa fa-edit"></i></a>					
						<a title="Delete" class="btn-edit btn-danger do_delete_client" href="{{ URL::to('clients/'.$client->id.'/delete') }}">
						<i class="fa fa-trash-o"></i></a>
					  </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
       		
       	 </table>
           	 
     
	<div id="bulk_action">		
	    <input type="submit" class="btn btn_light delete_selected_clients" name="delete_bulk" value="Delete checked"/>
		{{ Form::close() }}
	</div><!-- END Bulk Action -->
	
		<div class="simple_search">
			{{ Form::open(array('url' => 'clients', 'method' => 'GET')) }}
			<input type="text" class="search_term" name="q" value="search..." />
			<input type="submit" class="search_submit" value="Go" />
			<!-- <p class="tinytext">Invoice ID, Client name or item name.</p> -->
			{{ Form::close() }}				
		</div><!-- END search -->
	
	
	 <?php else: ?>	
	  
	 	 <?php if(isset($searchquery) && $searchquery == ""): ?>
	 	
		  <!-- NO Client yet -->
	     <div class="no_item">
	 	   <div class="msg">
	 		<h3>You haven't added any clients yet</h3>
	 	    <p>Your client database holds information about your clients or customers which can be used on an invoice or quote, and also used in various email messages.</p>
	   	  </div>
	    
	   </div><!-- End no client -->
	   
	   <?php else: ?> 
	    	<p>No records found in the search result.</p>
	    	<a class="btn" href="{{ URL::to('clients') }}">Back</a>
	    <?php endif; ?> 
	 
	 <?php endif; ?> 
	
			{{ $clients->links() }}
			
			 
	@stop
	

@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_clients').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			  }			 
		});
		
	</script>
		
@stop