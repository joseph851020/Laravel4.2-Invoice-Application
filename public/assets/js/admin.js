/**
Copyright © Integrity Invoice, All rights reserved.
Integrity Invoice is a service of Media Divinity Design Ltd.
**/

$(function(){
    
    ////////////////////////// MENU ////////////////////   
	   
	// HTML markup implementation, overlap mode, initilaize collapsed
	 ////////////////////////// MENU ////////////////////   
	   
	// HTML markup implementation, overlap mode, initilaize collapsed
	$('#menu').css({'display': 'block'});
	$('#menu').multilevelpushmenu({	
		containersToPush: [$( '.page-panel')], 
	    collapsed: true, 
	    menuHeight: '100%',
	    //fullCollapse: true,
	    overlapWidth: 33,
		menuWidth: 210,
		preventItemClick: false,
		wrapperClass: 'mlpm_w',
		menuInactiveClass: 'mlpm_inactive',
		swipe: 'both',
		onTitleItemClick: function(){
		  $('#pagebody').css({ 'z-index' : "100"});
		},
		onGroupItemClick: function(){
		  $('#pagebody').css({ 'z-index' : "100"});
		},
		onTitleItemClick: function(){
		  $('#pagebody').css({ 'z-index' : "100"});
		},
		onExpandMenuStart: function(){
		  $('#pagebody').css({ 'z-index' : "100"});
		},
		onCollapseMenuEnd: function(){
			$('#pagebody').css({ 'z-index' : "9000"});
		}
	});
	
 
	$( window ).resize(function() {
	    $( '#menu' ).multilevelpushmenu( 'redraw' );
	});
	
	$('#login-trigger').click(function(){
		$(this).next('#login-content').slideToggle();
		$(this).toggleClass('active');					
		
		if ($(this).hasClass('active'))
		{
			$(this).find('span.switcher').html('&#x25B2;');
			
			$('#login-content').mouseleave(function(){
				$(this).delay(1800).slideUp(500, function(){
					$('#login-trigger').find('span.switcher').html('&#x25BC;');
					$('#login-trigger').toggleClass('active');
				});			 
			});
	 
		}
		else 
		{
			$(this).find('span.switcher').html('&#x25BC;');
			 
		}
 
	});
	
	
	/////////////////////////////////////////////////////////////////////////////////
	
	
	if($('.search_term').length > 0){
		// Search term
		$('.search_term').click(function(){
			$('.search_term').val() == 'search...' ? $('.search_term').val('') : '';						  
		 });
		 
		 $('.search_term').bind('blur',function(){
			$('.search_term').val() == '' ? $('.search_term').val('search...') : '';						  
		 });
		 
		 $('.search_term').live('click', function(){
			 
			if($('.search_term').val() == 'search...' || $('.search_term').val() == ''){
				return false;
			} 
		 });
	}//
	 
	 
	 
	 
	 
	
	// Confirm delete account
	$('.do_delete_account').click(function(){
		if(confirm('Delete the account '+ $(this).attr('tenant') + '? Are you sure? This is not recoverable once deleted.')){
			
		 // var delete_password = prompt("Please enter delete code");
		 //    if (delete_password != "DELETE2000") {
		 //      return false;   
		 //    }    
			return true;
		}
		return false;
	});
	
	// Confirm delete all selected
	$('.do_delete_notification').click(function(){
		if(confirm('Delete the notification?')){
			return true;
		}else{
			return false;
		}
	});
	
	
	
	
	
	// Today
    var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	month < 10 ? month = "0" + month : month;
	var day = currentTime.getDate();
	day < 10 ? day = "0" + day : day;
	var year = currentTime.getFullYear();
 
  	var date_format = 'dd/mm/yyyy';
  	
	if($('.startdate').length > 0){
	 
			$('.startdate').pickadate({
	    		 format: date_format
			});
		}
		
	if($('.enddate').length > 0){
 
			$('.enddate').pickadate({
    		 format: date_format
	 		});
	 
	}
		
 
});

























