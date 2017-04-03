@extends('layouts.default')

	@section('content')
	<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
 
		<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <?php if(isset($searchquery) && $searchquery != ""){ echo "Search results: "; } ?>Services - <span>{{ $totalRecords }} record<?php echo (int)$totalRecords == 0 || (int)$totalRecords > 1 ? "s": ""; ?> 
			<?php if(isset($query) && !is_null($query)){ echo "found"; } ?></span></h1>
			
	 <a class="btn" href="{{ URL::Route('create_service') }}"><?php echo $totalRecords >= 1 ? "Create service" : "Create your first service"; ?></a>
	 <a class="btn" href="{{ URL::Route('importServices') }}">Import</a> <a class="btn" href="{{ URL::Route('exportServices') }}">Export</a>
	<?php if($totalRecords >= 1):  ?>
	{{ Form::open(array('url' => 'services/deletebulk', 'method' => 'DELETE')) }}
	 
           	<table class="table">
           		<thead>
           			<tr>
           				<th class="sorting"><input type="checkbox" name="" id="selectAll" /></th>
           				<th class="sorting item_name_width"><i class=""></i>Name</th>
           				<th class="sorting"><i class=""></i> Price</th>           				 
           				<th class="sorting"><i class="fa fa-cogs"></i> Actions</th>
           			</tr>
           		</thead>
           		 
               <tbody>
           		 
                <?php $row = 2; foreach($services as $service): ?>
		        <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
			    <tr class="<?php echo $colour; ?>">
					<td><input class="checkbox" name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $service->id; ?>"></td>
					<td><a href="{{ URL::to('services/'.$service->id.'/edit') }}"><strong class="itemname"> {{ $service->item_name }}</strong></a></td>					 
					<td>{{ AppHelper::two_decimal($service->unit_price) }}</td>				 
					 
			      <td class="">	               					
   					<a title="Edit" class="btn-edit" href="{{ URL::to('services/'.$service->id.'/edit') }}">
					<i class="fa fa-edit"></i></a>	
					
					<a title="Delete" class="btn-edit btn-danger do_delete_item" href="{{ URL::to('services/'.$service->id.'/delete') }}">
					<i class="fa fa-trash-o"></i></a>
				  </td>
				 
			      </tr>
			      <?php endforeach; ?>
			    </tbody>
           		
           	 </table>
      
		    <div id="bulk_action">
		    		
				{{ Form::submit('Delete checked', array('class' => 'btn btn_light delete_selected_items')) }}
				{{ Form::close() }}
				
			</div><!-- END Bulk Action -->
			
			 
           	 <div class="simple_search">
				{{ Form::open(array('url' => 'services', 'method' => 'GET')) }}
				<input type="text" class="search_term" name="q" value="search..." />
				<input type="submit" class="search_submit" value="Go" />
				<!-- <p class="tinytext">Invoice ID, Client name or item name.</p> -->
				{{ Form::close() }}				
			</div><!-- END search -->
	 
	 <?php else: ?>
	 	
	 	 <?php if($searchquery == ""): ?>
	 	
	  <!-- NO Items yet -->
     <div class="no_item">
 	<div class="msg">
	 	<h3>You haven't created any services yet</h3>
	    <p>This is a database of the services that you provide. When you have a list of services you can instantly insert them as line items on an invoice or quote.</p>
	    
	  </div>
    </div><!-- End no item -->
    
     	<?php else: ?> 
    	<p>No records found in the search result.</p>
    	<a class="btn" href="{{ URL::to('services') }}">Back</a>
    <?php endif; ?> 
 
 <?php endif; ?> 
	
	{{ $services->links() }}
 
	@stop
	
	 
 @section('footer')
	
	 	<script>
       		$(document).ready(function() {
       			
   			if($('#appmenu').length > 0){
		 		$('.manage_all_menu').addClass('selected_group'); 		 
		  		$('.menu_all_services').addClass('selected');		  		
		  		$('.manage_all_menu ul').css({'display': 'block'});
		 	}
			 
       			
       		$('.do_delete_item').click(function(){
		
				var itemname = $(this).parent().parent().find('.itemname').text();
			 
				if(confirm('Delete service: '+ itemname+'.')){				 
				     return true;
				}else{
					return false;
				}
			});
       			
       			// Confirm delete all selected items
			$('.delete_selected_items').click(function(){
				// If number of checked boxes is greater than 1
				if(($('.checkbox:checked').length) >= 1){
					if(confirm('Delete selected service(s)?')){
						return true;
					}else{
						return false;
					}
				}else{
					alert('At least one record must be selected.');
					return false;
				}
		
			});
		 	
				
		 });
 
        </script>
	  
	@stop
 