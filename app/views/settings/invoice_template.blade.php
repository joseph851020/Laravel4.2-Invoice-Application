@extends('layouts.default')


	@section('page_specific_css')
		<link rel="stylesheet" href="{{ URL::asset('assets/css/lightbox.css') }}">
	@stop

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Invoice Template / Design</h1>
	
	<div class="for_invoice_template" class="group">

    <div class="invoice_box">
        <div class="invoice_image"><a href="{{ URL::asset('assets/img/templates/3_big.jpg') }}" data-lightbox="image-3"><img src="{{ URL::asset('assets/img/templates/3.jpg') }}" class=""/></a></div>
        <div class="inv_desc">
            <h3>Business elite </h3>
            <a href="{{ URL::route('apply_invoice_template', 2) }}" class="gen_btn"><?php if($preferences->invoice_template == 2): ?>Current template<?php else: ?>Activate template <?php endif; ?></a>
        </div>
        <?php if($preferences->invoice_template == 2): ?>
            <div class="mark_current"><img src="{{ URL::asset('assets/img/mark_current.png') }}" class=""/></div>
        <?php endif; ?>

    </div><!-- END invoice_box -->

	 <div class="invoice_box">
    	<div class="invoice_image"><a href="{{ URL::asset('assets/img/templates/1_big.jpg') }}" data-lightbox="image-1"><img src="{{ URL::asset('assets/img/templates/1.jpg') }}" alt="" class=""/></a></div>
        <div class="inv_desc">
        	<h3>Professional elite</h3>           
            <a href="{{ URL::route('apply_invoice_template', 1) }}" class="gen_btn"><?php if($preferences->invoice_template ==  1): ?>Current template<?php else: ?>Activate template  <?php endif; ?></a>
        </div>
        <?php if($preferences->invoice_template == 1): ?>
        	<div class="mark_current"><img src="{{ URL::asset('assets/img/mark_current.png') }}" class=""/></div>
        <?php endif; ?>
         
    </div><!-- END invoice_box -->
   
    <div class="invoice_box">
    	<div class="invoice_image"><a href="{{ URL::asset('assets/img/templates/2_big.jpg') }}" data-lightbox="image-2"><img src="{{ URL::asset('assets/img/templates/2.jpg') }}" class=""/></a></div>
        <div class="inv_desc">
        	<h3>Rav pro v1.2</h3>           
             <a href="{{ URL::route('apply_invoice_template', 3) }}" class="gen_btn"><?php if($preferences->invoice_template ==  3): ?>Current template<?php else: ?>Activate template  <?php endif; ?></a>
        </div>
        <?php if($preferences->invoice_template ==  3): ?>
        	<div class="mark_current"><img src="{{ URL::asset('assets/img/mark_current.png') }}" class=""/></div>
        <?php endif; ?>
         
    </div><!-- END invoice_box -->

    <div class="invoice_box">
        <div class="invoice_image"><a href="{{ URL::asset('assets/img/templates/4_big.jpg') }}" data-lightbox="image-3"><img src="{{ URL::asset('assets/img/templates/4.jpg') }}" class=""/></a></div>
        <div class="inv_desc">
            <h3>Clean Slate </h3>
            <a href="{{ URL::route('apply_invoice_template', 4) }}" class="gen_btn"><?php if($preferences->invoice_template == 4): ?>Current template<?php else: ?>Activate template <?php endif; ?></a>
        </div>
        <?php if($preferences->invoice_template == 4): ?>
            <div class="mark_current"><img src="{{ URL::asset('assets/img/mark_current.png') }}" class=""/></div>
        <?php endif; ?>

    </div><!-- END invoice_box -->
 
     
	</div><!-- END for_help -->
	<input type="hidden" id="tenant_id" value="{{ Session::get('tenantID') }}" />
		  
 @stop
 
@section('footer')
	<script src="{{ URL::asset('assets/js/lightbox.js') }}"></script>
	 <script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				 
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_invoice_template').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	  </script>
@stop
