@extends('layouts.master')
@section('title')
    {{__('lb.create_treatment')}}
@endsection
@section('header')
    {{__('lb.create_treatment')}}
    @if($patient != null)
    &nbsp; &nbsp; &nbsp;
{{ $patient->code }}&nbsp;&nbsp;|
{{$patient->kh_first_name}}&nbsp;&nbsp;
{{$patient->kh_last_name}}&nbsp;&nbsp;|
{{$patient->en_first_name}}&nbsp;&nbsp;
{{$patient->en_last_name}}&nbsp;&nbsp;|
{{$patient->h_code}}&nbsp;&nbsp;|
{{$patient->phone}}&nbsp;&nbsp;
@endif
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<form action="{{route('treatment.store')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="toolbox pt-1 pb-1">
    <button class="btn btn-primary btn-sm">
        <i class="fa fa-save"></i> {{__('lb.save')}}
    </button>
    <a href="<?php if(isset($_GET['patient_id'])) echo url('patient/summary/'.$_GET['patient_id'].'?tab=4'); else echo route('treatment.index') ?>" class="btn btn-success btn-sm">
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
                    <label for="patient_id" class="col-sm-2">
                        {{__('lb.patients')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <select name="patient_id" id="patient_id" class="form-control chosen-select" onchange="getRequest()" required>
                            <option value="0"> {{__('lb.select_one')}} </option>
                            @foreach ($patients as $p)
                            <option value="{{$p->id}}"  {{$p->id==$patient_id?'selected':''}}> {{$p->code}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}} )</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label for="diagnosis1" class="col-sm-2">
                        {{__('lb.diagnosis')}}
                    </label>
                    <div class="col-sm-9">
                        <input   name="diagnosis1" id="diagnosis1" class="form-control input-xs"/>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="prescription_note" class="col-sm-2">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-sm-9">
                        <textarea rows="4" col="50" name="prescription_note" id="prescription_note" class="form-control input-xs"></textarea>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
            <div class="form-group row mb-1">
                    <label for="date" class="col-sm-2">
                        {{__('lb.date')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control input-xs" id="date" 
                            name="date" value="{{date('Y-m-d')}}">
                    </div>
                </div>
            <div class="form-group row mb-1">
                    <label for="doctor_id" class="col-sm-2">
                        {{__('lb.doctor')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <select name="doctor_id" id="doctor_id" class="form-control chosen-select" required>
                            <option value=""> {{__('lb.select_one')}} </option>
                            @foreach ($doctors as $d)
                            <option value="{{$d->id}}" {{$d->id==Auth::user()->id?'selected':''}}> {{$d->first_name}} {{$d->last_name}} ( {{$d->phone}} )</option>
                            @endforeach
                            
                        </select>

                        <div class="mt-3 ">
                            <input type="checkbox" id="is_medicine" name="is_medicine" onclick="isMedicine()">
                            <label for="is_medicine"> {{__('lb.is_medicine')}}</label>
                        </div>
                        
                        <div class="mb-2">
                            <input type="checkbox" id="is_template" name="is_template" onclick="isTemplate()">
                            <label for="is_template"> {{__('lb.is_template')}}</label>
                        </div>
                    </div>
             

                    
 
                 
                </div>

            </div>
        </div>
      
        

        <div class="row hide" id="check_medicine">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <label for="date">
                        {{__('lb.category')}}
                        </label>
                        <select name="category" id="category" class="form-control chosen-select">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach($categories as $ml)
                                <option 
                                    value="{{$ml->id}}"​
                                >
                                    {{$ml->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label for="date">
                            {{__('lb.name')}}{{__('lb.medicine')}}
                        </label>
                        <select name="medicine" id="medicine" class="form-control chosen-select"  onchange="getValue()">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach($medicine_libraries as $ml)
                                <option 
                                    value="{{$ml->id}}"​
                                    description="{{$ml->description}}" 
                                    note="{{$ml->note}}" 
                                >
                                    {{$ml->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="description" class="col-md-12"> {{__('lb.usage')}}  </label>
                        <input type="text" id="description" name="description" class="form-control input-xs" placeholder="{{__('lb.description')}}">
                    </div>
                    <div class="col-md-2">
                        <label for="note" class="col-md-12"> {{__('lb.note')}}  </label>
                        <input type="text" id="note" name="note" class="form-control input-xs" placeholder="{{__('lb.note')}}">
                    </div>
                    <div class="col-md-1">
                        <p style="padding-bottom: 10px;"></p>
                    <button type="button" class="btn-xs" style="cursor: pointer;" onclick="addItem()"> {{__('lb.insert')}} </button>
                    </div>
                    <div class="col-md-12">
                    <br>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th> {{__('lb.name')}}{{__('lb.medicine')}}</th>
                                    <th>{{__('lb.usage')}}</th>
                                    <th>{{__('lb.note')}}</th>
                                    <th style="width:150px">{{__('lb.action')}}</th>
                                </tr>
                            </thead>
                            <tbody id="data">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
        </div>
</div>
</div>

        <div class="row hide" id="check_template">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-md-4">
                        <select name="section_id" id="section_id" class="form-control chosen-select">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($sections as $s)
                                <option value="{{$s->id}}">{{$s->code}} - {{$s->name}}</option>
                            @endforeach
                        </select>
                </div>
                <div class="col-md-4">
                        <select name="template" id="template" class="form-control chosen-select">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($templates as $t)
                                <option value="{{$t->id}}" description="{!!$t->description!!}">{{$t->code}} - {{$t->title}}</option>
                            @endforeach
                        </select>
                </div>    
                <div class="col-md-1">
                    <button type="button" id="add" onclick="addTemplate()"​ >
                        {{__('lb.insert')}}
                    </button>
                </div>  
                </div>
            </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-7">
        <textarea  name="diagnosis" id="diagnosis" class="form-control ckeditor" rows="4"></textarea>
        </div>
    </div>
	</div>
</div>
</form>
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

<!-- edit model -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="uytyut" method="POST" id='edit_form' onsubmit="frm_update(event)">
          @csrf
          <input type="hidden" name="id" id='eid' value="">
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
                                    value="{{$ml->id}}"​
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
                <button type="button" class="btn btn-primary btn-sm" onclick="saveItem()">
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
            $("#section_id").change(function () {
                get_template();
            });
            $("#ecategory").change(function () {
                eget_medicine_library();
            });

         
        });
        
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
        
        function isTemplate() {
            if (is_template.checked == true){
                $('#check_template').removeClass('hide');
                $('#check_template').addClass('show');
            } else {
                $('#check_template').removeClass('show');
                $('#check_template').addClass('hide');
            }
        }

        function isMedicine() {
            if (is_medicine.checked == true){
                $('#check_medicine').removeClass('hide');
                $('#check_medicine').addClass('show');
            } else {
                $('#check_medicine').removeClass('show');
                $('#check_medicine').addClass('hide');
            }
           
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
        function getValue()
        { 
            let description = $("#medicine :selected").attr('description');
            let note = $("#medicine :selected").attr('note');
            $("#description").val(description);
            $("#note").val(note); 
        }

        function egetValue()
        { 
            let description = $("#emedicine :selected").attr('edescription');
            let note = $("#emedicine :selected").attr('enote');
            $("#edescription").val(description);
            $("#enote").val(note);
        }
        // function to add item to table
        function addItem()
        {
                let medicine = $("#medicine").val();
                let description = $("#description").val();
                let name = $("#medicine :selected").text();
                let note = $("#note").val();
               
                if(medicine=="")
                {
                    alert("Please input data correctly!");
                }
                else{
                  
                    // add item to table
                    let trs = $("#data tr");
                    let html = "<tr medicine='" +  medicine + "'>";
                    html += "<td>" + 
                        '<input type="hidden" value="'+ medicine +'" name="medicines[]">' +
                        '<input type="hidden" value="'+ name +'" name="name[]">' +
                        '<input type="hidden" value="'+ description +'" name="description[]">' +
                        '<input type="hidden" value="'+ note +'" name="note[]">'  +
                            name + "</td>";
                    html += "<td>" + description + "</td>";
                    html += "<td> " + note + "</td>";
                  
                    html += "<td>" + "<button class='btn btn-success btn-xs' type='button' onclick='editItem(this)'><i class='fa fa-edit'></i></button>&nbsp;";
                    html +=  "<button class='btn btn-danger btn-xs' type='button' onclick='deleteItem(this)'><i class='fa fa-trash'></i></button>";
                    html += "</tr>";
                    if(trs.length>0)
                        {
                            $("#data tr:last").after(html);
                        }
                        else{
                            $("#data").html(html);
                        }
                    $("#note").val("");
                    $("#description").val("");
                    $("#medicine").val("");
                    $("#medicine").trigger("chosen:updated");
                    $("#category").val("");
                    $("#category").trigger("chosen:updated");
                }
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
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].id + "' description='" + description +"' note='" + note +"'>" + data[i].name + "</option>";
                    }
                    $("#medicine").html(opts);
                    $("#medicine").trigger("chosen:updated");
                }
            });
        }

        function getRequest() {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/treatment/get-request/" + $("#patient_id").val(),
                success: function (data) {
                    opts ="<option value='" +0+"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        opts +="<option value='" + data[i].code + "'>" + data[i].code + "</option>";
                    }
                    $("#request_code").html(opts);
                    $("#request_code").trigger("chosen:updated");
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
                      
                        opts +="<option value='" + data[i].id + "' edescription='" + data[i].description +"' enote='" + note +"'>" + data[i].name + "</option>";
                    }
                    $("#emedicine").html(opts);
                    $("#emedicine").trigger("chosen:updated");
                }
            });
        }
        // function to remove an item
        function deleteItem(obj)
        {
            let con = confirm("Do you want to delete?");
            if(con)
            {
                $(obj).parent().parent().remove();
                getTotal();
            }
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

            let medicine = $(tr).attr('medicine');
            let description = $(tds[1]).html();
            let note = $(tds[2]).html();
            $("#emedicine").val(medicine);
            $("#emedicine").trigger("chosen:updated");
            $("#edescription").val(description);
            $("#enote").val(note);
            $("#ecategory").trigger("chosen:updated");
            $("#editModal").modal('show');
        }

        // save edit item back to table
        function saveItem()
        {
            let medicine = $("#emedicine").val();
            let name = $("#emedicine :selected").text();
            let description = $("#edescription").val();
            let note = $("#enote").val();
            if(medicine=="")
            {
                alert("Please input data correctly");
            }
            else{
                let tr = $("#data tr.active");
                $(tr[0]).attr('medicine', medicine);
                let tds = $("#data tr.active td");
                tds[0].innerHTML = name;
                tds[1].innerHTML = description;
                tds[2].innerHTML = note;
                // clear text box
                $("#ecategory").val("");
                $("#ecategory").trigger("chosen:updated");
                $("#emedicine").val("");
                $("#emedicine").trigger("chosen:updated");
                $("#edescription").val("");
                $("#enote").val("");
                $("#editModal").modal('hide');
            }
 }
    </script>
@endsection