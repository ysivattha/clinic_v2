@extends('layouts.master')
@section('title')
    {{__('lb.company_info')}}
@endsection
@section('header')
    {{__('lb.company_info')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
    @canedit('company')
    <a href="{{url('company/edit/'. $company->id)}}" class="btn btn-success btn-sm">
        <i class="fa fa-edit"></i> {{__('lb.edit')}}
    </a>
    @endcanedit
</div>
<div class="card">
    
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="" class="col-sm-3">{{__('lb.khmer_name')}}</label>
                    <div class="col-sm-9">
                        : {{$company->kh_name}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3">{{__('lb.english_name')}}</label>
                    <div class="col-sm-9">
                        : {{$company->en_name}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3">{{__('lb.phone')}}</label>
                    <div class="col-sm-9">
                        : {{$company->phone}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3">{{__('lb.email')}}</label>
                    <div class="col-sm-9">
                        : {{$company->email}}
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="" class="col-sm-3">{{__('lb.website')}}</label>
                    <div class="col-sm-9">
                        : {{$company->website}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3">{{__('lb.address')}}</label>
                    <div class="col-sm-9">
                        : {{$company->address}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3">Header Text</label>
                    <div class="col-sm-9">
                        : {{$company->header}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3">Footer Text</label>
                    <div class="col-sm-9">
                        : {{$company->footer}}
                    </div>
                </div>
                
            </div>
            <div class="col-sm-6">
                <p class="text-center">
                    <strong>{{__('lb.logo')}}</strong>
                </p>
                <div class="text-center">
                    <img src="{{asset($company->logo)}}" alt="{{$company->en_name}}" width='200'>
                </div>
            </div>
        </div>
        @if(count($branches)>0)
        <h5 class="text-success">
            <u>{{__('lb.branches')}}</u>
        </h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('lb.name')}}</th>
                    <th>{{__('lb.email')}}</th>
                    <th>{{__('lb.phone')}}</th>
                    <th>{{__('lb.address')}}</th>
                    <th>{{__('lb.logo')}}</th>
                </tr>
            </thead>
            <tbody>
                @php($i=1)
                @foreach($branches as $b)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$b->name}}</td>
                        <td>{{$b->email}}</td>
                        <td>{{$b->phone}}</td>
                        <td>{{$b->address}}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div> 
@endsection
@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_company").addClass("myactive");
        });
       
    </script>
@endsection