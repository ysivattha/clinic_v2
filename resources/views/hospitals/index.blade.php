@extends('layouts.master')
@section('title')
    {{__('lb.hospitals')}}
@endsection
@section('header')
    {{__('lb.hospitals')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
    @cancreate('hospital')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal' id='btnCreate'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
    @candelete('hospital')
    <button class="btn btn-danger btn-sm" title="Delete selected users" id='btnDelete' 
        table='hospitals' permission='hospital' token="{{csrf_token()}}">
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
                    <th>{{__('lb.logo')}}</th>
                    <th>{{__('lb.letterhead')}}</th>
                    <th>{{__('lb.reference_no')}}</th>
                    <th>{{__('lb.kh_name')}}</th>
                    <th>{{__('lb.en_name')}}</th>
                    <th>{{__('lb.phone')}}</th>
                    <th>{{__('lb.bank')}}</th>
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
          <input type="hidden" name="tbl" value="hospitals">
          <input type="hidden" name="per" value="hospital">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_hospital')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row">
                    <label for="code" class="col-md-3">
                        {{__('lb.reference_no')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="code" class="form-control input-xs" id="code" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-3">
                        {{__('lb.kh_name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="name" class="form-control input-xs" id="name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="en_name" class="col-md-3">
                        {{__('lb.en_name')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="en_name" class="form-control input-xs" id='en_name'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-md-3">
                        {{__('lb.phone')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="phone" class="form-control input-xs" id='phone'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-3">
                        {{__('lb.address')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="address" class="form-control input-xs" id='address'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bank" class="col-md-3">
                        {{__('lb.bank')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="bank" class="form-control input-xs" id='bank'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="logo" class="col-md-3">
                        {{__('lb.logo')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" id="logo" name="logo"  class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="logo" class="col-md-3">
                        {{__('lb.letterhead')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" id="letterhead" name="letterhead" class="form-control input-xs">
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
          <input type="hidden" name="tbl" value="hospitals">
          <input type="hidden" name="per" value="hospital">
          <input type="hidden" name="id" id='eid' value="">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_hospital')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label for="ecode" class="col-md-3">
                        {{__('lb.reference_no')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="code" class="form-control input-xs" id='ecode' required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ename" class="col-md-3">
                        {{__('lb.kh_name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="name" class="form-control input-xs" id='ename' required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="een_name" class="col-md-3">
                        {{__('lb.en_name')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="en_name" class="form-control input-xs" id='een_name'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ephone" class="col-md-3">
                        {{__('lb.phone')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="phone" class="form-control input-xs" id='ephone'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="eaddress" class="col-md-3">
                        {{__('lb.address')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="address" class="form-control input-xs" id='eaddress'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ebank" class="col-md-3">
                        {{__('lb.bank')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="bank" class="form-control input-xs" id='ebank'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="elogo" class="col-md-3">
                        {{__('lb.logo')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" id="elogo" name="logo" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="eletterhead" class="col-md-3">
                        {{__('lb.letterhead')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" id="eletterhead" name="letterhead" class="form-control input-xs">
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
	<script>
        $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_hospital").addClass("myactive");
            var table = $('#dataTable').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('hospital.index') }}",
                columns: [
                    {data: 'check', name: 'check', searchable: false, orderable: false},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                    {
                        data: 'logo', 
                        name: 'logo', 
                        orderable: false, 
                        searchable: false
                    },
                    {
                        data: 'letterhead', 
                        name: 'letterhead', 
                        orderable: false, 
                        searchable: false
                    },
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'en_name', name: 'en_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'bank', name: 'bank'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    },
                ],
                
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
                url: "{{route('hospital.store')}}",
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
                    $('#sms').html(txt);
                    $("#create_form")[0].reset();
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
                    let data = JSON.parse(sms);
                    $('#eid').val(data.id);
                    $('#ecode').val(data.code);
                    $('#een_name').val(data.en_name);
                    $('#ephone').val(data.phone);
                    $('#ebank').val(data.bank);
                    $('#eaddress').val(data.address);
                    $('#ename').val(data.name);
                }
            });
        }
        // function to save data from a form
    function update_user(evt)
    {
        var form = $('#edit_form')[0];
        var formData = new FormData(form);
        evt.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{route('hospital.update')}}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(sms){
                if(sms>0)
                {
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