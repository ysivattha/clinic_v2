@extends('layouts.master')
@section('title')
    {{__('lb.invoice')}}
@endsection
@section('header')
    {{__('lb.invoice')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1 pl-2">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-1 pt-3 pl-0 pr-0">
            @cancreate('invoice') 
            <a href="{{route('invoice.create')}}">
            <button type="button" class="btn btn-success btn-xs">
                <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
            </button>
            </a>
            @endcancreate
           
        
           </div>
        <div class="col-md-2">
        <form action="{{url('invoice')}}" method="GET">
            <div class="row">
        {{__('lb.start_date')}} 

        <input type="date" value="{{$start_date}}" name="start_date" class="form-control input-xs"></div>
        </div>
        <div class="col-md-2">
        {{__('lb.to')}} <input type="date" value="{{$end_date}}" name="end_date" class="form-control input-xs">
        </div>
        <div class="col-md-2">
            <br>
            <input type="text" name="keyword" value="{{$keyword}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
        </div>
        <div class="col-md-1"><br>
            <button style="height: 26px;">
                <i class="fa fa-search"></i> {{__('lb.search')}}
            </button>
        </div>

        </form>

        </div>
        </div>  
</div> 
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered " style="width: 100%">
            <thead>
                <tr>
                   
                    <th class="text-center">#</th>
                    <th class="text-center">{{__('lb.invoice_no')}}</th>
                    <th>{{__('lb.patients')}}</th>
                    <th>{{__('lb.age')}}</th>
                    <th>{{__('lb.phone')}} </th>
                    <th>{{__('lb.gender')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.due_date')}}</th>
                    <th>{{__('lb.paid')}} ($)</th>
                    <th>{{__('lb.total')}} ($)</th>
                    <th>{{__('lb.due_amount')}} ($)</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
            <tbody>			
                <?php
                    $pagex = @$_GET['page'];
                    if(!$pagex)
                        $pagex = 1;
                    $i = config('app.row') * ($pagex - 1) + 1;
                    
                ?>
                
                @foreach($invoices as $t)
                    <?php 
                        $dob = \Carbon\Carbon::parse( $t->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ'); 
                        $payment = DB::table('payments')->where('invoice_id', $t->id)->get();
                    ?>
                    <tr @if($t->due_amount>0) style="background: #acd1f9;" @endif>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center"><a href="{{url('invoice/detail/'.$t->id)}}">{{$t->invoice_no}}</a></td>
                        <td> {{$t->first_name}} {{$t->last_name}}</td>
                        <td>{{$dob}}</td>
                        <td>{{$t->phone}}</td>
                        <td>{{$t->gender}}</td>
                        <td>{{$t->start_date}}</td>
                        <td>{{$t->due_date}}</td>
                        <td>$ {{number_format($t->paid,2)}}</td>
                        <td>$ {{number_format($t->total,2)}}</td>
                        <td>$ {{number_format($t->due_amount,2)}}</td>
               
                        <td>
                        
                             @candelete('invoice')
                            @if(count($payment)<=0)
                            <a href="{{url('invoice/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endif
                            @endcandelete
                        </td>
                    </tr>
                  
                @endforeach
             
            </tbody>
        </table> <br>
        {{$invoices->links('pagination::bootstrap-4')}}
	</div>
</div>

@endsection

@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_invoice").addClass("active");
			
        });
       
    </script>
@endsection