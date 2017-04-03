@extends('layouts.default')

@section('content')

<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; How to create an expense</h1>

<div id="quick_start">

    <div class="guide_section help_section">

        <div class="quick_start_video vid_in_help">
            <iframe src="//player.vimeo.com/video/104409271" width="561" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>

        <h2>Text instruction</h2>

        <ol class="steps">
            <li>Open the Create Tab from the navigation menu</li>
            <li>Click <span>New Expense</span></li>
            <li>Fill in the expense details. You can attach scanned receipt by clicking on the browse button to select your file</li>
            <li>...</li>
            <li>...</li>
            <li>...</li>
            <li>Finally, click on create button to save the expense</li>
        </ol>

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