@extends('layouts.master')
@section('title')
    {{__('lb.patient')}}
@endsection
@section('header')
{{__('lb.patient')}}&nbsp; &nbsp; &nbsp;
{{ $p->code }}&nbsp;&nbsp;|
{{$p->kh_first_name}}&nbsp;&nbsp;
{{$p->kh_last_name}}&nbsp;&nbsp;|
{{$p->en_first_name}}&nbsp;&nbsp;
{{$p->en_last_name}}&nbsp;&nbsp;|
{{$p->h_code}}&nbsp;&nbsp;|
{{$p->phone}}&nbsp;&nbsp;
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @cancreate('patient')
    <a href="{{route('patient.create')}}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </a>
    @endcancreate
    @canedit('patient')
    <a href="{{route('patient.edit', $p->id)}}" class="btn btn-success btn-sm">
        <i class="fa fa-edit"></i> {{__('lb.edit')}}
    </a>
    @endcanedit
    @candelete('patient')
    <a href="{{route('patient.delete', $p->id)}}" class="btn btn-danger btn-sm" 
        onclick="return confirm('{{__('lb.confirm')}}')">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </a>
    @endcandelete
   <a href="{{route('patient.index')}}" class="btn btn-success btn-sm">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
   <a href="{{route('patient.detail', $p->id)}}" class="btn btn-success btn-sm" >
        <i class="fa fa-eye"></i> {{__('lb.show_more')}}
    </a>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
      <div class="row">
          <div class="col-sm-5">
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.system_code')}}</label>
                <div class="col-sm-8">
                    : {{$p->code}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.kh_name')}}</label>
                <div class="col-sm-8">
                    : {{$p->kh_first_name}} {{$p->kh_last_name}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.en_name')}}</label>
                <div class="col-sm-8">
                    : {{$p->en_first_name}} {{$p->en_last_name}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.address')}}</label>
                <div class="col-sm-8">
                    : {{$p->address}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.note')}}</label>
                <div class="col-sm-8">
                    : {{$p->note}}
                </div>
            </div>
          </div>
          <div class="col-sm-5">
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.hospital_code')}}</label>
                <div class="col-sm-8">
                    : {{$p->h_code}}
                </div>
            </div>

            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.hospital')}}</label>
                <div class="col-sm-8">
                    : @if($hospital)
                         {{  $hospital->name }}
                    
                    @endif
                    
                </div>
            </div>
             <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.gender')}}</label>
                <div class="col-sm-8">
                    : {{$p->gender}}
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.dob')}}</label>
                <div class="col-sm-8">
                    : {{$p->dob}} ( {{$dob}} )
                </div>
            </div>
            <div class="form-group row mb-1">
                <label class='col-sm-3'>{{__('lb.phone')}}</label>
                <div class="col-sm-8">
                    : {{$p->phone}}
                </div>
            </div>
            
          </div>
          <div class="col-md-2">
              @if($p->photo!='default.png')
                <img src="{{asset($p->photo)}}" alt="" width="150">
            @else
                @if($p->gender=='ស្រី' || $p->gender=='Female')
                <img src="{{asset('img/girl.png')}}" alt="" width="150">
                @else
                <img src="{{asset('img/boy.png')}}" alt="" width="150">
                @endif
            @endif
          </div>
      </div>

      <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
           <!-- ​tab 1  -->
        <li class="nav-item">
          <a class="nav-link <?php echo (!isset($_GET['tab']) || @$_GET['tab'] == 1)? 'active' : '' ?>" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">
                <strong>{{__('lb.disease_history')}}</strong>
          </a>
        </li>
         <!-- ​tab 2  -->
        <li class="nav-item">
          <a class="nav-link <?php echo (isset($_GET['tab']) && @$_GET['tab'] == 2)? 'active' : '' ?>" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">
              <strong>{{__('lb.pregnancy')}}</strong>
          </a>
        </li>
         <!-- ​tab 3  -->
        <li class="nav-item ">
            <a class="nav-link <?php echo (isset($_GET['tab']) && @$_GET['tab'] == 3)? 'active' : '' ?>" id="custom-tabs-appointment-tab" data-toggle="pill" href="#custom-tabs-appointment" role="tab" aria-controls="custom-tabs-appointment" aria-selected="false">
                <strong>{{__('lb.appointments')}}</strong>
            </a>
        </li>
         <!-- ​tab 4  -->
          <li class="nav-item">
            <a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 4)? 'active' : '' ?>" id="custom-tabs-treatment-tab" data-toggle="pill" href="#custom-tabs-treatment" role="tab" aria-controls="custom-tabs-treatment" aria-selected="false">
                <strong>{{__('lb.treatments')}}</strong>
            </a>
          </li>
           <!-- ​tab 5  -->
          <li class="nav-item">
            <a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 5)? 'active' : '' ?>" id="custom-tabs-request-tab" data-toggle="pill" href="#custom-tabs-request" role="tab" aria-controls="custom-tabs-request" aria-selected="false">
                <strong>{{__('lb.result')}}</strong>
            </a>
          </li>
           <!-- ​tab 6  -->
          <li class="nav-item">
            <a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 6)? 'active' : '' ?>" id="custom-tabs-paraclinical-tab" data-toggle="pill" href="#custom-tabs-paraclinical" role="tab" aria-controls="custom-tabs-paraclinical" aria-selected="false">
                <strong>{{__('lb.paraclinical')}}</strong>
            </a>
          </li>
           <!-- ​tab 7  -->
          <li class="nav-item">
            <a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 7)? 'active' : '' ?>" id="custom-tabs-invoice-tab" data-toggle="pill" href="#custom-tabs-invoice" role="tab" aria-controls="custom-tabs-invoice" aria-selected="false">
                <strong>{{__('lb.invoices')}}</strong>
            </a>
          </li>
      </ul>
      <div class="tab-content" id="custom-tabs-three-tabContent">
        <!-- ​tab 1  -->
        <div class="tab-pane fade <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 1)? 'active show' : '' ?> pt-2" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
            <p>
                <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target='#dModal'>
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.other_disease')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($diseases as $dis)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$dis->date}}</td>  
                            <td>{!!$dis->disease!!}</td> 
                            <td>
                            <a href="#" class="btn btn-success btn-oval btn-xs" 
                                                onclick="get_disease(this, '{{$dis->id}}')" data-toggle="modal" data-target="#eDisease"> <i class="fa fa-edit"></i> </a>
                            <a href="{{route('disease.delete', $dis->id)}}" class="btn btn-danger btn-xs" title='{{__('lb.delete')}}' 
                                    onclick="return confirm('{{__('lb.confirm')}}')">
                                    <i class="fa fa-trash"></i>
                            </td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
         <!-- ​tab 2  -->
        <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 2)? 'active show' : '' ?> pt-2" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
            <p>
                <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target='#addModal'>
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.date')}}</th>
                        <th>អាយុគភ៌</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($pgs as $pg)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$pg->date}}</td>
                            <td>{!!$pg->pregnancy!!}</td>
                            <td>
                            <a href="#" class="btn btn-success btn-oval btn-xs" 
                                                onclick="get_pregnancy(this, '{{$pg->id}}')" data-toggle="modal" data-target="#ePregnancy"> <i class="fa fa-edit"></i> </a>
                                <a href="{{route('pregnancy.delete', $pg->id)}}" class="btn btn-danger btn-xs" title='{{__('lb.delete')}}' 
                                    onclick="return confirm('{{__('lb.confirm')}}')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
         <!-- ​tab 3  -->
        <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 3)? 'active show' : '' ?> pt-2" id="custom-tabs-appointment" role="tabpanel" aria-labelledby="custom-tabs-appointment-tab">
            <p>
                <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target='#addAppointmentModal'>
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.topic')}}</th>
                        <th>{{__('lb.note')}}</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.meet_time')}}</th>
                        <th>{{__('lb.doctor')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($appointments as $app)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$app->topic}}</td>
                            <td>{{$app->description}}</td>
                            <td>{{$app->meet_date}}</td>
                            <td>{{$app->meet_time}}</td>
                            <td>{{$app->dfirst_name}} {{$app->dlast_name}} ( {{$app->phone}} ) </td>
                            <td>
                                <a href="#" class="btn btn-danger btn-xs" title='{{__('lb.delete')}}' 
                                onclick="delete_appointment(this, '{{$app->id}}')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
         <!-- ​tab 4  -->
        <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 4)? 'active show' : '' ?> pt-2" id="custom-tabs-treatment" role="tabpanel" aria-labelledby="custom-tabs-treatment-tab">
            <p>
                <a href="{{url('treatment/create?patient_id='.$p->id)}}" class="btn btn-success btn-xs">
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.diagnosis')}}</th>
                        <th>{{__('lb.doctor')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($treatments as $t)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$t->date}}</td>
                          <td><a href="{{url('treatment/detail/'.$t->id)}}" target="_blank">{{ \Illuminate\Support\Str::limit($t->diagnosis1, 150, $end='...') }}</a></td>  
                           
                            <td>{{$t->dfirst_name}} {{$t->dlast_name}} ( {{$t->phone}} )</td>
                            <td>
                                <a href="{{url('treatment/print/'.$t->id)}}" target="_blank" class='btn btn-xs btn-primary' title='{{__('lb.print')}}' 
                               ><i class="fa fa-print"></i></a>
                               <a href="{{url('treatment/detail/'.$t->id)}}" target="_blank" class='btn btn-xs btn-success' title='{{__('lb.detail')}}' 
                               ><i class="fa fa-eye"></i></a>
                                <a href="#" class='btn btn-xs btn-danger' title='{{__('lb.delete')}}' 
                                onclick="delete_treatment(this, '{{$t->id}}')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
         <!-- ​tab 5  -->
        <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 5)? 'active show' : '' ?> pt-2 " id="custom-tabs-request" role="tabpanel" aria-labelledby="custom-tabs-request-tab">
            <p>
                <a href="{{url('request/create?patient_id='.$p->id)}}" class="btn btn-success btn-xs">
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                    <th class="text-center">#</th>
                        <th>{{__('lb.code')}}</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.body_part')}}</th>
                        <th>{{__('lb.items')}}</th>
                        <th>{{__('lb.symptom')}}</th>
                        <th>{{__('lb.status')}}</th>
                        <th>{{__('lb.doctor')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($request_details as $rc)
                        <?php 
                            $request = DB::table('requestchecks')->where('active',1)
                            ->where('id', $rc->request_id)
                            ->first();
                            $percent3 = null;
                            if($rc->percent3) {
                                $percent3 = DB::table('users')->where('id', $rc->percent3)->first();
                            }
                        ?>
                      
                        <tr>
                            <td class="text-center">{{$i++}}</td>
                        <td>@if($request!=null){{$request->code}}@endif</td>
                            <td>
                                {{$rc->date}} {{\Carbon\Carbon::createFromFormat('H:i:s',$rc->time)->format('h:i A')}}
                            </td> 
                            <td>{{$rc->section_name}}</td>
                            <td>{{$rc->item_name}}</td>
                            <td>@if($request!=null){{$request->symptom}}@endif</td>
                            <td> @if($rc->request_status==1) {{__('lb.scheduling')}}
                        @elseif($rc->request_status==2){{__('lb.confirmed')}}
                        @elseif($rc->request_status==3){{__('lb.arrived')}}
                        @elseif($rc->request_status==4){{__('lb.rescheduled')}}
                        @elseif($rc->request_status==5){{__('lb.waiting_shot')}}
                        @elseif($rc->request_status==6){{__('lb.performing')}}
                        @elseif($rc->request_status==8){{__('lb.waiting_reading')}}
                        @elseif($rc->request_status==9){{__('lb.reading')}}
                        @elseif($rc->request_status==10){{__('lb.reading')}}
                        @elseif($rc->request_status==11){{__('lb.validated')}}
                        @elseif($rc->request_status==0){{__('lb.canceled')}}
                        @endif</td>
                        <td>
                            @if($percent3!=null)
                        {{$percent3->first_name}}       {{$percent3->last_name}}
                        @endif
                        </td>
                            <td>
                                @if($rc->request_status==11)
                                 <a href="{{url('patient/result/print/'.$rc->id)}}" target="_blank" class=" btn btn-xs btn-primary"> 
                                    <i class="fa fa-print"></i>
                                 </a>
                                @endif
                               
                               
                                
                            </td>
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- ​tab 6  -->
        <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 6)? 'active show' : '' ?> pt-2" id="custom-tabs-paraclinical" role="tabpanel" aria-labelledby="custom-tabs-paraclinical-tab">
        <p>
                <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target='#addParaModal'>
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('lb.paraclinical_date')}}</th>
                        <th>{{__('lb.paraclinical_title')}}</th>
                        <th>{{__('lb.note')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($paraclinicals as $pr)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$pr->date}}</td>  
                            <td><a href="{{url('paraclinical/result/'.$pr->id)}}" target="_blank"> {{$pr->title}}</a></td> 
                            <td>{{$pr->note}}</td>
                            <td>
                                <a href="{{url('paraclinical/result/'.$pr->id)}}" target="_blank"  class="btn btn-xs btn-success"> 
                                    <i class="fa fa-file-pdf"  title="{{__('lb.download')}}"></i>
                                 </a>
                                 <a href="#" class="btn btn-success btn-oval btn-xs" 
                                                onclick="get_para(this, '{{$pr->id}}')" data-toggle="modal" data-target="#eParaModal"> <i class="fa fa-edit"></i> </a>
                                <a href="#"  title='Delete' class="btn btn-xs btn-danger"
                                onclick="delete_paraclinical(this, '{{$pr->id}}')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- ​tab 7  -->
        <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 7)? 'active show' : '' ?> pt-2" id="custom-tabs-invoice" role="tabpanel" aria-labelledby="custom-tabs-invoice-tab">
            <p>
                <a href="{{url('invoice/create?patient_id='.$p->id)}}" class="btn btn-success btn-xs">
                    <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
                </a>
            </p>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                       
                        <th class="text-center">#</th>
                        <th class="text-center">{{__('lb.invoice_no')}}</th>
                  
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.due_date')}}</th>
                        <th>{{__('lb.paid')}} ($)</th>
                        <th>{{__('lb.total')}} ($)</th>
                        <th>{{__('lb.due_amount')}} ($)</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($invoices as $t)
         
                    <tr @if($t->due_amount>0) style="background: #acd1f9;" @endif>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center"><a href="{{url('invoice/detail', $t->id)}}">{{$t->invoice_no}}</a></td>
                     
                        <td>{{$t->start_date}}</td>
                        <td>{{$t->due_date}}</td>
                        <td>$ {{number_format($t->paid,2)}}</td>
                        <td>$ {{number_format($t->total,2)}}</td>
                        <td>$ {{number_format($t->due_amount,2)}}</td>
               
                        <td>
                            <a href="{{url('invoice/detail', $t->id)}}" title="{{__('lb.detail')}}" class='btn btn-success btn-xs'
                             >
                                <i class="fa fa-eye"></i>
                            </a> &nbsp;
                             @candelete('invoice')
                            
                            <a href="{{url('invoice/delete', $t->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
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
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{route('pregnancy.save')}}" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{$p->id}}">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.add_pregnancy')}}
                    &nbsp;&nbsp;
                    {{ $p->code }}&nbsp;&nbsp;|
                    {{$p->kh_first_name}}&nbsp;&nbsp;
                    {{$p->kh_last_name}}&nbsp;&nbsp;|
                    {{$p->en_first_name}}&nbsp;&nbsp;
                    {{$p->en_last_name}}&nbsp;&nbsp;|
                    {{$p->h_code}}&nbsp;&nbsp;|
                    {{$p->phone}}&nbsp;&nbsp;</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div class="form-group row mb-1">
                    <label class="col-sm-2">{{__('lb.date')}}</label>
                    <div class="col-sm-8">
                        <input type="date" class="txt" value="{{date('Y-m-d')}}" name="date">
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class="col-sm-2">អាយុគភ៌</label>
                    <div class="col-sm-10">
                        <textarea name="pregnancy" id="" cols="30" rows="10" class="ckeditor"></textarea>
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


<!-- create model -->
<div class="modal fade" id="addParaModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{route('paraclinical.save')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="patient_id" value="{{$p->id}}">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_paraclinical')}}
                    &nbsp;&nbsp;
                    {{ $p->code }}&nbsp;&nbsp;|
                    {{$p->kh_first_name}}&nbsp;&nbsp;
                    {{$p->kh_last_name}}&nbsp;&nbsp;|
                    {{$p->en_first_name}}&nbsp;&nbsp;
                    {{$p->en_last_name}}&nbsp;&nbsp;|
                    {{$p->h_code}}&nbsp;&nbsp;|
                    {{$p->phone}}&nbsp;&nbsp;</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms">
                </div>
             
                <div class="form-group row">
                    <label for="paraclinical_date" class="col-md-3">
                        {{__('lb.paraclinical_date')}} 
                    </label>
                    <div class="col-md-4">
                        <input type="date" name="paraclinical_date" class="form-control input-xs" id="paraclinical_date" value="{{date('Y-m-d')}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="paraclinical_title" class="col-md-3">
                        {{__('lb.paraclinical_title')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="paraclinical_title" class="form-control input-xs" id="paraclinical_title">
                    </div>
                </div>
               
                
                <div class="form-group row">
                    <label for="paraclinical_result" class="col-md-3">
                        {{__('lb.document')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" id="paraclinical_result" name="paraclinical_result" id="paraclinical_result" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="paraclinical_note" class="col-md-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-md-9">
                        <textarea name="paraclinical_note" rows="2" id="paraclinical_note" class="form-control"></textarea>
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
<div class="modal fade" id="eParaModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{route('para.update')}}" method="POST" id='edit_form' enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="patient_id" value="{{$p->id}}">
          <input type="hidden" name="para_id" id='para_id'>
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
                    <label for="eparaclinical_title" class="col-md-3">
                        {{__('lb.paraclinical_date')}}
                    </label>
                    <div class="col-md-4">
                        <input type="date" name="paraclinical_date" class="form-control input-xs" id="eparaclinical_date" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="etitle" class="col-md-3">
                        {{__('lb.paraclinical_title')}}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="paraclinical_title" class="form-control input-xs" id="eparaclinical_title" required>
                    </div>
                </div>
               
                <div class="form-group row">
                    <label for="eparaclinical_note" class="col-md-3">
                        {{__('lb.note')}}
                    </label>
                    <div class="col-md-9">
                        <textarea name="paraclinical_note" rows="2" id="eparaclinical_note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="eparaclinical_result" class="col-md-3">
                        {{__('lb.document')}}
                    </label>
                    <div class="col-md-9">
                        <input type="file" name="paraclinical_result" id="eparaclinical_result" class="form-control input-xs">
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
<div class="modal fade" id="ePregnancy" tabindex="-1" role="dialog" aria-labelledby="ePregnancy" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{route('pregnancy.update')}}" method="POST">
          @csrf
          <input type="hidden" name="customer_id" value="{{$p->id}}">
          <input type="hidden" name="pregnancy_id" id="pregnancy_id">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">កែប្រែ គភ៌</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div class="form-group row mb-1">
                    <label class="col-sm-2">{{__('lb.date')}}</label>
                    <div class="col-sm-8">
                        <input type="date" id="pregnancy_date" class="txt" value="{{date('Y-m-d')}}" name="date">
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label class="col-sm-2">អាយុគភ៌</label>
                    <div class="col-sm-10">
                        <textarea name="pregnancy" id="epregnancy" cols="30" rows="10" class="ckeditor"></textarea>
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
<div class="modal fade" id="dModal" tabindex="-1" role="dialog" aria-labelledby="dModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{route('disease.save')}}" method="POST">
          @csrf
          <input type="hidden" name="customer_id" value="{{$p->id}}">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.add_disease')}} &nbsp;&nbsp;
                    {{ $p->code }}&nbsp;&nbsp;|
                    {{$p->kh_first_name}}&nbsp;&nbsp;
                    {{$p->kh_last_name}}&nbsp;&nbsp;|
                    {{$p->en_first_name}}&nbsp;&nbsp;
                    {{$p->en_last_name}}&nbsp;&nbsp;|
                    {{$p->h_code}}&nbsp;&nbsp;|
                    {{$p->phone}}&nbsp;&nbsp;
                </strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
              <div class="form-group row mb-1">
                    <label class="col-sm-2">ការបរិច្ឆេត</label>
                    <div class="col-sm-8">
                    <input type="date" class="txt" value="{{date('Y-m-d')}}" name="date">
                    </div>
                </div>
            
                <div class="form-group row mb-1">
                <label class="col-sm-2">ជំងឺផ្សេងៗ</label>
                    <div class="col-md-10">
                           
                            <textarea name="input_disease" id="" cols="30" rows="10" class="ckeditor"></textarea>
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

<div class="modal fade" id="eDisease" tabindex="-1" role="dialog" aria-labelledby="eDisease" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{route('disease.update')}}" method="POST">
          @csrf
          <input type="hidden" name="customer_id" value="{{$p->id}}">
          <input type="hidden" name="disease_id" id="disease_id">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">កែប្រែ ប្រវត្តិជម្ងឺ</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
              <div class="form-group row mb-1">
                    <label class="col-sm-2">ការបរិច្ឆេត</label>
                    <div class="col-sm-8">
                    <input type="date" class="txt" id="edisease_date" name="date">
                    </div>
                </div>
            
                <div class="form-group row mb-1">
                <label class="col-sm-2">ជំងឺផ្សេងៗ</label>
                    <div class="col-md-10">
                           
                            <textarea name="input_disease"  id="edisease" cols="30" rows="10" class="ckeditor"></textarea>
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

<div class="modal fade" id="addAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="addAppointmentModal" aria-hidden="true">
    <div class="modal-dialog" role="appointment">
      <form action="{{url('patient/appointment/save')}}" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{$p->id}}">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.add_appointment')}}
                    &nbsp;&nbsp;
                    {{ $p->code }}&nbsp;&nbsp;|
                    {{$p->kh_first_name}}&nbsp;&nbsp;
                    {{$p->kh_last_name}}&nbsp;&nbsp;|
                    {{$p->en_first_name}}&nbsp;&nbsp;
                    {{$p->en_last_name}}&nbsp;&nbsp;|
                    {{$p->h_code}}&nbsp;&nbsp;|
                    {{$p->phone}}&nbsp;&nbsp;
                </strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
              <div class="modal-body">
            <div class="form-group mb-1 ">
                    <label>
                        {{__('lb.doctor')}} <span class="text-danger">*</span>
                    </label>
                    <select name="doctor_id" id="doctor_id" class="chosen-select" required>
                        <option value="">{{__('lb.select_one')}}</option>
                        @foreach ($users as $u)
                            <option value="{{$u->id}}">{{$u->last_name}} {{$u->first_name}}  ( {{$u->phone}} )</option>
                        @endforeach
                    </select>
            </div>
                <div class="form-group mb-1">
                    <label>
                        {{__('lb.meet_date')}} <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="meet_date" class="form-control" required>
                </div>
                <div class="form-group mb-1">
                    <label>
                        {{__('lb.meet_time')}} <span class="text-danger">*</span>
                    </label>
                    <input type="time" name="meet_time" class="form-control" required>
                </div>
                <div class="form-group mb-1">
                    <label>
                        {{__('lb.topic')}} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="topic" class="form-control" required>
                </div>
                <div class="form-group mb-1">
                    <label>
                        {{__('lb.description')}}
                    </label>
                    <textarea name="description" id="" cols="3" class="form-control"></textarea>
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
          </div>
      </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/ckeditor2/ckeditor.js')}}"></script>
    <script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_patient").addClass("active");
            
        });
       // function to delete photo
       function delete_appointment(obj, id)
        {
            let con = confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');
            if(con)
            {
                $.ajax({
                    type: "GET",
                    url: burl + "/patient/appointment/delete/" + id,
                    success: function (data)
                    {
                        if(data>0)
                        {
                            $(obj).parent().parent().remove();
                        }
                    }
                });
            }
        }

        function delete_treatment(obj, id)
        {
            let con = confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');
            if(con)
            {
                $.ajax({
                    type: "GET",
                    url: burl + "/patient/treatment/delete/" + id,
                    success: function (data)
                    {
                        if(data>0)
                        {
                            $(obj).parent().parent().remove();
                        }
                    }
                });
            }
        }

        function delete_invoice(obj, id)
        {
            let con = confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');
            if(con)
            {
                $.ajax({
                    type: "GET",
                    url: burl + "/patient/invoice/delete/" + id,
                    success: function (data)
                    {
                        if(data>0)
                        {
                            $(obj).parent().parent().remove();
                        }
                    }
                });
            }
        }
        function delete_request(obj, id)
        {
            let con = confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');
            if(con)
            {
                $.ajax({
                    type: "GET",
                    url: burl + "/patient/request/delete/" + id,
                    success: function (data)
                    {
                        if(data>0)
                        {
                            $(obj).parent().parent().remove();
                        }
                    }
                });
            }
        }

        function delete_paraclinical(obj, id)
        {
            let con = confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');
            if(con)
            {
                $.ajax({
                    type: "GET",
                    url: burl + "/patient/paraclinical/delete/" + id,
                    success: function (data)
                    {
                        if(data>0)
                        {
                            $(obj).parent().parent().remove();
                        }
                    }
                });
            }
        }
        function get_disease(obj,id)
        {
            let tr = $(obj).parent().parent();
            $("#data tr").removeClass('active');
            $(tr).addClass('active');
            $('#treatment_id').val(id);
        
                $.ajax({
                    type: "GET",
                    url: burl + "/customer/get-disease/" + id,
                    success: function (sms)
                    {
                        let data = JSON.parse(sms);
                        $('#edisease_date').val(data.date);
                        $('#disease_id').val(data.id);
                        $('#edisease').val(CKEDITOR.instances['edisease'].setData(data.disease));
                        $("#edisease").trigger("chosen:updated");
                    }
                });
       
        }
        function get_para(obj,id)
        {
            let tr = $(obj).parent().parent();
            $("#data tr").removeClass('active');
            $(tr).addClass('active');
            $('#para_id').val(id);
        
                $.ajax({
                    type: "GET",
                    url: burl + "/patient/get-para/" + id,
                    success: function (sms)
                    {
                        let data = JSON.parse(sms);
                        $('#eparaclinical_date').val(data.date);
                        $('#eparaclinical_title').val(data.title);
                        $('#eparaclinical_note').val(data.note);
                    }
                });
       
        }
        function get_pregnancy(obj,id)
        {
            let tr = $(obj).parent().parent();
            $("#data tr").removeClass('active');
            $(tr).addClass('active');
            $('#treatment_id').val(id);
        
                $.ajax({
                    type: "GET",
                    url: burl + "/customer/get-pregnancy/" + id,
                    success: function (sms)
                    {
                        let data = JSON.parse(sms);
                        $('#pregnancy_date').val(data.date);
                        $('#pregnancy_id').val(data.id);
                        $('#epregnancy').val(CKEDITOR.instances['epregnancy'].setData(data.pregnancy));
                        $("#epregnancy").trigger("chosen:updated");
                    }
                });
       
        }
    </script>
@endsection