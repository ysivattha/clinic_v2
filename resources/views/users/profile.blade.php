@extends('layouts.master')
@section('title')
    {{__('lb.my_profile')}}
@endsection
@section('header')
    {{__('lb.my_profile')}}
@endsection
@section('content')
<form action="{{url('user/profile/update')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="toolbox pt-1 pb-1">
    <button class="btn btn-primary btn-sm">
        <i class="fa fa-save"></i> {{__('lb.save')}}
    </button>
   
</div>
<div class="card">
    <div class="card-body">
        @component('coms.alert')
        @endcomponent
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group mb-1">
                    <label for="name">{{__('lb.first_name')}} <span class="text-danger">*</span>
                    
                    </label>
                    <input type="name" class="form-control" id="name" name="first_name" 
                            value="{{$user->first_name}}" required>
                </div>
                <div class="form-group mb-1">
                    <label for="last_name">{{__('lb.last_name')}} <span class="text-danger">*</span>
                    
                    </label>
                    <input type="name" class="form-control" id="last_name" name="last_name" 
                            value="{{$user->last_name}}" required>
                </div>
                <div class="form-group mb-1">
                    <label for="gender">{{__('lb.gender')}}</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="Male" {{$user->gender=='Male'?'selected':''}}>{{__('lb.male')}}</option>
                        <option value="Female" {{$user->gender=='Female'?'selected':''}}>{{__('lb.female')}}</option>
                    </select>
                </div>
                
                <div class="form-group mb-1">
                    <label for="email">{{__('lb.email')}}</label>
                    <input type="email" class="form-control" id="Email" name="email" 
                        value="{{$user->email}}">
                </div>
                <div class="form-group mb-1">
                    <label for="phone">{{__('lb.phone')}}</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}">
                </div>
                <div class="form-group mb-1">
                    <label for="Username">{{__('lb.username')}}</label> 
                    <input type="text" class="form-control" disabled value="{{$user->username}}">
                </div>
                <div class="form-group mb-1">
                    <label for="Password">{{__('lb.password')}}</label>
                    <input type="password" class="form-control" id="Password" name="password">
                    <small>{{__('lb.old_pass')}}</small>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-5">
                
                <div class="form-group mb-1">
                    <label >{{__('lb.positions')}} : {{$user->pname}}</label>
                    <p></p> 
                </div>
                <div class="form-group mb-1">
                    <label >{{__('lb.departments')}} : {{$user->dname}}</label>
                    <p></p> 
                </div>
                <div class="form-group mb-1">
                    <label >{{__('lb.role')}} : {{$user->rname}}</label>
                </div>
                <div class="form-group mb-1">
                    <label for="photo">{{__('lb.photo')}}</label>
                    <input type="file" id="photo" name="photo" onchange="preview(event)" class="form-control">
                    <div class="mt-3">
                        <img src="{{asset($user->photo)}}" alt="" width="150" id='img'>
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
        $(document).ready(function(){
            $("#sidebar li a").removeClass("active");
            $("#menu_security>a").addClass("active");
            $("#menu_security").addClass("menu-open");
            $("#menu_user").addClass("myactive");
        });
        function preview(e)
        {
            var img = document.getElementById('img');
            img.src = URL.createObjectURL(e.target.files[0]);
        }
    </script>
@endsection
