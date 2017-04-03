@extends('layouts.default')

@section('content')

<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; How to create a product</h1>

<div id="quick_start">

    <div class="guide_section help_section">

        <div class="quick_start_video vid_in_help">
            <iframe src="//player.vimeo.com/video/102754460" width="561" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>

        <h2>Text instruction</h2>

        <ol class="steps">
            <li>Open the Create Tab from the navigation menu</li>
            <li>Click <span>New Product</span></li>
            <li>Enter the amount of the product</li>
            <li>Optionally, if tax is enabled select the tax rate from the drop down</li>
            <li>Finally, click on create button to save the product</li>
        </ol>

        <p>Your products are readily available to be inserted quickly into line items when creating and editing invoices or quotes</p>

    </div><!-- END Quide section -->

</div><!-- END QUICK START-->


@stop


@section('footer')

<script>

    $(function(){

        if($('#appmenu').length > 0){

            $('.more_all_menu').addClass('selected_group');
            $('.menu_help').addClass('selected');
            $('.more_all_menu ul').css({'display': 'block'});
        }

    });

</script>

@stop