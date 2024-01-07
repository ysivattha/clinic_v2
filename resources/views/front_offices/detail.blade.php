@extends('layouts.master')
@section('title')
    {{__('lb.front_office')}}
@endsection
@section('header')
    {{__('lb.front_office')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @canedit('doctor_check')
    @if($request_detail->request_status < 2 && $request_detail->request_status > 0 )
        <a href="{{route('front_office.confirm',$request_detail->id)}}" class="btn btn-success btn-sm"  onclick="return confirm('{{__('lb.alert_confirmed')}}')">
            <i class="fa fa-check-circle"></i> {{__('lb.confirmed')}}
        </a>
    @endif
    @if($request_detail->request_status > 0 && $request_detail->request_status < 5 && $request_detail->request_status !=3 )
        <a href="{{route('front_office.arrived',$request_detail->id)}}" class="btn btn-success btn-sm"  onclick="return confirm('{{__('lb.alert_arrived')}}')">
            <i class="fa fa-smile" aria-hidden="true"></i> {{__('lb.arrived')}}
        </a>
    @endif
    @if($request_detail->request_status > 0 && $request_detail->request_status < 6)
    <a href="{{url('front-office/edit',$request_detail->id)}}" class="btn btn-success btn-sm">
        <i class="fa fa-edit"></i> {{__('lb.edit')}}
    </a>
    @endif
   
    <a href="{{url('front-office/canceled',$request_detail->id)}}" class="btn btn-danger btn-sm">
        <i class="fa fa-times-circle" aria-hidden="true"></i> {{__('lb.canceled')}}
    </a>
    @endcanedit
    <a href="{{route('front_office.index')}}" class="btn btn-success btn-sm">
        <i class="fa fa-reply"></i> {{__('lb.back')}}
    </a>
    
    @candelete('front_office')
    <a href="{{url('front-office/request-delete/'.$request_detail->id)}}" class="btn btn-danger btn-sm" 
        onclick="return confirm('{{__('lb.confirm')}}')">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </a>
    @endcandelete
    @if($request_detail->request_status != 0 && $request_detail->request_status != 7 && $request_detail->request_status != 6 && $request_detail->request_status != 5 && $request_detail->request_status < 8 )
    <a href="{{url('front-office/send-technical',$request_detail->id)}}" class="btn btn-primary btn-sm">
        <i class="fa fa-camera" aria-hidden="true"></i> {{__('lb.request_to_technical')}}
    </a>
    @endif
    @if($request_detail->request_status > 0 && $request_detail->request_status != 8 && $request_detail->request_status != 9 && $request_detail->request_status != 10 )
    <a href="{{url('front-office/send-doctor',$request_detail->id)}}" class="btn btn-primary btn-sm">
        <i class="fa fa-user-md" aria-hidden="true"></i> {{__('lb.request_to_doctor')}}
    </a>
    @endif
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
      <div class="row">
          <div class="col-sm-6">
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.code')}}</label>
                <div class="col-sm-8">
                    : {{$request->code}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.patient')}}</label>
                <div class="col-sm-8">
                    : <a href="{{url('patient/summary/'.$request->pid)}}"> {{$request->pfirst_name}}  {{$request->plast_name}} ( {{$request->phone}} )  -{{$request->gender}} -   {{__('lb.age')}} :  {{$dob}}
                    </a>
                </div>
            </div>
          
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.status')}}</label>
                <div class="col-sm-8  font-weight-bold">​ 
                     :
                     @if($request_detail->request_status==1) {{__('lb.scheduling')}} 
                     @elseif($request_detail->request_status==2) {{__('lb.confirmed')}} 
                     @elseif($request_detail->request_status==3) {{__('lb.arrived')}} 
                     @elseif($request_detail->request_status==0) {{__('lb.canceled')}} 
                     @elseif($request_detail->request_status==4) {{__('lb.rescheduled')}} 
                     @elseif($request_detail->request_status==5) {{__('lb.waiting_shot')}} 
                     @elseif($request_detail->request_status==6) {{__('lb.performing')}} 
                     @elseif($request_detail->request_status==7) {{__('lb.done')}} 
                     @elseif($request_detail->request_status==8) {{__('lb.waiting_reading')}} 
                     @elseif($request_detail->request_status==9) {{__('lb.reading')}} 
                     @elseif($request_detail->request_status==10) {{__('lb.reading')}} 
                     @elseif($request_detail->request_status==11) {{__('lb.validated')}} 
                     @endif
                </div>
            </div>
          
          </div>
          <div class="col-sm-6">
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.date')}} & {{__('lb.time')}}</label>
                <div class="col-sm-8">
                    : {{$request->date}}  {{\Carbon\Carbon::createFromFormat('H:i:s',$request->time)->format('h:i A')}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.percent1')}}</label>
                <div class="col-sm-8">
                    : {{$request_detail->dfirst_name}} {{$request_detail->dlast_name}} ( {{$request_detail->phone}} )
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.symptom')}}</label>
                <div class="col-sm-8">
                    : {{$request->symptom}}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th> {{__('lb.body_part')}}</th>
                        <th>{{__('lb.items')}}</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.time')}}</th>
                        <th>{{__('lb.note')}}</th>
                    </tr>
                </thead>
                <tbody id="data">
                        <tr>
                            <td>{{$request_detail->section_name}}</td>
                            <td>{{$request_detail->item_name}}</td>
                            <td>{{$request_detail->date}}</td>
                            <td> {{\Carbon\Carbon::createFromFormat('H:i:s',$request_detail->time)->format('h:i A')}}</td>
                            <td>{{$request_detail->request_note}}</td>
                        </tr>
                </tbody>
            </table>
        </div>
      </div>
    
       
 
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