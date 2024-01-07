@extends('layouts.master')
@section('title')
    {{__('lb.treatments')}}
@endsection
@section('header')
    {{__('lb.treatments')}}
@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
<div class="col-md-12">
        <div class="row">
            <div class="col-md-1 pt-3 pl-0 pr-0">

    @cancreate('patient')
    <a href="{{route('treatment.create')}}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </a>
    @endcancreate
   

    </div>
    <div class="col-md-2">
        <form action="{{url('treatment/search')}}" method="GET">
            {{__('lb.date')}} 
            <input type="date" name="date" value="{{$date}}" placeholder="{{__('lb.date')}}" class="form-control input-xs">
        </div>
        <div class="col-md-2">
            {{__('lb.keyword')}} 
            <input type="text" name="q" value="{{$q}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
        </div>
        <div class="col-md-1"><br>
            <button style="height: 26px;">
                <i class="fa fa-search"></i> {{__('lb.search')}}
            </button>
        </div>
     </div>
     </div>
        </form>
</div>     
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered datatable" id='dataTable' style="width: 100%">
            <thead>
                <tr>
                
                    <th>#</th>
                    <th class="text-center">{{__('lb.code')}}</th>
                    <th>{{__('lb.kh_name')}}</th>
                    <th>{{__('lb.en_name')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.diagnosis')}}</th>
                    <th>{{__('lb.note')}}</th>
                    <th>{{__('lb.doctor')}}</th>
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
                @foreach($treatments as $t)
                <?php 
                        $dob = \Carbon\Carbon::parse( $t->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ'); 
                    ?>
                    <tr>
                        <td  class="text-center">{{$i++}}</td>
                        <td class="text-center"><a href="{{url('treatment/detail/'.$t->id)}}">{{$t->treatment_code}}</a></td>
                        <td> {{$t->kh_first_name}} {{$t->kh_last_name}}</td>
                        <td> {{$t->en_first_name}} {{$t->en_last_name}}</td>
                        <td>{{$t->date}}</td>
                        <td>{{$t->diagnosis1}}</td>
                        <td>{{$t->note}}</td>
                        <td>{{$t->first_name}} {{$t->last_name}}</td>
              
                        <td>
                             <a href="{{url('treatment/detail', $t->id)}}" class="btn btn-success btn-xs"  title="លម្អីត">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{url('treatment/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                @endforeach
</tbody>
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
                <div class="form-group mb-1">
                    <label>
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" required>
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
                <div class="form-group mb-1">
                    <label>
                        {{__('lb.name')}} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" id='ename' required>
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
            $("#menu_treatment").addClass("active");
			
        });
    </script>
@endsection