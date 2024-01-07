@extends('layouts.master')
@section('title')
{{__('lb.detail')}} {{__('lb.treatments')}}
@endsection
@section('header')
    {{__('lb.detail')}} {{__('lb.treatments')}}&nbsp;&nbsp;&nbsp;
    {{ $t->code }}&nbsp;&nbsp;|
    {{ $t->pfirst_name }}&nbsp;&nbsp;
    {{ $t->plast_name }}&nbsp;&nbsp;|
    {{ $t->efirst_name }}&nbsp;&nbsp;
    {{ $t->elast_name }}&nbsp;&nbsp;|
    {{ $t->h_code }}&nbsp;&nbsp;|
    {{ $t->cphone }}&nbsp;&nbsp;

@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @cancreate('treatment')
    <a href="{{route('treatment.create')}}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </a>
    @endcancreate
    @candelete('treatment')
    <a href="{{route('treatment.delete', $t->id)}}" class="btn btn-danger btn-sm" 
        onclick="return confirm('{{__('lb.confirm')}}')">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </a>
    @endcandelete
   
    <a href="{{url('treatment/print/'.$t->id)}}" target="_blank" class="btn btn-primary btn-sm">
        <i class="fa fa-print"></i> {{__('lb.print')}}
    </a>
    <a href="{{url('treatment/print-no-letterhead/'.$t->id)}}" target="_blank" class="btn btn-primary btn-sm">
        <i class="fa fa-print"></i> {{__('lb.print_no_letterhead')}}
    </a>

   <a href="{{route('treatment.index')}}" class="btn btn-success btn-sm">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
</div>   
    <div class="card">
        <div class="card-body">
        @component('coms.alert')
        @endcomponent
        <form action="{{url('treatment/update')}}" method="POST">
                @csrf
                <button class="btn btn-success btn-xs hide" type="submit" id='btnSave'>
                    <i class="fa fa-save"></i> {{__('lb.save')}}
                </button>
                <button class="btn btn-danger btn-xs hide" type="button" id='btnCancel' onclick="cancelMaster()">
                    <i class="fa fa-times"></i> {{__('lb.cancel')}}
                </button><p></p>
                <input type="hidden" name="id" value="{{$t->id}}">
                <div class="row">
                    <div class="col-sm-6">
                        
                        <div class="form-group row mb-1">
                            <label class='col-sm-2'>{{__('lb.patients')}}</label>
                            <div class="col-sm-9 show" id="lb_patient">
                                : <a href="{{url('patient/detail/'.$t->pid)}}"> {{$t->code}} - {{$t->pfirst_name}} {{$t->plast_name}} ( {{$t->cphone}} )</a>
                            </div>
                            <div class="col-sm-9 hide" id="in_patient">
                                <select name="patient_id" id="patient_id" class="form-control chosen-select" required>
                                    <option value=""> {{__('lb.select_one')}} </option>
                                    @foreach ($patients as $p)
                                    <option value="{{$p->id}}"  {{$p->id==$t->pid?'selected':''}}> {{$p->code}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}} )</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="diagnosis1" class="col-sm-2">
                                {{__('lb.diagnosis')}}
                            </label>
                            <div class="col-sm-9 show" id="lb_d1">
                            : {{$t->diagnosis1}}
                            </div>
                        
                            <div class="col-sm-9 hide" id="in_d1" >
                                <input type="text" value="{{$t->diagnosis1}}" name="diagnosis1" id="diagnosis1"  class="form-control input-xs"/>
                            </div>

                            <label for="lb_prescription_note" class="col-sm-2 mt-1">
                                {{__('lb.note')}}
                            </label>
                            <div class="col-sm-9 show" style="white-space: pre-wrap" id="prescription_note">{{$t->note}}</div>
                            <div class="col-sm-9 hide mt-1" id="in_prescripition_note" >
                                <textarea rows="4" cols="50"  name="prescription_note"  style="white-space: pre-wrap" class="form-control input-xs">{{$t->note}}</textarea>
                            </div>
                        </div>
                    
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row mb-1">
                            <label class='col-sm-2'>{{__('lb.date')}}</label>
                            <div class="col-sm-9 show" id="lb_date">
                                : {{$t->date}}
                            </div>
                            <div class="col-sm-4 hide" id="in_date">
                                <input type="date" class="form-control input-xs" id="date" 
                                name="date" value="{{$t->date}}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class='col-sm-2'>{{__('lb.doctor')}}</label>
                            <div class="col-sm-9 show" id="lb_doctor">
                                : {{$t->dfirst_name}} {{$t->dlast_name}}
                            </div>
                            <div class="col-md-9 hide" id="in_doctor">
                                <select name="doctor_id" id="doctor_id" class="form-control chosen-select" required>
                                    <option value=""> {{__('lb.select_one')}} </option>
                                    @foreach ($doctors as $d)
                                    <option value="{{$d->id}}" {{$d->id==$t->doctor_id?'selected':''}}> {{$d->first_name}} {{$d->last_name}} ( {{$d->phone}} )</option>
                                    @endforeach
                                    
                                </select>
                            </div> 
                            

                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="hide" id="esection">
                            <div class="form-group row mb-1 ">
                                <label class="col-md-2" for="section_id ">
                                    {{__('lb.body_part')}}
                                </label>
                                <div class="col-md-9">
                                    <select name="section_id" id="section_id" class="form-control chosen-select">
                                        <option value="">{{__('lb.select_one')}}</option>
                                        @foreach ($sections as $s)
                                            <option value="{{$s->id}}">{{$s->code}} - {{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <span id="etemplate" class="hide">
                            <select name="template" id="template" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach ($templates as $te)
                                    <option value="{{$te->id}}" description="{!!$te->description!!}">{{$te->code}} - {{$te->title}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>    
                    <div class="col-sm-1">
                        <button type="button" class="hide" id="add" onclick="addTemplate()"​ >
                            {{__('lb.insert')}}
                        </button>
                    </div>  
                
                </div>
                <div class="form-group row mb-1 mt-5">
                    <label class='col-sm-1'>{{__('lb.treatments')}}</label>
                    <div class="col-sm-9 show" id="lb_diagnosis">
                            {!!$t->diagnosis!!}
                    </div>
                    <div class="col-sm-9 hide" id="in_diagnosis">
                        <textarea name="diagnosis" id="diagnosis" class="form-control ckeditor" rows="4">{{$t->diagnosis}}</textarea>
                        </div>
                </div>
                <div class="form-group row">
                    <label class='col-sm-1'></label>
                    <div class="col-md-4">
                    @canedit('treatment')
                    <a href="#" class="btn btn-success btn-xs" id="btnEdit"  onclick="editMaster()">
                        <i class="fa fa-edit"></i> {{__('lb.edit')}}
                    </a>
                    @endcanedit
                </div>
                </div>
            </form>      
        </div>
    </div>
</div>
<div>     
    <div class="col-md-12">
        <h5 class="text-success">{{__('lb.list')}} {{__('lb.medicine')}} 
            @canedit('treatment')
                <a href="#" class="btn btn-primary btn-oval btn-xs"  data-toggle='modal' data-target='#createModal' id='btnCreate'>
            <i class="fa fa-plus"></i> {{__('lb.add')}}
            @endcanedit
        </a></h5>  
    </div>


    <div class="col-md-12">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>{{__('lb.name')}}{{__('lb.medicine')}}</th>
                    <th>{{__('lb.usage')}}</th>
                    <th>{{__('lb.note')}}</th>
                    @candelete('treatment')
                    <th>{{__('lb.status')}}</th>
                    @endcandelete
                </tr>
            </thead>
            <tbody id="data">
                @foreach($tds as $td)
                    <tr id="{{$td->id}}">
                        <td>{{$td->name}}</td>
                        <td>{{$td->description}}</td>
                        <td>{{$td->note}}</td>
                        <td>
                            @canedit('treatment')
                            <a href="#"  data-toggle='modal' data-target='#editModal' onclick='editItem(this)' class="btn btn-success btn-xs">
                                <i class="fa fa-edit"></i>
                                </a>
                                @endcanedit
                            
                            @candelete('treatment')
                            <a href="{{url('treatment-detail/delete/'.$td->id.'?treatment_id='.$t->id)}}" onclick="return confirm('{{__('lb.confirm')}}?')" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                                </a>
                                @endcandelete
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
<!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{url('treatment/medicine/save')}}" method="POST">
          @csrf
            <input type="hidden" value="{{$t->id}}" name="treatment_id">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.add_medicine')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="category">
                    {{__('lb.category')}}
                    </label>
                    <div class="col-md-9">
                        <select name="category" id="category" class="form-control chosen-select">
                            <option value="0">{{__('lb.select_one')}}</option>
                            @foreach($categories as $ml)
                                <option 
                                    value="{{$ml->id}}"​
                                >
                                    {{$ml->name}}
                                </option>
                            @endforeach
                        </select>
            
                    </div>
                </div>
                <div class="form-group row">
                    <label for="medicine" class="col-md-3">
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="medicine" id="medicine" class="form-control chosen-select" required onchange="getValue()">
                            <option value="0">{{__('lb.select_one')}}</option>
                            @foreach($medicine_libraries as $ml)
                                <option 
                                    value="{{$ml->id}}"​
                                    description="{{$ml->description}}" 
                                    note="{{$ml->note}}" 
                                    name="{{$ml->name}}"
                                >
                                    {{$ml->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="name" id="name">
                <div class="form-group row">
                <label for="edescription" class="col-md-3"> {{__('lb.usage')}}  </label>
                <div class="col-md-9">
                <input type="text" id="description" name="description" class="form-control input-xs" placeholder="{{__('lb.description')}}">
                </div>
                        
                </div>
                <div class="form-group row">
                    <label for="neote" class="col-md-3"> {{__('lb.note')}}  </label>
                    <div class="col-md-9">  
                        <input type="text" id="note" name="note" class="form-control input-xs" placeholder="{{__('lb.note')}}">
               
                    </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> {{__('lb.save')}}
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> {{__('lb.close')}}
                </button>
              </div>
          </div>
      </form>
    </div>
</div>
<!-- edit model -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{url('treatment/medicine/update')}}" method="POST">
          @csrf
          <input type="hidden" name="treatment_detail_id" id='treatment_detail_id'>
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_medicine')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="eprotocol_category_id">
                    {{__('lb.category')}}
                    </label>
                    <div class="col-md-9">
                        <select name="category" id="ecategory" class="form-control chosen-select">
                            <option value="0">{{__('lb.select_one')}}</option>
                            @foreach($categories as $ml)
                                <option 
                                    value="{{$ml->id}}"​
                                >
                                    {{$ml->name}}
                                </option>
                            @endforeach
                        </select>
            
                    </div>
                </div>
                <div class="form-group row">
                    <label for="emedicine" class="col-md-3">
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="medicine" id="emedicine" class="form-control chosen-select" required onchange="egetValue()">
                            <option value="0">{{__('lb.select_one')}}</option>
                            @foreach($medicine_libraries as $ml)
                                <option 
                                    value="{{$ml->name}}"​
                                    edescription="{{$ml->description}}" 
                                    enote="{{$ml->note}}" 
                                >
                                    {{$ml->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                <label for="edescription" class="col-md-3"> {{__('lb.usage')}}  </label>
                <div class="col-md-9">
                <input type="text" id="edescription" name="description" class="form-control input-xs" placeholder="{{__('lb.description')}}">
                </div>
                        
                </div>
                <div class="form-group row">
                    <label for="neote" class="col-md-3"> {{__('lb.note')}}  </label>
                    <div class="col-md-9">  
                        <input type="text" id="enote" name="note" class="form-control input-xs" placeholder="{{__('lb.note')}}">
                    </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> {{__('lb.save')}}
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> {{__('lb.close')}}
                </button>
              </div>
          </div>
      </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_treatment").addClass("active");
            $("#category").change(function () {
                get_medicine_library();
            });
            $("#ecategory").change(function () {
                eget_medicine_library();
            });
            $("#section_id").change(function () {
                get_template();
            });
        });
        
        function egetValue()
        { 
           
            let description = $("#emedicine :selected").attr('edescription');
            let note = $("#emedicine :selected").attr('enote');
            $("#edescription").val(description);
            $("#enote").val(note);
            
        }
        function addTemplate() {
            $.ajax({
                type: "GET",
                url: burl + "/doctor-check/update-status/" + $('#request_id').val(),
                success: function (data) {
                    $("#status").text("{{__('lb.reading')}}");
                    if (confirm("{{__('lb.confirm_template')}}")) {
                        let diagnosis = $("#template :selected").attr('description');
                        CKEDITOR.instances['diagnosis'].setData(diagnosis);
                        $('#diagnosis').val(diagnosis);
                        } else {
                            alert('Why did you press cancel? You should have confirmed');
                        }
                    }
            });
          
        }
        
        function getValue()
        { 
            let description = $("#medicine :selected").attr('description');
            let note = $("#medicine :selected").attr('note');
            let name = $("#medicine :selected").attr('name');
            $("#description").val(description);
            $("#note").val(note); 
            $("#name").val(name);
      
        }
        function get_template()
        {
    
            $.ajax({
                type: "GET",
                url: burl + "/treatment/get-template/" + $("#section_id").val(),
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
                        opts +="<option value='" + data[i].id + "' description='" + data[i].description +"' >" + data[i].code + '-' + data[i].title + "</option>";
                    }
                    $("#template").html(opts);
                    $("#template").trigger("chosen:updated");
                }
            });
        }
         // function to get sub category
         function get_medicine_library()
        {
         
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/treatment/get-medicine-library/" + $("#category").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var note = data[i].note;
                        var description = data[i].description;
                        var name = data[i].name;
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        if(data[i].name == null)
                        {
                            name = '';
                        }
                        opts +="<option value='" + data[i].id + "' name='" + name +"' description='" + description +"' note='" + note +"'>" + data[i].name + "</option>";
                    }
                    $("#medicine").html(opts);
                    $("#medicine").trigger("chosen:updated");
                }
            });
        }
        // function to get sub category
        function eget_medicine_library()
        {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/treatment/eget-medicine-library/" + $("#ecategory").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var note = data[i].note;
                        var description = data[i].description;
            
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                       
                        opts +="<option value='" + data[i].name + "' description='" + description + "' note='" + note +"'>" + data[i].name + "</option>";
                    }
                    $("#emedicine").html(opts);
                    $("#emedicine").trigger("chosen:updated");
                }
            });
        }
        
        function editMaster()
        {
            $('#add').removeClass('hide');
            $('#add').addClass('show');
            $('#etemplate').removeClass('hide');
            $('#etemplate').addClass('show');
            $("#lb_date").removeClass('show');
              
            $("#prescription_note").addClass('hide');
            $("#prescription_note").removeClass('show');
            $("#in_prescripition_note").removeClass('hide');
            $("#in_prescripition_note").addClass('show');
            $("#lb_date").addClass('hide');
            $("#lb_hname").removeClass('show');
            $("#lb_hname").addClass('hide');
            $("#lb_d1").removeClass('show');
            $("#lb_d1").addClass('hide');
            $("#in_hname").removeClass('hide');
            $("#in_hname").addClass('show');
            $("#in_d1").removeClass('hide');
            $("#in_d1").addClass('show');
            $("#in_date").removeClass('hide');
            $("#in_date").addClass('show');
            $("#esection").addClass('show');
            $("#lb_diagnosis").removeClass('show');
            $("#lb_diagnosis").addClass('hide');
            $("#lb_patient").addClass('hide');
            $("#lb_patient").removeClass('show');
            $("#lb_doctor").addClass('hide');
            $("#lb_doctor").removeClass('show');
            $("#in_doctor").addClass('show');
            $("#in_doctor").removeClass('hide');
            $("#in_patient").removeClass('hide');
            $("#in_patient").addClass('show');
			$("#in_diagnosis").addClass('show');
            $("#in_diagnosis").removeClass('hide');

            $("#btnSave").removeClass('hide');
            $("#btnEdit").addClass('hide');
            $("#btnCancel").removeClass('hide');
        }
        function cancelMaster()
        {
            $('#add').removeClass('show');
            $('#add').addClass('hide');
            $('#etemplate').removeClass('show');
            $('#etemplate').addClass('hide');
            $("#in_date").removeClass('show');
            $("#in_prescripition_note").removeClass('show');
            $("#in_prescripition_note").addClass('hide');
            $("#prescription_note").removeClass('hide');
            $("#prescripition_note").addClass('show');
            $("#in_date").addClass('hide');
            $("#in_hname").removeClass('show');
            $("#in_hname").addClass('hide');
            $("#lb_hname").removeClass('hide');
            $("#lb_hname").addClass('show');
            $("#lb_d1").removeClass('hide');
            $("#lb_d1").addClass('show');
            $("#esection").removeClass('show');
            $("#esection").addClass('hide');
            $("#in_d1").removeClass('show');
            $("#in_d1").addClass('hide');
            $("#lb_date").addClass('show');
            $("#lb_diagnosis").removeClass('hide');
            $("#lb_diagnosis").removeClass('hide');
            $("#lb_patient").addClass('show');
            $("#lb_patient").removeClass('hide');
            $("#lb_diagnosis").addClass('show');
			$("#in_diagnosis").addClass('hide');
            $("#in_diagnosis").removeClass('show');
            $("#in_doctor").removeClass('show');
            $("#in_doctor").addClass('hide');
            $("#lb_doctor").removeClass('hide');
            $("#lb_doctor").addClass('show');
            $("#in_patient").removeClass('show');
            $("#in_patient").addClass('hide');
            $("#btnEdit").removeClass('hide');
            $("#btnSave").addClass('hide');
            $("#btnCancel").addClass('hide');
        }
        // function to edit item
        function editItem(obj)
        {
            //remove active class from all row
            $("#data tr").removeClass('active');
            let tr = $(obj).parent().parent();
            // add class active to the current edit row
            $(tr).addClass('active');
            let tds = $(tr).children("td");

            let id = $(tr).attr('id');
            let name = $(tds[0]).html();
            let description = $(tds[1]).html();
            let note = $(tds[2]).html();
            $("#emedicine").val(name);
            $('#treatment_detail_id').val(id);
            $("#emedicine").trigger("chosen:updated");
            $("#edescription").val(description);
            
            $("#enote").val(note);
            $("#ecategory").trigger("chosen:updated");
            $("#editModal").modal('show');
        }

    </script>
    
@endsection