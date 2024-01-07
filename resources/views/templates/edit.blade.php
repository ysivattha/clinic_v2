@extends('layouts.master')
@section('title')
    {{__('lb.edit_templates')}}
@endsection
@section('header')
    {{__('lb.edit_templates')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">


<div class="toolbox pt-1 pb-1">
    <div class="col-md-12">
    <div class="row">
  
    <form action="{{url('template/update')}}" method="POST">
        @csrf
    <button class="btn btn-primary btn-sm">
        <i class="fa fa-save"></i> {{__('lb.save')}}
    </button>
    <a href="{{route('template.index')}}">
    <button class="btn btn-success btn-sm" type="button">
        <i class="fa fa-reply"></i> {{__('lb.back')}}
    </button>
    
</a>
@cancreate('template')
<a href="{{route('template.create')}}">
    <button class="btn btn-success btn-sm" type="button">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
</a>&nbsp;
@endcancreate</div>
</div></div>
<div class="card">
    <div class="card-body">
        @component('coms.alert')
        @endcomponent
        <div class="row">
            <div class="col-sm-6">
                <input type="hidden" id="id" name="id" value="{{$tem->id}}">
                <div class="form-group row">
                    <label class="col-md-2" for="section_id">{{__('lb.body_part')}}  <span class="text-danger">*</span></label></label>
                    <div class="col-md-7">
                    <select name="section_id" id="section_id" class="form-control chosen-select" required>
                        <option value="">{{__('lb.select_one')}}</option>
                        @foreach ($sections as $d)
                        <option value="{{$d->id}}" {{$tem->section_id==$d->id?'selected':''}}>{{$d->code}} - {{$d->name}}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="code">{{__('lb.code')}} <span class="text-danger">*</span></label>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-xs" id="code" name="code" required value="{{$tem->code}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2"​ for="title">{{__('lb.title')}} <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="title" class="form-control input-xs" id="title" name="title" value="{{$tem->title}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="status">{{__('lb.status')}} <span class="text-danger">*</span></label>
                    <div class="col-md-2">
                    <select name="status" id="status" class="form-control input-xs" required>    
                        <option value="">{{__('lb.select_one')}}</option>
                        <option value="{{Auth::user()->id}}" {{$tem->status==Auth::user()->id?'selected':''}}>{{__('lb.only_me')}}</option>
                        <option value="0" {{$tem->status==0?'selected':''}}>{{__('lb.public')}}</option>
                    </select>
                </div>
                </div>
            </div>
            <div class="col-sm-12">
                <textarea name="description" id="description" cols="30" rows="10" class="ckeditor form-control">{{$tem->description}}</textarea>
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
        var roxyFileman = "{{asset('fileman/index.html?integration=ckeditor')}}"; 

        CKEDITOR.replace( 'txt',{filebrowserBrowseUrl:roxyFileman, 
             filebrowserImageBrowseUrl:roxyFileman+'&type=image',
             removeDialogTabs: 'link:upload;image:upload'});
    </script>
@endsection
