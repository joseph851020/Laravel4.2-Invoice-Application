@extends('layouts.admin')



	@section('content')



	  <h1>Accounts</h1>



	   <?php if($totalRecords >= 1):  ?>



		<table class="table">

       		<thead>

       			<tr>

       				<th class="sorting"><i class=""></i>Company ID</th>

       				<th class="sorting"><i class=""></i>User Level</th>

       				<th class="sorting tenant_name_width"><i class=""></i>Company Name</th>

       				<th class="sorting"><i class=""></i>Contact person</th>

       				<th class="sorting"><i class=""></i>Email</th>

       				<th class="sorting"><i class=""></i>Signup Date</th>

				<th class="sorting"><i class=""></i>login</th>



       				<th class="sorting"><i class="fa fa-cogs"></i> Action</th>

       			</tr>

       		</thead>



	        <tbody>

				<?php $row = 2; foreach($tenants as $tenant): ?>

				<?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>

				<tr class="{{ $colour }}">

					<td><strong>{{ $tenant->tenantID; }}</strong> <br />

							<?php if($tenant->status == "" || $tenant->status == NULL || $tenant->status == 0): ?>

								<span class="stat_gray"> Inactive</span>

							<?php elseif($tenant->status == 1): ?>

								<span class="stat_green"> Active</span>

							<?php elseif($tenant->status == -1): ?>

								<span class="stat_red"> Suspended</span>

							<?php elseif($tenant->status == -2): ?>

								<span class="stat_orange"> Cancelled {{ $tenant->updated_at->diffForHumans() }}</span>

							<?php endif; ?>

							<?php if($tenant->verified == 0): ?>

							  <span class="stat_red">&amp; unverified</span>

							<?php endif; ?>

						 </td>

					<td>

						<?php if($tenant->account_plan_id== 1): ?>

							<span class="stat_green">Free</span>

						<?php elseif($tenant->account_plan_id== 2): ?>

							<span class="stat_blue">Preuium </span>

						<?php elseif($tenant->account_plan_id== 3): ?>

							<span class="stat_orange">Super Preuium</span>

						<?php endif; ?>





					</td>

					<td>
                        <?php if(!is_null($tenant->company)): ?>
                            <strong class="account_company">{{ $tenant->company->company_name }}</strong>
                            <br />
                            {{ $tenant->company->phone }}
                        <?php endif; ?>
                    </td>
					<td><?php if($tenant->users->first()): ?>{{ $tenant->users->first()->firstname }}  {{ $tenant->users->first()->lastname }}<?php endif; ?></td>

					<td>{{ !is_null($tenant->company) ? $tenant->company->email : "" }}</td>

					<td>{{  $tenant->created_at->diffForHumans() }}</td>

					<td>

						<a class="btn-edit btn-danger" href="{{ URL::to('admin/accounts/'.$tenant->tenantID.'/ImpersonateUser') }}"><i class="fa fa-sign-in"></i> Login</a>

					</td>

					 <td>

					 	<a class="btn-edit" href="{{ URL::to('admin/accounts/'.$tenant->tenantID.'/edit') }}">

						<i class="fa fa-edit"></i> Edit</a>

					 	<a class="btn-edit" href="{{ URL::to('admin/accounts/'.$tenant->tenantID.'/status') }}">

						<i class="fa fa-edit"></i>Status</a>



						<a class="btn-edit btn-danger do_delete_account" tenant="{{ $tenant->tenantID }}" href="{{ URL::to('admin/accounts/'.$tenant->tenantID.'/delete') }}">

						<i class="fa fa-trash-o"></i> Delete</a>

					  </td>

				</tr>

				<?php endforeach; ?>

			</tbody>



       	 </table>



		<div class="simple_search">

			{{ Form::open(array('url' => 'admin/accounts', 'method' => 'GET')) }}

			<input type="text" class="search_term" name="q" value="search..." />

			<input type="submit" class="search_submit" value="Go" />

			<!-- <p class="tinytext">Invoice ID, Client name or item name.</p> -->

			{{ Form::close() }}

		</div><!-- END search -->





	 <?php else: ?>



	 	 <?php if(isset($searchquery) && is_null($searchquery) || $searchquery ==  ""): ?>



	  <!-- NO Client yet -->

     <div class="no_item">



 		<h4>No Accounts yet</h4>



   	</div><!-- End no client -->



   <?php else: ?>

    	<p>No records found in the search result.</p>

    	<a class="btn" href="{{ URL::to('admin_accounts') }}">Back</a>

    <?php endif; ?>



 <?php endif; ?>



		{{ $tenants->links() }}



	@stop
