@include('layouts/adminheader')
 
   	
	@if (Session::get('flash_message'))
		<div class="flash success">{{ Session::get('flash_message') }}</div>
	@endif
	
	@if (Session::get('failed_flash_message'))
		<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
	@endif
	
	@yield('content')


</div>  <!-- End panel -->   
  
 </div> <!-- End pagebody -->
 		
 
@include('layouts/adminmenu')
   
@include('layouts/adminfooter')	
       