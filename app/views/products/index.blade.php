@extends('layouts.default')

	@section('content')
	<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
 
		<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <?php if(isset($searchquery) && $searchquery != ""){ echo "Search results: "; } ?>Products - <span>{{ $totalRecords }} record<?php echo (int)$totalRecords == 0 || (int)$totalRecords > 1 ? "s": ""; ?></span></h1>
	
	 <a class="btn" href="{{ URL::Route('create_product') }}"><?php echo $totalRecords >= 1 ? "Create product" : "Create your first product"; ?></a>
	 <a class="btn" href="{{ URL::Route('importProducts') }}">Import</a> <a class="btn" href="{{ URL::Route('exportProducts') }}">Export</a>
	<?php if($totalRecords >= 1):  ?>
	{{ Form::open(array('url' => 'products/deletebulk', 'method' => 'DELETE')) }}
	 
           	<table class="table">
           		<thead>
           			<tr>
           				<th class="sorting"><input type="checkbox" name="" id="selectAll" /></th>
           				<th class="sorting product_name_width"><i class=""></i>Name</th>
           				<th class="sorting"><i class=""></i> Price</th>           				 
           				<th class="sorting"><i class="fa fa-cogs"></i> Actions</th>
           			</tr>
           		</thead>
           		
           		<tbody>
           		 
                <?php $row = 2; foreach($products as $product): ?>
		        <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
			    <tr class="<?php echo $colour; ?>">
					<td><input class="checkbox" name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $product->id; ?>"></td>
					<td><a href="{{ URL::to('products/'.$product->id.'/edit') }}"><strong class="itemname"> {{ $product->item_name }}</strong></a></td>
					 
					<td>{{ AppHelper::two_decimal($product->unit_price) }}</td>				 
					 
			      <td class="">	               					
   					<a title="Edit" class="btn-edit" href="{{ URL::to('products/'.$product->id.'/edit') }}">
					<i class="fa fa-edit"></i></a>	
					
					<a title="Delete" class="btn-edit btn-danger do_delete_item" href="{{ URL::to('products/'.$product->id.'/delete') }}">
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
				{{ Form::open(array('url' => 'products', 'method' => 'GET')) }}
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
			 	<h3>You haven't created any products yet</h3>
			    <p>This is a database of the products that you provide. When you have list of products you can instantly insert them as lines item on an invoice or quote. </p>
			     
			   </div>
		    </div><!-- End no item -->
		    
		    <?php else: ?> 
		       <p>No records found in the search result.</p>
		     <a class="btn" href="{{ URL::to('services') }}">Back</a>
    <?php endif; ?> 
 
 <?php endif; ?> 
	
	{{ $products->links() }}
	
 @stop
 
 @section('footer')
	
	 	<script>
	 	
       		$(document).ready(function() {
       			
       			
       		if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_products').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			 }
			 
       			
       		$('.do_delete_item').click(function(){
		
				var itemname = $(this).parent().parent().find('.itemname').text();
			 
				if(confirm('Delete product: '+ itemname+'.')){
				 
				     return true;
					// return true;
				}else{
					return false;
				}
			});
       			
       			// Confirm delete all selected items
			$('.delete_selected_items').click(function(){
				// If number of checked boxes is greater than 1
				if(($('.checkbox:checked').length) >= 1){
					if(confirm('Delete selected products?')){
						return true;
					}else{
						return false;
					}
				}else{
					alert('At least one record must be selected');
					return false;
				}
		
			});
		 	
				
		 });
 
        </script>
	  
	@stop
  