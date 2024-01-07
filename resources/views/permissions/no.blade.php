@extends('layouts.master')
@section('title')
    {{__('lb.warning')}}
@endsection
@section('header')
    {{__('lb.warning')}}
@endsection
@section('content')
    <div class="toolbox pt-1 pb-1">
        <a href="{{url()->previous()}}" class="btn btn-success btn-sm">
            <i class="fa fa-reply"></i> {{__('lb.back')}}
        </a>
    </div>
    <div class="card">
       
        <div class="card-body">
            <div class="error-page">
                <h2 class="headline text-danger">403</h2>
        
                <div class="error-content">
                  <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Access Forbidden!</h3>
                    <p>
                        លោកអ្នកមិនមានសិទ្ធិមើលផ្នែកនេះទេ, សូមទំនាក់ទំនងអ្នកគ្រប់គ្រង!
                    </p>
                    <p>
                        You don't have permission to access this page, please contact your system administrator!
                    </p>
                </div>
              </div>
        </div>
    </div>
    
@endsection
@section('js')

   <script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_security>a").addClass("active");
            $("#menu_security").addClass("menu-open");
            $("#menu_role").addClass("myactive");
        });
    </script>
@endsection