<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@if(isset($title)) {{ $title }} @endif Admin Integrity</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow"/>
        <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">	  
		<link rel="apple-touch-icon" href="{{ URL::asset('apple-touch-icon.png') }}">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
 		<link href='https://fonts.googleapis.com/css?family=Lato:300,100' rel='stylesheet' type='text/css'>
 		<link rel="stylesheet" href="{{ URL::asset('assets/css/normalize.css') }}">
 		<link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.multilevelpushmenu_admin.css') }}"> 
 		
 		<link rel="stylesheet" href="{{ URL::asset('assets/pickdate/themes/default.css') }}" id="theme_base">
 		<link rel="stylesheet" href="{{ URL::asset('assets/pickdate/themes//classic.date.css') }}" id="theme_date">
 		<link rel="stylesheet" href="{{ URL::asset('assets/pickdate/themes/classic.time.css') }}" id="theme_time">	
 		
        <link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.dialog.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/css/admin.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/css/account_nav.css') }}">
        <script src="{{ URL::asset('assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>
        
       <?php 
        		
        	$background = 1;
		?>
		
		<style type="text/css">       		 
       		  body{ background: url('{{ Config::get("app.app_domain") }}assets/img/textures/{{ $background }}.png') !important; }
        </style>
        
        @yield('page_specific_css')
 	 
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div id="page-container">
        	
        <div id="toparea">
        	 
        	 <div class="appTopLeft">
        	  			
					<a href="{{ URL::route('admin_dashboard') }}" class="company-name"><i class="fa fa-home" title="List" href="#"></i> Account Management</a>
			 
        	 </div><!-- End appTopLeft -->
        	 
        	 <div class="appTopRight">
        	  <!--  <p><a class="signoff" title="Sign off" href="#">Adeniyi Moses</a><a class="fa fa-ellipsis-v" title="List" href="#"></a></p> --> 
        	  
        	  <div id="profile-nav">
					<ul>
						<li id="login">
							<a id="login-trigger" href="#">
							<span class="fa fa-user">&nbsp;</span>
                                <?php if(Session::get('firstname') == NULL || Session::get('firstname') == ""){
                                    echo "My Account";
                                }else{
                                    echo  Session::get('firstname');
                                }?><span class="switcher"> ▼ </span>
							</a>
							<div id="login-content">
								 <ul>
								 	<li><a href="{{ URL::to('users/'.Session::get('admin_user_id').'/edit') }}">Edit profile</a></li>
								 	<li><a href="{{ URL::to('admin/logout') }}">Log out</a></li>
								 </ul>
							</div>                     
						</li>
					</ul>
				</div><!-- End appTopRight -->
     	 	
        	 </div><!-- End appTopRight -->
        	 
        </div> <!-- End toparea -->
        
        <div id="pagebody">
      	
		<div class="page-panel">