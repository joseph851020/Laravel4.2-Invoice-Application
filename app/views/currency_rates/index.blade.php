@extends('layouts.default')

	@section('content')
 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Currency List and Rates</h1>
	
	<a class="btn" href="{{ URL::Route('create_currency_rate') }}">Create new currency rate</a>
	 
	
	<?php if($total_records >= 1):  ?>
		
	{{ $currency_rates->links() }}
	    
	     <h3>Your Home currency is: <strong> {{ $preferences->currency_code }} - {{ $home_currency }}</strong></h3>
  			<br />
			<table class="table">
       		<thead>
       			<tr>       			 
       				<th class=""><i class=""></i>Additonal Currency</th>
       				<th class="sorting"><i class=""></i>Currency Code</th>   
       				<th class="sorting"><i class=""></i>Exchange Rate</th>      		     				 
       				<th class="sorting"><i class="fa fa-cogs"></i> Action</th>
       			</tr>
       		</thead>
       		<tbody>
			<?php $row = 2; ?> 
			@foreach($currency_rates as $currency_rate)
		    <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
			<tr class="{{ $colour }}">				 
				<td width="30%"><a class="" href="{{ URL::to('currency-rates/'.$currency_rate->currency_code.'/edit') }}"><span class="amount">{{ $currency_rate->country_currency }} ({{ IntegrityInvoice\Utilities\AppHelper::dumCurrencyCode($currency_rate->currency_code) }}) </span></a></td>
				<td width="30%"><span> {{ $currency_rate->currency_code }}</span></td>
				<td class="">{{ $currency_rate->unit_exchange_rate }}</td>
		 
				<td class="">	               					
					<a title="Edit" class="btn-edit" href="{{ URL::to('currency-rates/'.$currency_rate->currency_code.'/edit') }}">
					<i class="fa fa-edit"></i></a>
					
					<a title="Delete" class="btn-edit btn-danger do_delete_currency_rate" href="{{ URL::to('currency-rates/'.$currency_rate->currency_code.'/delete') }}">
					<i class="fa fa-trash-o"></i></a>
				</td>				 
			</tr>
			 @endforeach
		 </tbody>
       		
       	 </table>
    
	 <?php else: ?>
	 	
	  <!-- NO Items yet -->
     <div class="no_item">
 	<div class="msg">
	 	<h3>You haven't created any currency rates yet</h3>
	    <p>This is a list of the currencies that you bill in and their corresponding exchange rates to your home currency.
	    	To start creating currency rates right away,  click the create button above or <a class="ordinary_link2" href="{{ URL::to('help') }}">see how to</a>.</p>
	     
	  </div>
    </div><!-- End no item -->
 
 <?php endif; ?> 
	
	{{ $currency_rates->links() }}
			 
@stop
	
@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.settings_all_menu').addClass('selected_group'); 		 
			  		$('.menu_currency_rate_settings').addClass('selected');		  		
			  		$('.settings_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop