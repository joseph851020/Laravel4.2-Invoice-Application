/**
Copyright © Integrity Invoice, All rights reserved.
Integrity Invoice is a service of Media Divinity Design Ltd.
**/

function print_today() {
  // ***********************************************
  // AUTHOR: WWW.CGISCRIPT.NET, LLC
  // URL: http://www.cgiscript.net
  // Use the script, just leave this message intact.
  // Download your FREE CGI/Perl Scripts today!
  // ( http://www.cgiscript.net/scripts.htm )
  // ***********************************************
  var now = new Date();
  var months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');
  var date = ((now.getDate()<10) ? "0" : "")+ now.getDate();
  function fourdigits(number) {
    return (number < 1000) ? number + 1900 : number;
  }
  var today =  date + " " +months[now.getMonth()] + " , " + (fourdigits(now.getYear()));
  return today;
}

// from http://www.mediacollege.com/internet/javascript/number/round.html
function roundNumber(number,decimals) {
  var newString;// The new rounded number
  decimals = Number(decimals);
  if (decimals < 1) {
    newString = (Math.round(number)).toString();
  } else {
    var numString = number.toString();
    if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
      numString += ".";// give it one at the end
    }
    var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
    var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
    var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
    if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
      if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
        while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
          if (d1 != ".") {
            cutoff -= 1;
            d1 = Number(numString.substring(cutoff,cutoff+1));
          } else {
            cutoff -= 1;
          }
        }
      }
      d1 += 1;
    } 
    if (d1 == 10) {
      numString = numString.substring(0, numString.lastIndexOf("."));
      var roundedNum = Number(numString) + 1;
      newString = roundedNum.toString() + '.';
    } else {
      newString = numString.substring(0,cutoff) + d1.toString();
    }
  }
  if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
    newString += ".";
  }
  var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
  for(var i=0;i<decimals-decs;i++) newString += "0";
  //var newNumber = Number(newString);// make it a number if you like
  return newString; // Output the result to the form field (change for your purposes)
}


function update_total() {
  var total = 0;
  var vat = 0;
  var discount = 0;
  var new_tax = $('#thetax').val();
  var new_discount = $('#thediscount').val();
  
  $('.price').each(function(i){
    price = $(this).html();
    if (!isNaN(price)) total += Number(price);
  });

  total = roundNumber(total, 2);
  isNaN(new_discount) ? $('#thediscount').html("N/A") : discount = total * new_discount / 100;  
  // discount = total * new_discount / 100;
  discount = roundNumber(discount, 2);
 
  //vat = total * new_tax / 100;
  isNaN(new_tax) ? $('#thetax').html("N/A") : vat = total * new_tax / 100;
  vat = roundNumber(vat, 2);
  
  $('#vat').html(vat);
  $('#discount').html(discount);
  //total = total * 17.5/100;
  //total = roundNumber(total, 2);
  $('#subtotal').html(roundNumber(total, 2));
  total = Number(total) + Number(vat);
  total = Number(total) - Number(discount);
  total = roundNumber(total, 2);
  //$('#total').html(total);
   $('.due').html(total);
  
  // update_balance();
}

function update_balance() {
  var due = $("#total").html();
  due = roundNumber(due, 2);
  mybalance = due;
  
  $('.due').html(due);
}


function update_tax(){	
	var mytax = $('#mytax');
	// mytax.hide();
	var thetax = $('#thetax');
	thetax.change(function(){
	var new_tax = $('#thetax').val();
	if(new_tax != null && new_tax !="" && !isNaN(new_tax) && new_tax != 0){
		mytax.show();
		$('#tax_percent').html(new_tax + "%");
			update_total();
	}else{
		mytax.hide();
		$('#tax_percent').html("");
	}
  });
}

function update_discount(){	
	var mydiscount = $('#mydiscount');
	// mydiscount.hide();
	var thediscount = $('#thediscount');
	thediscount.change(function(){
	var new_discount = $('#thediscount').val();
	if(new_discount != null && new_discount !="" && !isNaN(new_discount) && new_discount != 0){
		mydiscount.show();
		$('#discount_percent').html(new_discount + "%");
		    update_total();
	}else{
		mydiscount.hide();
		$('#discount_percent').html("");
	}
	
  });
}

function update_price() {
  var row = $(this).parents('.item-row');
  var price = row.find('.cost').val() * row.find('.qty').val();
  price = roundNumber(price,2);
  isNaN(price) ? row.find('.price').html("N/A") : row.find('.price').html(price);  
  update_tax();
  update_discount();
  
  update_total();
}

function bind() {
  $(".cost").blur(update_price);
  $(".qty").blur(update_price);
}

function thecompany(){
	var c_address = $('#customer-title');
	var companyselected = $('#client_number option:selected').text();	
	c_address.val(companyselected);
	var c_mainadd = $('.customer-add');
	var cadd_1 = $('#client_number option:selected').attr('cl_add1');
	var cadd_2 = $('#client_number option:selected').attr('cl_add2');
	var cpcode = $('#client_number option:selected').attr('cl_pcode');
	var cpstate = $('#client_number option:selected').attr('cl_state');
	c_mainadd.html(cadd_1+"<br />"+cpstate+"<br />"+cpcode);
}


$(document).ready(function() {	
						   
	//$('#mytax').val() = $('hidden-tax').text();
	$('#mytax').val($('.hidden-tax').html());

     update_tax();
     update_discount();
	 
	 bind();
	 update_price();

	 
	// $('#mytax').hide();
	// $('#mydiscount').hide();
	 
	 var val1 = $('#thetax').val();
	 var val2 = $('#thediscount').val();     
	 
	 if(val1 == null || val1 == 0){
		 $('#mytax').hide();		
	 }
	 
	 if(val2 == null || val2 == 0){
		$('#mydiscount').hide();	
	 }
	 
	 var tenant_id = $('input#tenant_id').val();
	 var mycursymbol;
	
	var currency_id;
	 var curSymbol = $('#the_currency');	
	function setCurrency(){
		var curSymbolVal = $('#the_currency option:selected').attr('value');
		currency_id = $('#the_currency option:selected').attr('cur_id');
       // var singleCurSym = curSymbolVal.replace(/^.*\((.*)\).*$/m, '$1'); // Run the regex to grab the middle string
		//mycursymbol = singleCurSym;
	 $('.cur_symbol').html(curSymbolVal);

	}
	setCurrency();
	curSymbol.change(function() {
		setCurrency();
	});
	
	// line number
	 var itm_num =  $('.item-row').find('.itm').attr('num_row');
	
	$('#client_number').change(function() {
		thecompany();
	});
	
	 $("#createInvoice").live('click',function(){
		var lineItemsnumber = 1;
		var cp_address = $('#customer-title').val();
		// validate
		if(cp_address == '- select client -' || cp_address == ''){
			
			alert('Please select one client for this invoice');
			return false;	
		}

			var descript = $('.desc').val();

			if(descript == 'item description' || descript == ''){
				alert('Please enter correct item description for all items');
				return false;	
			}	
		
		
		
		var items = new Array();
		// get all data
		 $('.item-row').each(function(i){			
			var num = Number([i]) + 1;
			
			//eval("var item_" + num + "= new Array();");
		
			 var row = $(this);			 
			 var itm =  row.find('.itm').val().replace(",","__");
			 var desc =  row.find('.desc').val().replace(",","__");
			 var unit_cost =  row.find('.cost').val();
			 var qty =  row.find('.qty').val();
 			 var itm_price = row.find('.price').text();
			 var delimiter = ' | ';

			// eval("var item_" + num + "= new Array();");
		    var we = window["item_" + num] = new Array();
			we = [itm,desc,unit_cost,qty,itm_price,delimiter];
			// add them to list
			items.push(we);
		  });
		
		var inv_num = $('.inv_num').val();
		var client_company = $('#client_number').val();
		var cl_id = $('#client_number option:selected').attr('cl_id');
		
		if(client_company=="" || client_company == null){
			alert("please select the company you're issuing invoice for");
			return false;
		}
		
		if(currency_id == 0 || currency_id == null){
			alert("please select currency");
			return false;
		}
		var user_id = $('#header_w').attr('user_id');
		var inv_note = $('.notetext').val();
		var discount_perc = $('#thediscount').val();
		var tax_perc = $('#thetax').val();
		var cur_val = currency_id;
		var duedate = $('#invoice_due_date').val();
		
		var discount_val = roundNumber(Number($(".c_discount").html()), 2);
		var tax_val = roundNumber(Number($(".c_vat").html()), 2);
		var subtotal = roundNumber(Number($(".c_subtotal").html()), 2);
		var balance_due =  roundNumber(Number($(".due").html()), 2);	

		// Data String
		var invoiceData = "cl_id="+cl_id+"&data="+items+"&client_company="+client_company+"&user_id="+user_id+"&discount_val="+discount_val+"&tax_val="+tax_val+"&cur_val="+cur_val+"&inv_note="+inv_note+"&due_date="+duedate+"&discount_perc="+discount_perc+"&tax_perc="+tax_perc+"&subtotal="+subtotal+"&balance_due="+balance_due+'&tenant_id='+tenant_id;
		
		// overlay
		 $(".processing").modal();
		 $(".modalCloseImg").css({"display":"none"});
		 $(".simplemodal-close").css({"display":"none"});


		// Assign handlers immediately after making the request,
		// and remember the jqxhr object for this request
		var jqxhr = $.ajax({ url: "http://localhost:8888/biivs/invoices/submit_create", 
							 type: "POST",	
							 data: invoiceData
			})
			.success(function() {
			  //
			  $(".processing").delay(5000).fadeTo(400,0.1,function() //start fading the messagebox
				   {
					//add message and change the class of the box and start fading					
					$(".processing").css({"display":"none"});
					$.modal.close();

				});
			})
			.error(function() { alert("error adding new client"); })
			.complete(function() { 

				window.location = "http://localhost:8888/biivs/invoices/showall"; 				

		     });
		
		// perform other work here ...
		
		// Set another completion function for the request above
		//jqxhr.complete(function(){ alert("second complete"); });
		
		return false;
	});
	
	
  $('input').click(function(){
    $(this).select();
  });
	
  $("#paid").blur(update_balance);
   
  $("#addrow").live('click', function(){
									  
   var last_item_num = $(".item-row:last").find('.itm').text();
   var next_num = Number(last_item_num) + 1;

    $(".item-row:last").after('<tr class="item-row"><td class="item-name"><div class="delete-wpr"><textarea class="cw itm">'+ next_num +'</textarea><a class="item_selector_icon" href="javascript:;" title="Add from item">i</a><a class="delete" href="javascript:;" title="Remove row">X</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc">item description</textarea></td><td><textarea class="cost cw">0.00</textarea></td><td><textarea class="qty cw">1</textarea></td><td><span class="cur_symbol"></span><span class="price">0.00</span></td></tr>');
    if ($(".delete").length > 0) $(".delete").show();
    bind();
	setCurrency();
  });
  
  bind();
  
  
  $(".delete").live('click',function(){
    $(this).parents('.item-row').remove();
	update_item_number();
    update_total();
    if ($(".delete").length < 2) $(".delete").hide();
  });
  
  function update_item_number(){
	  var first_row = $(".item-row:first").find('.itm').text();
	  //first_row = 1;
	  
	  //var counti = 0;
	 //alert(first_row);

	//$(".item-row").each(function(){
		//$(this).find('.itm').text(parseInt($(this).find('.itm').text()) + 1);

		//alert(txt);
		//$(this).find('.itm').text() = Number(first_row) ++;
	//});
  }
  
  $("#cancel-logo").click(function(){
    $("#logo").removeClass('edit');
  });
  $("#delete-logo").click(function(){
    $("#logo").remove();
  });
  $("#change-logo").click(function(){
    $("#logo").addClass('edit');
    $("#imageloc").val($("#image").attr('src'));
    $("#image").select();
  });
  $("#save-logo").click(function(){
    $("#image").attr('src',$("#imageloc").val());
    $("#logo").removeClass('edit');
  });
  
  $("#date").val(print_today());
  
 	 window.setTimeout(function() {
			update_total();
	}, 200);
	 
	function removeInitRow2(){
 	 $(".init_row_2").remove();
	   update_total();
      if ($(".delete").length < 2) $(".delete").hide();
	}
	
	//$(".datepicker").datepicker();

	$( ".datepicker" ).datepicker({
        dateFormat: 'dd MM, yy',
        altField: ".date_alternate",
        altFormat: "yy-mm-dd"
    });

	
	$('.item_selector_icon').live('click',function(){
												   										   
		var item_container = $('.item-container').css({"display":"block"});
		var item_container_inner = $('.item-container-inner');	
		
		var item_box = $('.item_selector');
		item_box.css({"display":"block", opacity:0.0}).fadeTo(600,1.0,function(){

		}); 
		
		$(this).parent().find('.item-container-inner').after(item_box);	
		
		//var item_row = $(this).parent().find('.item-row');
		if($(this).parents().siblings().hasClass('selectedRow')){
			$(this).parents().siblings().removeClass('selectedRow');
		}
		
	    $(this).parent().parent().parent().addClass('selectedRow');
		//alert(item_row.attr('class'));
		
		
		///////////////////////////////////////
		
		var item_row = $(this).parents('.item-row');
		//var item_row = $(this).parent.parent().parent().hasClass('selectedRow');
		//var item_row = $(this).parent().parent().parent().hasClass('selectedRow');
		//var item_row = $(this).parent().find('.item-row');
		//var item_row = $(this).parent();
		
		var box = $(this).parent().find('.item_selector .item_sel');
		
		$('.item_sel').change(function(event) {
									
			var itm_name = $('.item_sel option:selected').attr('item_name');
			var itm_desc = $('.item_sel option:selected').attr('item_description');	
			var itm_unit = $('.item_sel option:selected').attr('item_unit_price');
			var itm_qty = 1;
			
		   // Update this line item only		
			if(item_row.hasClass('selectedRow')){
				 var t_desc = item_row.find(".desc");
				 var t_unit = item_row.find(".cost");
				 var t_qty = item_row.find(".qty");
	
				t_desc.val(itm_desc);
				t_unit.val(itm_unit);
				t_qty.val(itm_qty);
			}
							
			// update other areas			
			$(".cost").trigger('blur');
 			$(".qty").trigger('blur');
			update_tax();
			update_discount();
	
			return false;
			
	   }); // End .item_sel Click
				  
								  
	}); // End Item selector 
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
}); // jquery
