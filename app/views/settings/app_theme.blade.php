@extends('layouts.default')

	@section('content')
 
      <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; App Themes</h1>		               
   	
   	{{ Form::open(array('url' => 'settings/apptheme', 'method' => 'put')) }}
   	
   	@if($errors->has())
	<div class="flash error">
		<ul>
			{{ $errors->first('theme', '<li>:message</li>') }}
		</ul>
	</div>
	@endif 
 	<div>&nbsp; <br /></div>
	<label>Menu and button colours</label> 
	<select id="theme_id" name="theme_id" class="sel">
		<option <?php echo $theme_id == 6 ? "selected" : ""; ?> value="6">Blue</option>
		<option <?php echo $theme_id == 3 ? "selected" : ""; ?> value="3">Teel</option>
		<option <?php echo $theme_id == 5 ? "selected" : ""; ?> value="5">Lime</option> 
		<option <?php echo $theme_id == 7 ? "selected" : ""; ?> value="7">Pink</option>       
        <option <?php echo $theme_id == 1 ? "selected" : ""; ?> value="1">Red Orange</option>       
        <option <?php echo $theme_id == 2 ? "selected" : ""; ?> value="2">Purple</option>   
                                   
	</select>
	 
      <br /> <br /> <input type="submit" id="" class="gen_btn" name="theme" value="Save" />
     
    {{ Form::close() }}
    
     
	@stop


	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
				   
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_app_theme').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			 }
			 
			 $('#theme_id').select2({ width: 'element' });
			 
		});
		
	</script>
 
	@stop