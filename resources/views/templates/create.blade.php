@extends('layouts.master')
@section('title')
    {{__('lb.templates')}}
@endsection
@section('header')
    {{__('lb.create_template')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<form action="{{url('template/save')}}" method="POST">
@csrf
<div class="toolbox pt-1 pb-1">
    <button class="btn btn-primary btn-sm">
        <i class="fa fa-save"></i> {{__('lb.save')}}
    </button>
    <a href="{{route('template.index')}}">
    <button class="btn btn-success btn-sm" type="button">
        <i class="fa fa-reply"></i> {{__('lb.back')}}
    </button>
</a>
</div>
<div class="card">
    <div class="card-body">
        @component('coms.alert')
        @endcomponent
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label class="col-md-2"  for="section_id">{{__('lb.body_part')}}  <span class="text-danger">*</span></label></label>
                    <div class="col-md-7">
                        <select name="section_id" id="section_id" class="form-control chosen-select" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($sections as $d)
                            <option value="{{$d->id}}">{{$d->code}} - {{$d->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="code">{{__('lb.code')}} <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control input-xs" id="code" name="code" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="title">{{__('lb.title')}} <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="title" class="form-control input-xs" id="title" name="title" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="status">{{__('lb.status')}} <span class="text-danger">*</span></label>
                    <div class="col-md-2">
                        <select name="status" id="status" class="form-control input-xs" required>    
                            <option value="">{{__('lb.select_one')}}</option>
                            <option value="{{Auth::user()->id}}">{{__('lb.only_me')}}</option>
                            <option value="0">{{__('lb.public')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <textarea name="description" id="description" cols="30" rows="10" class="ckeditor form-control"></textarea>
            </div>
        </div>
    </div>
</div>
</form>
<style>
    .cke_contents {
        max-height: 297mm;
        height: 297mm !important;
        width: 201mm!important;
    }
    .cke_reset {
        width: 203mm;
    }
</style>
@endsection
@section('js')
    <script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
    <script>
        $(".chosen-select").chosen({width: "100%"});
        $(document).ready(function(){
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_template").addClass("myactive");
        });
    </script>
@endsection
