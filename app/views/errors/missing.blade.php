@extends('layouts.default')

  @section('content')
	 <div class="for_report">
	 <h1 class=""><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; 404. Uh Oh... Somethng went wrong!</h1>
	 
	 <p> We're sorry the page you're looking for doesn't exist. </p>
	 
	 <p>Check out our <a class="ordinary_link2" href="{{ URL::to('help') }}">Help Center</a> for help, or head to <a class="ordinary_link2" href="{{ URL::to('dashboard') }}">dashboard</a>.</p>
	  
	</div><!-- End for_report -->
  @stop
 