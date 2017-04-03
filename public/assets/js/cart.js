/**
Copyright © Integrity Invoice, All rights reserved.
Integrity Invoice is a service of Media Divinity Design Ltd.
**/

$(function(){
	var duration, duration1, duration2, duration3, duration4, sel_plan, sel_plan_val, item_count, per_savings, savings_amount, 
	cart_saving, cum_total, total, thisplan, item_number, amount, item_name, lineitem, sel_itm_number, apply_upgrade_amount, applied_upgrade_amount; 
	
	sel_plan_val = parseFloat($('#thisplan_price').val());
	cum_total = $('.cum_total');
	savings_amount = $('.savings_amount');
	cart_savings = $('.cart_savings');

	item_count = $('.item_count');
	item_count.html(1);
	cum_total.html(sel_plan_val);
	total = $('.cart_amount');
	
	thisplan = $('#thisplan').val();
	item_number = $('#item_number');
	amount = $('#amount');
	item_name = $('#item_name');
	
	apply_upgrade_amount = $('#apply_upgrade_amount').val();
	applied_upgrade_amount = $('#applied_upgrade_amount').val();
	
	
	function do_calculate(){

		sel_itm_number = parseInt($("input:radio[name=subcr_duration]:checked").val());
		sel_plan = parseInt($("input:radio[name=subcr_duration]:checked").attr('lineitem'));
		per_savings = parseInt($("input:radio[name=subcr_duration]:checked").attr("per_savings"));
		
		cart_savings.html("Discount (" + per_savings + "%) = ");
		// alert(per_savings);
		item_count.html(sel_plan);
			
			switch(sel_plan){
			case 1:
				item_count.html(1);
				cum_total.html(sel_plan_val * item_count.html());
				savings_amount.html((cum_total.html() * per_savings / 100).toFixed(2));

				if(apply_upgrade_amount == "yes"){
					total.html((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) +  (parseFloat(applied_upgrade_amount))).toFixed(2));
				}else{
					total.html((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
				}
				
				item_name.val("Integrity " + thisplan +" "+ item_count.html() + " months subscription");
				item_number.val(sel_itm_number);
				
				/*(thisplan == "Premium plan" && sel_plan == "1") ? item_number.val(9): item_number.val(2);
				(thisplan == "Premium plan" && sel_plan == 2) ? item_number.val(10): item_number.val(0);
				(thisplan == "Premium plan" && sel_plan == 3) ? item_number.val(11): item_number.val(0);
				(thisplan == "Premium plan" && sel_plan == 4) ? item_number.val(12): item_number.val(0);*/
				
				
			break;
			
			case 2:
				item_count.html(3);
				cum_total.html(sel_plan_val * item_count.html());
				savings_amount.html((cum_total.html() * per_savings / 100).toFixed(2));
				
				if(apply_upgrade_amount == "yes"){
					total.html((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
				}else{
					total.html((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
				}
				
				item_name.val("Integrity basic 3 months subscription");
				item_name.val("Integrity " + thisplan +" "+ item_count.html() + " months subscription");
				item_number.val(sel_itm_number);
			break;
			
			case 3:
				item_count.html(6);
				cum_total.html(sel_plan_val * item_count.html());
				savings_amount.html((cum_total.html() * per_savings / 100).toFixed(2));
				
				if(apply_upgrade_amount == "yes"){
					total.html((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
				}else{
					total.html((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
				}
				
				item_name.val("Integrity basic 6 months subscription");
				item_name.val("Integrity " + thisplan +" "+ item_count.html() + " months subscription");
				item_number.val(sel_itm_number);
			break;
			
			case 4:
				item_count.html(12);
				cum_total.html(sel_plan_val * item_count.html());
				savings_amount.html((cum_total.html() * per_savings / 100).toFixed(2));
				
				if(apply_upgrade_amount == "yes"){
					total.html((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - (parseFloat(savings_amount.html())) + (parseFloat(applied_upgrade_amount))).toFixed(2));
				}else{
					total.html((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
					amount.val((parseFloat(cum_total.html()) - parseFloat(savings_amount.html())).toFixed(2));
				}
				
				item_name.val("Integrity basic 12 months subscription");
				item_name.val("Integrity " + thisplan +" "+ item_count.html() + " months subscription");
				item_number.val(sel_itm_number);
			break;
			
			default:
			break;
		}
	
		return true;
		
	} // End Do calculate function
	
	do_calculate();
		
	$("input:radio[name=subcr_duration]").bind("change", function(){
		do_calculate();
	}); // End Radio select change event
	
	$('#checkout_pay').live('click', function(){

		if (!$("input:radio[name=subcr_duration]:checked").val()) {
		   alert('Please select billing cycle!');
			return false;
		}
		
		if($("#agree_terms").is(":checked")){ }else{
			alert("You must agree to the terms by checking the box.");
			return false;
		}
		
		$("#agree_terms").change(function(){									  
			if($("#agree_terms").is(":checked")){ }else{
			alert("You must agree to the terms by checking the box.");
			return false;
			}
		});
		
		return true;
											  
	}); // End click;
	
	
	function setplan($param1, $param2){
		if($param1 == "Premium plan" && $param2 == 1){
			return 1;
		}
	}
	
		
});