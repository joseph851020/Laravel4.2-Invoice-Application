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
  		   
		  appliedDisc = $.trim(row.find('select.itemdisc option:selected').val());  
		  appliedDiscValue = $.trim(rowDiscount);  
		  price_excluding_discount = row.find('.cost').val() * rowQty;  
		  price_excluding_tax = row.find('.cost').val() * rowQty;
		  
		  if(appliedDisc == 1){
		  	 appliedDiscValue = price_excluding_discount * appliedDiscValue / 100;
		  }else if(appliedDisc == 2){
		  	appliedDiscValue = appliedDiscValue;
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
		  appliedDisc = $.trim(row.find('select.itemdisc option:selected').val());  
		  appliedDiscValue = $.trim(rowDiscount);  
		  price_excluding_discount = row.find('.cost').val() * rowQty;  
		  price_excluding_tax = row.find('.cost').val() * rowQty;
		  
		  if(appliedDisc == 1){
		  	 appliedDiscValue = price_excluding_discount * appliedDiscValue / 100;
		  }else if(appliedDisc == 2){
		  	appliedDiscValue = appliedDiscValue;
		  }else{
		  	appliedDiscValue = 0;
		  }
	 
		  price = (price_excluding_tax - appliedDiscValue)  + price_excluding_tax * appliedTax / 100;
  
  	}
    
 
  //console.log("Applied Discount = "+appliedDisc + "and AppliedValue = "+appliedDiscValue);
  //console.log("Applied Tax = "+appliedTax);
 
  
  
  
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


// unblock when ajax activity stops 
// $(document).ajaxStop($.unblockUI); 

$(document).ready(function() {
	

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
		
		$('.issuedate').pickadate({
    		format: 'dd/mm/yyyy',
		    formatSubmit: 'yyyy/mm/dd'
		});
		
		$('.duedate').pickadate({
    		format: 'dd/mm/yyyy',
		    formatSubmit: 'yyyy/mm/dd'
		});
		
 
	
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
				 
			 // var itm =  row.find('.itm').val().replace(",","__");
			 var desc =  row.find('.desc').val().replace(",","__");
			  desc =  desc.replace("&","__amp__");
			 var unit_cost =  row.find('.cost').val();
			 var qty =  row.find('.qty').val();
			 var line_tax =  row.find('select.itemtax option:selected').val();
			 var line_tax_value = Number(row.find('.hide-line-tax').text());
			 var line_discount = row.find('select.itemdisc option:selected').val();
			 var line_discount_value =  Number(row.find('.hide-line-discount').text());
			 
 			 var itm_price = row.find('.price').text();
			 var delimiter = ' | ';

			// eval("var item_" + num + "= new Array();");
			
			// Only Insert if Somethin is in Description
			if($.trim($(this).find('.desc').val()) != "" && $.trim($(this).find('.desc').val()) != '-'){
				
				 var we = window["item_" + num] = new Array();
				 we = [1, desc,unit_cost,qty,line_tax,line_tax_value,line_discount,line_discount_value,itm_price,delimiter];
				 // we = [itm,desc,unit_cost,qty,line_tax,line_tax_value,line_discount,line_discount_value,itm_price,delimiter];
				// add them to list
				items.push(we);
			}
		   
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

		 // Set var variable to keep track of description if empty
		 var err = false;
		 $('.item-row').each(function(i){	
		 	
		 	if($.trim($(this).find('.desc').val()) == "" || $.trim($(this).find('.desc').val()) == '-'){
				err = true;
			}
		 });
		 
		 if(err == true){
		 	alert('You have a line with an empty description');
			return false;
		 }
		
		
		var user_id = $('#user_id').val();
		var inv_note = $('.notetext').val();

		var cur_val = currency_id;
		var issuedate = $('#issuedate').val();
		var duedate = $('#duedate').val();

		var discount_val = roundNumber(Number($(".c_discount").html()), 2);
		var tax_val = roundNumber(Number($(".c_vat").html()), 2);
		var subtotal = roundNumber(Number($(".c_subtotal").html()), 2);
		var balance_due =  roundNumber(Number($(".due").html()), 2);
		
		var invoice_subj = $('#inv_subject').val();
		
		var enable_discount = $('#param').attr('enable_discount');
		var enable_tax = $('#param').attr('enable_tax');

		// Data String
		var invoiceData = "invoice_subj="+invoice_subj+"&cl_id="+cl_id+"&data="+items+"&client_company="+client_company+"&user_id="+user_id+"&discount_val="+discount_val+"&tax_val="+tax_val+"&cur_val="+cur_val+"&inv_note="+inv_note+"&issue_date="+issuedate+"&due_date="+duedate+"&subtotal="+subtotal+"&balance_due="+balance_due+'&enable_discount='+enable_discount+'&enable_tax='+enable_tax+'&tenantID='+tenantID;
		
		
		// overlay
		//$.blockUI({ message: '<h1><img src="assets/images/bigrotation.gif" /> Just a moment...</h1>' }); 
		
		 $.blockUI({ 
		 	message: '<h1><img src="../assets/img/bigrotation.gif" />  Just a moment...</h1>',
		 	overlayCSS: { backgroundColor: '#fff' },
		 	css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#fff', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .9, 
            color: '#5e5e5e',
            zIndex: 99999
        	}
        
        }); 
 
       
		
		// Assign handlers immediately after making the request,
		// and remember the jqxhr object for this request
		var jqxhr = $.ajax({ url: "./store", 
							 type: "POST",	
							 data: invoiceData
			})
			.success(function() {
			  //
			  /* $(".processing").delay(5000).fadeTo(400,0.1,function() //start fading the messagebox
				   {
					//add message and change the class of the box and start fading					
					$(".processing").css({"display":"none"});
					$.modal.close();
					

				}); */
				 setTimeout($.unblockUI, 2000);
				 setTimeout(showAll, 2000);
					
			})
			.error(function() { alert("error creating invoice"); })
			.complete(function() { 
				
				// alert("Invoice created successfully");		

		     });
		
		// perform other work here ...
		
		// Set another completion function for the request above
		//jqxhr.complete(function(){ alert("second complete"); });
		
		
		return false;
	}); // End Submit button
	
	function showAll(){
		 window.location = "../invoices"; 
	}
	
	function showAllInvoices(){
		 window.location = "../invoices"; 
	}
	
	function showAllQuotes(){
		 window.location = "../quotes"; 
	}
	
	
	
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
	
	
	
	
	
	$("#prorata_due_date").change(function(){
	   $('#duedate').val(addDays(Number($.trim($('#prorata_due_date option:selected').attr('value'))), $.trim($('#issuedate').val()))); 
	});
		
	
	// Update Due Date
	$('#prorata_due_date').live('blur',function(){
		 $('#duedate').val(addDays(Number($.trim($('#prorata_due_date option:selected').attr('value'))), $.trim($('#issuedate').val()))); 
	});

	
	
  $('input').click(function(){
    $(this).select();
  });
	
  $("#paid").keyup(update_balance);
   
  $("#addrow").live('click', function(){
	 
	  var $line_row; 
	 
	  var provision_type = $('#param').attr('provision_type');
	  var tax_discount_options = $('#param').attr('tax_discount_options');
	  
	  var qtyBlock = '<td><textarea class="qty cw">1</textarea></td>';
	  var discountBlock = '<td class="disc_holder"><input class="disc" value="0" name="" /><select class="itemdisc"><option value="0"> - </option><option value="1">%</option><option value="2">Flat</option></select></td>';
	  var taxBlock = '<td><select class="itemtax cw"><option value="0"> - </option><option class="ttax1" value="1"></option><option class="ttax2" value="2"></option></select></td>';
	  
	  var qtyNoneBlock = '<td><textarea class="qty cw">1</textarea></td>';
	  var discountNoneBlock = '<td class="disc_holder makeNoneDiscount"><input class="disc" value="0" name="" /><select class="itemdisc"><option value="0"> - </option><option value="1">%</option><option value="2">Flat</option></select></td>';
	  var taxNoneBlock = '<td class="makeNoneTax"><select class="itemtax cw"><option value="0"> - </option><option class="ttax1" value="1"></option><option class="ttax2" value="2"></option></select></td>';
	  
	  
	  var discountNoneAfterBlock = '<td class="disc_holder makeNoneDiscountAfter"><input class="disc" value="0" name="" /><select class="itemdisc"><option value="0"> - </option><option value="1">%</option><option value="2">Flat</option></select></td>';
	  var taxNoneAfterBlock = '<td class="makeNoneTaxAfter"><select class="itemtax cw"><option value="0"> - </option><option class="ttax1" value="1"></option><option class="ttax2" value="2"></option></select></td>';
	   
  
	   /****  ADD ROW PRODUCT  *****/   
	   if(provision_type == 'product')
	   {  
		   	// None
		  	if(tax_discount_options == 'none')
		  	{
		  		discountBlock = discountNoneBlock;
	   			taxBlock = taxNoneBlock;	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Discount Only
		  	if(tax_discount_options == 'discount')
		  	{ 
	   			taxBlock = taxNoneBlock;	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}  	
		  	
		  	// Tax only
		  	if(tax_discount_options == 'tax')
		  	{ 
		  		discountBlock = discountNoneBlock;   		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Both
		  	if(tax_discount_options == 'both')
		  	{
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
	  	 
	   }
     
    
    
	   /****  ADD ROW SERVICE  *****/
	   if(provision_type == 'service')
	   {
			 
	   	    // Discount was checked
		   	if($('.discount_enabler').is(":checked"))
		   	{ 
		   	   // None
			  	if(tax_discount_options == 'none')
			  	{
			  		qtyBlock = '';
			  		discountBlock = discountNoneBlock;
		   			taxBlock = taxNoneBlock;	  		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	
			  	// Discount Only
			  	if(tax_discount_options == 'discount')
			  	{ 
			  		qtyBlock = '';
		   			taxBlock = '';
		   			discountBlock = discountNoneAfterBlock; 		  		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}	
			  	
			  	// Tax only
			  	if(tax_discount_options == 'tax')
			  	{ 
			  		qtyBlock = '';
			  		discountBlock = '';		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	
			  	// Both
			  	if(tax_discount_options == 'both')
			  	{
			  		qtyBlock = '';
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	 
			} // Discount was checked
			 
			
			
			// Tax was checked
			if($('.tax_enabler').is(":checked"))
		   	{ 
		   	   // None
			  	if(tax_discount_options == 'none')
			  	{
			  		qtyBlock = '';
			  		discountBlock = discountNoneBlock;
		   			taxBlock = taxNoneBlock;	  		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	
			  	// Discount Only
			  	if(tax_discount_options == 'discount')
			  	{ 
			  		qtyBlock = '';
		   			taxBlock = '';	   			 		  		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}	
			  	
			  	// Tax only
			  	if(tax_discount_options == 'tax')
			  	{ 
			  		qtyBlock = '';
			  		discountBlock = '';	
			  		taxBlock = taxNoneAfterBlock;	 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	
			  	// Both
			  	if(tax_discount_options == 'both')
			  	{
			  		qtyBlock = '';
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	 
			  	
			 } // Tax was checked
			 
		 
			// Discount and Tax checked
			if($('.discount_enabler').is(":checked") && $('.tax_enabler').is(":checked"))
		   	{ 
		   	   // None
			  	if(tax_discount_options == 'none')
			  	{
			  		qtyBlock = '';
			  		discountBlock = discountNoneBlock;
		   			taxBlock = taxNoneBlock;	  		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	
			  	// Discount Only
			  	if(tax_discount_options == 'discount')
			  	{ 
			  		qtyBlock = '';
		   			taxBlock = '';
		   			discountBlock = discountNoneAfterBlock; 		  		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}	
			  	
			  	// Tax only
			  	if(tax_discount_options == 'tax')
			  	{ 
			  		qtyBlock = '';
			  		discountBlock = '';		 
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	
			  	// Both
			  	if(tax_discount_options == 'both')
			  	{
			  		qtyBlock = '';
			  		discountBlock = discountNoneAfterBlock; 
			  		taxBlock = taxNoneAfterBlock;
			  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
			  	}
			  	 
			}
			
			
			// Default
			// Both Tax and discount not checked
		  	if(tax_discount_options == 'none')
		  	{
		  		qtyBlock = '';
		  		discountBlock = discountNoneBlock;
	   			taxBlock = taxNoneBlock;	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Discount Only
		  	if(tax_discount_options == 'discount')
		  	{ 
		  		qtyBlock = '';
	   			taxBlock = ''; 	
	   			discountBlock = discountNoneBlock;	  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}	
		  	
		  	// Tax only
		  	if(tax_discount_options == 'tax')
		  	{ 
		  		qtyBlock = '';
		  		discountBlock = discountNoneBlock;  		 
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		  	
		  	// Both
		  	if(tax_discount_options == 'both')
		  	{
		  		qtyBlock = '';
		  		$line_row = '<tr class="item-row"><td class="item-name"><div class="delete-wpr"><a class="item_selector_icon fa fa-pencil" href="javascript:;" title="Add from item">&nbsp;</a><a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a><div class="item-container"><div class="item-container-inner"></div></div></div></td><td class="description"><textarea class="cw desc alignLeft"> - </textarea></td><td><textarea class="cost cw">0.00</textarea></td>' + qtyBlock + discountBlock + taxBlock+ '<td><span class="price">0.00</span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td></tr>'; 
		  	}
		 
  
  	   } // Provision type = service
 
     
	   $(".item-row:last").after($line_row);
	    
	   if ($(".delete").length > 0) $(".delete").show();
	    
	    // Default tax preferences
		$('.ttax1').html($('#pref_tax1').attr('tax_1name'));
		$('.ttax2').html($('#pref_tax2').attr('tax_2name'));
		
	    bind();
		setCurrency();
	
 });
 
  
  bind();
  
  
  $(".delete").live('click',function(){
    $(this).parents('.item-row').remove();
	// update_item_number();
    update_total();
    if ($(".delete").length < 2) $(".delete").hide();
  });
   
   
  /** TOGGLE DISCOUNT **/
 
   // If Checked add the discount column
   //set initial state.
   // $('.discount_enabler').val($(this).is(':checked'));

    $('.discount_enabler').live('change', function(){
    	
        if($(this).is(":checked")) {
           // var returnVal = confirm("Are you sure?");
           // $(this).attr("checked", returnVal);
           // alert('now checked');
           
	           if($('.makeNoneDiscount').length > 0)
		       { 
		       	
		       	   // (SCENARIO 1 => Template default has no discount and no tax) 
		       	   if($('#param').attr('tax_discount_options') == 'none')
		       	   {
			       	   	$('#param').attr('enable_discount', 1);
		       			$('#param').attr('tax_discount_options', 'discount');
		       			 
		       			$('.makeNoneDiscount').css({'display': 'table-cell'});
		       			$('.makeNoneDiscountAfter').css({'display': 'table-cell'});
		       			$('.makeNoneSubDiscount').css({'display': 'table-row'});
		       		 
		       			// noneBlocks.removeClass('makeNone');
		       			update_price();
		       			bind();
		       			update_total();
		       	   }
		       	   
		       	   // (SCENARIO 2 => Template default has Tax but no discount)
		       	   else if($('#param').attr('tax_discount_options') == 'tax')
		       	   {
		       	   		$('#param').attr('enable_discount', 1);
		       			$('#param').attr('tax_discount_options', 'both');
		       			 
		       			$('.makeNoneDiscount').css({'display': 'table-cell'});		       			
		       			$('.makeNoneSubDiscount').css({'display': 'table-row'});
		       		 
		       			// noneBlocks.removeClass('makeNone');
		       			update_price();
		       			bind();
		       			update_total();		       	   	
		       	   }
		       	  
	       		 
	           }
	          
           
        }
        else
        { 
        	
        	 // (SCENARIO 1 => Template has only discount) 
	       	   if($('#param').attr('tax_discount_options') == 'discount')
	       	   {
	        	    $('#param').attr('enable_discount', 0);
	        	    $('#param').attr('tax_discount_options', 'none');
	        	    
	        	    $('.makeNoneDiscount').css({'display': 'none'});
	        	    $('.makeNoneDiscountAfter').css({'display': 'none'});
	       			$('.makeNoneSubDiscount').css({'display': 'none'});
	       			 
	       			update_price();
	       			bind();
	       			update_total();
	       		}
	       		// (SCENARIO 2 => Template has only both)
	       	   else if($('#param').attr('tax_discount_options') == 'both')
	       	   {
	       	   		$('#param').attr('enable_discount', 0);
	        	    $('#param').attr('tax_discount_options', 'tax');
	        	    
	        	    $('.makeNoneDiscount').css({'display': 'none'});
	       			$('.makeNoneSubDiscount').css({'display': 'none'});
	       		 
	       			update_price();
	       			bind();
	       			update_total();
	       	   	
	       	   }
       		    	 
        	
        } // End If Checked
        
    });
    
  
   
   /** TOGGLE TAX **/
 
   // If Checked add the discount column
   //set initial state.
   // $('.discount_enabler').val($(this).is(':checked'));

    $('.tax_enabler').live('change', function(){
    	
        if($(this).is(":checked")) {
     
	           if($('.makeNoneTax').length > 0)
		       { 
		       	
		       	   // (SCENARIO 1 => Template default has no discount and no tax) 
		       	   if($('#param').attr('tax_discount_options') == 'none')
		       	   {
			       	   	$('#param').attr('enable_tax', 1);
		       			$('#param').attr('tax_discount_options', 'tax');
		       			 
		       			$('.makeNoneTax').css({'display': 'table-cell'});
		       			$('.makeNoneTaxAfter').css({'display': 'table-cell'});
		       			$('.makeNoneSubTax').css({'display': 'table-row'});
		       		 
		       			// noneBlocks.removeClass('makeNone');
		       			update_price();
		       			bind();
		       			update_total();
		       	   }
		       	   
		       	   // (SCENARIO 2 => Template default has Tax but no discount)
		       	   else if($('#param').attr('tax_discount_options') == 'discount')
		       	   {
		       	   		$('#param').attr('enable_tax', 1);
		       			$('#param').attr('tax_discount_options', 'both');
		       			 
		       			$('.makeNoneTax').css({'display': 'table-cell'});		       			
		       			$('.makeNoneSubTax').css({'display': 'table-row'});
		       		 
		       			// noneBlocks.removeClass('makeNone');
		       			update_price();
		       			bind();
		       			update_total();		       	   	
		       	   }
		       	  
	       		 
	           }
	          
           
        }
        else
        { 
        	
        	 // (SCENARIO 1 => Template has only discount) 
	       	   if($('#param').attr('tax_discount_options') == 'tax')
	       	   {
	        	    $('#param').attr('enable_tax', 0);
	        	    $('#param').attr('tax_discount_options', 'none');
	        	    
	        	    $('.makeNoneTax').css({'display': 'none'});
	        	    $('.makeNoneTaxAfter').css({'display': 'none'});
	       			$('.makeNoneSubTax').css({'display': 'none'});
	       			 
	       			update_price();
	       			bind();
	       			update_total();
	       		}
	       		// (SCENARIO 2 => Template has only both)
	       	   else if($('#param').attr('tax_discount_options') == 'both')
	       	   {
	       	   		$('#param').attr('enable_discount', 0);
	        	    $('#param').attr('tax_discount_options', 'discount');
	        	    
	        	    $('.makeNoneTax').css({'display': 'none'});
	       			$('.makeNoneSubTax').css({'display': 'none'});
	       		 
	       			update_price();
	       			bind();
	       			update_total();
	       	   }
       		    	 
        } // End If Checked
        
    });
    
   
   
  
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
		var box = $(this).parent().find('.item_selector .item_sel');
		
		$('.item_sel').change(function(event) {
									
			// var itm_name = $('.item_sel option:selected').attr('item_name');
			var itm_desc = $('.item_sel option:selected').attr('item_description');	
			var itm_unit = $('.item_sel option:selected').attr('item_unit_price');
			var itm_qty = 1;
			var itm_tax = $('.item_sel option:selected').attr('item_tax');
			
		   // Update this line item only		
			if(item_row.hasClass('selectedRow')){
				 var t_desc = item_row.find(".desc");
				 var t_unit = item_row.find(".cost");
				 var t_qty = item_row.find(".qty");
				 item_row.find(".itemtax option[selected]").removeAttr("selected");
				 var t_tax = item_row.find(".itemtax option[value='"+itm_tax+"']").attr("selected", "selected");
				// $(".itemtax option[selected]").removeAttr("selected");
				// $(".itemtax option[value='"+itm_tax+"']").attr("selected", "selected");
	
				t_desc.val(itm_desc);
				t_unit.val(itm_unit);
				t_qty.val(itm_qty);
				//t_tax.val(itm_tax);
			}
							
			// update other areas			
			$(".cost").trigger('keyup');
 			$(".qty").trigger('keyup');
 			$(".cost").trigger('blur');
 			$(".qty").trigger('blur');
 			$(".disc").trigger('blur');
 			$(".disc").trigger('mouseup');
 			$(".itemtax").trigger('mouseup');
 			$(".itemdisc").trigger('mouseup');
 			
 		// fade in after selection	
		$('.item_selector').css({"display":"block", opacity:0.0}); 
 			
			update_tax();
			update_discount();
	
			return false;
			
	   }); // End .item_sel Click
				  
								  
	}); // End Item selector 
	
	//  Hide Item selector when description textarea is clicked
	$('textarea.desc').live('click',function(){
		$('.item_selector').css({"display":"none", opacity:0.0}); 
	});
	$('textarea.desc').live('blur',function(){
		$('.item_selector').css({"display":"block", opacity:0.0}); 
	});
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
}); // jquery
