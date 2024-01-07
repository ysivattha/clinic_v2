@extends('layouts.master')
@section('title')
    {{__('lb.edit_request')}}
@endsection
@section('header')
    {{__('lb.edit_request')}} / <a href="{{url('front-office/detail/'.$request_detail->id)}}" class="text-white">{{$request->code}} - {{$patient->kh_first_name}} {{$patient->kh_last_name}}</a> 
@endsection 
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<form action="{{route('front_office.update')}}" method="POST">
@csrf
<input type="hidden" name="id" value="{{$request_detail->id}}">
<input type="hidden" name="section_name" id="section_name">
<input type="hidden" name="item_name" id="item_name">
<div class="toolbox pt-1 pb-1">
    <button type="submit"  class="btn btn-oval btn-primary btn-sm"> 
        <i class="fa fa-save "></i> {{trans('lb.save')}}
    </button>
    <a href="{{url('front-office/detail/'.$request_detail->id)}}" class="btn btn-success btn-sm">
        <i class="fa fa-reply"></i> {{__('lb.back')}}
    </a>
</div>   
<div class="card">
	<div class="card-body">
        @component('coms.alert')
        @endcomponent
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="section_id" class="col-sm-3">
                        {{__('lb.body_part')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <select name="section_id" id="section_id" class="form-control chosen-select" required onchange="get_item()">
                            <option value="0"> {{__('lb.select_one')}} </option>
                            @foreach ($sections as $s)
                            <option value="{{$s->id}}" {{$s->id==$request_detail->section_id?'selected':''}}> {{$s->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

              <input type="hidden" id="request_id" name="request_id">
                <div class="form-group row">
                    <label for="item_id" class="col-sm-3">
                        {{__('lb.items')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <select name="item_id" id="item_id" class="form-control chosen-select" required onchange="getPrice()">
                            <option value="0"> {{__('lb.select_one')}} </option>
                            @foreach ($items as $item)
                            <option value="{{$item->id}}" price="{{$item->price}}" {{$item->id==$request_detail->item_id?'selected':''}}>{{$item->name}} </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="customer_id" name="customer_id">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-sm-3">
                        {{__('lb.price')}} ($)<span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-3">
                        <input type="number" required class="form-control input-xs" id="price" 
                            name="price" min="0" step="0.01" value="{{$request_detail->price}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-sm-3">
                        {{__('lb.discount')}} ($)
                    </label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control input-xs" id="discount" 
                            name="discount" value="{{$request_detail->discount}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group row">
                    <label for="date" class="col-sm-3">
                        {{__('lb.date')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="date" required class="form-control input-xs" id="request_date" 
                            name="date" value="{{$request_detail->date}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="time" class="col-sm-3">
                        {{__('lb.time')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="time" class="form-control input-xs" id="item" 
                            name="time" value="{{$request_detail->time}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="request_detail" class="col-sm-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-sm-8">
                       <textarea name="request_note" id="request_detail" class="form-control" cols="3" rows="2">{{$request_detail->request_note}}</textarea>
                    </div>
                </div>
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
         // function to get sub category
         function get_item()
        {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/item/get-item/" + $("#section_id").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        $("#item_name").val(data[i].name);
                        $('#section_name')
                        var note = data[i].code;
                        var description = data[i].name;
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].id + "' price='" + data[i].price +"' >" + data[i].code + '-' + data[i].name + "</option>";
                    }
                    $("#item_id").html(opts);
                    $("#item_id").trigger("chosen:updated");
                }
            });
        }
        function getPrice()
        { 
            let price = $("#item_id :selected").attr('price');
        
            $("#price").val(price);
        }
    </script>
@endsection