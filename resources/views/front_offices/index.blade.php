@extends('layouts.master')
@section('title')
    {{__('lb.front_office')}}
@endsection
@section('header')
    {{__('lb.front_office')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1 pl-2">
    <div class="col-md-12">
  <div class="row">
      <div class="col-md-3 pt-3 pl-0 pr-0">
      <a href="{{route('front_office.today')}}">
        <button class="btn btn-success btn-xs" 
            table='request_details' permission='technical_check' token="{{csrf_token()}}">
            <i class="fa fa-calendar"></i> {{__('lb.today')}}
        </button>
    </a>

    <a href="{{route('front_office.yesterday')}}">
    <button class="btn btn-success btn-xs" 
    table='request_details' permission='technical_check' token="{{csrf_token()}}">
    <i class="fa fa-calendar"></i> {{__('lb.yesterday')}}
</button>
    </a>
<a href="{{route('front_office.week')}}">
<button class="btn btn-success btn-xs" 
    table='request_details' permission='technical_check' token="{{csrf_token()}}">
    <i class="fa fa-calendar"></i> {{__('lb.last_week')}}
</button>
</a></div>
<div class="col-md-2">
<form action="{{url('front-office')}}" method="GET">
    <div class="row">
{{__('lb.start_date')}} 

 <input type="date" value="{{$start_date}}" name="start_date" class="form-control input-xs"></div>
</div>
<div class="col-md-2">
  {{__('lb.to')}} <input type="date" value="{{$end_date}}" name="end_date" class="form-control input-xs">
</div>
<div class="col-md-3">
    {{__('lb.body_part')}} 
    <select name="section" id="section" class="chosen-select">
        <option value="">{{__('lb.select_one')}}</option>
        @foreach ($sections as $sec)
        <option value="{{$sec->id}}" {{$sec->id==$section?'selected':''}}>{{$sec->name}}</option>
        @endforeach
      
    </select>
   </div>    
</div>

<div class="row">
    <div class="col-md-3 pt-3 pl-0 pr-0">

    </div>
<div class="col-md-2 p-0 pt-1">
    <input type="text" name="keyword" value="{{$keyword}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
   </div>
   <div class="col-md-2 pt-1">

   <select name="status" class="input-xs form-control">
       <option value="" {{$status===""?'selected':''}}>{{__('lb.select_one')}}</option>
       <option class="text-success" value="1" {{$status==1?'selected':''}}>{{__('lb.scheduling')}}</option>
       <option style="color: #007afd;" value="2"  {{$status==2?'selected':''}}>{{__('lb.confirmed')}}</option>
       <option style="color: #d4be00;" value="3"  {{$status==3?'selected':''}}>{{__('lb.arrived')}}</option>
       <option style="color: #c37636;" value="4"  {{$status==4?'selected':''}}>{{__('lb.rescheduled')}}</option>
       <option value="5"  {{$status==5?'selected':''}}>{{__('lb.waiting_shot')}}</option>
       <option value="6" {{$status==6?'selected':''}}>{{__('lb.performing')}}</option>
       <option value="8" {{$status==8?'selected':''}}>{{__('lb.waiting_reading')}}</option>
       <option value="9" {{$status==9?'selected':''}}>{{__('lb.reading')}}</option>
       <option value="11" {{$status==11?'selected':''}}>{{__('lb.validated')}}</option>
       <option style="color: red;" value="0" {{$status==="0"?'selected':''}}>{{__('lb.canceled')}}</option>
   </select>
</div>
<div class="col-md-2 pt-1">
    <button style="height: 26px;">
        <i class="fa fa-search"></i> {{__('lb.search')}}
    </button>
</div>

</div>
</form>
</div> 
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered " style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">{{__('lb.reference_no')}}</th>
                    <th>{{__('lb.patients')}}</th>
                    <th>{{__('lb.phone')}}</th>
                    <th>{{__('lb.gender')}}</th>
                    <th>{{__('lb.hospitals')}} & {{__('lb.reference_no')}}</th>
                    <th>{{__('lb.body_part')}}</th>
                    <th>{{__('lb.items')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.time')}}</th>
                    <th>{{__('lb.status')}}</th>
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
                @foreach($technicals as $t)
                    <?php $patient = DB::table('customers')->where('id', $t->patient_id)->first();
                       $request = DB::table('requestchecks')->where('id', $t->request_id)->first();
                       $hospital = DB::table('hospitals')->where('id',$request->hospital_id)->first();
                    ?>
                    <tr  
                        @if(@$t->request_status==0)style="background: #f9cccc;"
                        @elseif(@$t->request_status==1)style="background: #b1f1c0;"
                        @elseif(@$t->request_status==2)style="background: #acd1f9;"
                        @elseif(@$t->request_status==3)style="background: #ffecc7;"
                        @elseif(@$t->request_status==4)style="background: #f9d0ae;"
                        @endif
                    >
                        <td>{{$i++}}</td>
                        <td class="text-center">   <a href="{{url('front-office/detail', $t->id)}}" title="{{__('lb.detail')}}"
                            >{{$request->code}}</a></td>
                        <td>{{$patient->kh_first_name}} {{$patient->kh_last_name}}</td>
                        <td>{{$patient->phone}}</td>
                        <td>{{$patient->gender}}</td>
                        <td>{{$request->hospital_reference}} {{$hospital->name}}</td>
                        <td>{{$t->section_name}}</td>
                        <td><a href="{{url('front-office/detail', $t->id)}}" style="font-size:12px;" title="{{__('lb.detail')}}"
                            >{{$t->item_name}}</a></td>
                        <td>{{$t->date}}</td>
                        <td> {{\Carbon\Carbon::createFromFormat('H:i:s',$t->time)->format('h:i A')}}</td>
                      <td>@if(@$t->request_status==1) {{__('lb.scheduling')}}
                        @elseif(@$t->request_status==2){{__('lb.confirmed')}}
                        @elseif(@$t->request_status==3){{__('lb.arrived')}}
                        @elseif(@$t->request_status==4){{__('lb.rescheduled')}}
                        @elseif(@$t->request_status==5){{__('lb.waiting_shot')}}
                        @elseif(@$t->request_status==6){{__('lb.performing')}}
                        @elseif(@$t->request_status==8){{__('lb.waiting_reading')}}
                        @elseif(@$t->request_status==9){{__('lb.reading')}}
                        @elseif(@$t->request_status==10){{__('lb.reading')}}
                        @elseif(@$t->request_status==11){{__('lb.validated')}}
                        @elseif(@$t->request_status==0){{__('lb.canceled')}}
                        @endif</td>
                        <td class="action">
                            @if($t->request_status == 11)
                            <a href="{{url('doctor-check/print/'.$t->id)}}" target="_blank" class="btn btn-primary btn-xs">
                                <i class="fa fa-print"></i> </a>
                                @endif
                            @canedit('front_office')
                            @if($t->request_status > 0 && $t->request_status < 6)
                            <a href="{{url('front-office/edit', $t->id)}}" class="btn btn-success btn-xs"  title="{{__('lb.edit')}}">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endif
                            @endcanedit
                            @candelete('front_office')
                            
                            <a href="{{url('front-office/request-delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="{{__('lb.delete')}}">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endcandelete
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> <br>
        {{$technicals->links('pagination::bootstrap-4')}}
	</div>
</div>

@endsection

@section('js')
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_front_office").addClass("active");
        });
    </script>
@endsection