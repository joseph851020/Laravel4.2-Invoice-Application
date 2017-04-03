@extends('layouts.admin')

	@section('content')
	 
	  <h1>Admin Dashboard</h1>
	  
	  <div class="dashcover">
	  	
		  <div class="stats">

              <div id="all_subscription_volume">

              </div><!-- END all_subscription_volume -->

              <div id="all_subscription_this_month">

              </div><!-- END all_subscription_volume -->
		  	
		  </div><!-- END Stats -->
		  
		  
		  <div class="stats">
		  	
		  	<h2>Sales</h2>

              <div class="summary_counts">
                  <div class="">
                      <h4>Total</h4>
                      <small>£{{ $total_sales }}</small>
                  </div>

                  <div class="">
                      <h4>Last 7 days</h4>
                      <small>£{{ $total_sales_this_week }}</small>
                  </div>

                  <div>
                      <h4>{{ date("M, Y", time()) }}</h4>
                      <small>£{{ $total_sales_this_month }}</small>
                   </div>

                  <div class="last">
                      <h4>Year {{ date("Y", time()) }}</h4>
                      <small>£{{ $total_sales_this_year }}</small>
                   </div>

              </div>
		  	
		  </div><!-- END Stats -->
	  
	  </div><!-- END dashcover -->
			 
	@stop


@section('footer')

<script src="{{ URL::asset('assets/js/highchart/highcharts.js') }}"></script>
<script src="{{ URL::asset('assets/js/highchart/highcharts-3d.js') }}"></script>
<script src="{{ URL::asset('assets/js/highchart/modules/exporting.js') }}"></script>

<script>

    $(function(){

     // Radialize the colors
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        });

        // Build the chart
        $('#all_subscription_volume').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'All subscriptions - Active({{ $total_tenants_this_month }})'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br /> <b>Total</b>: {point.y}'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} % <br /> <b>Total</b>: {point.y}',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        },
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Subscriptions',
                data: [

                    ['Super Premium accounts', {{ $total_super_premium == 0 ? 0.001 : $total_super_premium }} ],
                    {
                        name: 'Premium accounts',
                        y: {{ $total_premium == 0 ? 0.001 : $total_premium }}  ,
                        sliced: true,
                        selected: true
                    },
                    ['Starter accounts',    {{ $total_starter == 0 ? 0.001 : $total_starter }} ]

                ]
            }]
        });




        ///////////////////////////

        $('#all_subscription_this_month').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Subscriptions for {{ date("M, Y", time()) }}  - Active({{ $total_tenants }})'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <br /> <b>Total</b>: {point.y}'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 55,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} % <br /> <b>Total</b>: {point.y}',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        },
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Subscriptions',
                data: [
                    ['Super premium',   {{ $total_super_premium_this_month == 0 ? 0.001 : $total_super_premium_this_month }}  ],

                    {
                        name: 'Premium',
                        y: {{ $total_premium_this_month == 0 ? 0.001 : $total_premium_this_month }},
                        sliced: true,
                        selected: true
                    },
                    ['Starter',    {{ $total_starter_this_month == 0 ? 0.001 : $total_starter_this_month }}  ]
                ]
            }]
        });


    });

</script>

@stop
