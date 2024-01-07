@extends('layouts.master')
@section('title')
    {{__('lb.detail_diagnosis_templates')}}
@endsection
@section('header')
    {{__('lb.detail_diagnosis_templates')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">

    @cancreate('template')
    <a href="{{route('diagnosis_template.create')}}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </a>
    @endcancreate
    @if($tem->created_by==Auth::user()->id)
    @canedit('template')
   
    <a href="{{route('diagnosis_template.edit', $tem->id)}}" class="btn btn-success btn-sm">
        <i class="fa fa-edit"></i> {{__('lb.edit')}}
    </a>
 
    @endcanedit
    @candelete('template')
    <a href="{{route('diagnosis_template.delete', $tem->id)}}" class="btn btn-danger btn-sm" 
        onclick="return confirm('{{__('lb.confirm')}}')">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </a>
    @endcandelete
    @endif
   <a href="{{route('diagnosis_template.index')}}" class="btn btn-success btn-sm">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
      <div class="row">
          <div class="col-sm-6">
            <div class="form-group row mb-1">
                <label class='col-sm-2'>{{__('lb.body_part')}}</label>
                <div class="col-sm-8">
                    : {{$tem->dcode}} -- {{$tem->dname}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-2'>{{__('lb.code')}}</label>
                <div class="col-sm-8">
                    : {{$tem->code}}
                </div>
            </div>
            
            <div class="form-group row mb-1">
                <label class='col-sm-2'>{{__('lb.title')}}</label>
                <div class="col-sm-8">
                    : {{$tem->title}}
                </div>
            </div>
            <?php $user = DB::table('users')->where('id', $tem->created_by)->first();?>
            <div class="form-group row mb-1">
                <label class='col-sm-2'>{{__('lb.create_by')}}</label>
                <div class="col-sm-8">
                    : {{$user->first_name}} {{$user->last_name}} ( {{$user->phone}} )
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-2'>{{__('lb.status')}}</label>
                <div class="col-sm-8">
                    : @if($tem->status==Auth::user()->id) {{__('lb.only_me')}} @endif @if($tem->status==0) {{__('lb.public')}} @endif
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-2'>{{__('lb.description')}}</label>
                <div class="col-sm-9">
                     {!!$tem->description!!}
                </div>
            </div>
          </div>
       
	</div>
</div>

@endsection

@section('js')
	<script>
        $("#sidebar li a").removeClass("active");
        $("#menu_config>a").addClass("active");
        $("#menu_config").addClass("menu-open");
        $("#menu_diagnosis_template").addClass("myactive");
</script>
@endsection