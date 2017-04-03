/**
Copyright © Integrity, All rights reserved.
Integrity is a service of Integrity Invoice Ltd.
**/
 
$(function(){
	
	// Profile Menu
    if($('.profile_account').length > 0){
        $(".profile_account").click(function(){
            var X= $(this).attr('id');
            if(X==1){
                $(".profile_submenu").hide();
                $(this).attr('id', '0');
            }
            else
            {
                $(".profile_submenu").show();
                $(this).attr('id', '1');
            }

         });
    }
		
	//Mouse click on sub menu
    if($('.profile_submenu').length > 0){
        $(".profile_submenu").mouseup(function(){
            return false;
        });
    }
		
	//Mouse click on my account link
    if($('.profile_account').length > 0){
        $(".profile_account").mouseup(function(){
            return false;
        });
    }
		
	//Document Click
    if($('.profile_submenu').length > 0){
        $(document).mouseup(function(){
            $(".profile_submenu").hide();
            $(".profile_account").attr('id', '');
        });
    }

	
	///////////////////////////////////////////////////////
	
	// Menu New Invoice 
	$(".menu_newinvoice").click(function(){
		
		$('#form_type').val($(this).attr('data')); 
        $('.form_type').html($(this).attr('data'));   
        			
		var X= $(this).attr('id');
		if(X==1){
			$(".menu_newinvoice_submenu").hide();
			$(this).attr('id', '0');
		}
		else
		{
			$(".menu_newinvoice_submenu").show();
			$(this).attr('id', '1');
		}
   
        $(".page-panel").css({position : "relative"}).children().fadeTo( "fast" , 0.2);
	 
		return false;
		
	 });
	 
	 $('.cancel_newinvoce').click(function(){
	 	$(".page-panel").css({position : "relative"}).children().fadeTo( "fast" , 1);
	 	$(".menu_newinvoice_submenu").hide();
	 	return false;
	 });
		
	//Mouse click on sub menu
	$(".menu_newinvoice_submenu").mouseup(function(){
		return false;
	});
		
	//Mouse click on my account link
	$(".menu_newinvoice").mouseup(function(){
		return false;
	});		
		
	//Document Click
	$(document).mouseup(function(){
		$(".page-panel").css({position : "relative"}).children().fadeTo( "fast" , 1);
		$(".menu_newinvoice_submenu").hide();
		$(".menu_newinvoice").attr('id', '');
	});
	

	/////////////////////////////////////////////////////
	 
	
	// Page Invoice Menu 
	$(".page_menu_newinvoice").click(function(){
		
		$('#form_type').val($(this).attr('data')); 
        $('.form_type').html($(this).attr('data'));   
        			
		var X= $(this).attr('id');
		if(X==1){
			$(".page_menu_newinvoice_submenu").hide();
			$(this).attr('id', '0');
		}
		else
		{
			$(".page_menu_newinvoice_submenu").show();
			$(this).attr('id', '1');
		}
	  
        $(".page-panel").css({position : "relative"}).children().not($(".page_newinvoice_dropdown")).fadeTo( "fast" , 0.2);
	 
		return false;
		
	 });
	 
	 $('.cancel_newinvoce').click(function(){
	 	$(".page-panel").css({position : "relative"}).children().fadeTo( "fast" , 1);
	 	$(".page_menu_newinvoice_submenu").hide();
	 	return false;
	 });
		
	//Mouse click on sub menu
	$(".page_menu_newinvoice_submenu").mouseup(function(){
		return false;
	});
		
	//Mouse click on my account link
	$(".page_menu_newinvoice").mouseup(function(){
		return false;
	});		
		
	//Document Click
	$(document).mouseup(function(){
		$(".page_menu_newinvoice_submenu").hide();
		$(".page_menu_newinvoice").attr('id', '');
	});
	

	/**
	 * returns the current context path,
	 * ex: http://localhost:8080/MyApp/Controller returns /MyApp/
	 * ex: http://localhost:8080/MyApp returns /MyApp/
	 * ex: https://www.example.co.za/ returns /
	 */

  
	if($('.description').length > 0)
	{
	  $(document).on( 'keyup', 'textarea', function (){
	     $(this).height(0);
	     $(this).height(this.scrollHeight);
	  });
	
	 $('.description').find('textarea').keyup();
	 
	}
	
 
 
	if($('.notification').length > 0)
	{
		$('.notification').slideDown(1000);
	 
	 	$('.close-notice').click(function(){
	 	 
	 	  var $data2 = "notification_hide=true";
	 		
	 	   var jqxhr2 = $.ajax({ url: "../notification/close", 
							 type: "POST",	
							 data: $data2
			})
			.success(function() {
		 
					$('.notification').slideUp(600, function(){
						
						 location.reload(true);
					 	 return true;
					 	
				    });
			})
			.error(function() { alert("Error closing notification - please try later."); })
			.complete(function() { 
				
				// alert("created successfully");		

		     });
	 	 
	 		return false;
	 	});	
	 
	}
	
	
	 
	
	if($('#tenant_verify').length > 0)
	{
	 	$('.close_verification_message').click(function(){
	 		
	 	   var $data3 = "tenant_verify_remind="+1;
	 		
	 	   var jqxhr = $.ajax({ url: "../dashboard/remove_tenant_verify", 
							 type: "POST",	
							 data: $data3
			})
			.success(function(){
		 
				$('#tenant_verify').slideUp(600, function(){ 
					 location.reload(true);
					 return true;					 
				});
				
			})
			.error(function() { alert("Error closing message - please try later."); })
			.complete(function(){ 
					
			});		   
	 	 
	 		return false;
	 	});	
	 
	}
	
	
	
	
	if($('.newuser-close').length > 0)
	{
	 
	 	$('.newuser-close').click(function(){
	 		
	 	   var $data1 = "firsttimer="+1;
	 		
	 	   var jqxhr = $.ajax({ url: "../firsttimer/remove", 
							 type: "POST",	
							 data: $data1
			})
			.success(function() {
		 
					$('.newuser').slideUp(600, function(){

                        location.reload(true);
                        return true;
                    });
			})
			.error(function() { alert("Error removing welcome message - Please try later."); })
			.complete(function() { 
				
				// alert("created successfully");		

		     });
		   
	 	 
	 		return false;
	 	});	
	 
	}
	
  	 
    /////////////// MENU ////////////////////  
    
    $('footer').css({'display': 'block'});

    if($('#appmenu').length > 0){
        $('#appmenu').css({'display': 'block'});

        $('#appmenu > ul > li ul').each(function(index, element){
            var count = $(element).find('li').length;
            //var content = '<span class="cnt fa fa-angle-right">' + count + '</span>';
            var content = '<span class="cnt fa fa-sort-desc"></span>';
            $(element).closest('li').children('a').append(content);
        });

        $('#appmenu ul ul li:odd').addClass('odd');
        $('#appmenu ul ul li:even').addClass('even');


        $('#appmenu > ul > li > a').click(function() {

            var checkElement = $(this).next();

            $('#appmenu li').removeClass('active');
            // $(this).closest('li').addClass('active');
            if(!$(this).closest('li').hasClass('selected_group')){
                $(this).closest('li').addClass('active');
            }

            if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                $(this).closest('li').removeClass('active');
                checkElement.slideUp('normal');
            }
            if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                $('#appmenu ul ul:visible').slideUp('normal');
                checkElement.slideDown('normal');
            }

            if($(this).closest('li').find('ul').children().length == 0) {
                return true;
            } else {
                return false;
            }

        });
    }
	

	
	/*
	 $('#appmenu').on('click', '.selected a', function() {
    	 
	 }); */
	
 
	/////////////////////////////////////////////////////////////////////////////////
	
	// Placeholder focus event
	if($('.cost').length > 0){
		
		$('.cost').data('holder',$('.cost').attr('placeholder'));

		$('#items').on('focusin', '.cost', function() { 
      		 $(this).attr('placeholder','');
    	});
		
		$('#items').on('focusout', '.cost', function() { 
      		$(this).attr('placeholder',$(this).data('holder'));
      		$('.cost').data('holder',$('.cost').attr('placeholder'));
    	});
    	
    	$('#items').on('blur', '.cost', function() { 
      		$(this).attr('placeholder',$(this).data('holder'));
      		$('.cost').data('holder',$('.cost').attr('placeholder'));
    	});
	
	}//
 
	
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
	 
	
	//$('#inner_wrap').css({"display":"none"});
	//$('#inner_wrap').fadeIn(500);
	
	if($('.task_item').length > 0){
		
		$('.task_item img').css({opacity:1.0});	
	
	
		$('.task_item').hover(function(){
			$(this).find('img').css({opacity:0.6});	
		},function(){
			$(this).find('img').css({opacity:1.0});	
		});
	}
	
	
	if($('#selectAll').length > 0)
	{
		$('#selectAll').click(function(e){
		    var table = $(e.target).closest('table');
		    $('td input:checkbox', table).prop('checked', this.checked);
		});
	}
	
 
	
	// Confirm delete all selected
    if($('.delete_selected').length > 0){
        $('.delete_selected').click(function(){
            if(confirm('Delete selected list?')){
                return true;
            }else{
                return false;
            }
        });
    }
	
	// Confirm delete all selected clients
    if($('.delete_selected_clients').length > 0){
        $('.delete_selected_clients').live('click',function(){

            // If number of checked boxes is greater than 1
            if(($('.checkbox:checked').length) >= 1){
                if(confirm('Delete selected list? This action will also delete all associated invoices to avoid redundancy.')){
                    return true;
                }else{
                    return false;
                }
            }else{
                alert('At least one record must be selected');
                return false;
            }
        });
    }
	
	// Confirm delete all selected merchants
    if($('.delete_selected_merchants').length > 0){
        $('.delete_selected_merchants').live('click',function(){

            // If number of checked boxes is greater than 1
            if(($('.checkbox:checked').length) >= 1){
                if(confirm('Delete selected list? This action will also delete all associated expenses to avoid redundancy.')){
                    return true;
                }else{
                    return false;
                }
            }else{
                alert('At least one record must be selected');
                return false;
            }
        });
    }
	
	// Confirm delete all selected expenses
    if($('.delete_selected_expenses').length > 0){
        $('.delete_selected_expenses').live('click',function(){

            // If number of checked boxes is greater than 1
            if(($('.checkbox:checked').length) >= 1){
                if(confirm('Delete selected list of expense(s)?')){
                    return true;
                }else{
                    return false;
                }
            }else{
                alert('At least one record must be selected');
                return false;
            }
        });
    }


	// CONFIRM CLIENT DELETE
    if($('.do_delete_client').length > 0){
        $('.do_delete_client').click(function(){
            var itemname = $(this).parent().parent().find('.itemname').text();
            if(confirm('Delete the Client: '+ itemname+ '? This action will also delete all associated invoices to avoid redundancy.')){
                return true;
            }else{
                return false;
            }
        });
    }
	
	// CONFIRM VENDOR DELETE
    if($('.do_delete_merchant').length > 0){
        $('.do_delete_merchant').click(function(){
            var itemname = $(this).parent().parent().find('.itemname').text();
            if(confirm('Are you sure you want to delete the Merchant: '+ itemname+' This action is irreversible.')){
                return true;
            }else{
                return false;
            }
        });
    }

	// CONFIRM DLETE USER
    if($('.do_delete_user').length > 0){
        $('.do_delete_user').click(function(){

            var username = $(this).parent().parent().find('.itemname').text();
            if(confirm('Are you sure you want to delete: ' + username)){
                return true;
            }else{
                return false;
            }
        });
    }
	
	// CONFIRM EXPENSE DELETE
    if($('.do_delete_expense').length > 0){
          $('.do_delete_expense').click(function(){
		
            var expensename = $(this).parent().parent().find('.amount').text();
            if(confirm('Delete Expense of value: '+ expensename +'.')){
                return true;
            }else{
                return false;
            }
        });
    }
	
	
	// CONFIRM ACCOUNT CANCELATION
    if($('#cancelaccount').length > 0){
        $('#cancelaccount').click(function(){

            if(confirm('Cancel account? This will permanently delete all your Integrity account information. This action is irrecoverable, if you have any issues we may be able to help resolve it by emailing us at support@integrityinvoice.com. If you are sure about canceling then click ok.')){
                return true;
            }else{
                return false;
            }
        });
    }


    // Today
    var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	month < 10 ? month = "0" + month : month;
	var day = currentTime.getDate();
	day < 10 ? day = "0" + day : day;
	var year = currentTime.getFullYear();
	
	var date_format = $('.date_format').val();

	 /////////////////////////////
	  
	  if($('#preference_form').length > 0){
	  	
		   $('#preference_form').easytabs();
		   
		   var $legends = $('#legend-variables');
		   
		   $legends.css({'display': 'none'});
		   
		   $('.general').bind('click', function(){	   		
		   		$legends.css({'display': 'none'});	   		
		   });
		   
		   $('.notes').bind('click', function(){	   		
		   		$legends.css({'display': 'none'});	   		
		   });
		   
		   $('.reminder').bind('click', function(){	   		
		   		$legends.css({'display': 'block'});	   		
		   });
		   
		   $('.progress').bind('click', function(){	   		
		   		$legends.css({'display': 'block'});	   		
		   });
	  }
	  
});