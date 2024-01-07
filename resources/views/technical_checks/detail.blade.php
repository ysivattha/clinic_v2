@extends('layouts.master')
@section('title')
    {{__('lb.technical_checks')}}
@endsection
@section('header')
    {{__('lb.technical_checks')}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
    <div class="toolbox pt-1 pb-1">
        @canedit('technical_check')
        @if($request_detail->request_status == 5)
            <a href="{{route('technical.confirm',$request_detail->id)}}" class="btn btn-success btn-sm"  onclick="return confirm('{{__('lb.alert_confirm_check')}}')">
                <i class="fa fa-check-circle"></i> {{__('lb.performing')}}
            </a>
        @endif
        @if($request_detail->request_status == 6)
            <a href="{{route('technical.done',$request_detail->id)}}" class="btn btn-primary btn-sm"  onclick="return confirm('{{__('lb.alert_finished')}}')">
                <i class="fa fa-check-circle"></i> {{__('lb.done')}}
            </a>
        @endif
        @if($request_detail->request_status <= 5)
            <a href="{{route('technical.canceled',$request_detail->id)}}" class="btn btn-danger btn-sm"  onclick="return confirm('{{__('lb.firm_done')}}')">
                <i class="fa fa-times-circle"></i> {{__('lb.canceled')}}
            </a>
        @endif
        @endcanedit
{{--        @candelete('technical_check')--}}
{{--        <a href="{{url('technical/request-delete/'.$request_detail->id)}}" class="btn btn-danger btn-sm"--}}
{{--           onclick="return confirm('{{__('lb.confirm')}}')">--}}
{{--            <i class="fa fa-trash"></i> {{__('lb.delete')}}--}}
{{--        </a>--}}
{{--        @endcandelete--}}
        <a href="{{route('technical.index')}}" class="btn btn-success btn-sm">
            <i class="fa fa-reply"></i> {{__('lb.back')}}
        </a>

            <a href="{{url('technical/print/'.$request_detail->id)}}" target="_blank" class="btn btn-primary btn-sm">
                <i class="fa fa-print"></i> {{__('lb.print')}}

            </a>
    </div>
    <div class="card">

        <div class="card-body">
            @component('coms.alert')
            @endcomponent
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row mb-1">
                        <label class='col-sm-3'>{{__('lb.request_code')}}</label>
                        <div class="col-sm-8">
                            : {{$request->code}}
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class='col-sm-3'>{{__('lb.patient')}}</label>
                        <div class="col-sm-8">
                            : <a href="{{url('patient/detail/'.$request->pid)}}"> {{$request->pfirst_name}}  {{$request->plast_name}} (  {{$request->en_first_name}} - {{$request->en_last_name}})  -{{$request->gender}} -   {{__('lb.age')}} :  {{$dob}}
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
                        <label class='col-sm-3'>{{__('lb.time_technical')}}</label>
                        <div class="col-sm-8">
                            :  @if($request_detail->time_technical!=null){{date('d-m-Y​ H:i A', strtotime($request_detail->time_technical))}}@endif
                        </div>
                    </div>

                    <div class="form-group row mb-1">
                        <label class='col-sm-3'>{{__('lb.percent1')}}</label>
                        <div class="col-sm-8">
                            : {{$request_detail->dfirst_name}} {{$request_detail->dlast_name}} ( {{$request_detail->phone}} )
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class='col-sm-3'>{{__('lb.hospitals')}}</label>
                        <div class="col-sm-8">
                            @php
                                $hos = DB::table('hospitals')->where('id',$request->hospital_id)->first();

                            @endphp
                            :{{$request->hospital_reference}}  {{$hos->name}}
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class='col-sm-3'>អ្នកស្នើសុំ វេជ្ជ/សាស្រ្ត.</label>
                        <div class="col-sm-8">

                            :  {{$request_detail->behind_of}}
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th> {{__('lb.body_part')}}</th>
                            <th>{{__('lb.items')}}</th>
                            <th>{{__('lb.request_datetime')}}</th>
                            <th>{{__('lb.translator')}}</th>
                            <th>{{__('lb.status')}}</th>
                        </tr>
                        </thead>
                        <tbody id="data">
                        <tr>
                            <td>{{$request_detail->section_name}}</td>
                            <td>{{$request_detail->item_name}}</td>
                            <td>{{date('d-m-Y', strtotime($request->date))}} {{date('H:i A', strtotime($request->time))}}</td>
                            @php
                            $translator = DB::table('users')->where('id',$request_detail->percent3)->first();
                            @endphp

                            <td>  @if($translator){{$translator->first_name}}     @endif</td>

                            <td> @if($request_detail->request_status==5) {{__('lb.waiting_shot')}}
                                @elseif($request_detail->request_status==6) {{__('lb.performing')}}
                                @elseif($request_detail->request_status==7) {{__('lb.done')}}
                                @elseif($request_detail->request_status==8) {{__('lb.waiting_reading')}}
                                @elseif($request_detail->request_status==9) {{__('lb.reading')}}
                                @elseif($request_detail->request_status==10) {{__('lb.reading')}}
                                @elseif($request_detail->request_status==11) {{__('lb.validated')}}
                                @endif
                            </td>


                        </tr>
                        </tbody>
                    </table>
                    <form action="{{route('technical.send',$request_detail->id)}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$request_detail->id}}" name="id">
                    <div class="form-group row mb-1">

                        <label class='col-sm-3 text-right'>ស្នើសុំ វេជ្ជបណ្ឌិតបកប្រែ</label>

                        <div class="col-sm-3">

                            <select class="chosen-select" >
                                <option value="0">{{__('lb.select_one')}}</option>
                                @php

                                @endphp

                                @foreach($departments  as $dep)
                                    <option {{$sections->department_id == $dep->id?'selected':''}} value="{{$dep->id}}">{{$dep->name}}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="translate"  class="chosen-select"  required>
                                <option value="0">{{__('lb.select_one')}}</option>
                                @foreach($users as $doc)
                                    <option value="{{$doc->id}}">{{$doc->code}}-{{$doc->first_name}} {{$doc->last_name}} ({{$doc->phone}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-3">

                                <button {{($request_detail->request_status >=9 ) ? 'disabled':''}} type="submit" class="btn btn-primary btn-xs mb-1"><i class="fa fa-user-md" aria-hidden="true">{{__('lb.urgent_request_to_doctor')}}</i></button>
{{--                                <a  href="{{url('technical/send-doctor',$request_detail->id)}}"  class="btn btn-primary btn-xs mb-1" disabled>--}}
{{--                                    <i class="fa fa-user-md" aria-hidden="true"></i> {{__('lb.urgent_request_to_doctor')}}--}}
{{--                                </a>--}}

                        </div>


                    </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-success">{{__('lb.add_techincal_info')}}
                        @cancreate('technical_check')
                        @if($request_detail->request_status > 5)
                            @if($request_detail->percent2 == null )
                                <button class="btn btn-success btn-xs" data-toggle='modal' data-target='#createModal' id='btnCreate'>
                                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                                    @endif
                                </button>
                            @endif
                            @endcancreate</h6>
                </div>
                @if($request_detail->contrast_enhancement!=null)
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>

                                <th>{{__('lb.percent2')}}​ 1</th>
                                <th>{{__('lb.percent2')}} 2</th>
                                <th> {{__('lb.contrast_enhancement')}}</th>
                                <th>{{__('lb.fil')}}</th>
                                <th>{{__('lb.view')}}</th>
                                <th>{{__('lb.protocol_category')}}</th>
                                <th>{{__('lb.protocol')}}</th>
                                <th>{{__('lb.protocol_qty')}}</th>
                                <th>{{__('lb.note')}}</th>
                                @if($request_detail->request_status > 6)
                                    <th>{{__('lb.action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="data">
                            <?php
                            $percent2 = DB::table('users')->where('id', $request_detail->percent2)->first();
                            $percent2_ass = DB::table('users')->where('id', $request_detail->percent2_ass)->first();
                            $protocol = DB::table('request_protocols')->where('request_detail_id', $request_detail->id)->where('active', 1)->get();
                            ?>
                            <tr>
                                <td>
                                    {{$percent2->first_name}} {{$percent2->last_name}}
                                </td>
                                <td>
                                    @if($percent2_ass)
                                    {{$percent2_ass->first_name}} {{$percent2_ass->last_name}}
                                    @endif
                                </td>
                                <td>{{$request_detail->contrast_enhancement}}</td>
                                <td>{{$request_detail->fil_size}}</td>
                                <td>{{$request_detail->fil_qty}}</td>
                                <td>@if($request_detail->protocol_category_name!=null){{$request_detail->protocol_category_name}}@endif</td>
                                <td>
                                    @foreach($protocol as $p)
                                        {{$p->name}},
                                    @endforeach
                                </td>
                                <td>{{$request_detail->protocol_qty}}</td>
                                <td>{{$request_detail->technical_note}}</td>

                                @if($request_detail->request_status > 5)
                                    <th>

                                        <a href="{{url('technical/delete/'.$request_detail->id)}}" class="btn btn-danger btn-xs"
                                           onclick="return confirm('{{__('lb.confirm')}}')">
                                            <i class="fa fa-trash"></i>
                                        </a>

                                    </th>
                                @endif
                            </tr>
                            </tbody>
                        </table>



                    </div>
                @endif
            </div>
        </div>
        <!-- create model -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{url('technical/save/'.$request_detail->id)}}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <strong class="modal-title">{{__('lb.create_technical')}}</strong>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="time_technical"​ class="col-md-3">
                                    {{__('lb.time_technical')}}<span class="text-danger">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="datetime-local" value="{{Carbon\Carbon::now()->format('Y-m-d')."T".Carbon\Carbon::now()->format('H:i')}}" name="time_technical" class="form-control input-xs">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="percent2"​ class="col-md-3">
                                    {{__('lb.percent2')}} 1<span class="text-danger">*</span>
                                </label>
                                <div class="col-md-9">
                                    <select name="percent2" id="percent2" class="form-control chosen-select"  required>
                                        <option value="0">{{__('lb.select_one')}}</option>
                                        @foreach($doctors as $d)
                                            <option value="{{$d->id}}" {{Auth::user()->id==$d->id?'selected':''}}>{{$d->first_name}} {{$d->last_name}}  ( {{$d->phone}} )</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="percent2_ass" class="col-md-3">
                                    {{__('lb.percent2')}} 2
                                </label>
                                <div class="col-md-9">
                                    <select name="percent2_ass" id="percent2_ass" class="form-control chosen-select"  >
                                        <option value="0">{{__('lb.select_one')}}</option>
                                        @foreach($doctors as $d)
                                            <option value="{{$d->id}}" {{Auth::user()->id==$d->id?'selected':''}}>{{$d->first_name}} {{$d->last_name}}  ( {{$d->phone}} )</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">

                                <label for="protocol_category_id" class="col-md-3">
                                    {{__('lb.protocol_category')}}
                                </label>
                                <div class="col-md-9">
                                    <select name="protocol_category_id" id="protocol_category_id" class="form-control chosen-select" onchange="getProtocol()">
                                        <option value="0">{{__('lb.select_one')}}</option>
                                        @foreach ($protocol_categories as $pc)
                                            <option value="{{$pc->id}}"> {{$pc->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden"  id="protocol_category_name" name="protocol_category_name">
                            </div>
                            <div class="form-group row">

                                <label class="col-md-3">
                                    {{__('lb.protocol')}}
                                </label>
                                <div class="col-md-9">
                                    <select name="protocol[]" id="protocol" class="form-control chosen-select" multiple>


                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fil_size" class="col-md-3">
                                    {{__('lb.fil')}}
                                </label>
                                <div class="col-md-4">
                                    <select name="fil_size" id="fil_size" class="form-control input-xs" >
                                        <option value="">{{__('lb.select_one')}}</option>
                                        @foreach($fils as $f)
                                            <option value="{{$f->name}}">{{$f->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fil_qty" class="col-md-3">
                                    {{__('lb.view')}}
                                </label>
                                <div class="col-md-4">
                                    <select name="fil_qty" id="fil_qty" class="form-control chosen input-xs"  >
                                        <option value="">{{__('lb.select_one')}}</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="not_used" class="col-md-3">
                                    {{__('lb.contrast_enhancement')}}
                                </label>
                                <div class="col-md-8">
                                    <input type="radio" id="not_used" name="contrast_enhancement" value="{{__('lb.not_used')}}" onclick="check_not_used()" checked>
                                    <label for="not_used">{{__('lb.not_used')}}</label> &nbsp;

                                    <input type="radio" id="used" name="contrast_enhancement" onclick="check_used()" value="{{__('lb.used')}}">
                                    <label for="used"> {{__('lb.used')}}</label>
                                    <span class="hide" id="hide_check">
                            <input type="checkbox" id="iv" name="iv" value="{{__('lb.iv')}}">
                            <label for="iv"> {{__('lb.iv')}}</label>&nbsp;&nbsp;

                            <input type="checkbox" id="po" name="po" value="{{__('lb.po')}}">
                            <label for="po"> {{__('lb.po')}}</label>&nbsp;&nbsp;

                            <input type="checkbox" id="enema" name="enema" value="{{__('lb.enema')}}">
                            <label for="enema">{{__('lb.enema')}}</label>&nbsp;&nbsp;

                            <input type="checkbox" id="other" name="other" value="{{__('lb.other')}}">
                            <label for="other">{{__('lb.other')}}</label>
                             <div>
                                      <label for="quantity">បរិមាណ</label>
                                      <input type="text" id="quantity" name="quantity" class="m-1" />
                             </div>
                        </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="note" class="col-md-3">
                                    {{__('lb.note')}}
                                </label>
                                <div class="col-md-9">
                                    <textarea name="note" id="note" cols="3" class="form-control" rows="2"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> {{__('lb.save')}}
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"
                                    onclick="reset('#create_form')">
                                <i class="fa fa-times"></i> {{__('lb.close')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endsection

        @section('js')
            <script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
            <script>
                $(document).ready(function () {
                    $(".chosen-select").chosen({width: "100%"});
                    $("#sidebar li a").removeClass("active");
                    $("#menu_technical_check").addClass("active");
                    $("#protocol_category_id").change(function () {
                        getProtocol();
                        let category_id =  "";
                        if($('#protocol_category_id').val() != 0) {
                            category_id =  $('#protocol_category_id option:selected').text();
                        }
                        $('#protocol_category_name').val(category_id);
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
                function getProtocol()
                {
                    // get sub category
                    $.ajax({
                        type: "GET",
                        url: burl + "/get/protocol/" + $("#protocol_category_id").val(),
                        success: function (data) {
                            opts = '<option value="0">{{__('lb.select_one')}}</option>';
                            for(var i=0; i<data.length; i++)
                            {
                                opts +="<option value='" + data[i].name + "' id='" + data[i].id +"' >"+ data[i].name + "</option>";

                            }
                            $("#protocol").html(opts);
                            $("#protocol").trigger("chosen:updated");
                        }
                    });
                }
            </script>
@endsection