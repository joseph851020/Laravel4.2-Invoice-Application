@extends('layouts.admin')

@section('content')
<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
<h1><a class="do_previous" href="{{ URL::previous() }}">&nbsp; <i class="fa fa-chevron-left">&nbsp;&nbsp; </i> </a> Extend subscription for <strong>{{ $tenant->company->company_name }}</strong> ({{ $tenant->tenantID }})</h1>


<div id="history_page">

    <div class="longbox">

        <table class="table">
            <thead>
            <tr>
                <th class="sorting client_name_width"><i class=""></i>Plan</th>
                <th class="sorting"><i class=""></i>Valid from</th>
                <th class="sorting"><i class=""></i>Valid to</th>
                <th class="sorting displayNone"><i class=""></i>Payment type</th>
                <th class="sorting"><i class=""></i>Amount paid</th>
                <th class="sorting displayNone"><i class=""></i>Paid on</th>
            </tr>
            </thead>

            <tbody>

            <?php $row = 2; foreach($histories as $history): ?>

                <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
                <tr class="<?php echo $colour; ?>">
                    <td><strong><?php echo AppHelper::get_subscription_plan($history->subscription_type); ?></strong></td>
                    <td><?php echo AppHelper::date_to_text($history->valid_from, $date_format); ?></td>
                    <td><?php echo AppHelper::date_to_text($history->valid_to, $date_format); ?></td>
                    <td class="displayNone"><?php echo $history->payment_system; ?></td>
                    <td><?php echo 'GPB '.$history->amount; ?></td>
                    <td class="displayNone"><?php echo AppHelper::date_to_text($history->created_at, $date_format); ?></td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>


    </div><!-- END longbox -->

</div><!-- END history_page -->


<div class="">

        {{ Form::open(array('url' => 'admin/account/'.$tenant->tenantID.'/process-extend-subscription', 'method' => 'POST')) }}

        <div class="">

            <p>&nbsp;</p>

            <label>Subscription Level</label>
            <select id="" class="sel" name="subscription_level">
                <option <?php echo $tenant->account_plan_id  == 1 ? "selected=selected": ""; ?> value="1">Starter</option>
                <option <?php echo $tenant->account_plan_id  == 2 ? "selected=selected": ""; ?> value="2">Premium</option>
                <option <?php echo $tenant->account_plan_id  == 3 ? "selected=selected": ""; ?> value="3">Super Premium</option>
             </select>

            <label>Extended Duration:</label>
            <select id="duration" name="duration" class="sel">
                <option value="">- select -</option>
                <option value="1 week">1 week</option>
                <option value="2 weeks">2 weeks</option>
                <option value="1 month">1 month</option>
                <option value="2 months">2 months</option>
                <option value="3 months">3 months</option>
                <option value="6 months">6 months</option>
                <option value="1 year">1 year</option>
                <option value="1 year 6 months">1 year 6 months</option>
                <option value="2 years">2 years</option>
                <option value="2 years 6 months">2 years 6 months</option>
                <option value="3 years">3 years</option>
                <option value="4 years">4 years</option>
                <option value="5 years">5 years</option>
            </select>

            <p>&nbsp;</p>

            <label>Notify User of extension: <input type="checkbox" name="notify" id="" checked value="yes" /></label>
        </div>

        <div class="btn-submit">
            <input type="submit" id="" class="fa fa-edit gen_btn button btn" name="theme" value="Process extension" />
        </div><!-- END btn-submit -->

        {{ Form::close() }}

</div>

@stop