<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@if(isset($title)) {{ $title }} @endif Sighted</title>
        <meta name="description" content="">
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">	  
		<link rel="apple-touch-icon" href="{{ URL::asset('apple-touch-icon.png') }}">
        <link rel="stylesheet" href="/assets/css/font-awesome.css">
         <!-- Bootstrap styles -->    	 

        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
 		<link href='//fonts.googleapis.com/css?family=PT+Sans:400,700,400italic' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic,300,300italic' rel='stylesheet' type='text/css'>
 		<link rel="stylesheet" href="/assets/css/normalize.css">
    
	 	<link rel="stylesheet" href="/assets/css/jquery.datetimepicker.css"/>
        <link rel="stylesheet" href="/assets/css/main.css">
        
        <link rel="stylesheet" href="/assets/css/app_themes/{{ Session::get('theme_id').'.css' }}"> 
        
        <link rel="stylesheet" href="/assets/css/mobile.css">
        <link rel="stylesheet" href="/assets/css/account_nav.css">
        <link rel="stylesheet" href="/assets/css/select2.css" media="screen and (min-width: 600px)">
        
        <?php 
        		
        	$background = 6;
        	
        	 if(str_contains(Request::url(), 'invoice')){ $background = 10; }
			 
			 if(str_contains(Request::url(), 'quote')){ $background = 10; }
			 
			 if(str_contains(Request::url(), 'dashboard')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'company')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'import') || str_contains(Request::url(), 'export')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'expense')){ $background = 2; }
			 
			 if(str_contains(Request::url(), 'merchant')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'product')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'service')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'client')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'settings')){ $background = 6; }
			 
			 if(str_contains(Request::url(), 'apptheme')){ $background = 2; }
			 
			 if(str_contains(Request::url(), 'paymentgateways')){ $background = 6; }
			 
			 if(!is_int($background)){ $background = 5; }
        
        ?>
   
        <style type="text/css">
       		 body{ background: url('/assets/img/textures/{{ $background }}.png') !important; }
        </style>
        
        <script src="/assets/js/vendor/modernizr-2.6.2.min.js"></script>
 	 
 	 	@yield('page_specific_css')
 	 	
 	 	@include('common.inspectlet')
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        		<?php 
        			$preferences = Preference::where('tenantID', '=', Session::get('tenantID'))->first();
        			$notification = AdminNotification::where('active', '=', '1')->orderBy('updated_at', 'desc')->first();
			 		if(Session::get('close_notification') != true): 
					
					if(Tenant::where('tenantID','=', Session::get('tenantID'))->pluck('verified') == 1): 
						     
					if($notification != NULL && $notification->active == 1): ?>
				        <div class="notification">
				        	<h2><i class="fa fa-info-circle"></i> {{ $notification->title }}</h2>
				        	<p>{{ $notification->info }} &nbsp; <a class="yellow_btn close-notice" href="">I've got it, Close</a></p>
				        </div>
			   <?php endif; endif; endif; ?>
        <div id="page-container">
        	
        <div id="toparea">
        	 
        	 <div class="appTopLeft"> 
        	 		<a href="{{ URL::to('dashboard') }}" class="company-name">{{ Company::where('tenantID','=', Session::get('tenantID'))->pluck('company_name') }}</a>
			 		<input type="hidden" class="date_format" value="<?php echo $preferences->date_format; ?>">
        	 </div><!-- End appTopLeft -->
        	 
        	 <div class="appTopRight">
        	  
        	   <div id="" class="profile_dropdown">
					<a class="profile_account"> <?php if(Session::get('firstname') == NULL || Session::get('firstname') == ""){
						echo "My Account";
					}else{
						echo  Session::get('firstname');
					}?> <i class="fa fa-sort-desc"></i></a>					
					<div class="profile_submenu">
						<ul class="profile_root">				 
							<li ><a href="{{ URL::to('users/'.Session::get('user_id').'/edit') }}"><i class="fa fa-user"></i> My Profile</a></li>
							<li ><a href="{{ URL::route('settings') }}"><i class="fa fa-cog"></i> Account Settings</a></li>
							<li ><a href="{{ URL::route('help') }}"><i class="fa fa-life-ring"></i> Get Help</a></li>
							<li ><a href="{{ URL::route('support') }}"><i class="fa fa-paper-plane"></i> Send feedback</a></li>
							<li ><a href="{{ URL::to('logout') }}"><i class="fa fa-sign-out"></i> Sign Out</a></li>
						</ul>
					</div>
					
			      </div><!-- END profile_dropdown -->
		 
				</div><!-- End appTopRight --> 
     	 	
        	 </div><!-- End appTopRight -->
        	 
        </div> <!-- End toparea -->

        @include('layouts/mobilemenu')
        
        <div id="pagebody">
      	
		<div class="page-panel {{ str_contains(Request::url(), array('report', 'invoice', 'quote', 'dashboard', 'subscription', 'client', 'currency-rates', 'expenses', 'products', 'services', 'payments')) ? 'make_block' : 'make_inline_block' }}">