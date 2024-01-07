@extends('layouts.master')
@section('title')
    {{__('lb.request')}}
@endsection
@section('header')
    {{__('lb.request')}}
@endsection

@section('btn')
@cancreate('request') 
<a href="{{route('request.create')}}">
<button type="button" class="btn btn-success btn-xs">
    <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
</button>
</a>
@endcancreate
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('multiselect/css/bootstrap-multiselect.css') }}">

@endsection
@section('content')
<div class="toolbox pt-1 pb-1 pl-2">
    {{-- <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 pt-3 pl-0 pr-0">

            <a href="{{route('request.today')}}">
                <button class="btn btn-success btn-xs">
                    <i class="fa fa-calendar"></i> {{__('lb.today')}}
                </button>
            </a>
            <a href="{{route('request.yesterday')}}">
            <button class="btn btn-success btn-xs" >
            <i class="fa fa-calendar"></i> {{__('lb.yesterday')}}
        </button>
            </a>
        <a href="{{route('request.week')}}">
        <button class="btn btn-success btn-xs">
            <i class="fa fa-calendar"></i> {{__('lb.last_week')}}
        </button>
        </a>
    </div> --}}
    <div class="col-md-12 pt-3 pb-3">
        <form action="/request" method="GET">
            <div class="row">
                
                <div class="col-1">
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
                <div class="col  mt-1">
                    {{__('lb.start_date')}} 
                    <input type="date" readonly name="start_date" value="{{ $start_date }}" class="form-control input-xs"  id="start_date">
                </div>
    
                <div class="col-6 col-sm-2 mt-1">
                    {{__('lb.to')}}
                    <input type="date" readonly name="end_date" value="{{ $end_date }}" class="form-control input-xs"  id="end_date">
                </div>

                <div class="col-12 col-md-2 mt-1">
                    {{__('lb.keyword')}} 
                    <input type="text" name="keyword" value="{{$keyword}}" id="keyword"  placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
                </div>


                <div class="col-3">
                    {{__('lb.hospital')}}

                    <select   name="hospital_id[]" id="hospital_id"  class="mt-1 input-xs "   multiple="multiple">
        
                     
                        @foreach ($hospitals as $s)
                           
                              <option    @foreach ($h as $hid)
                                  @if($hid == $s->id) selected @endif
                              @endforeach
                                
                                value="{{ $s->id }}">{{ $s->name }}</option>
                         

                      
        
                        @endforeach
                  
                    </select>
                </div>

                <div class="col-1 mt-3">
                  <div class="mt-1">
                    <button style="height: 26px;">
                        <i class="fa fa-search"></i> {{__('lb.search')}}
                    </button>
                  </div>

   

                    
                </div>

                <div class="col-1 mt-3">
                    <div class="mt-1">
                      <button style="height: 26px;" onclick="redirect()">
                          Clear
                      </button>
                    </div>
  
     
  
                      
                </div>
  

   

            </div>

           

         </form>

    </div>
        {{-- <div class="col-md-1">
        <form action="{{url('request')}}" method="GET">
            
            <div class="row">
                

               
                {{__('lb.start_date')}} 

                <input type="date" name="start_date" class="form-control input-xs"></div>
        </div>
        <div class="col-md-1">
        {{__('lb.to')}} <input type="date"  name="end_date" class="form-control input-xs">
        </div>
        <div class="col-md-1">
            {{__('lb.keyword')}} 
            <input type="text" name="keyword" value="{{$keyword}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
        </div>
        <div class="col-md-1 ">
           ផ្នែក
            <select {{ Auth::user()->role_id==105?'disabled':'' }}  name="hospital_id" id="" class="form-select input-xs mt-1">
                <option  {{ Auth::user()->role_id==105?'disabled':'' }}  value="">ទាំងអស់</option>
                @foreach ($hospitals as $s)

                <option   @if((Auth::user()->role_id==105) && (Auth::user()->hospital_id != $s->id)) disabled @endif  {{ ($h==$s->id)?'selected':'' }} value="{{ $s->id }}" >{{ $s->name }}</option>

                @endforeach
          
            </select>
        </div>
        <div class="col-md-2 mt-3">
            <select {{ Auth::user()->role_id==105?'disabled':'' }}  name="hospital_id" id="" class="form-select input-xs mt-1">
                <option  {{ Auth::user()->role_id==105?'disabled':'' }}  value="">ទាំងអស់</option>
                @foreach ($hospitals as $s)

                <option   @if((Auth::user()->role_id==105) && (Auth::user()->hospital_id != $s->id)) disabled @endif  {{ ($h==$s->id)?'selected':'' }} value="{{ $s->id }}" >{{ $s->name }}</option>

                @endforeach
          
            </select>
        </div>
        
        <div class="col-md-1 ml-5"><br>
            <button style="height: 26px;">
                <i class="fa fa-search"></i> {{__('lb.search')}}
            </button>
        </div>

        </form>
        </div>
        </div>   --}}
</div> 
<div class="card">
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered " style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="76"  class="text-center">{{__('lb.request_code')}}</th>
                    <th>{{__('lb.patients')}}</th>
                    <th>{{__('lb.name_en')}}</th>
                    <th>{{__('lb.gender')}}</th>
                    <th width="150">{{__('lb.code_and_hospital')}}</th>
                
                    <th>{{__('lb.items')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.time')}}</th>
                    {{-- <th width="76">{{__('lb.total')}}</th> --}}
                    {{-- <th>{{__('lb.status')}}</th> --}}
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
            <tbody>			
                <?php
                    $pagex = @$_GET['page'];
                    if(!$pagex)
                        $pagex = 1;
                    $i = 1;
                    
                ?>
                @foreach($requests as $t)
                    <?php 
                        $patient = DB::table('customers')->where('id', $t->patient_id)->first();
                        
                        $request_detail = DB::table('request_details')->where('active', 1)->where('request_id', $t->id)->get();
                        $hospital= DB::table('hospitals')->where('id',$t->hospital_id)->orWhere('name',$t->hospital)->first();
                        $total = 0;
                    ?>
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center"><a href="{{url('request/detail/'.$t->id)}}">{{$t->code}}</a></td>
                        <td 
                        id="{{ $t->id }}" 
                     
                        title="ភេទ {{ $patient->gender }} ថ្ងៃខែឆ្នាំកំណើត {{ $patient->dob }}({{ \Carbon\Carbon::parse($patient->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ') }}) លេខទូរសព្ទ {{ $patient->phone }}"
                >
                        {{$patient->kh_first_name}} {{$patient->kh_last_name}}
                        <input id="id" type="text" hidden value={{ $t->id }}>
                        </td>
                        {{-- <td>{{$patient->en_first_name}} {{$patient->en_last_name}}</td> --}}
                        <td >{{$patient->en_first_name}} {{$patient->en_last_name}}</td>
                        <td>{{$patient->gender}}</td>
                        <td width="15%">{{ $t->hospital_reference  }}{{ $hospital->name }}</td>
                 
                        <td >@foreach ($request_detail as $rd)
                            <li style="font-size: 11px;">
                            {{$rd->section_name}} - {{$rd->item_name,6}}
                            <?php   $total += $rd->price - $rd->discount;?>
                        </li>
                        @endforeach
                      
                        </td>
                        
                        <td width="8%">{{\Carbon\Carbon::createFromFormat('Y-m-d', $t->date)->format('d-m-Y')}}</td>
                        <td width="7%"> {{\Carbon\Carbon::createFromFormat('H:i:s',$t->time)->format('h:i A')}}</td>
                        {{-- <td>$ {{number_format($total,2)}}</td> --}}
                        
                        {{-- <td> 
                          
                            @if($t->request_status==1) {{__('lb.scheduling')}} 
                            @elseif($t->request_status==2) {{__('lb.confirmed')}} 
                            @elseif($t->request_status==3) {{__('lb.arrived')}} 
                            @elseif($t->request_status==0) {{__('lb.canceled')}} 
                            @elseif($t->request_status==4) {{__('lb.rescheduled')}} 
                            @elseif($t->request_status==5) {{__('lb.waiting_shot')}} 
                            @elseif($t->request_status==6) {{__('lb.performing')}} 
                            @elseif($t->request_status==7) {{__('lb.done')}} 
                            @elseif($t->request_status==8) {{__('lb.waiting_reading')}} 
                            @elseif($t->request_status==9) {{__('lb.reading')}} 
                            @elseif($t->request_status==10) {{__('lb.reading')}} 
                            @elseif($t->request_status==11) {{__('lb.validated')}} 
                            @endif
                            
                        </td> --}}

                        <td>
                            @candelete('request')
                           
                           <a href="{{url('request/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                               <i class="fa fa-trash"></i>
                           </a>
                           @endcandelete
                           @candelete('request')
                       
                           <a target="_blank" href="{{url('request/detail', $t->id)}}" class="btn btn-success btn-xs"  title="Edit">
                               <i class="fa fa-edit"></i>
                           </a>
                           @endcandelete
                       </td>


                       
                    </tr>
                  
                @endforeach
             
            </tbody>
        </table> <br>
        @if($paginate != 1)
        {{$requests->links('pagination::bootstrap-4')}}
        @endif
	</div>
</div>

@endsection

@section('js')
<script src="{{ asset('multiselect/js/bootstrap-multiselect.js') }}"></script>

<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_request").addClass("active");
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
            $('#btnDelete').onclick=function (id)
            {
                console.log('hi');
            }

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
       
        };

 


     

    </script>
@endsection