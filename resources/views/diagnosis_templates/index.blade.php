@extends('layouts.master')
@section('title')
    {{__('lb.diagnosis_template')}}
@endsection
@section('header')
    {{__('lb.diagnosis_template')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    <div class="row">
        <div class="col-md-1">
    @cancreate('diagnosis_template')
    <a href="{{url('diagnosis-template/create')}}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </a>
    @endcancreate
        </div>
    <div class="col-md-2">    
        <form action="{{url('diagnosis-template')}}" method="GET">
        @csrf
        <input type="text" name="keyword" value="{{$keyworld}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
       </div>
       <div class="col-md-1">
        <button>
            <i class="fa fa-search"></i> {{__('lb.search')}}
        </button>
    </div>
</div>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered datatable" id='dataTable' style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('lb.body_parts')}}</th>
                    <th>{{__('lb.code')}}</th>
                    <th>{{__('lb.title')}}</th>
                    <th>{{__('lb.create_by')}}</th>
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

                @foreach($diagnosis_templates as $t)
                    @if($t->status==Auth::user()->id || $t->status==0)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>
                            <a href="{{url('diagnosis-template/detail/'.$t->id)}}">{{$t->code}}</a>
                        </td>
                        <td>
                            <a href="{{url('diagnosis-template/detail/'.$t->id)}}">{{$t->title}}</a>
                        </td>
                        <td>{{$t->dcode}} - {{$t->dname}}</td>
                        <td>{{$t->first_name}} {{$t->last_name}}</td>
                        <td> @if($t->status==Auth::user()->id) {{__('lb.only_me')}} @endif @if($t->status==0) {{__('lb.public')}} @endif </td>
                        <td class="text-left">
                            @if($t->created_by==Auth::user()->id)
                            @canedit('diagnosis_template')
                            
                           <a href="{{url('diagnosis-template/edit/'.$t->id)}}" class="btn btn-success btn-xs">
                                <i class="fa fa-edit"></i></a>&nbsp; 
                             
                            @endcanedit  
                            @candelete('diagnosis_template')
                            <a href="{{url('diagnosis-template/delete?id='.$t->id)}}"
                            onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?')" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i></a>
                            @endcandelete
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        {{$diagnosis_templates->links()}}
	</div>
</div>

@endsection

@section('js')
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_diagnosis_template").addClass("myactive");
        });
       
    </script>
@endsection