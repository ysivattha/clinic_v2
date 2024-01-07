@extends('layouts.master')
@section('title')
    {{__('lb.items')}}
@endsection
@section('header')
    {{__('lb.items')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">

    <div class="row">
    <div class="col-md-2">
    @cancreate('item')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal' id='btnCreate'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
</div>

<div class="col-md-10">
    <form action="">
    <div class="row">
        <div class="col-md-5">
        <select name="section" id="section" class="chosen-select">
            <option value="">{{__('lb.select_one')}}</option>
            @foreach ($sections as $sec)
            <option value="{{$sec->id}}" {{$sec->id==$section?'selected':''}}>{{$sec->name}}</option>
            @endforeach
        
    </select>

        </div>
   
<div class="col-md-2">
    <button style="height: 26px;">
            <i class="fa fa-search"></i> {{__('lb.search')}}
        </button>
    </div>
    </div>
</div>
</form>
</div>  
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('lb.body_part')}}</th>
                    <th>{{__('lb.code')}}</th>
                    <th>{{__('lb.name')}}</th>
                    <th>{{__('lb.price')}} ($)</th>
                    <th>{{__('lb.commission_percent1')}} ($)</th>
                    <th>{{__('lb.commission_percent2')}} ($)</th>
                    <th>{{__('lb.commission_percent3')}} ($)</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
            <tbody>			
                <?php
                    $pagex = @$_GET['page'];
                    if(!$pagex)
                        $pagex = 1;
                    $i = config('app.row') * ($pagex - 1) + 1;
                    
                ?>
                @foreach($items as $t)
                    <tr>
                        <td>{{$i++}}</td>
                        <td> {{$t->sname}}</td>
                        <td>{{$t->code}}</td>
                        <td>{{$t->name}}</td>
                        <td>$ {{$t->price}}</td>
                        <td>$ {{$t->percent1}}</td>
                        <td>$ {{$t->percent2}}</td>
                        <td>$ {{$t->percent3}}</td>
                        <td class="text-left">
                            <a href="#" title="{{__('lb.edit')}}"  onclick="edit({{$t->id}}, this)" data-toggle='modal' data-target='#editModal' class='btn btn-success btn-xs'
                             >
                                <i class="fa fa-edit"></i>
                            </a>
                             @candelete('request')
                            
                            <a href="{{url('item/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endcandelete
                        </td>
                    </tr>
                  
                @endforeach
             
            </tbody>
        </table> <br>
        {{$items->links('pagination::bootstrap-4')}}
        </table>
	</div>
</div>

<!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="uytyut" method="POST" id='create_form' onsubmit="frm_submit(event)">
          @csrf
          <input type="hidden" name="tbl" value="items">
          <input type="hidden" name="per" value="item">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_item')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="section_id">
                        {{__('lb.body_parts')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <select name="section_id" id="section_id" class="chosen-select" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($sections as $s)
                                <option value="{{$s->id}}">{{$s->code}} - {{$s->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="code">
                        {{__('lb.code')}} 
                    </label>
                    <div class="col-md-4">
                        <input type="text" name="code" id="code" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="name">
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="name" id="name" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="price">
                        {{__('lb.price')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" min='0' step="0.01" name="price" for="price" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5" for="percent1">
                        {{__('lb.commission_percent1')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" name="percent1" id="percent1" step="0.01" min="0" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5" for="percent2">
                        {{__('lb.commission_percent2')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" name="percent2" id="percent2" step="0.01" min="0" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5" for="percent3">
                        {{__('lb.commission_percent3')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" name="percent3" id="percent3" step="0.01" min="0" class="form-control input-xs">
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <form action="uytyut" method="POST" id='edit_form' onsubmit="frm_update(event)">
          @csrf
          <input type="hidden" name="tbl" value="items">
          <input type="hidden" name="per" value="item">
          <input type="hidden" name="id" id='eid'>
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.edit_item')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="esection_id">
                        {{__('lb.body_parts')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <select name="section_id" id="esection_id" class="chosen-select" required>
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($sections as $s)
                                <option value="{{$s->id}}">{{$s->code}} - {{$s->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="ecode">
                        {{__('lb.code')}} 
                    </label>
                    <div class="col-md-4">
                        <input type="text" name="code" id="ecode" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="ename">
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="name" id="ename" class="form-control input-xs" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2" for="eprice">
                        {{__('lb.price')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" min='0' step="0.01" name="price" for="price" id="eprice" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5" for="epercent1">
                        {{__('lb.commission_percent1')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" name="percent1" id="epercent1" step="0.01" min="0" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5" for="epercent2">
                        {{__('lb.commission_percent2')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" name="percent2" id="epercent2" step="0.01" min="0" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5" for="epercent3">
                        {{__('lb.commission_percent3')}} ($)
                    </label>
                    <div class="col-md-3">
                        <input type="number" name="percent3" id="epercent3" step="0.01" min="0" class="form-control input-xs">
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
@endsection

@section('js')
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_item").addClass("myactive");
        });
        function edit(id, obj)
        {
            let tbl = 'items';
            $.ajax({
                type: 'GET',
                url: burl + '/bulk/get/' + id + '?tbl=' + tbl,
                success: function(sms)
                {
                    let data = JSON.parse(sms);
                    $('#eid').val(data.id);
                    $('#ecode').val(data.code);
                    $('#ename').val(data.name);
                    $('#eprice').val(data.price);
                    $('#epercent1').val(data.percent1);
                    $('#epercent2').val(data.percent2);
                    $('#epercent3').val(data.percent3);
                    $('#esection_id').val(data.section_id);
                    $("#esection_id").trigger("chosen:updated");
                }
            });
        }
        $( "#edit_form" ).submit(function( event ) {
            location.reload();
        });
        $( "#create_form" ).submit(function( event ) {
            location.reload();
        });
    </script>
@endsection