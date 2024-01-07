@extends('layouts.master')
@section('title')
    {{__('lb.appointments')}}
@endsection
@section('header')
    {{__('lb.appointments')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @cancreate('appointment')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal' id='btnCreate'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
    
    @candelete('appointment')
    <button class="btn btn-danger btn-sm" id='btnDelete' 
        table='appointments' permission='appointment' token="{{csrf_token()}}">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </button>
    @endcandelete
    @canview('appointment')
    <a class="btn btn-success btn-sm" href="{{url('appointment/schedule')}}"
        table='appointments' permission='appointment' token="{{csrf_token()}}">
        <i class="fa fa-calendar"></i> {{__('lb.calendar')}}
    </a>
    @endcanview
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered datatable" id='dataTable' style="width: 100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" onclick="check(this)" value="off">
                    </th>
                    <th>#</th>
                    <th>{{__('lb.code')}}</th>
                    <th>{{__('lb.name')}}</th>
                    <th>{{__('lb.meet_date')}}</th>
                    <th>{{__('lb.meet_time')}}</th>
                    <th>{{__('lb.topic')}}</th>
                    <th>{{__('lb.description')}}</th>
                    <!-- <th>{{__('lb.doctor_first_name')}}</th> -->
                    <th>{{__('lb.doctor')}}</th>
                    <th>{{__('lb.doctor_phone')}}</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
        </table>
	</div>
</div>

<!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="#" method="POST" id='create_form' onsubmit="frm_submit(event)">
          @csrf
          <input type="hidden" name="tbl" value="appointments">
          <input type="hidden" name="per" value="appointment">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.add_appointment')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row ">
                        <label class="col-md-3">
                            {{__('lb.patients')}} <span class="text-danger">*</span>
                        </label>    
                        <div class="col-md-9">
                            <select name="patient_id" id="patient_id" class="chosen-select" required>
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach ($patients as $p)
                                    <option value="{{$p->id}}"> {{$p->code}} {{$p->kh_last_name}} - {{$p->kh_first_name}}  ( {{$p->phone}} ) </option>
                                @endforeach
                            </select>
                        </div>  
                </div>
                <div class="form-group row ">
             
                        <label class="col-md-3">
                            {{__('lb.doctor')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select name="doctor_id" id="doctor_id" class="chosen-select" required>
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach ($users as $u)
                                    <option value="{{$u->id}}">{{$u->last_name}} - {{$u->first_name}}  ( {{$u->phone}} )</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">
                        {{__('lb.meet_date')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="date" name="meet_date" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">
                        {{__('lb.meet_time')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="time" name="meet_time" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">
                        {{__('lb.topic')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="topic" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">
                        {{__('lb.description')}}
                    </label>
                    <div class="col-md-9">
                        <textarea name="description" id="" cols="2" class="form-control"></textarea>
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
<!-- edit model -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="#" method="POST" id='edit_form' onsubmit="frm_update(event)">
          @csrf
            <input type="hidden" name="tbl" value="appointments">
          <input type="hidden" name="per" value="appointment">
          <input type="hidden" name="id" id='eid' value="">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_appointment')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label class="col-md-3">
                        {{__('lb.patients')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="patient_id" id="epatient_id" class="chosen-select" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($patients as $p)
                                <option value="{{$p->id}}"> {{$p->code}} {{$p->kh_last_name}} - {{$p->kh_first_name}}  ( {{$p->phone}} ) </option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="form-group row">
                    <label class="col-md-3">
                        {{__('lb.doctor')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="doctor_id" id="edoctor_id" class="chosen-select" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($users as $u)
                                <option value="{{$u->id}}">{{$u->last_name}} - {{$u->first_name}}  ( {{$u->phone}} )</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3">
                    {{__('lb.meet_date')}} <span class="text-danger">*</span>
                </label>
                <div class="col-md-4">
                    <input type="date" name="meet_date" id="emeet_date" class="form-control input-xs" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3">
                    {{__('lb.meet_time')}} <span class="text-danger">*</span>
                </label>
                <div class="col-md-4">
                    <input type="time" name="meet_time" id="emeet_time" class="form-control input-xs" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3">
                    {{__('lb.topic')}} <span class="text-danger">*</span>
                </label>
                <div class="col-md-9">
                    <input type="text" name="topic" id="etopic" class="form-control input-xs" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3">
                    {{__('lb.description')}}
                </label>
                <div class="col-md-9">
                    <textarea name="description" id="edescription" cols="2" class="form-control"></textarea>
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
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_appointment").addClass("active");

			var table = $('#dataTable').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('appointment.index') }}",
                columns: [
                    {
                        data: 'check', 
                        name: 'check', 
                        orderable: false, 
                        searchable: false
                    },
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'code', name: 'customers.code'},
                    {
                        data: 'kh_last_name', name: 'customers.kh_last_name',
                        "mRender": function (data, type, row, meta) {
                            return  row.kh_first_name +' '+row.kh_last_name;
                        }
                    },
                    // {data: 'kh_first_name', name: 'customers.kh_first_name'},
                    // {data: 'kh_last_name', name: 'customers.kh_last_name'},
                    {data: 'meet_date', name: 'meet_date'},
                    {data: 'meet_time', name: 'meet_time'},
                    {data: 'topic', name: 'topic'},
                    {data: 'description', name: 'description'},
                    // {data: 'dfirst_name', name: 'users.first_name'},
                    // {data: 'dlast_name', name: 'users.last_name'},
                    {
                        data: 'dlast_name', name: 'users.last_name',
                        "mRender": function (data, type, row, meta) {
                            return  row.dfirst_name +' '+row.dlast_name;
                        }
                    },
                    {data: 'phone', name: 'users.phone'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    },
                ]
            });
        });
        function edit(id, obj)
        {
            $('#esms').html('');
            let tbl = $(obj).attr('table');
            $.ajax({
                type: 'GET',
                url: burl + '/bulk/get/' + id + '?tbl=' + tbl,
                success: function(sms)
                {
                    let data = JSON.parse(sms);
                    $('#eid').val(data.id);
                    $('#epatient_id').val(data.patient_id);
                    $("#epatient_id").trigger("chosen:updated");
                    $('#edoctor_id').val(data.doctor_id);
                    $("#edoctor_id").trigger("chosen:updated");
                    $('#etopic').val(data.topic);
                    $('#emeet_date').val(data.meet_date);
                    $('#emeet_time').val(data.meet_time);
                    $('#edescription').val(data.description);
                }
            });
        }
    </script>
@endsection