@extends('layouts.default')

	@section('content')	 
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Account Upgrade: Current subscription is {{ $account_type }}</h1>
	<p>See payment history, <a href="{{ URL::to('subscription/history') }}">click here</a></p>
	 
 	<div id="upgrade_page">

        <div class="pricing">

            <div class="whole">
                <div class="type super_premium">
                    <p>Super Premium</p>
                </div>
                <div class="plan">

                    <div class="pricing_header">
                        <span>&#36;</span>8<sup>.99</sup>
                        <p class="month">per month <br />
                            <small class="saving">Was </small><small class="oldprice">&#36;13.99</small> <small class="saving">save 36%</small></p>
                    </div>
                    <div class="content">
                        <ul class="">
                            <li>{{ $super_premium_plan->user_limit }} Users</li>
                            <li>Unlimited clients</li>
                            <li>Unlimited invoices</li>
                            <li>Unlimited expenses</li>
                            <li>Accept Online Payments</li>
                            <li>Profit &amp; Loss Report</li>
                            <li>Unlimited design</li>
                        </ul>
                    </div><!-- END content -->
                </div><!-- END Plan -->

                <div class="yourplan">
                    {{ Form::open(array('url' => 'subscription/cart', 'method' => 'POST')) }}
                    <span class="push_coupon" for="coupon_code_premium">Coupon code</span>
                    <input class="coupon_box" type="text" value="" name="coupon_code" id="coupon_code_premium" />
                    <input type="hidden" value="3" name="plan" />
                    <input type="hidden" value="8.99" name="plan_price" />

                    <?php if($account_type == "Starter"){ ?>
                        <input type="submit" class="gen_btn" id="" value="UPGRADE">
                    <?php }else if($account_type == "Super Premium" && $account_expired == 0){ ?>
                        <input type="submit" class="gen_btn" id="" value="EXTEND ">
                    <?php }else if($account_type == "Super Premium" && $account_expired == 1){ ?>
                        <input type="submit" class="gen_btn" id="" value="RENEW ">
                    <?php }else if($account_type == "Premium"){ ?>
                        <input type="submit" class="gen_btn" id="" value="UPGRADE">
                    <?php } ?>

                    {{ Form::close() }}
                </div><!-- END yourplan -->
            </div> <!-- END Whole -->


            <div class="whole">
                <div class="type premium">
                    <p>Premium</p>
                    <a class="popular_badge" href="#"></a>
                </div>

                <div class="plan">
                    <div class="pricing_header">
                        <span>&#36;</span>4<sup>.99</sup>
                        <p class="month">per month <br /><small class="saving">Was </small><small class="oldprice">&#36;8.32</small> <small class="saving">save 40%</small></p>
                    </div>
                    <div class="content">
                        <ul class="">
                            <li>{{ $starter_plan->user_limit }} User</li>
                            <li>{{ $premium_plan->client_limit }} clients</li>
                            <li>Unlimited invoices</li>
                            <li>Unlimited expenses</li>
                            <li>Accept Online Payments</li>
                            <li>Profit &amp; Loss Report</li>
                            <li>Unlimited design</li>
                        </ul>
                    </div><!-- END content -->
                </div><!-- END Plan -->

                <div class="yourplan">
                    {{ Form::open(array('url' => 'subscription/cart', 'method' => 'POST')) }}
                    <span class="push_coupon" for="coupon_code_premium">Coupon code</span>
                    <input class="coupon_box" type="text" value="" name="coupon_code" id="coupon_code_premium" />
                    <input type="hidden" value="2" name="plan" />
                    <input type="hidden" value="4.99" name="plan_price" />

                    <?php if($account_type == "Starter"){ ?>
                        <input type="submit" class="gen_btn" id="" value="UPGRADE">
                    <?php }else if($account_type == "Premium" && $account_expired == 0){ ?>
                        <input type="submit" class="gen_btn" id="" value="EXTEND ">
                    <?php }else if($account_type == "Premium" && $account_expired == 1){ ?>
                        <input type="submit" class="gen_btn" id="" value="RENEW ">
                    <?php }else if($account_type == "Super Premium"){ ?>
                        <input type="submit" class="gen_btn" id="" value="DOWNGRADE">
                    <?php } ?>
                    {{ Form::close() }}
                </div><!-- END yourplan -->
            </div> <!-- END Whole -->


            <div class="whole">
                <div class="type starter">
                    <p>Starter</p>
                </div>
                <div class="plan">
                    <div class="pricing_header">
                        <span>FREE</span> <sup> </sup>
                        <p class="month">per month <br /><small class="saving">Was</small> <small class="oldprice">&#36;1.95</small></p>
                    </div>
                    <div class="content">
                        <ul class="">
                            <li>{{ $starter_plan->user_limit }} User</li>
                            <li>{{ $starter_plan->client_limit }} clients</li>
                            <li>{{ $starter_plan->invoice_limit }} monthly invoices</li>
                            <li>{{ $starter_plan->expense_limit }} monthly expenses</li>
                            <li> Accept Online Payments </li>
                            <li> Profit &amp; Loss Report </li>
                            <li>Unlimited design</li>
                        </ul>
                    </div><!-- END content -->
                </div><!-- END Plan -->


                <div class="yourplan">
                    {{ Form::open(array('url' => 'subscription/cart', 'method' => 'POST')) }}
                    <p class="free_plan_push"><span for="coupon_code_starter">&nbsp;</span></p>

                    <input type="hidden" value="1" name="plan" />
                    <?php if($account_type == "Starter"){ ?>
                        <!-- <input type="submit" class="gen_btn" id="" value="EXTEND "> -->
                    <?php }else if($account_type == "Premium"){ ?>
                        <input type="submit" class="gen_btn push_free_downgrade" id="" value="DOWNGRADE">
                    <?php }else if($account_type == "Super Premium"){ ?>
                        <input type="submit" class="gen_btn push_free_downgrade" id="" value="DOWNGRADE">
                    <?php }?><p>&nbsp;</p>
                    {{ Form::close() }}
                </div><!-- END yourplan -->

            </div> <!-- END Whole -->

        </div> <!-- END pricing -->

	</div><!-- END upgrade_page -->
	
	<div class="creditcards"><img src="{{URL::asset('assets/img/creditcards.png') }}" alt="" /></div>
 	
	@stop
	
	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_subscription').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
			 
		});
		
	</script>
 
	@stop