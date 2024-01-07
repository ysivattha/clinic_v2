@extends('layouts.master')
@section('title')
    {{__('lb.appointments')}}
@endsection
@section('header')
    {{__('lb.appointments')}}
@endsection
@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	{{-- <script src="https:///cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script> --}}
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.0/main.min.js"></script>

    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.0/main.css"/>
<div class="card">
	
	<div class="card-body">
    <div class="toolbox">
        <a href="{{url('appointment')}}" class="btn btn-success-outline btn-oval btn-sm">
            <i class="fa fa-list"></i> {{__('lb.list')}}</a>
    </div>
    <div class="card-block">
            {!! $calendar->calendar() !!}
            {!! $calendar->script() !!}

    </div>
</div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $("#sidebar-menu li").removeClass('active');
            $("#menu_appointment").addClass('active');
        });
    </script>
    <script src='https://fullcalendar.io/js/fullcalendar-3.1.0/lib/moment.min.js'></script>
    <script src='https://fullcalendar.io/js/fullcalendar-3.1.0/lib/jquery.min.js'></script>
    <script src='https://fullcalendar.io/js/fullcalendar-3.1.0/lib/jquery-ui.min.js'></script>
    <script src='https://fullcalendar.io/js/fullcalendar-3.1.0/fullcalendar.min.js'></script>
@stop