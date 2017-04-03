@extends('layouts.download')

  @section('content')
  
  <?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
 
  <h1 class=""><span class="fa fa-bar-chart-o"></span>Report &raquo; {{ $company->company_name }} </h1>
  
  <h3 class="stat_green">Generated on: {{ \Carbon\Carbon::Now()->timezone($preferences->time_zone)->toDayDateTimeString() }} </h3>
  	<table class="table">
			<thead>
				<th>Over All</th>
		 
			</thead>
			 
			<tr class="tabl_label">
				<td><strong>Total Balance value</strong></td> 
			</tr>
			
			<tr>
				<td><strong>Outstanding:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$all_value_outstanding }}<br /> <strong>Payment received:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$all_value_paid }} <br /> <strong>Quotes:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$all_value_quote }}</td> 
			</tr>						
			 
	</table>
	
	<div id="donutchart" style="width:800px; height:500px"></div>

			               
  
   <table  class="table">
			<thead>
				<th>Last 7 days - <?php echo date('F, Y.', time()); ?></th>
		 
			</thead>
			 
			<tr class="tabl_label">
				<td><strong>Total Balance value</strong></td> 
			</tr>
			
			<tr>
				<td><strong>Outstanding:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$weekly_value_outstanding }}<br /> <strong>Payment received:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$weekly_value_paid }} <br /> <strong>Quotes:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$weekly_value_quote }}</td> 
			</tr>						
			 
	</table>
	 <div id="piechart" style="width:800px; height:500px"></div>

				
	<table class="table">
			<thead>
				<th>This month - <?php echo date('F, Y.', time()); ?></th>
		 
			</thead>
			 
			<tr class="tabl_label">
				<td><strong>Total Balance value</strong></td> 
			</tr>
			
			<tr>
				<td><strong>Outstanding:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$monthly_value_outstanding }}<br /> <strong>Payment received:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$monthly_value_paid }} <br /> <strong>Quotes:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$monthly_value_quote }}</td> 
			</tr>						
			 
	</table>
	
	
	<table class="table">
			<thead>
				<th>This year - <?php echo date('Y.', time()); ?></th>
		 
			</thead>
			  
			<tr class="tabl_label">
				<td><strong>Total Balance value</strong></td> 
			</tr>
			
			<tr>
				<td><strong>Outstanding:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$yearly_value_outstanding }}<br /> <strong>Payment received:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$yearly_value_paid }} <br /> <strong>Quotes:</strong> {{ AppHelper::dumCurrencyCode($preferences->currency_code).$yearly_value_quote }}</td> 
			</tr>						
			 
	</table>
 
  @stop

  @section('footer')
  
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart1);
      function drawChart1() {
        var data1 = google.visualization.arrayToDataTable([
          ['Account activity', 'Amount'],
          ['Outstanding', {{ $all_value_outstanding }}],
          ['Payment received',  {{ $all_value_paid }}],
          ['Expenses',  2000] 
        ]);

        var options1 = {
          title: 'Performance Overview',
          pieHole: 0.5,
        };

        var chart1 = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart1.draw(data1, options1);
      }
      
 
     // pie
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data2 = google.visualization.arrayToDataTable([
          ['Last 7 days account activity', 'Amount'],
          ['Outstanding', {{ $weekly_value_outstanding }}],
          ['Payment received',  {{ $weekly_value_paid }}],
          ['Quotes',  {{ $weekly_value_quote }} ] 
        ]);

        var options2 = {
          title: 'Last 7 days activities'
        };

        var chart2 = new google.visualization.PieChart(document.getElementById('piechart'));
        chart2.draw(data2, options2);
      }
      
    </script>



  @stop
