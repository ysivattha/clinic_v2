@extends('layouts.master')
@section('title')
    {{__('lb.paraclinical')}}
@endsection
@section('header')
    {{__('lb.paraclinical')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @cancreate('paraclinical')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal' id='btnCreate'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
    @candelete('paraclinical')
    <button class="btn btn-danger btn-sm" id='btnDelete' 
        table='paraclinicals' permission='paraclinical' token="{{csrf_token()}}">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </button>
    @endcandelete
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
                    <th>{{__('lb.first_name')}}</th>
                    <th>{{__('lb.last_name')}}</th>
                    <th>{{__('lb.phone')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.title')}}</th>
                    <th>{{__('lb.note')}}</th>
                    <th>{{__('lb.document')}}</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
        </table>
	</div>
</div>
<!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="#" method="POST" id='create_form' onsubmit="create_user(event)" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="tbl" value="paraclinicals">
          <input type="hidden" name="per" value="paraclinical">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_paraclinical')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row">
                    <label for="patient_id" class="col-md-3">
                        {{__('lb.patients')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="patient_id" id="patient_id" class="form-control chosen-select" required>
                            <option value=""> {{__('lb.select_one')}} </option>
                            @foreach ($patients as $p)
                            <option value="{{$p->id}}">{{$p->code}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}} )</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date" class="col-md-3">
                        {{__('lb.paraclinical_date')}} 
                    </label>
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control input-xs" id="date" value="{{date('Y-m-d')}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-md-3">
                        {{__('lb.paraclinical_title')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="title" class="form-control input-xs" id="title">
                    </div>
                </div>
               
                <div class="form-group row">
                    <label for="note" class="col-md-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-md-9">
                        <textarea name="note" rows="2" id="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="result" class="col-md-3">
                        {{__('lb.document')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" id="result" name="result" id="result" class="form-control input-xs">
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
      <form action="#" method="POST" id='edit_form' onsubmit="update_user(event)" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="tbl" value="paraclinicals">
          <input type="hidden" name="per" value="paraclinical">
          <input type="hidden" name="id" id='eid' value="">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_paraclinical')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label for="epatient_id" class="col-md-3">
                        {{__('lb.patients')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="patient_id" id="epatient_id" class="form-control chosen-select" required>
                            <option value=""> {{__('lb.select_one')}} </option>
                            @foreach ($patients as $p)
                            <option value="{{$p->id}}">{{$p->code}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}} )</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edate" class="col-md-3">
                        {{__('lb.paraclinical_date')}}
                    </label>
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control input-xs" id="edate" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="etitle" class="col-md-3">
                        {{__('lb.paraclinical_title')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="title" class="form-control input-xs" id="etitle" required>
                    </div>
                </div>
               
                <div class="form-group row">
                    <label for="enote" class="col-md-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-md-9">
                        <textarea name="note" rows="2" id="enote" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="eresult" class="col-md-3">
                        {{__('lb.document')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" name="result" id="eresult" class="form-control input-xs">
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
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $(".chosen-select").chosen({width: "100%"});
            $("#menu_paraclinical").addClass("active");
			var table = $('#dataTable').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('paraclinical.index') }}",
                columns: [
                    {data: 'check', name: 'check', searchable: false, orderable: false},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                   
                  
                    {data: 'code', name: 'customers.code'},
                    {data: 'kh_first_name', name: 'customers.kh_first_name'},
                    {data: 'kh_last_name', name: 'customers.kh_last_name'},
                    {data: 'phone', name: 'customers.phone'},
                    {data: 'date', name: 'date'},
                    {data: 'title', name: 'title'},
                    {data: 'note', name: 'note'},
                    {
                        data: 'result', 
                        name: 'result', 
                        orderable: false, 
                        searchable: false
                    },
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    },
                ]
            });
        });
         // function to save data from a form
         function create_user(evt)
        {
            var form = $('#create_form')[0];
            var formData = new FormData(form);
            evt.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{route('paraclinical.store')}}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(sms){
                    if(sms>0)
                    {
                        let txt = `<div class='alert alert-success p-2' role='alert'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <div>
                            {{__('lb.success')}}
                        </div>
                    </div>`;
                    $("#create_form")[0].reset();
                    $("#patient_id").val("");
                    $("#patient_id").trigger("chosen:updated");
                    $("#note").val("");
                    $('#sms').html(txt);
                
                    
                    $('#dataTable').DataTable().ajax.reload();
                }
                else{
                    let txt = `<div class='alert alert-danger p-2' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <div>
                            {{__('lb.error')}}
                        </div>
                    </div>
                    `;
                    $('#sms').html(txt);
                }
                
            }
        });
    }
        function edit(id, obj)
        {
            $('#esms').html('');
            let tbl = $(obj).attr('table');
            $.ajax({
                type: 'GET',
                url: burl + '/bulk/get/' + id + '?tbl=' + tbl,
                success: function(sms)
                {
                    let txt = `<div class='alert alert-success p-2' role='alert'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <div>
                            {{__('lb.success')}}
                        </div>
                    </div>`;
                    let data = JSON.parse(sms);
                    $('#eid').val(data.id);
                    $('#etitle').val(data.title);
                    $('#edate').val(data.date);
                    $('#epatient_id').val(data.patient_id);
                    $("#epatient_id").trigger("chosen:updated");
                    $('#enote').val(data.note);
                }
            });
        }

        function update_user(evt)
    {
        var form = $('#edit_form')[0];
        var formData = new FormData(form);
        evt.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{route('paraclinical.update')}}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(sms){
                if(sms>0)
                {
                    let txt = `<div class='alert alert-success p-2' role='alert'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <div>
                            {{__('lb.success')}}
                        </div>
                    </div>`;
                    var result = document.getElementById('eresult');
                
                    result.src = null;
                    $("#edit_form")[0].reset();
                    $('#dataTable').DataTable().ajax.reload();
                    $('#editModal').modal('hide');
                }
                else{
                    let txt = `<div class='alert alert-danger p-2' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <div>
                            Fail to save data, please check again!
                        </div>
                    </div>
                    `;
                    $('#esms').html(txt);
                }
                
            }
        });
    }
    </script>
@endsection