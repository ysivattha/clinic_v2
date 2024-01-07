@extends('layouts.master')
@section('title')
    {{__('lb.doctor_check')}}
@endsection
@section('header')
    {{__('lb.doctor_check')}} 
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<form action="{{url('doctor-check/save/'.$request_detail->id)}}" method="POST">
   
<div class="toolbox pt-1 pb-1">

    @canedit('doctor_check')

    @if($request_detail->request_status==10 || $request_detail->request_status==11)
    <a href="{{url('doctor-check/detail/edit',$request_detail->id)}}" class="btn btn-success btn-sm">
        <i class="fa fa-edit"></i> {{__('lb.edit')}}
    </a>
    @endif
    @if($request_detail->request_status == 8)
        <a href="{{route('doctor_check.reviewing',$request_detail->id)}}" class="btn btn-success btn-sm"  onclick="return confirm('{{__('lb.alert_reading')}}')">
            <i class="fa fa-check-circle"></i> {{__('lb.reading')}}
        </a>
    @endif
    @if($request_detail->request_status == 10 || $request_detail->request_status == 19)
        <a href="{{route('doctor_check.validated',$request_detail->id)}}" class="btn btn-success btn-sm"  onclick="return confirm('{{__('lb.alert_validated')}}')">
            <i class="fa fa-check-circle"></i> {{__('lb.save_validated')}}
        </a>
    @endif
    @endcanedit
   <a href="{{route('doctor_check.index')}}" class="btn btn-success btn-sm">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
   @if($request_detail->request_status == 11)
   <a href="{{url('doctor-check/print/'.$request_detail->id)}}" target="_blank" class="btn btn-primary btn-sm">
    <i class="fa fa-print"></i> {{__('lb.print')}}
    @endif
</a>
 
</div>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
      <div class="row">
          <div class="col-sm-6">
           
                @csrf
                <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.status')}}  </label>
                <div class="col-sm-8">
                    :  
                       @if($request_detail->request_status==8) {{__('lb.waiting_reading')}} 
                        @elseif($request_detail->request_status==9) {{__('lb.reading')}} 
                        @elseif($request_detail->request_status==10) {{__('lb.reading')}} 
                        @elseif($request_detail->request_status==11) {{__('lb.validated')}} @endif
                       
                </div>
            </div>
                <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.patient')}}</label>
                <div class="col-sm-8">
                    :  <a href="{{url('patient/detail/'.$request->pid)}}"> 
                        {{$request->pfirst_name}}  {{$request->plast_name}} ( {{$request->phone}} )
                        </a>
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'> {{__('lb.translator')}} </label>
                <div class="col-sm-8">
                    @if($percent3)
                    :  {{$percent3->first_name}}  {{$percent3->last_name}} ( {{$percent3->phone}})
                    @endif
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>  {{__('lb.approvor')}} </label>
                <div class="col-sm-8">
                    @if($percent3_approvor)
                    :  {{$percent3_approvor->first_name}}  {{$percent3_approvor->last_name}} ( {{$percent3_approvor->phone}})
                    @endif
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.time_translate')}}</label>
                <div class="col-sm-8">
                    : {{date('d-m-Y H:i A', strtotime($request_detail->time_translate))}}
                </div>
            </div>
               

                    <div class="row">
                      
                        <div class=" col-md-12">
                            <h6 class="text-success">{{__('lb.translation')}}</h6>
                            {!!$request_detail->doctor_description!!}
                        </div>        
                    </div>
            </form>
          </div>
         
          <div class="col-sm-6">
         
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.items')}}</label>
                <div class="col-sm-8">
                    : {{$request_detail->item_name}} 
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.note')}}</label>
                <div class="col-sm-8">
                    : {{$request_detail->request_note}}
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
</div>
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
            if (confirm("{{__('lb.confirm_template')}}")) {
                let description = $("#template :selected").attr('description');
           
           CKEDITOR.instances['description'].setData(description);
            } else {
                alert('Why did you press cancel? You should have confirmed');
            }
        }
        
        function get_template()
        {
            // get medicine
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