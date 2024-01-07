@extends('layouts.master')
@section('title')
{{__('lb.indebted')}}
@endsection
@section('header')
{{__('lb.indebted')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
            <form action="{{url('report/due/')}}">
                {{__('lb.start_date')}} <span class="text-danger">*</span>&nbsp;&nbsp;<input type="date" name="start_date" value="{{$start_date}}"> 
                &nbsp;&nbsp;
                {{__('lb.end_date')}}<span class="text-danger">*</span> <input type="date" name="end_date" value="{{$end_date}}"> 
                <button type="submit" class="btn btn-primary btn-sm btn-oval"> {{__('lb.search')}}</button>
                @if(count($due)>0)
                    <a href="{{url('report/due/print?start_date='.$start_date.'&end_date='.$end_date)}}" target="_blank"
                        class="btn btn-primary btn-sm btn-oval">
                        <i class="fa fa-print"></i> {{__('lb.print')}}
                    </a>
                @endif
            </form>
        </div>
        <div class="card-block">
           @if(count($due)>0)
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.invoice_no')}}</th>
                        <th>{{__('lb.patients')}}</th>
                        <th>{{__('lb.paid')}}</th>
                        <th>{{__('lb.due_amount')}}</th>
                    </tr>
                </thead>
                <?php
                    $i = 1;
                ?>
                @foreach($due as $ex)
                    <?php 
                        $patient = DB::table('customers')->where('id', $ex->patient_id)->first();
                    ?>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$ex->start_date}}</td>
                        <td>{{$ex->invoice_no}}</td>
                        <td>{{$patient->kh_first_name}} {{$patient->kh_last_name}} </td>
                        <td>$ {{number_format($ex->paid,2)}}</td>
                        <td>$ {{number_format($ex->due_amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class='text-right text-danger'>{{__('lb.total')}}</td>
                    <td><b >$ {{number_format($total_due_amount,2)}}</b></td>
                </tr>
            </table>
           @endif
        </div>
    </div>               
@endsection

@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_report>a").addClass("active");
            $("#menu_report").addClass("menu-open");
            $("#menu_due").addClass("myactive");
        });
    </script>
@endsection