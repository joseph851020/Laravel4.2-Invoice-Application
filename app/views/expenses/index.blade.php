@extends('layouts.default')

	@section('content')
<?php	
	use IntegrityInvoice\Utilities\AppHelper as AppHelper;
 
	$cat_array = array();
	foreach($categories as $category){
		$cat_array[$category->id] = $category->expense_name;
	}
	 
?>

<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Expenses &raquo; <span><?php echo $total_records; ?> record<?php echo (int)$total_records == 0 || (int)$total_records > 1 ? "s": ""; ?></span></span></h1>
 
	<div id="expense_total">
		<h4>Total: <?php echo AppHelper::dumCurrencyCode($preferences->currency_code). number_format($total_amount, 2, '.', ','); ?> </h4>
       		
	</div><!-- END Expense total -->
 
    <a class="btn" href="{{ URL::Route('create_expense') }}"><?php echo $total_records >= 1 ? "Create expense" : "Create your first expense"; ?></a>
    <a class="btn" href="{{ URL::Route('importExpenses') }}">Import</a> <a class="btn" href="{{ URL::Route('exportExpenses') }}">Export</a>
    <a class="btn" href="{{ URL::Route('merchants') }}">Merchants</a>
	
	<?php if($total_records >= 1):  ?>
		
	{{ $expenses->links() }}
 
	{{ Form::open(array('url' => 'expenses/deletebulk', 'method' => 'DELETE')) }}
			<table class="table">
       		<thead>
       			<tr>
       				<th class="sorting"><input type="checkbox" name="" id="selectAll" /></th>
       				<th class="sorting expense_name_width"><i class=""></i>Details &amp; Amount</th>
       				<th class="sorting"><i class=""></i>Merchant &amp; Category</th>   
       				<th class="sorting displayNone"><i class=""></i>Date</th>      		     				 
       				<th width="28%" class="sorting"><i class="fa fa-cogs"></i> Action</th>
       			</tr>
       		</thead>
       		<tbody>
			<?php $row = 2; ?> 
			@foreach($expenses as $expense)
		    <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
			<tr class="{{ $colour }}">
				<td><input class="checkbox" name="checkbox[]" type="checkbox" id="checkbox[]" value="{{ $expense->id }}"></td>
				<td width="30%"><a class="" href="{{ URL::to('expenses/'.$expense->id.'/edit') }}"><span class="amount"><strong class="expense_small"> {{ $expense->note }}</strong> {{ IntegrityInvoice\Utilities\AppHelper::dumCurrencyCode($expense->currency_code).number_format($expense->amount, 2, '.', ',')  }}</span></a></td>
				<td width="30%"><?php if($expense->merchant_id != "" && $expense->merchant_id != NULL): ?><span> {{ $expense->merchant->company }} </span><?php else: ?><span> - <?php endif; ?> </span> 
					<span class=""><?php if($expense->ref_no != ""): ?> (Ref: {{ $expense->ref_no }})</span><?php endif; ?> <span class="expense_small">{{ $cat_array[$expense->category_id] }} </span>
				</td>
				<td class="displayNone">{{ AppHelper::date_to_text($expense->expense_date, $preferences->date_format) }}
                    @if($expense->recurring == 1)
                    <br /><small class="small"><i class="fa fa-repeat"></i> Recurring</small>
                    @endif
                </td>
		 
				<td class="">	               					
					<a title="Edit" class="btn-edit" href="{{ URL::to('expenses/'.$expense->id.'/edit') }}">
					<i class="fa fa-edit"></i></a>
					
					<a title="Delete" class="btn-edit btn-danger do_delete_expense" href="{{ URL::to('expenses/'.$expense->id.'/delete') }}">
					<i class="fa fa-trash-o"></i></a>
					@if($expense->file != NULL && $expense->file != "")
					 <div>
					    <p><a class="ordinary_link2 status-bar2" href="{{ URL::to('expenses/'.$expense->id.'/download_file/') }}">File attached</a></p>
					 </div>
					@endif	
				</td>				 
			</tr>
			 @endforeach
		</tbody>
       		
       	 </table>
     
	 
	
	<div id="bulk_action">		
	    <input type="submit" class="button delete_selected_expenses" name="delete_bulk" value="Delete checked"/> 
		
		{{ Form::close() }}
		
	</div><!-- END Bulk Action -->
	
 
	 <?php else: ?>
	 	
	  <!-- NO Items yet -->
     <div class="no_item">
 	<div class="msg">
	 	<h3>You haven't created any expenses yet</h3>
	    <p>This is a record of any expenses incurred, whether they’re paid or unpaid.  
	    	To start creating expenses right away, click the create button above or <a class="ordinary_link2" href="{{ URL::to('help') }}">see how to</a>.</p>
	    
	  </div>
    </div><!-- End no item -->
 
 <?php endif; ?> 
	
	{{ $expenses->links() }}
			 
@stop
	
@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_expenses').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop