@extends('layouts.default')

	@section('content')
	 
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a> <?php if(isset($searchquery) && $searchquery != ""){ echo "Search results: "; } ?><a class="do_previous" href="{{ URL::to('expenses') }}">Expenses</a> &raquo; Merchants - <span><?php echo $total_records; ?> record<?php echo (int)$total_records == 0 || (int)$total_records > 1 ? "s": ""; ?></span></h1>

	 <a class="btn" href="{{ URL::Route('create_merchant') }}"><?php echo $total_records >= 1 ? "Create merchant" : "Create your first merchant"; ?></a>
	 <a class="btn" href="{{ URL::Route('importMerchants') }}">Import</a> <a class="btn" href="{{ URL::Route('exportMerchants') }}">Export</a>
	 
    <?php if($total_records >= 1):  ?>
   {{ Form::open(array('url' => 'merchants/deletebulk', 'method' => 'DELETE')) }}
   
   	<table class="table">
           		<thead>
           			<tr>
           				<th class="sorting"><input type="checkbox" name="" id="selectAll" /></th>
           				<th class="sorting merchant_name_width"><i class=""></i>Company &amp; Total spent</th>
           				<th class="sorting"><i class=""></i>Contact person</th>   
           				<th class="sorting dislayNone"><i class=""></i>Email</th>          				 
           				<th class="sorting"><i class="fa fa-cogs"></i> Action</th>
           			</tr>
           		</thead>
           		
		        <tbody>
					<?php $row = 2; foreach($merchants as $merchant): ?>
					<?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
					<tr class="{{ $colour }}">
						<td><input class="checkbox" name="checkbox[]" type="checkbox" id="checkbox[]" value="{{ $merchant->id }}"></td>
						<td><strong class="itemname"><a href="{{ URL::to('merchants/'.$merchant->id.'/edit') }}">{{ $merchant->company }}</a></strong> - {{ IntegrityInvoice\Utilities\AppHelper::dumCurrencyCode($preferences->currency_code). $merchant->expenses->sum('amount') }}</td>
						<td>{{ $merchant->firstname . " ". $merchant->lastname; }}</td>
						<td class="dislayNone">{{ $merchant->email; }}</td>
						 <td class="">	               					
		   					<a title="Edit" class="btn-edit" href="{{ URL::to('merchants/'.$merchant->id.'/edit') }}">
							<i class="fa fa-edit"></i></a>							
							<a title="Delete" class="btn-edit btn-danger do_delete_merchant" href="{{ URL::to('merchants/'.$merchant->id.'/delete') }}">
							<i class="fa fa-trash-o"></i></a>
						  </td>
					</tr>
					<?php endforeach; ?>
				</tbody>
           		
           	 </table>
           	 
     
	<div id="bulk_action">		
	    <input type="submit" class="btn btn_light delete_selected_merchants" name="delete_bulk" value="Delete checked"/>
		{{ Form::close() }}
	</div><!-- END Bulk Action -->
	
		<div class="simple_search">
			{{ Form::open(array('url' => 'merchants', 'method' => 'GET')) }}
			<input type="text" class="search_term" name="q" value="search..." />
			<input type="submit" class="search_submit" value="Go" />
			<!-- <p class="tinytext">Invoice ID, Client name or item name.</p> -->
			{{ Form::close() }}				
		</div><!-- END search -->
 
	
		 <?php else: ?>	
		 	
		 	<?php if(isset($searchquery) && $searchquery == ""): ?>
		 	
			  <!-- NO merchant yet -->
		     <div class="no_item">
		 	 <div class="msg">
		 		<h4>You haven't added any merchants yet</h4>
		 	    <p>Your merchant database holds information about your merchants / sellers which can be used in an expense entry.</p>
		   	   </div>
		    
		   </div><!-- End no merchant -->
	   
	   <?php else: ?> 
	    	<p>No records found in the search result.</p>
	    	<a class="btn" href="{{ URL::to('services') }}">Back</a>
	    <?php endif; ?> 
 
 <?php endif; ?> 
		 
		{{ $merchants->links() }}
 
	@stop
	
	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
		 
			if($('#appmenu').length > 0){
		 		$('.manage_all_menu').addClass('selected_group'); 		 
		  		$('.menu_all_expenses').addClass('selected');		  		
		  		$('.manage_all_menu ul').css({'display': 'block'});
		 	}
			 
		});
		
	</script>
 
	@stop