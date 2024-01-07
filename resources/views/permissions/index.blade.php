@extends('layouts.master')
@section('title')
    {{__('lb.permission')}}
@endsection
@section('header')
    {{__('lb.permission')}}
@endsection
@section('content')
    <div class="toolbox pt-1 pb-1">
        <a href="{{route('role.index')}}" class="btn btn-success btn-sm">
            <i class="fa fa-reply"></i> {{__('lb.back')}}
        </a>
    </div>   
    <div class="card">
       
        <div class="card-body">
            @csrf
            <p>
                <strong class="text-primary">{{__('lb.set_permission')}}: [{{$role->name}}]</strong>
            </p>
            <table class="table table-sm table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.function')}}</th>
                        <th>{{__('lb.view_permission')}}</th>
                        <th>{{__('lb.insert')}}</th>
                        <th>{{__('lb.edit')}}</th>
                        <th>{{__('lb.delete')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($per_role as $per)
                        <tr role-id="{{$role_id}}" permission-id="{{$per->permission_id}}" id="{{$per->id==''?'0':$per->id}}">
                            <td>{{$i++}}</td>
                            <td>{{$per->alias}}</td>
                            <td>
                               
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="list{{$per->permission_id}}" value="{{$per->list?'1':'0'}}" 
                                        {{$per->list==1?'checked':''}} onchange="save(this)">
                                    <label for="list{{$per->permission_id}}"></label>
                                  </div>
                            </td>
                            <td>
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="in{{$per->permission_id}}" value="{{$per->insert?'1':'0'}}" 
                                        {{$per->insert==1?'checked':''}} onchange="save(this)">
                                    <label for="in{{$per->permission_id}}"></label>
                                </div>
                            </td>
                            <td>
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="update{{$per->permission_id}}" value="{{$per->update?'1':'0'}}" 
                                        {{$per->update==1?'checked':''}} onchange="save(this)">
                                    <label for="update{{$per->permission_id}}"></label>
                                </div>

                            </td>
                            <td>
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="delete{{$per->permission_id}}" value="{{$per->delete?'1':'0'}}" 
                                        {{$per->delete==1?'checked':''}} onchange="save(this)">
                                    <label for="delete{{$per->permission_id}}"></label>
                                </div>

                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('js/role_permission.js')}}"></script>
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_security>a").addClass("active");
            $("#menu_security").addClass("menu-open");
            $("#menu_role").addClass("myactive");
        });
    </script>
@endsection