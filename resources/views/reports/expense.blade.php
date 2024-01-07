@extends('layouts.master')
@section('title')
{{__('lb.expense_report')}}
@endsection
@section('header')
{{__('lb.expense_report')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
            <form action="{{url('report/expense/search')}}">
                {{__('lb.start_date')}} <span class="text-danger">*</span>&nbsp;&nbsp;<input type="date" name="start_date" value="{{$start_date}}"> 
                &nbsp;&nbsp;
                {{__('lb.end_date')}}<span class="text-danger">*</span> <input type="date" name="end_date" value="{{$end_date}}"> 
                <button type="submit" class="btn btn-primary btn-sm btn-oval"> {{__('lb.search')}}</button>
                @if(count($expenses)>0)
                    <a href="{{url('report/expense/print?start='.$start_date.'&end='.$end_date)}}" target="_blank"
                        class="btn btn-primary btn-sm btn-oval">
                        <i class="fa fa-print"></i> {{__('lb.print')}}
                    </a>
                @endif
            </form>
        </div>
        <div class="card-block">
           @if(count($expenses)>0)
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.expense_for')}}</th>
                        <th>{{__('lb.description')}}</th>
                        <th>{{__('lb.amount')}}</th>
                    </tr>
                </thead>
                <?php
                    $i = 1;
                    $total = 0;
                ?>
                @foreach($expenses as $ex)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$ex->expense_date}}</td>
                        <td>{{$ex->item}}</td>
                        <td>{{$ex->description}}</td>
                        <td>$ {{$ex->amount}}</td>
                        <?php
                            $total += $ex->amount;
                        ?>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class='text-right text-danger'>{{__('lb.total')}}</td>
                    <td><span id="total">$ {{$total}}</span></td>
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
            $("#menu_report_expense").addClass("myactive");
        });
    </script>
@endsection