<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@if(isset($title)) {{ $title }} @endif Integrity Invoice</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
 		<link href='//fonts.googleapis.com/css?family=Lato:300,100' rel='stylesheet' type='text/css'>
 		<link href="/assets/css/normalize.css" rel="stylesheet">
 		<link href='//fonts.googleapis.com/css?family=PT+Sans:400,700,400italic' rel='stylesheet' type='text/css'>
 		<link href="/assets/css/public_invoice.css" rel='stylesheet' type='text/css'>
 	 
</head>
<body>
	<!--[if lt IE 7]>
	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<div id="page-container">
	 
	<div id="pagebody">
	
	<div class="page-panel">
		
		@if (Session::get('failed_flash_message'))
			<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
		@endif
 	
		@yield('content')
	 
		</div>  <!-- End panel -->   
		  
	</div> <!-- End pagebody -->
	
	<script src="//code.jquery.com/jquery-1.8.3.min.js"></script>
	
	@yield('footer')
	
	<script>
	
	$('.description').on( 'keyup', 'textarea', function (){
	    $(this).height( 0 );
	    $(this).height( this.scrollHeight );
	});
	$('.description').find( 'textarea' ).keyup();

	</script>
	
	<div class="powered">
		<small>Powered by <a target="_blank" class="link" href="{{ Config::get('app.app_domain') }}">Sighted</a></small>
	</div>
		
  </div><!-- END page-container -->
	 
		<!-- Load JS here for greater good =============================--> 
      
    </body>
</html>
