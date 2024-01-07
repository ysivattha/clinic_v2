@extends('layouts.master')
@section('title')
    {{__('lb.request')}}
@endsection
@section('header')
    {{__('lb.request')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1 pl-2">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 pt-3 pl-0 pr-0">
            @cancreate('request') 
            <a href="{{route('request.create')}}">
            <button type="button" class="btn btn-success btn-xs">
                <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
            </button>
            </a>
            @endcancreate
            <a href="{{route('request.today')}}">
                <button class="btn btn-success btn-xs">
                    <i class="fa fa-calendar"></i> {{__('lb.today')}}
                </button>
            </a>
            <a href="{{route('request.yesterday')}}">
            <button class="btn btn-success btn-xs" >
            <i class="fa fa-calendar"></i> {{__('lb.yesterday')}}
        </button>
            </a>
        <a href="{{route('request.week')}}">
        <button class="btn btn-success btn-xs">
            <i class="fa fa-calendar"></i> {{__('lb.last_week')}}
        </button>
        </a></div>
        <div class="col-md-2">
        <form action="{{url('request')}}" method="GET">
            <div class="row">
        {{__('lb.start_date')}} 

        <input type="date" name="start_date" class="form-control input-xs"></div>
        </div>
        <div class="col-md-2">
        {{__('lb.to')}} <input type="date" name="end_date" class="form-control input-xs">
        </div>
        <div class="col-md-2">
            {{__('lb.keyword')}} 
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
    <h5 class="text-primary">{{__('lb.request')}}</h5>
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered " style="width: 100%">
            <thead>
                <tr>
                <th>#</th>
                    <th class="text-center">{{__('lb.reference_no')}}</th>
                    <th width="100">{{__('lb.patients')}}</th>
                    <th>{{__('lb.phone')}}</th>
                    <th width="45">{{__('lb.age')}}</th>
                    <th>{{__('lb.gender')}}</th>
                    <th>{{__('lb.hospitals')}} & {{__('lb.reference_no')}}</th>
                    <th>{{__('lb.request')}} </th>
                    <th width="87">{{__('lb.date')}}</th>
                    <th width="71">{{__('lb.time')}}</th>
                    <th width="76">{{__('lb.total')}}</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
                </tr>
            </thead>
            <tbody>			
                <?php
                    $pagex = @$_GET['page'];
                    if(!$pagex)
                        $pagex = 1;
                    $i = config('app.row') * ($pagex - 1) + 1;
                ?>
                @foreach($requests as $t)
                <?php 
                        $patient = DB::table('customers')->where('id', $t->patient_id)->first();
                        $dob = \Carbon\Carbon::parse( $patient->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ');  
                        $request_detail = DB::table('request_details')->where('active', 1)->where('request_id', $t->id)->get();
                        $total = 0;
                    ?>
                    <tr>
                        <td>{{$i++}}</td>
                        <td class="text-center"><a href="{{url('request/detail/'.$t->id)}}">{{$t->code}}</a></td>
                        <td>{{$patient->kh_first_name}} {{$patient->kh_last_name}}</td>
                        <td>{{$patient->phone}}</td>
                        <td>@if($dob!=null){{$dob}}@endif</td>
                        <td>{{$patient->gender}}</td>
                        <td>{{$t->hospital_reference}} {{$t->hospital}}</td>
                        <td >@foreach ($request_detail as $rd)
                            <li style="font-size: 12px;">
                            {{$rd->section_name}} - {{$rd->item_name}}</li>
                            <?php   $total += $rd->price - $rd->discount;?>
                        @endforeach
                    </td>
                        <td>{{$t->date}}</td>
                        <td> {{\Carbon\Carbon::createFromFormat('H:i:s',$t->time)->format('h:i A')}}</td>
                        <td >$ {{number_format($total,2)}}
                        </li>
                        <td class="action">
                            <a href="{{url('request/detail', $t->id)}}" title="{{__('lb.detail')}}" class='btn btn-success btn-xs'
                             >
                                <i class="fa fa-eye"></i>
                            </a>
                             @candelete('request')
                            
                            <a href="{{url('request/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endcandelete
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> <br>
        {{$requests->links('pagination::bootstrap-4')}}
	</div>
</div>

@endsection

@section('js')
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_request").addClass("active");
        });
    </script>
@endsection