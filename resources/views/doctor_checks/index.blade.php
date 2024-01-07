@extends('layouts.master')
@section('title')
    {{__('lb.doctor_check')}}
@endsection
@section('header')
    {{__('lb.doctor_check')}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
    <link rel="stylesheet" href="{{ asset('multiselect/css/bootstrap-multiselect.css') }}">
    <form action="/doctor-check"  method="GET">
        <div class="row mb-3">
            <div class="col-3">
                <div class="row">
                    <div class="col-4">
                        {{__('lb.date')}}
                        <select   name="changdate" id="changdate" class="form-select input-xs mt-1" onchange="changeDate()">
                            <option    value="">គ្រប់ពេល</option>
                            <option value="1">ម្សិលមិញ</option>
                            <option value="2">ថ្ងៃនេះ</option>
                            <option value="4">សប្តាហ៍មុន</option>
                            <option value="3">ខែមុន</option>
                            <option value="5">ចន្លោះពេល</option>

                        </select>
                    </div>

                    <div class="col-4 mt-1">
                        {{__('lb.start_date')}}

                        <input type="date" readonly name="start_date" value="{{ $start_date }}" class="form-control input-xs"  id="start_date">
                    </div>
                    <div class="col-4 mt-1">
                        {{__('lb.to')}}
                        <input type="date" readonly name="end_date" value="{{ $end_date }}" class="form-control input-xs"  id="end_date">
                    </div>
                </div>

                <div class=" mt-3">
                    <div class="col-xs-12">
                        @php
                        $doctor_translates = DB::table('users')->where('active',1)->get();
                        @endphp
                        <select   name="doctor_translate" id="doctor_translate"  class="mt-1 input-xs chosen-select"     >
                            <option value="" {{$doc_tran===""?'selected':''}}>{{__('lb.select_one')}}</option>
                            @foreach ($doctor_translates as $d)

                                <option
                                        @if($doc_tran)
                                        {{$doc_tran == $d->id ? 'selected':''}}
                                        @endif

                                          value="{{ $d->id }}">{{ $d->first_name }} - {{ $d->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>






            </div>

            <div class="col-7">
                <div class="row">
                    <div class="col-6">
                        {{__('lb.hospital')}}

                        <select   name="hospital_id[]" id="hospital_id"  class="mt-1 input-xs "   multiple="multiple">


                            @foreach ($hospitals as $s)


                                <option   @if($h)  @foreach ($h as $hid)
                                    @if($hid == $s->id) selected @endif

                                          @endforeach
                                          @endif

                                          value="{{ $s->id }}">{{ $s->name }}</option>



                            @endforeach

                        </select>
                    </div>

                    <div class="col-6">
                        {{__('lb.section')}}

                        <select   name="section_id[]" id="section_id"  class="mt-1 input-xs "   multiple="multiple">


                            @foreach ($sections as $sec)

                                <option
                                        @if($section)
                                            @foreach ($section as $se)
                                                @if($se==$sec->id) selected @endif;
                                        @endforeach
                                        @endif
                                        value="{{ $sec->id }}">{{ $sec->name }}</option>




                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        {{__('lb.keyword')}}
                        <input type="text" name="keyword" value="{{$keyword}}" id="keyword"  placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
                    </div>
                    <div class="col-6 " style="margin-top: 10px;">
                        <div class="mt-2">


                            <select name="status" class="input-xs form-control">
                                <option value="" {{$status===""?'selected':''}}>{{__('lb.select_one')}}</option>
                                <option class="text-success" value="1" {{$status==1?'selected':''}}>{{__('lb.scheduling')}}</option>
                                <option style="color: #007afd;" value="2"  {{$status==2?'selected':''}}>{{__('lb.confirmed')}}</option>
                                <option style="color: #d4be00;" value="3"  {{$status==3?'selected':''}}>{{__('lb.arrived')}}</option>
                                <option style="color: #c37636;" value="4"  {{$status==4?'selected':''}}>{{__('lb.rescheduled')}}</option>
                                <option value="5"  {{$status==5?'selected':''}}>{{__('lb.waiting_shot')}}</option>
                                <option value="6" {{$status==6?'selected':''}}>{{__('lb.performing')}}</option>
                                <option value="8" {{$status==8?'selected':''}}>{{__('lb.waiting_reading')}}</option>
                                <option value="9" {{$status==9?'selected':''}}>{{__('lb.reading')}}</option>
                                <option value="11" {{$status==11?'selected':''}}>{{__('lb.validated')}}</option>
                                <option style="color: red;" value="0" {{$status==="0"?'selected':''}}>{{__('lb.canceled')}}</option>
                            </select>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-2 mt-4">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-primary">{{__('lb.search')}}</button>
                    </div>
                    <div class="col-6">
                        <a type="button"  class="btn btn-danger" href="/doctor-check">សំអាត</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- <div class="toolbox pt-1 pb-1 pl-2">
        <div class="col-md-12">
      <div class="row">
          <div class="col-md-3 pt-3 pl-0 pr-0">



        </div>
        <div class="col-md-2">
            <form action="{{url('front-office')}}" method="GET">
                <div class="row">
    {{__('lb.start_date')}}

     <input type="date" value="{{$start_date}}" name="start_date" class="form-control input-xs"></div>
    </div>
    <div class="col-md-2">
      {{__('lb.to')}} <input type="date" value="{{$end_date}}" name="end_date" class="form-control input-xs">
    </div>
    <div class="col-md-3">
        {{__('lb.body_part')}}
        <select name="section" id="section" class="chosen-select">
            <option value="">{{__('lb.select_one')}}</option>
            @foreach ($sections as $sec)
            <option value="{{$sec->id}}" {{$sec->id==$section?'selected':''}}>{{$sec->name}}</option>
            @endforeach

        </select>
       </div>
    </div>

    <div class="row">
        <div class="col-md-3 pt-3 pl-0 pr-0">

        </div>
    <div class="col-md-2 p-0 pt-1">
        <input type="text" name="keyword" value="{{$keyword}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
       </div>
       <div class="col-md-2 pt-1">

       <select name="status" class="input-xs form-control">
           <option value="" {{$status===""?'selected':''}}>{{__('lb.select_one')}}</option>
           <option class="text-success" value="1" {{$status==1?'selected':''}}>{{__('lb.scheduling')}}</option>
           <option style="color: #007afd;" value="2"  {{$status==2?'selected':''}}>{{__('lb.confirmed')}}</option>
           <option style="color: #d4be00;" value="3"  {{$status==3?'selected':''}}>{{__('lb.arrived')}}</option>
           <option style="color: #c37636;" value="4"  {{$status==4?'selected':''}}>{{__('lb.rescheduled')}}</option>
           <option value="5"  {{$status==5?'selected':''}}>{{__('lb.waiting_shot')}}</option>
           <option value="6" {{$status==6?'selected':''}}>{{__('lb.performing')}}</option>
           <option value="8" {{$status==8?'selected':''}}>{{__('lb.waiting_reading')}}</option>
           <option value="9" {{$status==9?'selected':''}}>{{__('lb.reading')}}</option>
           <option value="11" {{$status==11?'selected':''}}>{{__('lb.validated')}}</option>
           <option style="color: red;" value="0" {{$status==="0"?'selected':''}}>{{__('lb.canceled')}}</option>
       </select>
    </div>
    <div class="col-md-2 pt-1">
        <button style="height: 26px;">
            <i class="fa fa-search"></i> {{__('lb.search')}}
        </button>
    </div>

    </div>
    </form>
    </div>  --}}
    <div class="card">

        <div class="card-body">
            @component('coms.alert')
            @endcomponent
            <table class="table table-sm table-bordered " style="width: 100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">{{__('lb.request_code')}}</th>
                    <th>{{__('lb.patients')}}</th>
                    <th>{{__('lb.name_en')}}</th>

                    <th>{{__('lb.gender')}}</th>
                    <th>{{__('lb.hospitals')}} & {{__('lb.reference_no')}}</th>
                    <th>{{__('lb.body_part')}}</th>
                    <th>{{__('lb.items')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.time')}}</th>
                    <th>វេជ្ជបណ្ឌិតបកប្រែ</th>
                    <th  width="79">{{__('lb.status')}}</th>
                    <th >{{__('lb.action')}}</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $pagex = @$_GET['page'];
                if(!$pagex)
                    $pagex = 1;
                $i = config('app.row') * ($pagex - 1) + 1;
                ?>
                @foreach($technicals as $t)
                    <?php $patient = DB::table('customers')->where('id', $t->patient_id)->first();
                    $dob = \Carbon\Carbon::parse( $patient->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');
                    $request = DB::table('requestchecks')->where('id', $t->request_id)->first();


                    ?>
                    <tr
                            @if($t->request_status==8)style="background: #acd1f9;"
                            @elseif($t->request_status==9)style="background: #ffecc7;"
                            @elseif($t->request_status==10)style="background: #ffecc7;"
                            @endif
                    >
                        <td>{{$i++}}</td>
                        <td class="text-center">   <a href="{{url('request/detail', $t->request_id)}}" title="{{__('lb.detail')}}"
                            >{{$request->code}}</a></td>
                        <td
                                title="ភេទ {{ $patient->gender }} ថ្ងៃខែឆ្នាំកំណើត {{ $patient->dob }}({{ \Carbon\Carbon::parse($patient->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ') }}) លេខទូរសព្ទ {{ $patient->phone }}"
                        > {{$patient->kh_first_name}} {{$patient->kh_last_name}}</td>
                        <td> {{$patient->en_first_name}} {{$patient->en_last_name}}</td>

                        <td>{{$patient->gender}}</td>
                        <td>{{$request->hospital_reference}} - {{$t->name}}</td>
                        <td>{{$t->section_name}}</td>
                        <td>
                            <a 
                                href=@if($t->request_status == 8) "{{url('doctor-check/create', $t->id)}}"
                                @elseif($t->request_status == 9) "{{url('doctor-check/create', $t->id)}}"
                                @elseif($t->request_status == 10) "{{url('doctor-check/create', $t->id)}}"
                                @elseif($t->request_status == 11) "{{url('doctor-check/detail', $t->id)}}"
                                @endif
                                title="{{__('lb.detail')}}">
                                {{$t->item_name}}
                            </a>
                        </td>
                        <td>{{$t->date}}</td>
                        <td> {{\Carbon\Carbon::createFromFormat('H:i:s',$t->time)->format('h:i A')}}</td>
                        <td>
                            @php
                                $translation = DB::table('users')->where('id',$t->percent3)->first();
                            @endphp
                            @if($translation)
                                {{$translation->first_name}} {{$translation->last_name}}
                            @endif
                        </td>
                        <td>
                            @if($t->request_status==5) {{__('lb.waiting_shot')}}
                            @elseif($t->request_status==6) {{__('lb.performing')}}
                            @elseif($t->request_status==7) {{__('lb.done')}}
                            @elseif($t->request_status==8) {{__('lb.waiting_reading')}}
                            @elseif($t->request_status==9) {{__('lb.reading')}}
                            @elseif($t->request_status==10) {{__('lb.reading')}}
                            @elseif($t->request_status==11) {{__('lb.validated')}}
                            @endif</td>
                        <td>
        
                            @if($t->request_status==11)
                                <a href="{{url('/doctor-check/print/'.$t->id)}}" target="_blank" class="btn btn-primary btn-xs">
                                    <i class="fa fa-print"></i>
                                </a>
                            @endif


                            {{--                              @candelete('front_office')--}}
                            {{--                            <a href="{{url('front-office/request-delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">--}}
                            {{--                                <i class="fa fa-trash"></i>--}}
                            {{--                            </a>--}}
                            {{--                           --}}
                            {{--                            @endcandelete--}}
                                @if($t->request_status==11)
                                    <a href="{{url('doctor-check/detail', $t->id)}}" class="btn btn-success btn-xs"  >
                                        <i class="fa fa-edit"></i>
                                    </a>

                                @else
                                    <a href="{{url('doctor-check/create', $t->id)}}" class="btn btn-success btn-xs"  >
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table> <br>
            {{$technicals->links('pagination::bootstrap-4')}}
        </div>
    </div>

@endsection

@section('js')
    <script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
    <script src="{{ asset('multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_doctor_check").addClass("active");
            $('#hospital_id').multiselect({
                enableHTML:true,

                includeSelectAllOption:true,
                selectAllText:' គ្រប់មន្ទីពេទ្យ',
                selectAllValue:'multiselect-all',
                selectAllNumber:false,
                selectAllJustVisible:true,
                maxHeight:false,

                buttonWidth: '100%',

            });

            $('#section_id').multiselect({


                includeSelectAllOption:true,
                selectAllText:' គ្រប់ផ្នែក',
                selectAllValue:'multiselect-all',
                selectAllNumber:false,
                selectAllJustVisible:true,
                maxHeight:false,

                buttonWidth: '100%',

            });
        });
        function changeDate()
        {

            var selVal = $('#changdate').val();
            sessionStorage.setItem("SelItem", selVal);

            var i = $('#changdate').val();
            const date = new Date();
            var isoDateTime = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));
            var todayDate = new Date(isoDateTime.getFullYear()+'-'+isoDateTime.getMonth()+'-'+isoDateTime.getDate());

            if(i==1)
            {

                var year = date.getFullYear();
                var month = (date.getMonth()+1).toString();
                var day = (date.getDate()-1).toString();
                if(day.length<2)
                {
                    day = '0'+day;
                }

                if(month.length<2)
                {
                    month = '0'+month;
                }


                var yesterday = year+'-'+month +'-'+day;
                //    console.log(yesterday);
                $('#start_date').val(yesterday);
                $('#end_date').val(yesterday);


            }
            else if(i==2)
            {
                var year = date.getFullYear();
                var month = (date.getMonth()+1).toString();
                var day = (date.getDate()).toString();
                if(day.length<2)
                {
                    day = '0'+day;
                }

                if(month.length<2)
                {
                    month = '0'+month;
                }

                var today = year+'-'+month +'-'+day;

                $('#start_date').val(today);
                $('#end_date').val(today);

            }
            else if(i==4)
            {

                var year = date.getFullYear();
                var month = (date.getMonth()+1).toString();
                var day = (date.getDate()).toString();
                var last7day = (date.getDate()-7).toString();
                console.log(last7day);
                if(day.length<2 )
                {
                    day = '0'+day;
                }

                if(month.length<2)
                {
                    month = '0'+month;
                }
                if(last7day<=0)
                {
                    last7day='1';
                }
                if(last7day.length<2 )
                {
                    last7day = '0'+last7day;
                }


                var lastweek = year+'-'+month +'-'+last7day;;
                var thisweek = year+'-'+month +'-'+day;;




                $('#start_date').val(lastweek);
                $('#end_date').val(thisweek);

            }
            else if(i==5)
            {




                $('#start_date').attr("readonly", false);
                $('#end_date').attr("readonly", false);

            }
            else if(i=="")
            {
                $('#start_date').val("");
                $('#end_date').val("");
            }
            else if(i==3)
            {
                var mydate = new Date(date.getFullYear(),date.getMonth()-1,date.getDate());

                var firstdate = new Date(mydate.getFullYear(),mydate.getMonth(),1);
                var lastdate = new Date(mydate.getFullYear(),mydate.getMonth()+1,0);
                var last = lastdate.getDate().toString();
                var first = firstdate.getDate().toString();
                var month = date.getMonth().toString();
                if(first.length < 2)
                {
                    first = '0'+first;
                }
                if(month.length < 2 )
                {
                    month = '0'+month;
                }

                var start_date = date.getFullYear()+'-'+month+'-'+first;
                var end_date = date.getFullYear()+'-'+month+'-'+last;


                $('#start_date').val(start_date);
                $('#end_date').val(end_date);

            }


        };
        window.onload = function() {
            if($('#start_date').val()=="" || $('#end_date').val()=="" )
            {

            }else
            {
                var selItem = sessionStorage.getItem("SelItem");
                console.log(selItem);
                $('#changdate').val(selItem);
            }
        };
        function redirect()
        {

            $('#start_date').val('');
            $('#end_date').val('');
            $('#keyword').val('');
            window.location.href = '';
        };

        $("#printSticker").click(function(){
            $("#print").toggle();
        });
    </script>
@endsection