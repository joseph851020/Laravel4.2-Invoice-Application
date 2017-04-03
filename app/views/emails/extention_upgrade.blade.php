@extends('layouts.sendmail')

@section('content')

<p>Hi {{ $firstname }}, <p/>

<p>Your account subscription has been extended to: {{ $to_date }}
<br />
Subscription level: {{ $subscription_level }}.</p>

<p>If you have any questions please reply to this email.</p>

<p>Kind regards, <br />Sighted Sales Team</p>

@stop