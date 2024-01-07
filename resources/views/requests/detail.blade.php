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
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">


    @candelete('request')
    <a href="{{url('request/delete/'.$request->id)}}" class="btn btn-danger btn-xs" 
        onclick="return confirm('{{__('lb.confirm')}}')">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </a>
    @endcandelete
   <a href="{{route('request.index')}}" class="btn btn-success btn-xs">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <form action="{{url('request/detail/update')}}" method="POST">
        @csrf
        <input type="hidden" name="request_id" value="{{$request->id}}">
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
                    <div class="col-sm-8" id="lb_patient">
                    
                        : <a href="{{url('patient/summary/'.$request->pid)}}"> {{$request->pfirst_name}}  {{$request->plast_name}} - {{$request->phone}}
                        </a>
                    </div>
                    <div class="col-sm-8 hide" id="epatient">
                        <select name="patient_id" id="patient_id" class="form-control chosen-select" required>
                            <option value=""> {{__('lb.select_one')}} </option>
                            @foreach ($patients as $p)
                            <option value="{{$p->id}}"  {{$p->id==$request->patient_id?'selected':''}}>{{$p->code}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}} )</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class='col-sm-3' for="symptom">{{__('lb.symptom')}}</label>
                    <div class="col-sm-8 show" id="symptom">
                        : {{$request->symptom}} 
                    </div>
                    <div class="col-sm-8 hide" id="esymptom">
                        <textarea name="symptom"  class="form-control" cols="3" rows="2">{{$request->symptom}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group row mb-1">
                    <label class='col-sm-3' for="date">{{__('lb.date')}}  & {{__('lb.time')}}</label>
                    <div class="col-sm-8" id="datetime">
                        : {{$request->date}}  
                    </div>
                    <div class="col-md-6 hide" id="edatetime">
                        <div class="row">
                            <div class="col-md-6" >
                                <input type="date" required  id="date" 
                                    name="date" value="{{$request->date}}" class="form-control input-xs">
                                
                            </div>
                            <div class="col-md-4">
                                <input type="time" required  id="time" 
                                name="time" value="{{$request->time}}" class="form-control input-xs">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class='col-sm-3'>{{__('lb.hospitals')}}  <span id="show_no">& {{__('lb.reference_no')}}</span>  </label>
                    <div class="col-sm-8 show" id="lb_hospital">
                        @php
                            $hospitalname = DB::table('hospitals')->where('id',$request->hospital_id)->first();
                        @endphp
                        : {{$request->hospital_reference}} {{$hospitalname->name}}
                    </div>
                    <div class="col-sm-5 hide" id="ehospital">
                        <select name="hospital" id="hospital" class="chosen-select" onchange="getHospitalReference()">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($hospitals as $h) 
                                <option @if((Auth::user()->role_id==105) && (Auth::user()->hospital_id != $h->id)) disabled @endif value="{{$h->name}}" hospital_reference="{{$h->code}}" hospital_id="{{$h->id}}" {{$request->hospital_id==$h->id?'selected':''}}>{{$h->name}}</option>    
                            @endforeach
                        </select>
                
                    </div>
                    
                </div>
                <div class="form-group row hide" id="reference_no">
                    <label class='col-sm-3 ' > {{__('lb.reference_no')}}  </label>
                    <div class="col-sm-5">
                        <input type="hidden" id="hospital_id" name="hospital_id" value="{{$request->hospital_id}}" class="form-control input-xs">
                        
                        <input type="text" id="hospital_reference" name="hospital_reference" value="{{$request->hospital_reference}}" class="form-control input-xs">
                    </div>
                </div>
            <div class="form-group">
                @canedit('request')
                <a href="#" class="btn btn-success btn-xs" id="btnEdit"  onclick="editMaster()">
                    <i class="fa fa-edit"></i> {{__('lb.edit')}}
                </a>
                <button class="btn btn-primary btn-xs hide" type="submit" 
                id='btnSave'>
                    <i class="fa fa-save"></i> {{__('lb.save')}}
                </button>
                <button class="btn btn-danger btn-xs hide" type="button" 
                    id='btnCancel' onclick="cancelMaster()">
                    <i class="fa fa-times"></i> {{__('lb.cancel')}}
                </button> 
                @endcanedit
            </div>
            </div>
        </div>
       </form>
      <h6 class="text-primary">{{__('lb.items')}}
        @if($request->is_invoiced == 1)
        <button class="btn btn-success btn-xs" data-toggle='modal' data-target='#createModal' id='btnCreate'>
            <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
        </button>
       @endif
    </h6>
      <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('lb.body_part')}}</th>
                        <th>{{__('lb.items')}}</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.time')}}</th>
                        <th>{{__('lb.note')}}</th>
                        <th>{{__('lb.percent1')}}</th>
                        <th>{{__('lb.behind_of')}}</th>
                        <th>{{__('lb.price')}}</th>
                        <th>{{__('lb.discount')}}</th>
                        <th>{{__('lb.total')}}</th>
                        <th>{{__('lb.status')}}</th>
                        <th>សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody id="data">
                    @php $i = 1;   $total = 0;@endphp
                    
                    @foreach($request_details as $r)
                    <?php  
                   
                    $sub_total = $r->price - $r->discount; 
                   
                    ?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$r->section_name}}</td>
                            <td>{{$r->item_name}}</td>
                            <td>{{$r->date}}</td>
                            <td>@if($r->time!=null){{\Carbon\Carbon::createFromFormat('H:i:s',$r->time)->format('h:i A')}}@endif</td>
                            <td>{{$r->request_note}}</td>
                            <td>{{$r->dfirst_name}} {{$r->dlast_name}} - {{$r->phone}}</td>
                            <td>{{$r->behind_of}}</td>
                            <td>$ {{$r->price}}</td>
                            <td>$ {{$r->discount}}</td>
                            <td>$ {{$sub_total}}</td>
                            <td> @if($r->request_status==1) {{__('lb.scheduling')}} 
                                @elseif($r->request_status==2) {{__('lb.confirmed')}} 
                                @elseif($r->request_status==3) {{__('lb.arrived')}} 
                                @elseif($r->request_status==0) {{__('lb.canceled')}} 
                                @elseif($r->request_status==4) {{__('lb.rescheduled')}} 
                                @elseif($r->request_status==5) {{__('lb.waiting_shot')}} 
                                @elseif($r->request_status==6) {{__('lb.performing')}} 
                                @elseif($r->request_status==7) {{__('lb.done')}} 
                                @elseif($r->request_status==8) {{__('lb.waiting_reading')}} 
                                @elseif($r->request_status==9) {{__('lb.reading')}} 
                                @elseif($r->request_status==10) {{__('lb.reading')}} 
                                @elseif($r->request_status==11) {{__('lb.validated')}} 
                                @endif</td>
                               
                            <td>
                                @if($r->request_status > 0 && $r->request_status < 5 )
                                <a href="{{url('request/send-technical',$r->id)}}" class="btn btn-primary btn-xs mb-1">
                               <i class="fa fa-camera" aria-hidden="true"></i> {{__('lb.urgent_request_to_technical')}} 
                                </a>
                              
                               @endif
                               @if($r->request_status > 0 && $r->request_status < 8 )
                               <a href="{{url('request/send-doctor',$r->id)}}" class="btn btn-primary btn-xs mb-1">
                                   <i class="fa fa-user-md" aria-hidden="true"></i> {{__('lb.urgent_request_to_doctor')}}
                               </a>
                               @endif
                               @canedit('request')
                               @if($request->is_invoiced == 1)
                                @canedit('request')
                                @if($r->request_status!=11)
                                <a href="#" class="btn-xs btn-success btn mb-1"
                                        onclick='edit_request({{$r->id}}, this)'
                                        data-toggle='modal' data-target='#editModal'
                                        title='{{__('lb.edit')}}'>
                                        <i class="fa fa-edit"></i>
                                    </a>
                                        @else
                                            @if(Auth()->user()->role_id == 1 || Auth()->user()->role_id == 112)
                                                <a href="#" class="btn-xs btn-success btn mb-1"
                                                   onclick='edit_request({{$r->id}}, this)'
                                                   data-toggle='modal' data-target='#editModal'
                                                   title='{{__('lb.edit')}}'>
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
                                    @endif
                                 @endcanedit
                                    
                                @endif
                                @endcanedit
                                @if($request->is_invoiced == 1)
                                    @if($r->request_status != 11)

                                            <a href="{{url('request/request-detail/delete/'.$r->id.'?request_id='.$request->id)}}" title="{{__('lb.delete')}}" class='btn btn-xs btn-danger mb-1'
                                                onclick="return confirm('You want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @else

                                            @if(Auth()->user()->role_id == 1 || Auth()->user()->role_id == 112)
                                                <a href="{{url('request/request-detail/delete/'.$r->id.'?request_id='.$request->id)}}" title="{{__('lb.delete')}}" class='btn btn-xs btn-danger mb-1'
                                                   onclick="return confirm('You want to delete?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif

                                     @endif
                               @endif
                                @if($r->request_status == 11)
                                        <a target="_blank" href="{{url('doctor-check/print',$r->id)}}"  class='btn btn-xs btn-primary mb-1'
                                       >

                                            <i class="fa fa-print"></i>
                                        </a>
                                    @endif
                            </td>
                        </tr>
                        <?php $total += $sub_total;
                    ?>
                    @endforeach
                   
                    @php $i++; @endphp
                    
                </tbody>
            </table>
            <div class="form-group row mb-1">
              
                <div class="col-sm-8 font-weight-bold text-danger">
                    
                    <label class=''>{{__('lb.total')}}</label> : $ {{$total}}
                </div>
            </div>
            </div>
      </div>
      </div>
	</div>
</div>

 <!-- create model -->
 <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('request/detail/save')}}" method="POST">
          @csrf
          <input type="hidden" name="request_id" value="{{$request->id}}">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_position')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                    <div class="form-group row mb-1">    
                        <label for="section_id"​ class="col-md-3">
                            {{__('lb.body_part')}} <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                          <select name="section_id" id="section_id" class="form-control chosen-select">
                              <option value="0">{{__('lb.select_one')}} </option>
                              @foreach($sections as $s)
                                  <option 
                                      value="{{$s->id}}"​
                                  >
                                      {{$s->name}}
                                  </option>
                              @endforeach
                          </select>
                        </div>
                    </div>
                    <input type="hidden" name="section_name" id="section_name">
                    <input type="hidden" name="item_name" id="section_name">
                    <div class="form-group row mb-1">
                        <label for="item" class="col-md-3">
                            {{__('lb.items')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                        <select name="item_id" id="item_id" class="form-control chosen-select"  onchange="getPrice()">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach($items as $i)
                                <option 
                                    value="{{$i->id}}"
                                    price="{{$i->price}}"​
                                >
                                {{$i->code}} - {{$i->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="price" class="col-md-3"> {{__('lb.price')}} ($) <span class="text-danger">*</span> </label>
                        <div class="col-md-5">
                                <input type="number" id="price" min="0" step="0.01" name="price" value="0"  class="form-control input-xs">
                            </div>
                        </div>
                    <div class="form-group row mb-1">
                        <label for="discount" class="col-md-3"> {{__('lb.discount')}} ($)</label>
                        <div class="col-md-5">
                            <input type="number" id="discount" name="discount"  value="0" class="form-control input-xs">
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="request_date" class="col-md-3"> {{__('lb.date')}} <span class="text-danger">*</span> </label>
                            <div class="col-md-5">
                                <input type="date" class="form-control input-xs" id="request_date"  required name="request_date" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="request_time" class="col-md-3"> {{__('lb.time')}} <span class="text-danger">*</span> </label>
                                <div class="col-md-5">
                                    <input type="time" class="form-control input-xs" id="request_time"  required
                                    name="request_time" value="{{date('H:i')}}" >
                                </div>
                            </div>
                  
                    <div class="form-group row mb-1">
                       
                            <label for="request_note" class="col-md-3"> {{__('lb.note')}}  </label>
                        
                            <div class="col-md-8">
                                <input type="text" id="request_note" name="request_note"   class="form-control input-xs">
                            </div>
                    </div>
                        <div class="form-group row mb-1">
                            <label for="percent1" class="col-md-3">
                                {{__('lb.percent1')}}
                            </label>
                            <div class="col-md-8">
                            <select name="percent1" id="percent1" class="form-control chosen-select" onchange="getCode()">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($doctors as $d)
                                <option value="{{$d->id}}" code="{{$d->code}}" {{$d->id==Auth::user()->id?'selected':''}}> {{$d->first_name}} {{$d->last_name}} ( {{$d->phone}})</option>
                                @endforeach
                            </select>
                        </div>
                          
                            </div>
                           
                            <div class="form-group row behind_of">
                              
                                <label for="behind_of" class="col-md-3">
                                    {{__('lb.behind_of')}}
                                </label>
                                <div class="col-md-8">
                                <input type="text" name="behind_of" class="form-control input-xs" id="behind_of">
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
</div>



 <!-- create model -->
 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('request.update')}}" method="POST">
          @csrf
          <input type="hidden" name="request_id" value="{{$request->id}}">
          <input type="hidden" name="id" id="eid" i value="">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_request')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                    <div class="form-group row mb-1">
                        <label for="section_id"​ class="col-md-3">
                            {{__('lb.body_part')}} <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                          <select name="section_id" id="esection_id" class="form-control chosen-select">
                              <option value="0">{{__('lb.select_one')}} </option>
                              @foreach($sections as $s)
                                  <option 
                                      value="{{$s->id}}"​
                                  >
                                      {{$s->name}}
                                  </option>
                              @endforeach
                          </select>
                        </div>
                    </div>
                    <input type="hidden" name="section_name" id="esection_name">
                    <input type="hidden" name="item_name" id="eitem_name">
                    <div class="form-group row mb-1">
                        <label for="item" class="col-md-3">
                            {{__('lb.items')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                        <select name="item_id" id="eitem_id" class="form-control chosen-select"  onchange="egetPrice()">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach($items as $i)
                                <option 
                                    value="{{$i->id}}"
                                    price="{{$i->price}}"​
                                >
                                {{$i->code}} - {{$i->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="price" class="col-md-3"> {{__('lb.price')}} ($) <span class="text-danger">*</span> </label>
                        <div class="col-md-5">
                                <input type="number" id="eprice" min="0" step="0.01" name="price" value="0"  class="form-control input-xs">
                            </div>
                        </div>
                    <div class="form-group row mb-1">
                        <label for="discount" class="col-md-3"> {{__('lb.discount')}} ($)</label>
                        <div class="col-md-5">
                            <input type="number" id="ediscount" name="discount"  value="0" class="form-control input-xs">
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="request_date" class="col-md-3"> {{__('lb.date')}} <span class="text-danger">*</span> </label>
                            <div class="col-md-5">
                                <input type="date" class="form-control input-xs" id="erequest_date"  required name="request_date" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="request_time" class="col-md-3"> {{__('lb.time')}} <span class="text-danger">*</span> </label>
                                <div class="col-md-5">
                                    <input type="time" class="form-control input-xs" id="erequest_time"  required
                                    name="request_time" value="{{date('H:i')}}" >
                                </div>
                            </div>
                        <div class="form-group row mb-1">
                            <label for="request_note" class="col-md-3"> {{__('lb.note')}}  </label>
                            <div class="col-md-8">
                                <input type="text" id="erequest_note" name="request_note" class="form-control input-xs">
                            </div>
                        </div>
                            <div class="form-group row mb-1">
                                <label for="percent1" class="col-md-3">
                                    {{__('lb.percent1')}}
                                </label>
                                <div class="col-md-8">
                                <select name="percent1" id="epercent1" class="form-control chosen-select" onchange="egetCode()">
                                    <option value="">{{__('lb.select_one')}}</option>
                                    @foreach($doctors as $d)
                                    <option value="{{$d->id}}" code="{{$d->code}}" {{$d->id==Auth::user()->id?'selected':''}}> {{$d->first_name}} {{$d->last_name}} ( {{$d->phone}})</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                           
                            <div class="form-group row mb-1">
                                <label for="behind_of" class="col-md-3">
                                    {{__('lb.behind_of')}}
                                </label>
                                <div class="col-md-8">
                                <input type="text" name="behind_of" class="form-control input-xs" id="behind_of">
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
</div>
@endsection
@section('js')
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_request").addClass("active");
            $("#section_id").change(function () {
                get_item();
            });
            $("#esection_id").change(function () {
                edit_get_item();
            });
        });
        // function to get sub category
        function get_item()
        {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/item/get-item/" + $("#section_id").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var note = data[i].code;
                        var description = data[i].name;
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].id + "' price='" + data[i].price +"' >" + data[i].code + '-' + data[i].name + "</option>";
                    }
                    $("#item_id").html(opts);
                    $("#item_id").trigger("chosen:updated");
                }
            });
        }

        // function to get sub category
        function edit_get_item()
        {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/item/get-item/" + $("#esection_id").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var note = data[i].code;
                        var description = data[i].name;
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].id + "' price='" + data[i].price +"' >" + data[i].code + '-' + data[i].name + "</option>";
                    }
                    $("#eitem_id").html(opts);
                    $("#eitem_id").trigger("chosen:updated");
                }
            });
        }
        function getPrice()
        { 
            let price = $("#item_id :selected").attr('price');
        
            $("#price").val(price);
        }

        function egetPrice()
        { 
            let price = $("#eitem_id :selected").attr('price');
        
            $("#eprice").val(price);
        }

        function getCode()
        { 
            let code = $("#percent1 :selected").attr('code');
        
            $("#percent1_code").val(code);
        }

        function egetCode()
        { 
            let code = $("#epercent1 :selected").attr('code');
        
            $("#epercent1_code").val(code);
        }

        function getHospitalReference()
        { 
            let hospital_reference = $("#hospital :selected").attr('hospital_reference');
            let hospital_id= $("#hospital :selected").attr('hospital_id');
            $("#hospital_reference").val(hospital_reference);
            $("#hospital_id").val(hospital_id);
        }
        function editMaster()
        {
            $("#symptom").removeClass('show');
			$("#symptom").addClass('hide');
			$("#esymptom").addClass('show');
			$("#esymptom").removeClass('hide');
            $("#datetime").removeClass('show');
            $("#datetime").addClass('hide');
            $("#edatetime").removeClass('hide');
            $("#edatetime").addClass('show');
            $("#lb_patient").addClass('hide');
            $("#lb_patient").removeClass('show');
            $("#ehospital").removeClass('hide');
            $("#epatient").addClass('show');
            $("#lb_hospital").addClass('hide');
            $("#lb_hospital").removeClass('show');
            $("#reference_no").removeClass('hide');
            $("#show_no").addClass('hide');
            $("#btnSave").removeClass('hide');
            $("#btnEdit").addClass('hide');
            $("#btnCancel").removeClass('hide');
        }
        function cancelMaster()
        {
            $("#show_no").removeClass('hide');
            $("#datetime").removeClass('hide');
            $("#edatetime").removeClass('show');
            $("#edatetime").addClass('hide');
            $("#photo").addClass('hide');
            $("#ehospital").removeClass('show');
            $("#ehospital").addClass('hide');
            $("#lb_hospital").addClass('show');
            $("#epatient").addClass('hide');
            $("#epatient").removeClass('show');
            $("#lb_patient").addClass('show');
            $("#lb_patient").removeClass('hide');
            $("#symptom").removeClass('hide');
            $("#symptom").addClass('show');
            $("#reference_no").addClass('hide');
            $("#btnEdit").removeClass('hide');
            $("#esymptom").addClass('hide');
			$("#esymptom").removeClass('show');
            $("#btnSave").addClass('hide');
            $("#btnCancel").addClass('hide');
        }

        function edit_request(id, obj)
        {
            let tr = $(obj).parent().parent();
            $("#data tr").removeClass('active');
            $(tr).addClass('active');
            $.ajax({
                type: 'GET',
                url: burl + '/request/detail/get/' + id ,
                success: function(sms)
                {
                   
                    let data = JSON.parse(sms);
                    $('#eid').val(data.id);
                    $('#eprice').val(data.price);
                    $('#ediscount').val(data.discount);
                    $('#erequest_date').val(data.date);
                    $('#erequest_time').val(data.time);
                    $('#erequest_note').val(data.request_note);
                    $('#esection_id').val(data.section_id);
                    $('#esection_name').val(data.section_name);
                    $("#esection_id").trigger("chosen:updated");
                    $('#eitem_id').val(data.item_id);
                    $('#epercent1').val(data.percent1);
                    $("#epercent1").trigger("chosen:updated");
                    $("#behind_of").val(data.behind_of);
                    $('#eitem_name').val(data.item_name);
                    $("#eitem_id").trigger("chosen:updated");
                    
                }
            });
        }
    </script>
@endsection