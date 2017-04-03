@extends('layouts.default')

	@section('content')
	
	<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp; </i></a> &raquo; <a href="{{ URL::route('profit_and_loss_start') }}"> Profit &amp; Loss</a> &raquo; Report</h1>
 	
 	<div class="period"><h4>{{ $startdate }} - {{ $enddate }} </h4><p>All values are in Home currency (<?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?>)</p></div><!-- END -->
  
 	  <div class="pl_body">
 	   
 		<table class="pl_table">
 			 
 			<tr class="thick_line">
 				<td><h3>Gross Income</h3></td>
 				<td><h3><?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?>{{ AppHelper::two_decimal($income + $total_tax) }}</h3></td> 				
 			</tr>
 			
 			<tr>
 				<td><h3>Tax </h3></td>
 				<td><h3><?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?>{{ AppHelper::two_decimal($total_tax) }}</h3></td>				
 			</tr>
 			
 			<tr>
 				<td><h3>Net Income </h3></td>
 				<td><h3><?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?>{{ AppHelper::two_decimal($income) }}</h3</td>				
 			</tr>
 			 
 			</tr>
 			<tr>
 				<td><h3>Less Expense</h3></td>
 				<td>&nbsp;</td>
 			</tr>
 			<tr>
	 		   <?php foreach($expenses_with_category as $expense_with_category): ?>	
	 			<tr>
	 				<td class="expense_breakdown"><?php echo $expense_with_category->expense_name; ?></td>
	 				<td class=""><?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?><?php 	 					 
	 					if(array_key_exists($expense_with_category->currency_code, $currencyExchangeRates)){	 						 
	 							echo AppHelper::two_decimal($expense_with_category->total_expense_in_category * $currencyExchangeRates[$expense_with_category->currency_code]);					 
	 					}else{
	 					    echo AppHelper::two_decimal($expense_with_category->total_expense_in_category);	 						
	 					}	 				 
	 				?></td>
	 			</tr>
	 			<?php endforeach; ?>
	 			 		 
 		    </tr>
 			<tr>
 				<td><h3>Total Expense</h3></td>
 				<td><h3><?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?>{{ AppHelper::two_decimal($total_expenses) }}</h3></td>
 			</tr>
 			
 			<tr class="thick_line2">
 				<td><h3>Net Profit /Loss</h3></td>
 				<td><h3 class="<?php echo $income - $total_expenses < 0 ? "makeRed": "makeGreen"; ?>"><?php echo AppHelper::dumCurrencyCode($preferences->currency_code); ?>{{ AppHelper::two_decimal($income - $total_expenses) }}</h3></td>
 			</tr>
 		</table>
 		
 		</div><!-- END pl_body -->
 		
 		
 	   {{ Form::open(array('url' => 'reports/profit_and_loss_download', 'method' => 'POST')) }}
 		  <input type="hidden" name="startdate" class="" value="{{ $raw_startdate }}" >
 		  <input type="hidden" name="enddate" class="" value="{{ $raw_enddate }}" > 
 	      <input type="submit" class="btn" name="" value="Download PDF">
   	   {{ Form::close() }}
 		
 	</div><!-- END profit_and_loss -->
	
	@stop
	
	@section('footer')
	<script src="{{ URL::asset('assets/js/jquery.datetimepicker.js') }}"></script>
	<script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){				 
		  		 $('.report_all_menu').addClass('selected_group'); 		 
		  		 $('.menu_profit_loss').addClass('selected');		  		
		  		 $('.report_all_menu ul').css({'display': 'block'});
			 }
		  
		});
		
	</script>
	
@stop