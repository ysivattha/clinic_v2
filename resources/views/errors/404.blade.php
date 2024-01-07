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
                <h2 class="headline text-danger">404</h2>
        
                <div class="error-content">
                  <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Page not found.</h3>
                    <p>
                        ផ្នែកដែលលោកអ្នកកំពុងរក មិនមានក្នុងប្រព័ន្ធទេ!
                    </p>
                    <p>
                        The page you are looking for is not found, please contact your system administrator!
                    </p>
                </div>
              </div>
        </div>
    </div>
    
@endsection
