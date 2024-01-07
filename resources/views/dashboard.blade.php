@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('header')
    Dashboard
@endsection

@section('content')
<div class="card mt-2">
    <div class="card-body">
    <div class="row">
            <div class="col-md-2">
                <a href="{{url('print/letter-head')}}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> បោះពុម្ពក្បាលសំបុត្រ</a>
            </div>
      
</div>
<hr>
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$count_patients}}</h3>
    
                        <p>{{__('lb.new_patient')}}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{url('patient')}}" class="small-box-footer">{{__('lb.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$count_invoices}}</h3>
                    {{-- <sup style="font-size: 20px">%</sup> --}}
                    <p>{{(__('lb.invoices'))}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{url('invoice')}}" class="small-box-footer">{{__('lb.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$count_requestchecks}}</h3>
    
                    <p>{{__('lb.request')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{url('request')}}" class="small-box-footer">{{__('lb.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$count_appointments}}</h3>
    
                    <p>{{__('lb.appointments')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{url('appointment')}}" class="small-box-footer">{{__('lb.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
      
    </div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $("#sidebar li a").removeClass("active");
            $("#menu_home").addClass("active");
        });
    </script>
@endsection