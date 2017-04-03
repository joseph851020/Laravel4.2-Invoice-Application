<?php 

//$remover = new IntegrityInvoice\Services\Item\Remover($this);
		
		//$remover->remove(12);

?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@if(isset($title)) {{ $title }} @endif Integrity Invoice</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
 
          <link rel="stylesheet" href="{{ URL::asset('assets/css/normalize.css') }}">
          <link rel="stylesheet" href="{{ URL::asset('assets/css/datepicker.css') }}">
          <link rel="stylesheet" href="{{ URL::asset('assets/css/main.css') }}">
          <script src="{{ URL::asset('assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>
 

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
       
       <div class="header_wrap">
       	
        	<div id="header">
        		
        		<div class="leftspot">
        			
        			 <?php 
						$tenantID = Session::get('tenantID');
						//$ext1 = ".jpg";
						$ext = '.png'; $logo_file =  public_path(). '/te_da/'.$tenantID . '/'.$tenantID.$ext;
						//$logo_file_png = $this->config->item('server_root'). 'te_da/'.$tenant_id . "/".$tenant_id.$ext2; 
						 
						?>
						 
						@if (file_exists($logo_file))
							<img src="{{ URL::asset('/te_da/'.$tenantID . '/'.$tenantID.$ext) }}" alt="" />	
						@else
							<a href="{{ URL::to('company/logo') }}"><img src="{{ URL::asset('assets/img/default_logo.png') }}" alt="" /></a>
						@endif
				 
			   <?php // echo URL::base(). '/te_da/'.$tenant_id . '/'.$tenant_id.$ext; ?>
       			</div><!-- END leftspot -->
       			
       			<div class="middlespot">
       				<p><span class="company_name_title">{{ Company::where('tenantID','=', Session::get('tenantID'))->pluck('company_name') }}</span> <span>Online Invoice System</span></p>
       			</div><!-- END leftspot -->
       			
       			<div class="rightspot">
       				<p class="logged_in_info">Logged in as: <strong> {{ Auth::user()->firstname }}</strong> <a href="{{ URL::to('logout') }}">Logout</a></p>
       			</div><!-- END leftspot -->
       	
       		</div><!-- END Header -->
       	
       </div><!-- END Top header_wrap -->
       
       
       
        <div class="menu_wrap">
       	
        	<div id="menu">
        		
        		<ul id="mynav">
        			<li><a class="nav1" href="{{ URL::to('dashboard') }}">Dashboard</a></li>
        			<li><a class="nav2" href="{{ URL::to('invoices') }}">Invoices</a></li>
        			<li><a class="nav3" href="{{ URL::to('expenses') }}">Expenses</a></li>
        			<li><a class="nav4" href="{{ URL::to('items') }}">Items</a></li>
        			<li><a class="nav5" href="{{ URL::to('clients') }}">Clients</a></li>
        			<li><a class="nav6" href="{{ URL::to('vendors') }}">Vendors</a></li>
        			<li><a class="nav7" href="{{ URL::to('users') }}">Admin users</a></li>
        			<li><a class="nav8" href="{{ URL::to('settings') }}">Settings</a></li>
        			<li><a class="nav9" href="{{ URL::to('help') }}">Help Centre</a></li>
        		</ul>
        		       	
       		</div><!-- END menu -->
       	
       </div><!-- END Top menu_wrap -->
       
       
        <div class="page_wrap">
       	
        	<div id="page">
        		
        		<div class="container">
        			
        			@if (Session::get('flash_message'))
        				<div class="flash success">{{ Session::get('flash_message') }}</div>
        			@endif
        			
        			@if (Session::get('failed_flash_message'))
        				<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
        			@endif
        			
        			@yield('content')
        			
        	 
        		</div><!-- container -->
       	
       		</div><!-- END page -->
       	
       </div><!-- END page_wrap -->
       
       
        <div class="footer_wrap">
       	
        	<div id="footer">
        		
        		
        		<div class="side">
					<ul>
						<p class="hd-title">Your app</p>
						<li><a href="">Getting started</a></li>
						<li><a href=" ">Video tutorials</a></li>
						<li><a href=" ">Frequently Asked Questions</a></li>
						<li><a href=" ">Extend &amp; Upgrade</a></li>
					</ul>
					
				</div><!-- END side1 -->
				
				<div class="side">
					<p class="hd-title">What's new</p>
					<ul>
						<li><a href=" ">New features</a></li>
						<li><a href=" ">Refer your friends</a></li>
						<li><a target="_blank" href="http://www.integrityinvoice.com/forum">Forum</a></li>
					</ul>	
				</div><!-- END side1 -->
				
				<div class="side">
					<p class="hd-title">Need some help?</p>
					<ul>
						<li><a href=" ">Contact us</a></li>
						<li><a target="_blank" href="http://www.facebook.com/pages/Integrity-Invoice/323176251061059?sk=wall">Join us on facebook</a></li>
						<li><a target="_blank" href="https://twitter.com/Integrityinvoic">Follow twitter conversations</a></li>
						<li><a target="_blank" href="http://www.integrityinvoice.com/subscription_terms_conditions.php">Terms of service</a></li>	
					</ul>	
				</div><!-- END side1 -->
				
				<div class="announcements">
					<p class="hd-title">Announcements</p>
					<p>We would like to know what you think about Integrity Invoice. Leave us feedback today to help us continue to improve the system.</p>	
					<p class="copyright">Copyright &copy; 2012 Integrity Invoice. All rights reserved.</p>
				</div><!-- END side1 -->

       	
       		</div><!-- END footer -->
       	
       </div><!-- END footer_wrap -->
       
 

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ URL::asset("assets/js/vendor/jquery-1.10.2.min.js") }}"><\/script>')</script>       
        
        <script src="{{ URL::asset('assets/js/jquery.hoverIntent.minified.js') }}"></script>
        <script src="{{ URL::asset('assets/js/block.js') }}"></script>
        
        <?php if(isset($script)): ?>
 		<?php 
 		  $script_array = explode(',', $script);
		  foreach($script_array as $key => $value):
 		?>
 
    	 <script src="{{ URL::asset('assets/js/'.$value.'.js') }}"></script>
    	<?php  endforeach; endif; ?>
    	
    	<script src="{{ URL::asset('assets/js/plugins.js') }}"></script>
        <script src="{{ URL::asset('assets/js/main.js') }}"></script>
        <script src="{{ URL::asset('assets/js/script.js') }}"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
    </body>
</html>
