@extends('layouts.master')
@section('title')
    {{__('lb.edit_patient')}}
@endsection
@section('header')
    {{__('lb.edit_patient')}}
@endsection
@section('content')
<form action="{{route('patient.update', $p->id)}}" method="POST" enctype="multipart/form-data">
@csrf
@method('PATCH')
<div class="toolbox pt-1 pb-1">
    <button class="btn btn-primary btn-sm">
        <i class="fa fa-save"></i> {{__('lb.save')}}
    </button>
    <a href="{{route('patient.index')}}" class="btn btn-success btn-sm">
        <i class="fa fa-reply"></i> {{__('lb.back')}}
    </a>
</div>   
<div class="card">
	<div class="card-body">
        @component('coms.alert')
        @endcomponent
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="kh_first_name" class="col-sm-3">
                        {{__('lb.kh_first_name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="kh_first_name" required 
                            name="kh_first_name" value="{{$p->kh_first_name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kh_first_name" class="col-sm-3">
                        {{__('lb.kh_last_name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="kh_last_name" required 
                            name="kh_last_name" value="{{$p->kh_last_name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="en_first_name" class="col-sm-3">
                        {{__('lb.en_first_name')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="en_first_name" 
                            name="en_first_name" value="{{$p->en_first_name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="en_last_name" class="col-sm-3">
                        {{__('lb.en_last_name')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="en_last_name" 
                            name="en_last_name" value="{{$p->en_last_name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gender" class="col-sm-3">
                        {{__('lb.gender')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                        <select name="gender" id="gender" class="form-control input-xs" onchange="getGender()" required>
                            <option value="ប្រុស" {{$p->gender=='​ប្រុស'?'selected':''}}>ប្រុស</option>
                            <option value="ស្រី" {{$p->gender=='ស្រី'?'selected':''}}>ស្រី</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dob" class="col-sm-3">
                        {{__('lb.dob')}}
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control input-xs" id="dob" 
                            name="dob" value="{{$p->dob}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-3">
                        {{__('lb.phone')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="phone" 
                            name="phone" value="{{$p->phone}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-sm-3">
                        {{__('lb.address')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="address" 
                            name="address" value="{{$p->address}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="job" class="col-sm-3">
                        {{__('lb.job')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="job" 
                            name="job" value="{{$p->job}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nationality" class="col-sm-3">
                        {{__('lb.nationality')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="nationality" 
                            name="nationality" value="{{$p->nationality}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="blood" class="col-sm-3">
                        {{__('lb.blood')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="blood" 
                            name="blood" value="{{$p->blood}}">
                    </div>
                </div>
               
            </div>
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="reference" class="col-sm-3">
                        {{__('lb.reference')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="reference" 
                            name="reference" value="{{$p->reference}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reference_phone" class="col-sm-3">
                        {{__('lb.reference_phone')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="reference_phone" 
                            name="reference_phone" value="{{$p->reference_phone}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="hospital_id" class="col-sm-3">
                        {{__('lb.hospital')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-7">
                        <select name="hospital_id" id="hospital_id" class="form-control input-xs"  required>
                            @if($p->hospital_id){
                                @foreach ($hospitals as $hospital)
                            
                                    <option {{ ($p->hospital_id)==$hospital->id?'selected':'' }} value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            }
                            @else{
                                    <option value="">{{__('lb.select_one')}}</option>
                                    @foreach ($hospitals as $hospital)
                                        <option  value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                    @endforeach
                            }
                            @endif;
                       
                           
                            
                      
                        </select>
                    </div>
                </div>

                <div class="hide" id="qty_son">
                    
          
                <div class="form-group row">
                    <label for="child_number" class="col-sm-3">
                        {{__('lb.child_number')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="child_number" 
                            name="child_number" value="{{$p->child_number}}">
                    </div>
                </div>
            </div>
            <div class="hide" id="qty_pregnant">
                <div class="form-group row">
                    <label for="born_number" class="col-sm-3">
                        {{__('lb.born_number')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="born_number" 
                            name="born_number" value="{{$p->born_number}}">
                    </div>
                </div>
            </div>
                <div class="form-group row">
                    <label for="h_code" class="col-sm-3">
                        {{__('lb.hospital_code')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="h_code" 
                            name="h_code" value="{{$p->h_code}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="note" class="col-sm-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-sm-7">
                       <textarea name="note" id="" cols="30" rows="2" class="form-control">{{$p->note}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="photo" class="col-sm-3">
                        {{__('lb.photo')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="file" class="form-control" id="photo" 
                            name="photo" accept="image/*">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="social" class="col-sm-3">
                        {{__('lb.social')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="social" 
                            name="social" value="{{$p->social}}">
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
</form>
@endsection

@section('js')
    <script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $("#dob").inputmask('99-99-9999');
            $("#sidebar li a").removeClass("active");
            $("#menu_patient").addClass("active");
        });
        function getGender() {
            if($('#gender').val()=='Female' || $('#gender').val()=='ស្រី') {
                $('#qty_son').removeClass('hide');
                $('#qty_son').addClass('show');
                $('#qty_pregnant').removeClass('hide');
                $('#qty_pregnant').addClass('show');
            } 
            if($('#gender').val()=='Male' || $('#gender').val()=='ប្រុស') {
                $('#qty_son').addClass('hide');
                $('#qty_son').removeClass('show');
                $('#qty_pregnant').addClass('hide');
                $('#qty_pregnant').removeClass('show');
            }
          
       }
    </script>
@endsection