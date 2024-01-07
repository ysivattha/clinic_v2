@extends('layouts.master')
@section('title')
    {{__('lb.doctor_check')}}
@endsection
@section('header')
    {{__('lb.doctor_check')}}  / {{$request->code}} / <a href="{{url('patient/detail/'.$request->pid)}}" class="text-white"> {{$request->pfirst_name}}  {{$request->plast_name}} ( {{$request->phone}} )
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<form action="{{url('doctor-check/save/'.$request_detail->id)}}" method="POST">
   
<div class="toolbox pt-1 pb-1">
    @cancreate('doctor_check')
  
        <button class="btn btn-success btn-sm" name='submit' value='save' data-toggle='modal' data-target='#createModal' id='btnCreate'>
            <i class="fa fa-save"></i> {{__('lb.save')}}
        </button>
  @endcancreate
    @canedit('doctor_check')
    @if($request_detail->request_status == 10 || $request_detail->request_status == 9)
        <button class="btn btn-success btn-sm" name='submit' value='saveAndValidated' data-toggle='modal' data-target='#createModal' id='btnCreate' onclick="return confirm('{{__('lb.alert_validated')}}')">
            <i class="fa fa-check-circle"></i> {{__('lb.save_validated')}}
        </button>
    @endif
    @endcanedit
   <a href="{{route('doctor_check.index')}}" class="btn btn-success btn-sm">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
   @if($request_detail->request_status == 4)
   <a href="{{url('technical/print/'.$request_detail->id)}}" class="btn btn-primary btn-sm">
    <i class="fa fa-print"></i> {{__('lb.print')}}
    @endif
</a>
<label class='col-sm-3'>{{__('lb.status')}}  : <span id="status"> @if($request_detail->request_status==8) {{__('lb.waiting_reading')}} 
    @elseif($request_detail->request_status==9) {{__('lb.reading')}} 
    @elseif($request_detail->request_status==10) {{__('lb.reading')}} 
    @elseif($request_detail->request_status==11) {{__('lb.validated')}} @endif</span></label>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <div class="row">
          <div class="col-sm-6">
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.code')}}</label>
                <div class="col-sm-8">
                    : {{$request->code}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.patient')}}</label>
                <div class="col-sm-8">
                    : <a href="{{url('patient/detail/'.$request->pid)}}"> {{$request->pfirst_name}}  {{$request->plast_name}}   - {{$request->gender}} -   {{__('lb.age')}} :  {{$dob}}
                    </a>
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.symptom')}}</label>
                <div class="col-sm-8">
                    : {{$request->symptom}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.note')}}</label>
                <div class="col-sm-8">
                    : {{$request_detail->request_note}}
                </div>
            </div>
          
          </div>
          <div class="col-sm-6">
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.time_translate')}}</label>
                <div class="col-sm-6">
                     <input type="datetime-local" value="{{Carbon\Carbon::now()->format('Y-m-d')."T".Carbon\Carbon::now()->format('H:i')}}" class="form-control input-xs" name="time_translate">
                </div>
            </div>
            
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.items')}}</label>
                <div class="col-sm-8">
                    : {{$request_detail->item_name}} 
                </div>
            </div>
            <?php 
                $percent2 = DB::table('users')->where('id', $request_detail->percent2)->first();
                $percent2_ass = DB::table('users')->where('id', $request_detail->percent2_ass)->first();
                $protocol = DB::table('request_protocols')->where('request_detail_id', $request_detail->id)->where('active', 1)->get();
            ?>
            
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.percent2')}} 1</label>
                <div class="col-sm-8"> 
                    : @if($percent2!=null) {{$percent2->first_name}} {{$percent2->last_name}} ( {{$percent2->phone}} ) @endif
                    
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.percent2')}} 2</label>
                <div class="col-sm-8">
                    :  @if($percent2!=null) {{$percent2_ass->first_name}} {{$percent2_ass->last_name}} ( {{$percent2_ass->phone}} ) @endif
                </div>
            </div>
           
          </div>
      </div>
      <div class="row">
          <div class="col-sm-12">
                @csrf
                <div class="row">
              
                    
                    <div class="col-md-6 pb-2">
                        <div class="form-group row mb-1">
                        <label class='col-sm-3' for="section_id">
                                    {{__('lb.body_part')}}
                                </label>
                                <div class="col-sm-8">
                            <select name="section_id" id="section_id" class="form-control chosen-select">
                                <option value="0">{{__('lb.select_one')}}</option>
                                @foreach ($sections as $s)
                                    <option value="{{$s->id}}" {{$s->id==$request_detail->section_id?'selected':''}}>{{$s->code}} - {{$s->name}}</option>
                                @endforeach
                            </select>
                          </div>
                           
                        </div>
                       <div class="form-group row mb-1">
                       <label class='col-sm-3' for="section_id">
                            {{__('lb.templates')}}
                                </label>
                                <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-10">
                            <select name="template" id="template" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach ($templates as $t)
                                    <option value="{{$t->id}}" description='{{$t->description}}'>{{$t->code}} - {{$t->title}}</option>
                                @endforeach
                            </select>
                            </div>
                         
                            
                            <button type="button" id="add" onclick="addTemplate()">
                                {{__('lb.insert')}}
                            </button>

                            </div>
                          </div>
                       </div>

                    </div>   
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                        <label class='col-sm-3'>  {{__('lb.translator')}}</label>
                            <div class="col-sm-8">
                            <select name="percent3" id="percent3" class="form-control chosen-select">
                                    <option value="">{{__('lb.select_one')}}</option>
                                    @foreach ($doctors as $d)
                                        <option value="{{$d->id}}" {{$d->id==Auth::user()->id?'selected':''}}> {{$d->code}} - {{$d->first_name}} {{$d->last_name}} ( {{$d->phone}} )</option>
                                    @endforeach
                                
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class='col-sm-3'> {{__('lb.approvor')}}</label>
                            <div class="col-sm-8">
                            <select name="percent3_approvor" id="percent3_approvor" class="form-control chosen-select">
                               @foreach ($doctors as $d)
                                    <option value="{{$d->id}}" {{$d->id==Auth::user()->id?'selected':''}}> {{$d->code}} - {{$d->first_name}} {{$d->last_name}} ( {{$d->phone}} )</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                </div>
      </div>
    

                       
        <div class=" col-md-9">
            <textarea name="description" class="ckeditor" id="description"  rows="10"><?php echo $request_detail->doctor_description; ?></textarea>
        </div>        
    </div>
                  
    

<input type="hidden" id="request_id" value="{{$request_detail->id}}">
<style>
    .cke_contents {
        max-height: 297mm;
        height: 297mm !important;
        width: 201mm!important;
    }
    .cke_reset {
        width: 203mm;
    }
</style>
@endsection

@section('js')
<script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_doctor_check").addClass("active");
            $("#section_id").change(function () {
                get_template();
            });
        });

        function check_used() {
            $("#hide_check").addClass('show');
            $("#hide_check").removeClass('hide');
			
        }
        function check_not_used() {
            $("#hide_check").addClass('hide');
            $("#hide_check").removeClass('show');
			
        }

        function addTemplate() {
            $.ajax({
                type: "GET",
                url: burl + "/doctor-check/update-status/" + $('#request_id').val(),
                success: function (data) {
                    $("#status").text("{{__('lb.reading')}}");
                    if (confirm("{{__('lb.confirm_template')}}")) {
                        let description = $("#template :selected").attr('description');
                
                    CKEDITOR.instances['description'].setData(description);
                        } else {
                            alert('Why did you press cancel? You should have confirmed');
                        }
                    }
            });
          
        }
        
        function get_template()
        {
            $.ajax({
                type: "GET",
                url: burl + "/doctor-check/get-template/" + $("#section_id").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var description = data[i].description;
                     
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].id + "' description='" + escapeHtml(data[i].description) +"' >" + data[i].code + '-' + data[i].title + "</option>";
                    }
                    $("#template").html(opts);
                    $("#template").trigger("chosen:updated");
                }
            });
        }

        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
@endsection