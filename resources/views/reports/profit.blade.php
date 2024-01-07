@extends('layouts.master')
@section('header')
    <strong><i class="fa fa-arrow-right"> {{__('lb.profit')}}</i></strong>
@endsection
@section('content')
                       
<div class="toolbox pt-1 pb-1">
            <form action="{{url('report/profit')}}">
                {{__('lb.start_date')}} <span class="text-danger">*</span>&nbsp;&nbsp;<input type="date" name="start_date" value="{{$start_date}}"> 
                &nbsp;&nbsp;
                {{__('lb.end_date')}}<span class="text-danger">*</span> <input type="date" name="end_date" value="{{$end_date}}"> 
                <button type="submit" class="btn btn-primary btn-sm btn-oval">{{__('lb.search')}}</button>
               
            </form>
        </div>
        <div class="card p-4">
            @if($income>0 || $expense>0)
           <div class="row">
               <div class="col-sm-2">
                   <strong class="text-primary">{{__('lb.income')}}</strong>
               </div>
               <div class="col-sm-2">
                   <strong class="text-primary">$ {{number_format($income,2)}}</strong>
               </div>
           </div>
           <hr>
           <div class="row">
                <div class="col-sm-2">
                    <strong class="text-danger">{{__('lb.expense')}}</strong>
                </div>
                <div class="col-sm-2">
                    <strong class="text-danger">$ {{number_format($expense,2)}}</strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2">
                    <strong class="text-success">{{__('lb.profit')}}</strong>
                </div>
                <div class="col-sm-2">
                    <strong class="text-success">$ {{number_format($income - $expense,2)}}</strong>
                </div>
            </div>
            @endif
        </div>
    </div>
                           
@endsection

@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar-menu li ").removeClass("active open");
			$("#sidebar-menu li ul li").removeClass("active");
            $("#menu_report").addClass("active open");
			$("#report_collapse").addClass("collapse in");
            $("#menu_profit").addClass("active");
        });
    </script>
@endsection