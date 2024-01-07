@extends('layouts.master')
@section('title')
    {{__('lb.create_patient')}}
@endsection
@section('header')
    {{__('lb.create_patient')}}
@endsection
@section('content')
<form action="{{route('patient.store')}}" method="POST" enctype="multipart/form-data">
@csrf
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
        {{-- @component('coms.alert')
        @endcomponent --}}
        <div class="row">
            <div class="col-sm-6">
             
              
                <div class="form-group row">
                    <label for="kh_first_name" class="col-sm-3">
                        {{__('lb.kh_first_name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-7">
                        <input  type="text" class="form-control input-xs" id="kh_first_name" required 
                            name="kh_first_name" value="{{old('kh_first_name')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kh_last_name" class="col-sm-3">
                        {{__('lb.kh_last_name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="kh_last_name" required 
                            name="kh_last_name" value="{{old('kh_last_name')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="en_first_name" class="col-sm-3">
                        {{__('lb.en_first_name')}}
                    </label>
                    <div class="col-sm-7">
                        <input style="text-transform: uppercase;" type="text" class="form-control input-xs" id="en_first_name" 
                            name="en_first_name" value="{{old('en_first_name')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="en_last_name" class="col-sm-3">
                        {{__('lb.en_last_name')}}
                    </label>
                    <div class="col-sm-7">
                        <input style="text-transform: uppercase;" type="text" class="form-control input-xs" id="en_last_name" 
                            name="en_last_name" value="{{old('en_last_name')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gender" class="col-sm-3">
                        {{__('lb.gender')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                        <select name="gender" id="gender" class="form-control input-xs" onchange="getGender()" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            <option value="{{__('lb.male')}}">{{__('lb.male')}}</option>
                            <option value="{{__('lb.female')}}">{{__('lb.female')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dob" class="col-sm-3">
                        {{__('lb.dob')}}
                    </label>
                    <div class="col-sm-4">
                        <input required type="text" class="form-control input-xs" id="dob" 
                            
                            name="dob" value="{{old('dob')}}">
                    </div>
                    @error('dob')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="phone" class="col-sm-3">
                        {{__('lb.phone')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="phone" 
                            name="phone" value="{{old('phone')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-sm-3">
                        {{__('lb.address')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="address" 
                            name="address" value="{{old('address')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="job" class="col-sm-3">
                        {{__('lb.job')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="job" 
                            name="job" value="{{old('job')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nationality" class="col-sm-3">
                        {{__('lb.nationality')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="nationality" 
                            name="nationality" value="{{old('nationality')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="blood" class="col-sm-3">
                        {{__('lb.blood')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="blood" 
                            name="blood" value="{{old('blood')}}">
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
                            name="reference" value="{{old('reference')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="reference_phone" class="col-sm-3">
                        {{__('lb.reference_phone')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="reference_phone" 
                            name="reference_phone" value="{{old('reference_phone')}}">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="hospital_id" class="col-sm-3">
                        {{__('lb.hospital')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-7">
                        <select   name="hospital_id" id="hospital_id" class="form-control input-xs"  required>
                            @if(Auth()->user()->hospital_id){
                                 @foreach ($hospitals as $hospital)
                            
                                    <option @if((Auth::user()->role_id==105) && (Auth::user()->hospital_id != $hospital->id)) hidden @endif {{ (Auth()->user()->hospital_id)==$hospital->id?'selected':'' }} value="{{ $hospital->id }}">{{ $hospital->name }}</option>
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




                <div class="form-group row">
                    <label for="h_code" class="col-sm-3">
                        {{__('lb.hospital_code')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="h_code" 
                            name="h_code" value="{{old('h_code')}}">
                    </div>
                </div>
                <div class="hide" id="qty_son">
                    <div class="form-group row" >
                        <label for="child_number" class="col-sm-3">
                            {{__('lb.child_number')}}
                        </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control input-xs" id="child_number" 
                                name="child_number" value="{{old('child_number')}}">
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
                                name="born_number" value="{{old('born_number')}}">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="note" class="col-sm-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-sm-7">
                       <textarea name="note" id="" cols="30" rows="2" class="form-control">{{old('note')}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="photo" class="col-sm-3">
                        {{__('lb.photo')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="file" class="form-control input-xs" id="photo" 
                            name="photo" accept="image/*">
                    </div>
                </div>

                <div class="form-group row how">
                    <label for="social" class="col-sm-3">
                        {{__('lb.social')}}
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-xs" id="social" 
                            name="social" value="{{old('social')}}">
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">
                    <strong>{{__('lb.disease_history')}}</strong>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">
                  <strong>{{__('lb.pregnancy')}}</strong>
              </a>
            </li>
            
        </ul>
        <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade active show pt-2" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                <p>  
                    <label class="mr-3">
                        <input type="radio" value="no" name="ch" onchange="disease()" checked>
                        {{__('lb.no_disease')}}
                    </label>
                    <label class="mr-3">
                        <input type="radio" value="yes" name="ch" onchange="disease()">
                        {{__('lb.have_disease')}}
                    </label>
                </p>
                <div id="ds_list" class="hide col-md-6">
                <div class="form-group row mb-1">
                    <label class="col-sm-2">ការបរិច្ឆេត</label>
                    <div class="col-sm-8">
                     <input type="date" class="txt" id="disease_date" name="disease_date">
                    </div>
                </div>
            
                <div class="form-group row mb-1">
                    <label class="col-sm-2">ជំងឺផ្សេងៗ</label>
                        <div class="col-md-10">
                           
                            <textarea name="input_disease"  id="edisease" cols="30" rows="10" class="ckeditor"></textarea>
                        </div>
                    </div>
                    </div>
               

            </div>
            <div class="tab-pane fade pt-2" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                <p>
                   
                    <label class="mr-3">
                        <input type="radio" value="no" name="pg" onchange="pregnance()" checked>
                        {{__('lb.no_pregnant')}}
                    </label>
                    <label class="mr-3">
                        <input type="radio" value="yes" name="pg" onchange="pregnance()">
                        {{__('lb.yes_pregnant')}}
                    </label>
                </p>
               <div id="pg_box" class="hide">
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group row mb-1">
                    <label class="col-sm-2">{{__('lb.date')}}</label>
                    <div class="col-sm-8">
                        <input type="date" class="txt" value="{{date('Y-m-d')}}" name="pregnance_date">
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class="col-sm-2">អាយុគភ៌</label>
                    <div class="col-sm-10">
                        <textarea name="pregnancy" id="" cols="30" rows="10" class="ckeditor"></textarea>
                    </div>
                </div>
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
<script src="{{asset('js/ckeditor2/ckeditor.js')}}"></script>
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            //back to old
            $("#dob").inputmask('99-99-9999');
            $("#menu_patient").addClass("active");
        });
       function disease()
       {
           let ds = $('input[name=ch]:checked').val();
           if(ds=='yes')
           {
               $('#ds_list').removeClass('hide');
               $('#ds_list').addClass('show');
           }
           else{
                $('#ds_list').removeClass('show');
                $('#ds_list').addClass('hide');
                let chs = $('input.dsch');
                console.log(chs.length);
                for(i=0; i<chs.length;i++)
                {
                    chs[i].checked = false;
                    
                }
           }
       }
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
       function pregnance()
       {
        let ds = $('input[name=pg]:checked').val();
           if(ds=='yes')
           {
               $('#pg_box').removeClass('hide');
               $('#pg_box').addClass('show');
           }
           else{
                $('#pg_box').removeClass('show');
                $('#pg_box').addClass('hide');
               
           }
       }
    </script>
@endsection