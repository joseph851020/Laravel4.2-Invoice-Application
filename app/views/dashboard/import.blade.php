@extends('layouts.default')

	@section('content')
	 
	 <h1>Import data</h1>
	  
   
	    	<?php //echo $this->session->flashdata('operation_error_itemimport'); ?>
	    	
	       <div class="instruction">
	       	<p>Please ensure that your CSV file is the same format as the example item csv provided below. If you need help verifying your format please contact our administrators.</p>
	       	<a class="btn" href="<?php //echo '../sample_data/items.csv'; ?>"><img src="<?php // echo $this->config->item('img_dir').'/icons/csv_text.png'; ?>" alt="" /><span>Download sample Item CSV file</span></a>
	       </div><!-- END Instruction-->
	       
	   
	@stop
	

	@section('footer')
 
	@stop