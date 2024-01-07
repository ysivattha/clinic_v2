@extends('layouts.master')
@section('title')
    {{__('lb.exchange')}}
@endsection
@section('header')
    <strong> {{__('lb.exchange')}}</strong>
@endsection
@section('content')
<form action="{{route('exchange.update', $exc->id)}}" method="POST">
@csrf
@method('PATCH')

<div class="toolbox pt-1 pb-1">
    <button type="submit"  class="btn btn-oval btn-primary btn-sm"> 
        <i class="fa fa-save "></i> {{trans('lb.save')}}
    </button>
    <a href="{{route('exchange.index')}}" class="btn btn-success btn-sm">
        <i class="fa fa-reply"></i> {{__('lb.back')}}
    </a>
</div>   
    <div class="card">
	<div class="card-body">
		<div class="row">
            <div class="col-sm-6">
               @component('coms.alert')
               @endcomponent
                <div class="form-group row">
                    <label for="name" class="col-sm-3">ដុល្លារ <span class="float-right">:</span></label>
                    <div class="col-sm-9">
                        $ {{$exc->dollar}}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-3">រៀល <span class="text-danger">*</span>
                        <span class="float-right">:</span>
                    </label>
                    <div class="col-sm-9">
                        <input type="number" id="name" name="khr" min="0"
                            value="{{$exc->khr}}" required>
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
            $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_exchange").addClass("myactive");
        });
        });
    </script>
@endsection
