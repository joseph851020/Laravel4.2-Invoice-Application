@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Help Centre </h1>
  
    <div class="wrap_help">
    	 
    	<div class="announcement_help">
    		<h2>Title of announcements</h2>
    		<p class="anouncement_meta"><span class="">Murray Newlands</span> | <span class="">16 July</span></p>
    	</div><!-- END Help Wrap -->
    	
    	
    	<div class="help_section">

    		 <div class="help_box first-col">
    		 	<h3>Introduction</h3>
    		 	<ul>
    		 	    <li class=""><i class="fa fa-file-o"></i> <a class="" href="{{ URL::to('help/getting-started')}}">Getting Started with Sighted </a></li>
                    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/introduction/glossary-of-terms') }}">Glossary of Terms</a></li>
                    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/introduction/frequently-asked-questions') }}">Frequently Asked Questions</a></li>
                </ul>
    		 </div><!-- END box_help  -->

            <div class="help_box second-col">
                <h3>&nbsp;</h3>
                <ul>
                    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/introduction/about-sighted') }}">About Sighted</a></li>
                    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/introduction/why-use-sighted') }}">Why Use Sighted?</a></li>
                    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::route('support') }}">Request for features</a></li>
                </ul>
            </div><!-- END box_help  -->
    		 
    		 
    		 <div class="help_box first-col">
    		 	<h3>Settings / Customisation</h3>
    		 	<ul>
                   <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/settings/account') }}">General account settings</a></li>
    		 	</ul>    		 
    		 </div><!-- END box_help  -->
    		 
    		 
    		 <div class="help_box second-col">
    		 	<h3>Invoices</h3>
    		 	<ul>
    		 	   <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/invoices/how-to-create-and-send-invoice') }}">How to create and send an invoice</a></li>
    	        </ul>
    		 </div><!-- END box_help  -->

    		 <div class="help_box first-col">
    		 	<h3>Expenses</h3>
    		 	<ul>
    		 	   <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/expenses/how-to-create-an-expense') }}">How to create an expense</a></li>
    		 	 </ul>
    		 </div><!-- END box_help  -->

            <div class="help_box second-col">
                <h3>Clients / Customers</h3>
                <ul>
                    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/clients/how-to-create-a-client') }}">How to create a client</a></li>
                </ul>
            </div><!-- END box_help  -->

    		 <div class="help_box first-col">
    		 	<h3>Services</h3>
    		 	<ul>
    		 	   <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/services/how-to-create-a-service') }}">How to create a service</a></li>
    		 	 </ul>
    		 </div><!-- END box_help  -->
    		 
    		 
    		 <div class="help_box second-col">
    		 	<h3>Products</h3>
    		 	<ul>
    		 	    <li class=""><i class="fa fa-file-o"></i> <a href="{{ URL::to('help/products/how-to-create-a-product') }}">How to create a product</a></li>
    		    </ul>
    		 </div><!-- END box_help  -->

    	</div><!-- END all_help  -->

    	<div class="help_section">

            <div class="help_box first-col">
                <h3>Data Protection and Usage</h3>
                <ul>
                    <li class=""><i class="fa fa-file-o"></i> <a href="">Security and Privacy of Data</a></li>
                </ul>
            </div><!-- END box_help  -->
    		 
    		 
    		 <div class="help_box second-col">
    		 	<h3>Need further assistance?</h3>
                 <p>Send us a <a class="ordinary_link2" href="{{ URL::route('support') }}"> quick message</a>
                 <?php if(Config::has('app.support_phone')): ?>
                    or call us now on {{ Config::get('app.support_phone') }}. We are here to support you all the way!
                 <?php endif; ?>
                 </p>
    		 </div><!-- END box_help  -->
    		 
    	 
    	</div><!-- END all_help  -->
    	
    	
    	
    	
    </div><!-- END Help Wrap -->
    
 
 @stop
	

 @section('footer')

	<script>
	
		$(function(){
		 
		 	 if($('#appmenu').length > 0){
				    
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_help').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop