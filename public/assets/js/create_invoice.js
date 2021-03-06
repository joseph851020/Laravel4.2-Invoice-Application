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
  var totalTax = 0;
  var tax = 0;
  var discount = 0;
  var totalDiscount = 0;
  
  // Add up prices
  $('.price').each(function(i){
    price = $(this).html();
    if (!isNaN(price)) total += Number(price);
  });
  
  // Add up taxes
  $('.hide-line-tax').each(function(i){
    tax = $(this).html();
    if (!isNaN(tax)) totalTax += Number(tax);
  });
  
  // Add up discounts
  $('.hide-line-discount').each(function(i){
    discount = $(this).html();
    if (!isNaN(discount)) totalDiscount += Number(discount);
  });

  total = roundNumber(total, 2);
  discount = roundNumber(discount, 2);
  totalTax = roundNumber(totalTax, 2);
  totalDiscount = roundNumber(totalDiscount, 2);
  
  $('#vat').html(totalTax);
  $('#discount').html(totalDiscount);
 
  total = roundNumber(total, 2);
  //$('#total').html(total);
   $('.due').html(total);
   
  var subtotal = Number(total) + Number(totalDiscount) - Number(totalTax);
  $('#subtotal').html(roundNumber(subtotal, 2));
  
  // update_balance();
}

function update_balance() {
  var due = $("#total").html();
  due = roundNumber(due, 2);
  mybalance = due;
  
  $('.due').html(due);
}

function update_tax(){	
	var mytax = $('#vat');
	mytax.html();
}

function update_discount(){	
	var mydiscount = $('#discount');
	//mydiscount.html();
}


// Switch Gat row item tax
function getAppliedTax(itemtax){

	var the_itemtax = Number(itemtax);
	var appliedTax = 0;
	switch(the_itemtax){
		case 0:
		appliedTax = 0;
		break;
		
		case 1:
		appliedTax = $('#pref_tax1').val();
		break;
		
		case 2:
		appliedTax = $('#pref_tax2').val();
		break;
		
		default:
		appliedTax = 0;
		break;
	}
	
	return appliedTax;
}

function updateLineTaxAndDiscountChange(){
	
	$('select.itemtax').change(function(){
  
		update_price();
	});
	
	$('select.itemdisc').change(function(){
		update_price();
	});
 
}

// var checkPercentageDiscountValueOK = true;

// Update price
function update_price() {
	
  var row = $(this).parents('.item-row');    
  var provision_type = $('#param').attr('provision_type');
  var tax_discount_options = $('#param').attr('tax_discount_options');
  
  var rowQty = 1;
  var rowDiscount = 0;
  
  if($(".qty").length > 0){	rowQty = row.find('.qty').val(); }  
  if($(".disc").length > 0){ rowDiscount = row.find('.disc').val(); }  
  
  var appliedTax;
  var appliedDisc;
  var appliedDiscValue;
  var price_excluding_discount;
  var price_excluding_tax;
  var price_including_discount;
  var hide_line_tax;
  var hide_line_discount;
  var price;
 
 
    // Service type
    // None
  	if(tax_discount_options == 'none')
  	{
		  price_excluding_discount = row.find('.cost').val() * rowQty;  
		  price_excluding_tax = row.find('.cost').val() * rowQty;
		  price = price_excluding_tax;
  	}
  	
  	// Discount Only
  	if(tax_discount_options == 'discount')
  	{ 
		  appliedDiscValue = $.trim(rowDiscount);  
		  price_excluding_discount = row.find('.cost').val() * rowQty;  
		  price_excluding_tax = row.find('.cost').val() * rowQty;
		  
		  if(appliedDiscValue != ""){		    
		  	 appliedDiscValue = price_excluding_discount * appliedDiscValue / 100;		  	 
		  }else{
		  	appliedDiscValue = 0;
		  }	 
		  price = (price_excluding_tax - appliedDiscValue);
  		
  	}  	
  	
  	// Tax only
  	if(tax_discount_options == 'tax')
  	{
  		
  		  appliedTax = $.trim(getAppliedTax(row.find('select.itemtax option:selected').val()));  		     
		  price_excluding_tax = row.find('.cost').val() * rowQty;	 
		  price = (price_excluding_tax)  + (price_excluding_tax * appliedTax / 100);
  		
  	}
  	
  	// Both
  	if(tax_discount_options == 'both')
  	{
  		 	   
		  appliedTax = $.trim(getAppliedTax(row.find('select.itemtax option:selected').val()));		   
		  appliedDiscValue = $.trim(rowDiscount);  
		  price_excluding_discount = row.find('.cost').val() * rowQty;  
		  price_excluding_tax = row.find('.cost').val() * rowQty;
		  
		  if(appliedDiscValue != ""){		    
		  	 appliedDiscValue = price_excluding_discount * appliedDiscValue / 100;		  	 
		  }else{
		  	appliedDiscValue = 0;
		  }	 
		  
		  price_including_discount = price_excluding_tax - appliedDiscValue;
		  price = price_including_discount + price_including_discount * appliedTax / 100;
	  
  	}
   
  
  if($('.hide-line-tax').length > 0)
  {
  	hide_line_tax = row.find('.hide-line-tax');
  	hide_line_tax.html(price_excluding_tax * appliedTax / 100);
  }
  
  if($('.hide-line-discount').length > 0)
  {
  	hide_line_discount = row.find('.hide-line-discount');
  	hide_line_discount.html(appliedDiscValue);
  }
   
  // var hide_line_discount = row.find('.hide-line-discount');
 
  price = roundNumber(price, 2);
  isNaN(price) ? row.find('.price').html("N/A") : row.find('.price').html(price);  
  //update_tax();
  //update_discount();
   
  update_total();
}

 
function bind() {
  
  $(".cost").blur(update_price);
  if($(".qty").length > 0)
  {
    $(".qty").blur(update_price);
  }
 
  $(".disc").blur(update_price);
  $(".cost").keyup(update_price);
  
  if($(".qty").length > 0)
  {
  	$(".qty").keyup(update_price);
  }
  
  $(".disc").keyup(update_price);
  $(".itemtax").mouseup(update_price);
  $(".itemdisc").mouseup(update_price);
  $(".disc").mouseup(update_price);
 
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


var unsavedChanges = false;
	
function setUnsavedChanges()
{
    unsavedChanges = true;    
    return;
}

function savedChanges()
{
    unsavedChanges = false;    
    return;
}

// Ask user if they want to navigate away	
$(window).on('beforeunload', function(){
 	 
   if(unsavedChanges) {
     return "It looks like you have been entering some data -- if you leave before submitting your changes will be lost.";
   }
});

$(document).ready(function() {	
	
	// Check for unsaved changes
	$('#invoicecontainer :input:not(.discount_enabler, .tax_enabler), #invoicecontainer select').change(function(){
	    setUnsavedChanges();
	});
	
	 
	//
	function check_invoice_quote_id(){
		
		   var $new_id_raw = $('.inv_num').val();	   
		   var $new_id = parseInt($new_id_raw);
		   
		   var $ajax_data;	
		   var $uri = "../invoices/check_invoice_id";
		   
		   var $req_type = $('#request_type').val();
		
			if($req_type == 'invoice')
			{
				$uri = "../invoices/check_invoice_id";
				$ajax_data = "tenant_invoice_id="+$new_id;
			}
			else if($('#request_type').val() == 'quote')
			{
				$uri = "../quotes/check_quote_id";
				$ajax_data = "tenant_quote_id="+$new_id;
			}
			 
	 		
	 	   var jqxhr = $.ajax({ url:  $uri, 
							 type: "POST",	
							 data: $ajax_data
			})
			.success(function($response) {
		 		
		 		if($response == 1)
		 		{
		 			alert($req_type.charAt(0).toUpperCase() + $req_type.slice(1) + " ID is not available, please use a different number.");
		 			$('.inv_num').val('');
		 			return false;
		 		}
					 
			})
			.error(function() { alert("Error - please try later."); })
			.complete(function() { 
				 
	     });
		
	}
	
	
	$('.inv_num').on('blur', function(){
		check_invoice_quote_id();
	});
	
	 
	if($('.date_format').val() == "dd/mm/yyyy"){
		var date_format = "uk";
		var date_format_helper = "d/m/Y";
	}else if($('.date_format').val() == "mm/dd/yyyy"){
		var date_format = "us";
		var date_format_helper = "m/d/Y";
	}
	
	    // Due date picker
        var currentTime = new Date();
		var month = currentTime.getMonth() + 1;
		month < 10 ? month = "0" + month : month;
		var day = currentTime.getDate();
		day < 10 ? day = "0" + day : day;
		var year = currentTime.getFullYear();
		
		// Date format UK
		if(date_format == "uk"){
			var today = day + "/" + month + "/" + year;
		}
		// Date format US
		if(date_format == "us"){
			var today = month + "/" + day + "/" + year;
		}
		 
		$('.issuedate, .duedate').val(today);
		 
	 
	// Default tax preferences
	$('.ttax1').html($('#pref_tax1').attr('tax_1name'));
	$('.ttax2').html($('#pref_tax2').attr('tax_2name'));

     update_tax();
     update_discount();
	 
	 bind();
	 update_price();
	 updateLineTaxAndDiscountChange();

	 var tenantID = $('input#tenantID').val();	  
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
	 // var itm_num =  $('.item-row').find('.itm').attr('num_row');
	
	$('#client_number').change(function() {
		thecompany();
	});
	
	var discountPercentageError = 0;
	 
	 $("#createInvoice").on('click',function(){
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
				 
			 // var itm =  row.find('.itm').val().replace(",","__");
			 var desc =  row.find('.desc').val().replace(",","__");
			  desc =  desc.replace("&","__amp__");
			 var unit_cost =  row.find('.cost').val();

			 var qty;
			 if($('.qty').length > 0)
			 {
			 	qty = row.find('.qty').val();
			 }
			 else
			 {
			 	qty = 1;
			 }
			 
			 var line_tax =  row.find('select.itemtax option:selected').val();
			 var line_tax_value = Number(row.find('.hide-line-tax').text());
			 // var line_discount = row.find('select.itemdisc option:selected').val();
			 var line_discount = 1;
			 var line_discount_value =  Number(row.find('.hide-line-discount').text());

             if(isNaN(unit_cost) || $.trim(unit_cost) == ""){ unit_cost = 0; }
             if(isNaN(line_tax_value)){ line_tax_value = 0; }

 			 var itm_price = row.find('.price').text();
			 var delimiter = ' | ';
 
			// Only Insert if Something is in Description
			if($.trim($(this).find('.desc').val()) != "" && $.trim($(this).find('.desc').val()) != '-'){
				
				 var we = window["item_" + num] = new Array();
				 we = [1, desc,unit_cost,qty,line_tax,line_tax_value,line_discount,line_discount_value,itm_price,delimiter];
				 
				// add them to list
				items.push(we);
			}
		   
		  });

		var inv_num = parseInt($('.inv_num').val());
		var client_company = $('#client_number').val();
		var cl_id = $('#client_number option:selected').attr('cl_id');
		
		var currency_code = $('#the_currency option:selected').attr('cur_code');		 
		
		var user_id = $('#user_id').val();
		var inv_note = $('.notetext').val();

		var cur_val = currency_id;
		var issuedate = $('#issuedate').val();
		var duedate = $('#duedate').val();
		var po_number = $('#p-order').val();		 
		var request_type = $('#request_type').val();
		
		
		if(client_company=="" || client_company == null){
			alert("Please select the company you're issuing "+ request_type +" for");
			return false;
		}
		
		if(currency_id == 0 || currency_id == null){
			alert("Please select currency");
			return false;
		}
 
		
		var bankinfo;		
		if($('#bankinfo').is(":checked")) {        
           bankinfo = 1;
        }
        else
        {         	
        	bankinfo = 0;           	
        } 
		

		var discount_val = roundNumber(Number($(".c_discount").html()), 2);
		var tax_val = roundNumber(Number($(".c_vat").html()), 2);
		var subtotal = roundNumber(Number($(".c_subtotal").html()), 2);
		var balance_due =  roundNumber(Number($(".due").html()), 2);
		
		var invoice_subj = $('#inv_subject').val();
		
		var enable_discount = $('#param').attr('enable_discount');
		var enable_tax = $('#param').attr('enable_tax');
		var business_model = $('#param').attr('business_model');
		var bill_option = $('#param').attr('bill_option');
	 
		// Data String
		var invoiceData = "inv_num="+inv_num+"&invoice_subj="+invoice_subj+"&cl_id="+cl_id+"&request_type="+request_type+"&bankinfo="+bankinfo+"&po_number="+po_number+"&currency_code="+currency_code+"&data="+items+"&client_company="+client_company+"&user_id="+user_id+"&discount_val="+discount_val+"&tax_val="+tax_val+"&cur_val="+cur_val+"&inv_note="+inv_note+"&issue_date="+issuedate+"&due_date="+duedate+"&subtotal="+subtotal+"&balance_due="+balance_due+'&enable_discount='+enable_discount+'&enable_tax='+enable_tax+'&business_model='+business_model+'&bill_option='+bill_option+'&tenantID='+tenantID;

         // overlay
         $.blockUI({
             message: '<h2 style="color:#069576; font-weight:700; z-index: 99999;"><img src="../../assets/img/loader.gif" />  Just a moment...</h2>',
             overlayCSS: { backgroundColor: '#fff' },
             css: {
                 border: 'none',
                 top: 0,
                 left: 0,
                 padding: '15px 0',
                 backgroundColor: '#fff',
                 opacity: 1,
                 position: 'fixed',
                 textAlign: 'center',
                 color: '#18a98a',
                 zIndex: 199999,
                 display: 'block',
                 width: '100%'
             }

         });
 
		
		// Assign handlers immediately after making the request,
		// and remember the jqxhr object for this request
		var jqxhr = $.ajax({ url: "../invoices/store",
							 type: "POST",	
							 data: invoiceData
			})
			.success(function($response) {
				  
				if(request_type == 'invoice' && $response == 0){
			 		alert("Invoice ID is not available. Please use a different number");	
			 		$.unblockUI();
			 		return false;
			 	}else if(request_type == 'quote' && $response == 0){
			 		alert("Quote ID is not available. Please use a different number");	
			 		$.unblockUI();
			 		return false;
			 	}else{
			 		
			 	}
		  
				 setTimeout($.unblockUI, 2000);
				  
				 setTimeout(function(){		
				 	
				 	savedChanges();
				 	 	 	
				 	var request_type = $('#request_type').val();
				 	window.location = "../"+request_type+"s/"+$response; 
				 }, 2000);
					
			})
			.error(function() { alert("error creating "+ request_type); })
			.complete(function() { 
				 
		     });
		  
		return false;
	}); // End Submit button
	
 
	
	// Calculate New due date
	function addDays(daysAdded, issueDate){		

		// Date format UK
		if(date_format == "uk"){
			
			var dateArray = Array();
			dateArray = issueDate.split('/');
			formatted_issueDate = dateArray[1] +'/'+ dateArray[0] +'/'+ dateArray[2];
					
		    var myDate = new Date(formatted_issueDate); // 28th of Feb
	
			myDate.setDate(myDate.getDate() + daysAdded);
			var myMonth = myDate.getMonth() + 1;
			myMonth < 10 ? myMonth = "0" + myMonth : myMonth;
			var myDay = myDate.getDate();
			myDay < 10 ? myDay = "0" + myDay : myDay;
			var mYear = myDate.getFullYear();
			
		}
		// Date format US
		if(date_format == "us"){
			var dateArray = Array();
			dateArray = issueDate.split('/');
			formatted_issueDate = dateArray[0] +'/'+ dateArray[1] +'/'+ dateArray[2];
					
		    var myDate = new Date(formatted_issueDate); // 28th of Feb
	
			myDate.setDate(myDate.getDate() + daysAdded);
			var myMonth = myDate.getMonth() + 1;
			myMonth < 10 ? myMonth = "0" + myMonth : myMonth;
			var myDay = myDate.getDate();
			myDay < 10 ? myDay = "0" + myDay : myDay;
			var mYear = myDate.getFullYear();
		}

		// Date format UK
		if(date_format == "uk"){
			return myDay + "/" + myMonth + "/" + mYear;
		}
		// Date format US
		if(date_format == "us"){
			var today = month + "/" + day + "/" + year;
			return myMonth + "/" + myDay + "/" + mYear;
		}

	} // END 
	
 
	$("#prorata_due_date").on('change', function(){
	   $('#duedate').val(addDays(Number($.trim($('#prorata_due_date option:selected').attr('value'))), $.trim($('#issuedate').val()))); 
	});
		
	
	// Update Due Date
	$('#prorata_due_date').on('blur',function(){
		 $('#duedate').val(addDays(Number($.trim($('#prorata_due_date option:selected').attr('value'))), $.trim($('#issuedate').val()))); 
	});

	
	
  $('input').click(function(){
    // $(this).select();
  });
	
  $("#paid").keyup(update_balance);
  
  
 // Item Auto complete
  var items_ajax_url;
  if($('#bus_model').val() == 0){
  	items_ajax_url = "../products/json_list";
  }else if($('#bus_model').val() == 1){
  	items_ajax_url = "../services/json_list";
  }
   
  var all_products = [];
 
  $.getJSON( items_ajax_url, function(data){			 	 	
     	$.each( data, function(index, value) {			 	 	 	
 	 		var obj = {};
				obj.value = value.item_name;
				obj.data = value.unit_price;
				obj.id = value.id;
				obj.tax_type = value.tax_type;
			all_products.push(obj);
	 	});
   });
  
  
   
   
  $("#addrow").on('click', function(){
	 
	  var $line_row; 
	 
	  var provision_type = $('#param').attr('provision_type');
	  var tax_discount_options = $('#param').attr('tax_discount_options');
	  
	  var qtyBlock = '<td><textarea class="qty cw">1</textarea></td>';
	  var discountBlock = '<td class="disc_holder"><input class="disc" placeholder="0.00" name="" /></td>';
	  var taxBlock = '<td><select class="itemtax cw"><option value="0"> - </option><option class="ttax1" value="1"></option><option class="ttax2" value="2"></option></select></td>';
 
	  
	   /****  ADD ROW PRODUCT  *****/   
	   if(provision_type == 'product')
	   {  
		   	// None
		  	if(tax_discount_options == 'none')
		  	{
		  		discountBlock = '';
	   			taxBlock = '';	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Discount Only
		  	if(tax_discount_options == 'discount')
		  	{ 
	   			taxBlock = '';	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}  	
		  	
		  	// Tax only
		  	if(tax_discount_options == 'tax')
		  	{ 
		  		discountBlock = '';   		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Both
		  	if(tax_discount_options == 'both')
		  	{
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
	  	 
	   }


	   /****  ADD ROW SERVICE  *****/
	   if(provision_type == 'service'  && $('#param').attr('bill_option') == 1)
	   {
			 	// None
		  	if(tax_discount_options == 'none')
		  	{
		  		qtyBlock = '';
		  		discountBlock = '';
	   			taxBlock = '';	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Discount Only
		  	if(tax_discount_options == 'discount')
		  	{ 
		  		qtyBlock = '';
	   			taxBlock = '';		  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}  	
		  	
		  	// Tax only
		  	if(tax_discount_options == 'tax')
		  	{ 
		  		qtyBlock = '';
		  		discountBlock = '';   		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Both
		  	if(tax_discount_options == 'both')
		  	{
		  		qtyBlock = '';
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
	   	   
  	   } 
  	   else if(provision_type == 'service' && $('#param').attr('bill_option') == 0)
  	   {
  	   	 	// None
		  	if(tax_discount_options == 'none')
		  	{ 
		  		discountBlock = '';
	   			taxBlock = '';	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Discount Only
		  	if(tax_discount_options == 'discount')
		  	{  
	   			taxBlock = '';		  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}  	
		  	
		  	// Tax only
		  	if(tax_discount_options == 'tax')
		  	{  
		  		discountBlock = '';   		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Both
		  	if(tax_discount_options == 'both')
		  	{ 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td><td class="description"><textarea placeholder="New item" class="cw desc alignLeft"></textarea></td><td><textarea class="cost cw" placeholder="0.00"></textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="cur_symbol"></span><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
  	   	
  	   }
  	   
  	  
	   $(".item-row:last").after($line_row);

      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
          $(".item-row:last").find('.itemtax').select2({ width: 'element' }).on("select2-close", function(){
              $(".itemtax").trigger('mouseup');
          });
      }
	 
	   if ($(".delete").length > 0) $(".delete").show();
	    
	    // Default tax preferences
		$('.ttax1').html($('#pref_tax1').attr('tax_1name'));
		$('.ttax2').html($('#pref_tax2').attr('tax_2name'));
		
	    bind();
		setCurrency();
		 
        // AJAX For Auto complete
 	 	$('.desc').autocomplete({
			// serviceUrl: items_ajax_url,
			minChars:1,
			delimiter: /(,|;)\s*/, // regex or character								 
			lookup: all_products,
		    onSelect: function (suggestion) {
		        //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
		      
		        var selected_row = $(this).parent().parent();
		       	 
					 var itm_desc = suggestion.value;
					 var itm_unit = suggestion.data;
					 var itm_qty = 1;
					 var itm_tax = suggestion.tax_type; 
			    			
					 selected_row.find(".desc").val(itm_desc);
					 selected_row.find(".cost").val(itm_unit);
					 selected_row.find(".qty").val(itm_qty);
					 selected_row.find(".itemtax option[value='"+itm_tax+"']").attr("selected", "selected").val(itm_tax);
					 selected_row.find(".itemtax").trigger("change");
					 
					 
					 // update other areas			
					 $(".cost").trigger('keyup');
		 			 $(".qty").trigger('keyup');
		 			 $(".cost").trigger('blur');
		 			 $(".qty").trigger('blur');
		 			 $(".disc").trigger('blur');
		 			 $(".disc").trigger('mouseup');
		 			 $(".itemtax").trigger('mouseup');
		 			 $(".itemdisc").trigger('mouseup');
		 			 
		 		 
		    }	// End onSelect	
		    	 
		});
		
		
	  
	 
 });
 
  
  $('#pagebody').on('click', '.delete', function() { 
    $(this).parents('.item-row').remove();
	// update_item_number();
    update_total();
    if ($(".delete").length < 2) $(".delete").hide();
  });
  
  
   	 $(".cost").trigger('keyup');
 	 $(".cost").trigger('blur');
 	 
 	 $(".qty").trigger('keyup');
     $(".qty").trigger('blur');
  
    // Hide delete button on first row
    if($('.firstrow').length){  
       $('.firstrow').first().find('.delete').hide();
    }
 
   $("#date").val(print_today());
  
 	 window.setTimeout(function() {
			update_total();
	}, 200);
	 
 
}); // jquery
