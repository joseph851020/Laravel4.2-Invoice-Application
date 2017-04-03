@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; Videos</h1>

    <div class="div2">
    	
    	<h2>Welcome video </h2>
    	<p>This video was made to help you get started with the new version of Integrity Invoice.
    		 <div class="vid">
    			<iframe width="560" height="315" src="//www.youtube.com/embed/kOdtp_Hhsk8?rel=0&vq=hd1080" frameborder="0" allowfullscreen></iframe> 
    	     </div><!-- END VID -->
    	 
    </div><!-- END div2 -->


   @stop
   
	@section('footer')
	 
		<script src="{{ URL::asset('assets/js/jquery.fitvids.js') }}"></script>
		<script>
			  $(document).ready(function(){
			    $(".div2").fitVids();
			  });
		</script>
 
	@stop