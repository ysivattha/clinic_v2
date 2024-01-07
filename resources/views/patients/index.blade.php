@extends('layouts.master')
@section('title')
    {{__('lb.patients')}}
@endsection
@section('header')
    {{__('lb.patients')}}
@endsection

@section('btn')
@cancreate('patient')
<a href="{{route('patient.create')}}" class="btn btn-success btn-xs">
    <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
</a>
@endcancreate

@endsection
@section('content')
<div class="toolbox pt-1 pb-1">
<div class="col-md-12">
        <div class="row">
            <div class="col-md-1  pl-0 pr-0">



    </div>
        <div class="col-md-2">
        <form action="{{url('patient/search')}}" method="GET">
                <input type="text" name="q" value="{{$q}}" placeholder="{{__('lb.keyword')}}" class="form-control input-xs">
   
            </div>
            <div class="col-md-3">
               
                <select {{ Auth::user()->role_id==105?'disabled':'' }}  name="hospital_id" id="" class="form-select input-xs">
                    <option  {{ Auth::user()->role_id==105?'disabled':'' }} {{ ($h=="")?'selected':''; }} value="">ទាំងអស់</option>
                    @foreach ($hospital as $s)

                    <option   @if((Auth::user()->role_id==105) && (Auth::user()->hospital_id != $s->id)) disabled @endif  {{ ($h==$s->id)?'selected':'' }} value="{{ $s->id }}">{{ $s->name }}</option>

                    @endforeach
              
                </select>

            </div>

                <div class="col-md-1">
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
       <table class="table table-sm table-bordered resizable " id='dataTable' style="width: 100%">
            <thead>
                <tr>
                    <th  width="3%">#</th>
                    <th   style="width:8%;">{{__('lb.system_code')}}</th>
                    <th width="10%">{{__('lb.kh_name')}}</th>
                    <th width="15%">{{__('lb.en_name')}}</th>
                    <th  width="4%">{{__('lb.gender')}}</th>
                    <th >{{__('lb.age')}}</th>
                    <th width="10%">{{__('lb.hospital_code')}}</th>
                    <th width="15%" >{{__('lb.hospital')}}</th>
                    <th width="17%">{{__('lb.phone')}}</th>
                    <th  width="7.6%">{{__('lb.action')}}</th>
                </tr>
            </thead>
            <tbody>			
                <?php
                    $pagex = @$_GET['page'];
                    if(!$pagex)
                        $pagex = 1;
                    $i = config('app.row') * ($pagex - 1) + 1;
                    
                ?>
                @foreach($patients as $t)
                <?php 
                      
                        
                        $dob = \Carbon\Carbon::parse($t->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ'); 
                    ?>
                    <tr>
                        <td >{{$i++}}</td>
                        <td ><a href="{{url('patient/summary/'.$t->id)}}">{{$t->code}}</a></td>
                        <td>   <a href="{{url('patient/summary', $t->id)}}"  title="លម្អីត"> {{$t->kh_first_name}} {{$t->kh_last_name}}</a></td>
                        <td> {{$t->en_first_name}} {{$t->en_last_name}}</td>
                         <td >{{$t->gender}}</td>
                        <td width="110">@if($dob!=null){{$dob}}@endif</td>
                        <td>{{ $t->h_code }}</td>
                        <td  width="150">
                            @foreach ($hospital as $s)
                                @if($t->hospital_id == $s->id)
                                
                                    {{ $s->name }}
                                
                                @endif
                            @endforeach

                        </td>
                        <td>{{$t->phone}}</td>
                        <td>
                            @canedit('patient')
                            <a href="{{route('patient.edit', $t->id)}}" class="btn btn-success btn-xs">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endcanedit

                            @candelete('patient')
                            <a href="{{url('patient/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endcandelete
                        </td>
                @endforeach
</tbody>
        </table>
        <br>
        {{$patients->links('pagination::bootstrap-4')}}
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
            $("#menu_patient").addClass("active");



            
		
        });
    </script>
@endsection