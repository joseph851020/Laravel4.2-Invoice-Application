<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login to Integrity Invoice</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">	  
		<link rel="apple-touch-icon" href="{{ URL::asset('apple-touch-icon.png') }}">
		
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
		 <link rel="stylesheet" href="{{ URL::asset('assets/css/normalize.css') }}">
         <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
         <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
         <script src="{{ URL::asset('assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>
        
   <style type="text/css">
        
        
	*{margin:0; padding:0;}
 

	html{background: #fff /* #E5E8EB url('../img/bg_pattern.png'); */}
	
	body {
		margin: 0;
		padding: 0;
		overflow-x:hidden;
	}

 
	p{ color: #696969; font-size:14px; line-height: 22px;
	    margin: 10px 0; padding:0; font-family: 'Lato', sans-serif; }
	 
	h1, h2 {font-family: 'Lato', sans-serif; color:#696969; font-weight: 300; font-size:18px;}
 
			
	#login_form{min-width:200px;
	   max-width:300px;
	   margin:20px auto;
	   background:#fff;
		padding:20px;
	 
	}
	
	a{text-decoration:none; font-size:13px; padding-top:8px; color:#5bd4bc;}
	a:hover{text-decoration:underline; color:#333;}
 
 .mycenter{width:130px; margin:0 auto;}

 input[type=text], input[type=password]{
	background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #e3e3e3;
    border-radius: 0;
    color: #4C4C4C;  
    font-weight: 300;    
    color: #4C4C4C;
    font-size: 16px;
    padding: 12px 10px;
    width: 94%;
    margin:0 0 15px 0;
	}	
	
	
	input[type=text]:focus, input[type=password]:focus{background-color: #f9f9f9;
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0) inset;}	
    
    label {    color: #B2B2B2;
    display: block;
    font-size: 13px;
    margin: 0;
    text-transform: uppercase;
    font-weight: bold;}
		 
 
	
	.btn, .button, .gen_btn {
	    background: #22c7a7 !important;
	    border: 0 none;
	    border-radius: 3px;
	    box-shadow: none;
	    color: #FFFFFF !important;
	    font-size: 13px;
	    font-weight: bold;
	    line-height: 1;
	    margin-right: 10px;
	    padding: 0.9em 1.8em 0.8em;
	    text-shadow: none;
	    text-transform: uppercase;
	    text-decoration:none;
	    cursor: pointer;
	    display: inline-block;
	}
	 
	.btn .primary_btn {
	    background:  #333;
	}
	
	.btn:hover, .button:hover, .gen_btn:hover {background: #343536 !important;
	
	 /* Firefox */
	    -moz-transition: all 0.4s ease-in;
	    /* WebKit */
	    -webkit-transition: all 0.4s ease-in;
	    /* Opera */
	    -o-transition: all 0.4s ease-in;
	    /* Standard */
	    transition: all 0.4s ease-in;
	    
	    outline: medium none;
	}
	
	/* Notification */
	.flash{
	     margin-bottom: 7px 0 18px 0;
   		 padding: 8px 14px;
	     max-width: 97%;
	     border: 1px solid;
	     color: #fff;
	     font-size:12px;	    
	     -webkit-animation: animate-bg 5s linear infinite;
	     -moz-animation: animate-bg 5s linear infinite;
	}
	.info{
	     background-color: #4ea5cd;
	     border-color: #3b8eb5;
	}
	
	.error{
	     background-color: #fbe5ec;
	    border-color: #f9d8e8;
	    color: #db205d;
	}
	
	.warning{
	     background-color: #eaaf51;
	     border-color: #d99a36;
	}
	
	.success{
	    background-color: #dffaf4;
	    border-color: #bff6e1;
	    color: #18a98a;
	}
		
		
		#error_box{width:370px; padding:5px; margin:0 auto;}
		#forgetpassword{width:400px; margin:0 auto; text-align:center;}
		#forgetpassword a{text-decoration:none; color:#333;}
		#forgetpassword a:hover{text-decoration:underline; color:#555;}
		#error_box p{color:#88411b; font-weight:bold; padding: 4px 0 0 40px;}
		
		/* Button */
		 

        </style>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
      
       
        <div class="page_wrap">
       	
        	<div id="page">
        		
        		<div class="container">
        			 
        			@yield('content')
        	 
        		</div><!-- container -->
       	
       		</div><!-- END page -->
       	
       </div><!-- END page_wrap -->
      
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

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
