@extends('layouts.default')

  @section('content')
 <div class="for_report">
 <h1 class=""><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Intelligent Robot</h1>
 
 <p>Coming soon...!</p>
  
</div><!-- End for_report -->
  @stop

 @section('footer')
   
  <script>
	
		$(document).ready(function(){
			
			if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Reports');				 
				  $('.menu_intelligent_robot').addClass('selected');
			 }
			 
		});
		
	</script>

 @stop