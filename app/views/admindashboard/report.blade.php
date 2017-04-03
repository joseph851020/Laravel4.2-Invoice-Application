@extends('layouts.admin')

	@section('content')
	 
	  <h1>Admin Repert Summary</h1>
	  
	  <div class="dashcover">
	  	
		  <div class="stats">

              		<div id="all_subscription_volume">

              		</div><!-- END all_subscription_volume -->

              		<div id="all_subscription_this_month">

              		</div><!-- END all_subscription_volume -->
		   </div><!-- END Stats -->
			
	  </div><!-- END dashcover -->
			 
	@stop


@section('footer')

<script src="{{ URL::asset('assets/js/highchart/highcharts.js') }}"></script>
<script src="{{ URL::asset('assets/js/highchart/highcharts-3d.js') }}"></script>
<script src="{{ URL::asset('assets/js/highchart/modules/exporting.js') }}"></script>

<script>

   $('#all_subscription_volume').highcharts
   ({
   	chart: {
            type: 'line'
        },
        title:
        {
            text: 'Monthly Users'
        },
        subtitle:
        {
            text: 'From Last 6 Months'
        },
              xAxis:
       {
         categories: [ {{ $income_and_expenses['last5months']['monthtitle'] }} , {{ $income_and_expenses['last4months']['monthtitle'] }} ,{{ $income_and_expenses['last3months']['monthtitle'] }} , {{ $income_and_expenses['last2months']['monthtitle'] }} 			, {{ $income_and_expenses['lastmonth']['monthtitle'] }} , {{ $income_and_expenses['thismonth']['monthtitle'] }} ]
    },
	yAxis:
	{
	    opposite: false,
	    title:
	    {
	        text: 'Count'
	    },
	    min: 0
	},
	legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
	series: 
        [{
            name: 'Free User',
            data: [ {{ $income_and_expenses['last5months']['monthstart'] }} , {{ $income_and_expenses['last4months']['monthstart'] }} , {{ $income_and_expenses['last3months']['monthstart'] }} , {{ $income_and_expenses['last2months']['monthstart'] }} , 		{{ $income_and_expenses['lastmonth']['monthstart'] }} , {{ $income_and_expenses['thismonth']['monthstart'] }} ]
        }, 
        {
            name: 'Premium User',
                data: [ {{ $income_and_expenses['last5months']['monthpremium'] }} , {{ $income_and_expenses['last4months']['monthpremium'] }} , {{ $income_and_expenses['last3months']['monthpremium'] }} , {{ $income_and_expenses['last2months']['monthpremium'] }} , {{ $income_and_expenses['lastmonth']['monthpremium'] }} , {{ $income_and_expenses['thismonth']['monthpremium'] }} ]
        },
        
        {
            name: 'Super Premium User',
            data: [ {{ $income_and_expenses['last5months']['monthsuperpremium'] }} , {{ $income_and_expenses['last4months']['monthsuperpremium'] }} , {{ $income_and_expenses['last3months']['monthsuperpremium'] }} , {{ $income_and_expenses['last2months']['monthsuperpremium'] }} , {{ $income_and_expenses['lastmonth']['monthsuperpremium'] }} , {{ $income_and_expenses['thismonth']['monthsuperpremium'] }} ]
        }
             
        ]
    });

</script>

@stop
