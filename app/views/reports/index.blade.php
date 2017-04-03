@extends('layouts.default')

  @section('content')
 <div class="for_report">
  <h1 class=""><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a> &raquo; Report Summary</h1>
  
  <div id="invoice_expense_yearly">
  	
  </div><!-- END invoice_expense_yearly -->
 
  
  <div id="profit_and_loss">
  	
  </div><!-- END profit_and_loss -->
  
  
  <div id="compare_activities">
  	
  </div><!-- END compare_activities -->
 
    
 </div><!-- End for_report -->
  @stop

  @section('footer')
  
  <script src="{{ URL::asset('assets/js/highchart/highcharts.js') }}"></script>
  <script src="{{ URL::asset('assets/js/highchart/highcharts-3d.js') }}"></script> 
  <script src="{{ URL::asset('assets/js/highchart/modules/exporting.js') }}"></script> 
  
  <script type="text/javascript">
	$(function () {
    
      /// INVOICES AND EXPENSE ALL YEAR ///
      ////////////////////////////////////////////////////////
      $('#invoice_expense_yearly').highcharts({
                chart: {
                    type: 'column',
                    margin: 75,
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        beta: 25,
                        depth: 70
                    }
                },
                title: {
                    text: 'Monthly Income and Expenses'
                },
                subtitle: {
                    text: 'From Last 6 Months'
                },
                plotOptions: {
                    column: {
                        depth: 12
                    }
                },
                xAxis: {
                    categories: [ {{ $income_and_expenses['last5months']['monthtitle'] }} , {{ $income_and_expenses['last4months']['monthtitle'] }} ,{{ $income_and_expenses['last3months']['monthtitle'] }} , {{ $income_and_expenses['last2months']['monthtitle'] }} , {{ $income_and_expenses['lastmonth']['monthtitle'] }} , {{ $income_and_expenses['thismonth']['monthtitle'] }} ]
            },
            yAxis: {
            opposite: false,
                title: {
                text: 'Total'
            }
        },
        tooltip: {
            valuePrefix: '{{ $cur_symbol }}'
        },
        series: [{
            name: 'Expenses',
            data: [ {{ $income_and_expenses['last5months']['monthexpense'] }} , {{ $income_and_expenses['last4months']['monthexpense'] }} , {{ $income_and_expenses['last3months']['monthexpense'] }} , {{ $income_and_expenses['last2months']['monthexpense'] }} , {{ $income_and_expenses['lastmonth']['monthexpense'] }} , {{ $income_and_expenses['thismonth']['monthexpense'] }} ],
        color: '#c5e0e9'
        }, {
            name: 'Income',
                data: [ {{ $income_and_expenses['last5months']['monthincome'] }} , {{ $income_and_expenses['last4months']['monthincome'] }} , {{ $income_and_expenses['last3months']['monthincome'] }} , {{ $income_and_expenses['last2months']['monthincome'] }} , {{ $income_and_expenses['lastmonth']['monthincome'] }} , {{ $income_and_expenses['thismonth']['monthincome'] }} ],
        color: '#e0ebaf'
        }]
    });




    /// PROFIT AND LOSS ALL YEAR ///
      ////////////////////////////////////////////////////////
      
      $('#profit_and_loss').highcharts({
            title: {
                text: 'Monthly Profit & Loss',
                x: -20 //center
            },
            subtitle: {
                text: 'From Last 6 Months',
                x: -20
            },
            xAxis: {
                categories: [ {{ $profit_loss_data['last5months']['monthtitle'] }} , {{ $profit_loss_data['last4months']['monthtitle'] }} ,{{ $profit_loss_data['last3months']['monthtitle'] }} , {{ $profit_loss_data['last2months']['monthtitle'] }} , {{ $profit_loss_data['lastmonth']['monthtitle'] }} , {{ $profit_loss_data['thismonth']['monthtitle'] }} ]
            },
            yAxis: {
                title: {
                    text: 'Total'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valuePrefix: '{{ $cur_symbol }}'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Profit / Loss',
                color: Highcharts.getOptions().colors[10],
                data: [ {{ $profit_loss_data['last5months']['monthvalue'] }} , {{ $profit_loss_data['last4months']['monthvalue'] }} , {{ $profit_loss_data['last3months']['monthvalue'] }} , {{ $profit_loss_data['last2months']['monthvalue'] }} , {{ $profit_loss_data['lastmonth']['monthvalue'] }} , {{ $profit_loss_data['thismonth']['monthvalue'] }} ]
            }]
        });
      
    
	});
 
 </script>
 
    <script>
	
		$(function(){
		 
	 	   if($('#appmenu').length > 0){
		 
		  		$('.report_all_menu').addClass('selected_group'); 		 
		  		$('.menu_financial_summary').addClass('selected');		  		
		  		$('.report_all_menu ul').css({'display': 'block'});
			}
		 
		});
		
	</script>
   
    
  @stop
