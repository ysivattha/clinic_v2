@extends('layouts.master')
@section('title')
    {{__('lb.edit_company')}}
@endsection
@section('header')
    {{__('lb.edit_company')}}
@endsection
@section('content')
<form action="{{url('company/save')}}" method='POST' enctype="multipart/form-data">
    @csrf
    <div class="toolbox pt-1 pb-1">
        <button class="btn btn-primary btn-sm">
            <i class="fa fa-save"></i> {{__('lb.save')}}
        </button>
        <a href="{{url('company')}}" class="btn btn-success btn-sm">
            <i class="fa fa-reply"></i> {{__('lb.back')}}
        </a>
    </div>
    <div class="card">
       
        <div class="card-body">
            @component('coms.alert')
            @endcomponent
            <input type="hidden" name="id" value="{{$company->id}}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-2">
                            {{__('lb.khmer_name')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-7">
                        <input type="text" class="form-control input-xs" required value="{{$company->kh_name}}"
                            name="kh_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">
                            {{__('lb.english_name')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-7">
                            <input type="text" class="form-control input-xs" name="en_name" required value="{{$company->en_name}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">{{__('lb.phone')}}</label>
                        <div class="col-md-7">
                        <input type="text" class="form-control input-xs" name="phone" value="{{$company->phone}}">
                    </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">{{__('lb.email')}}</label>
                        <div class="col-md-7">
                        <input type="email" class="form-control input-xs" name="email" value="{{$company->email}}">
                    </div>
                    </div>
                    
                    <div class="form-group  row">
                        <label class="col-md-2">{{__('lb.website')}}</label>
                        <div class="col-md-7">
                        <input type="text" class="form-control input-xs" name="website" value="{{$company->website}}">
                    </div>
                    </div>
                    <div class="form-group  row">
                        <label class="col-md-2">{{__('lb.address')}}</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control input-xs" name="address" value="{{$company->address}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">Header Text</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control input-xs" name="header" value="{{$company->header}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">Footer Text</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control input-xs" name="footer" value="{{$company->footer}}">
                        </div>
                    </div>
                    <div class="form-group row">
                       <label class="col-md-2">{{__('lb.logo')}}</label>
                       <div class="col-md-7">
                        <input type="file" class="form-control input-xs" name="logo" accept="image/*" 
                            onchange="preview(event)">
                        <div class="mt-3">
                            <img src="{{asset($company->logo)}}" alt="{{$company->en_name}}" width='200' id="img">
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>   
@endsection
@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_company").addClass("myactive");
			
        });
        function preview(e){
            var img = document.getElementById('img');
            img.src = URL.createObjectURL(e.target.files[0]);
        }
    </script>
@endsection