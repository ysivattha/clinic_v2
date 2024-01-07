@extends('layouts.master')
@section('title')
    {{__('lb.body_parts')}}
@endsection
@section('header')
    {{__('lb.body_parts')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @cancreate('section')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal' id='btnCreate'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
    @candelete('section')
    <button class="btn btn-danger btn-sm" id='btnDelete' 
        table='sections' permission='section' token="{{csrf_token()}}">
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
                    <th>{{__('lb.departments')}}</th>
                    <th>{{__('lb.code')}}</th>
                    <th>{{__('lb.name')}}</th>
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
          <input type="hidden" name="tbl" value="sections">
          <input type="hidden" name="per" value="section">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_body_part')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row mb-1">
                    <label class="col-md-3">
                        {{__('lb.departments')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="department_id" id="department_id" class="form-control chosen-select" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($departments as $d)
                                <option value="{{$d->id}}">{{$d->code}} - {{$d->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class="col-md-3">
                        {{__('lb.code')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" name="code" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class="col-md-3">
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="name" class="form-control input-xs" required>
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
          <input type="hidden" name="tbl" value="sections">
          <input type="hidden" name="per" value="section">
          <input type="hidden" name="id" id='eid' value="">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_body_parts')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="edepartment_id">
                        {{__('lb.departments')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <select name="department_id" id="edepartment_id" class="form-control chosen-select"   required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($departments as $d)
                                <option value="{{$d->id}}">{{$d->code}} - {{$d->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="ecode">
                        {{__('lb.code')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" name="code" id="ecode" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="ename">
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="name" class="form-control input-xs" id='ename' required>
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
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_section").addClass("myactive");

			var table = $('#dataTable').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('section.index') }}",
                columns: [
                    {
                        data: 'check', 
                        name: 'check', 
                        orderable: false, 
                        searchable: false
                    },
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'dname', name: 'departments.name'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                  
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
                    $('#ename').val(data.name);
                    $('#ecode').val(data.code);
                    $('#edepartment_id').val(data.department_id);
                    $('#edepartment_id').trigger("chosen:updated");
                }
            });
        }
    </script>
@endsection