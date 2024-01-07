@extends('layouts.master')
@section('title')
    {{__('lb.expense')}}
@endsection
@section('header')
    {{__('lb.expense')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
    @cancreate('expense')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal' id='btnCreate'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
    @candelete('expense')
    <button class="btn btn-danger btn-sm" id='btnDelete' 
        table='expenses' permission='expense' token="{{csrf_token()}}">
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
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.expense_for')}}</th>
                    <th>{{__('lb.amount')}} ($)</th>
                    <th>{{__('lb.note')}}</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
        </table>
	</div>
</div>

<!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="uytyut" method="POST" id='create_form' onsubmit="frm_submit(event)">
          @csrf
          <input type="hidden" name="tbl" value="expenses">
          <input type="hidden" name="per" value="expense">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_expense')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="expense_date">
                        {{__('lb.date')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-4">
                    <input type="date" name="expense_date" id="expense_date" class="form-control input-xs" value="{{date('Y-m-d')}}" required>
                </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="item">
                        {{__('lb.expense_for')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                    <input type="text" name="item" id="item" class="form-control input-xs" required>
                </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="amount">
                        {{__('lb.amount')}} ($)<span class="text-danger">*</span>
                    </label>
                    <div class="col-md-3">
                        <input type="number" min="0" step="0.01" name="amount" id="amount" id class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="desciption">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="description" id="desciption" class="form-control input-xs">
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
      <form action="uytyut" method="POST" id='edit_form' onsubmit="frm_update(event)">
          @csrf
          <input type="hidden" name="tbl" value="expenses">
          <input type="hidden" name="per" value="expense">
          <input type="hidden" name="id" id='eid' value="">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_expense')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="eexpense_date">
                        {{__('lb.date')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-4">
                    <input type="date" name="expense_date" id="eexpense_date" class="form-control input-xs" value="{{date('Y-m-d')}}" required>
                </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="eitem">
                        {{__('lb.expense_for')}}  <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                    <input type="text" name="item" id="eitem" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="eamount">
                        {{__('lb.amount')}} ($) <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-3">
                    <input type="number" min='0' step="0.01" name="amount" id="eamount" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="edescription">
                        {{__('lb.description')}}
                    </label>
                    <div class="col-md-9">
                    <input type="text" name="description" id="edescription" class="form-control input-xs">
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
            $("#menu_expense").addClass("active");

			var table = $('#dataTable').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('expense.index') }}",
                columns: [
                    {
                        data: 'check', 
                        name: 'check', 
                        orderable: false, 
                        searchable: false
                    },
                    {data: 'DT_RowIndex', name: 'id', orderable: false},
                    {data: 'expense_date', name: 'expense_date'},
                    {data: 'item', name: 'item'},
                    {data: 'amount', name: 'amount'},
                    {data: 'description', name: 'description'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    },
                ],
                columnDefs: [{
                    targets: 3,
                    className: 'action'
                }]
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
                    $('#eitem').val(data.item);
                    $('#eamount').val(data.amount);
                    $('#edescription').val(data.description);
                    $('#eexpense_date').val(data.expense_date);
                }
            });
        }
    </script>
@endsection