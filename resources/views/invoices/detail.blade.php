@extends('layouts.master')
@section('title')
    {{__('lb.invoice')}}
@endsection
@section('header')
    {{__('lb.invoice')}}
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<div class="toolbox pt-1 pb-1">
    @cancreate('invoice')
    <a href="{{route('invoice.create')}}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </a>
    @endcancreate
    @candelete('invoice')
    <a href="{{url('invoice/delete/'.$invoice->id)}}" class="btn btn-danger btn-sm" 
        onclick="return confirm('{{__('lb.confirm')}}')">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </a>
    @endcandelete
    <a href="{{url('invoice/print/'.$invoice->id)}}" target="_blank" class="btn btn-primary btn-oval btn-sm">
        <i class="fa fa-print"></i> {{trans('lb.print')}}
    </a>
   <a href="{{route('invoice.index')}}" class="btn btn-success btn-sm">
       <i class="fa fa-reply"></i> {{__('lb.back')}}
   </a>
</div>   
<div class="card">
	
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
      <div class="row">
          <div class="col-sm-6">
          <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.invoice_no')}}</div>
                <div class="col-sm-8">
                    : <b>{{$invoice->invoice_no}}</b>
                </div>
            </div>
           
            <div class="form-group row mb-2">
                <div class='col-sm-3'><b>{{__('lb.patient')}}</b></div>
                <div class="col-sm-8">
                   
                    : <a href="{{url('patient/detail/'.$invoice->pid)}}"> <b> {{$invoice->pfirst_name}}  {{$invoice->plast_name}} - {{$invoice->phone}}</b>
                    </a>
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.bank')}}</div>
                <div class="col-sm-8">
                    :<b> {{$invoice->bank}}</b>
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.cashier')}}</div>
                <div class="col-sm-8">
                    : <b> {{$user->first_name}} {{$user->last_name}}</b>
                </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.date')}}</div>
                <div class="col-sm-8">
                    : <b>{{$invoice->start_date}} </b>
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.due_date')}}</div>
                <div class="col-sm-8">
                    : <b>{{$invoice->due_date}}</b>
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.total')}}</div>
                <div class="col-sm-8  font-weight-bold text-success">
                    : <b>$ {{number_format($invoice->total,2)}}</b>
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.paid')}}</div>
                <div class="col-sm-8  font-weight-bold text-success">
                    :<b> $ {{number_format($invoice->paid,2)}}</b>
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class='col-sm-3'>{{__('lb.due_amount')}}</div>
                <div class="col-sm-8  font-weight-bold text-danger">
                    : <b> $ {{number_format($invoice->due_amount,2)}}</b>
                </div>
            </div>
            
          </div>
      </div>
      <h6 class="text-success">
                បរិយាយសេវាកម្ម​ និងមុខទំនិញ
            </h6>
            <form action="{{url('invoice/request-update')}}" method="POST">
            @csrf
            <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
            <div class="row">
                <div class="col-md-12">
                <div class="form-group row">
                    <label for="due_date" class="col-sm-2">
                       សេវាកម្មដែលយើងបានស្នើរសុំរួច
                    </label>
                    <div class="col-sm-6">
                        <select name="request_id" multiple id="request_id" class="form-control chosen-select" onchange="getRequest()">
                        <option value="0"> {{__('lb.select_one')}} </option>
                            @foreach($requestchecks as $req)
                            <option value="{{$req->id}}"> {{$req->code}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-oval btn-xs">
                        <i class="fa fa-save"></i> {{__('lb.save')}}
                    </button>
                    </div>
                </div>
                </div>
            </div>
            <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>{{__('lb.reference_no')}}</th>
                    <th>{{__('lb.body_part')}}</th>
                    <th>{{__('lb.items')}}</th>
                    <th>{{__('lb.price')}} ($)</th>
                    <th>{{__('lb.discount')}} ($)</th>
                    <th>{{__('lb.total')}} </th>
                </tr>
            </thead>
            <tbody id="data1">
                
            </tbody>
</table>
</form>
<div class="col-sm-12">
            <strong class='text-danger'>
                សរុប: $<span id="total">0.00</span> 
            </strong>
            <input type="hidden" name="grand_total" id="grand_total" value="0">
        </div>
<br>
      <h5 class="text-success">{{__('lb.items')}}
      <a href="#" class="btn btn-primary btn-oval btn-xs" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> {{__('lb.request_more')}}
            </a>
      </h5>
      <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('lb.reference_no')}}</th>
                        <th> {{__('lb.body_part')}}</th>
                        <th>{{__('lb.items')}}</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.time')}}</th>
                        <th>{{__('lb.percent1')}}</th>
                        <th>{{__('lb.reference_no')}}</th>
                        <th>{{__('lb.price')}}</th>
                        <th>{{__('lb.discount')}}</th>
                        <th>{{__('lb.total')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody id="data">
                    @php $i = 1; @endphp
                    @foreach($invoice_details as $r)
                        <?php 
                            $sub_total = $r->price - $r->discount;
                        ?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$r->request_code}}</td>
                            <td>{{$r->section_name}}</td>
                            <td>{{$r->item_name}}</td>
                            <td>{{$r->date}}</td>
                            <td>{{date('H:i A', strtotime($r->time))}}</td>
                            <td>{{$r->first_name}} {{$r->last_name}} ( {{$r->phone}} )</td>
                            <td>{{$r->code}}</td>
                            <td>$ {{number_format($r->price,2)}}</td>
                            <td>$ {{number_format($r->discount,2)}}</td>
                            <td>$ {{number_format($sub_total,2)}}</td>
                            <td>
                                  <a href="{{url('invoice/delete-request',$r->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </tr>
                    @endforeach
                    @php $i++; @endphp
                </tbody>
            </table>
            </div>
            
      </div>
    
      <h5 class="text-success">{{__('lb.medicine')}}
      <a href="#" class="btn btn-primary btn-oval btn-xs" data-toggle="modal" data-target="#medicineModal">
                <i class="fa fa-plus"></i> {{__('lb.add_item')}}
            </a>
      </h5>
      <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th> {{__('lb.body_part')}}</th>
                        <th> {{__('lb.medicine')}}</th>
                        <th>{{__('lb.qty')}}</th>
                        <th>{{__('lb.price')}}</th>
                        <th>{{__('lb.discount')}}</th>
                        <th>{{__('lb.total')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody id="data">
                @if(count($invoice_detail2)>0)
                    @php $j = 1; @endphp
                  
                    @foreach($invoice_detail2 as $inv2)
             
                        <?php 
                            $total = $inv2->qty * $inv2->price - $inv2->discount;
                            $category = DB::table('categories')->where('active', 1)->first();
                        ?>
                        <tr>
                            <td>{{$j++}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$inv2->name}}</td>
                            <td>{{$inv2->qty}}</td>
                            <td>$ {{number_format($inv2->price,2)}}</td>
                            <td>$ {{number_format($inv2->discount,2)}}</td>
                            <td>$ {{number_format($total,2)}}</td>
                            <td>
                                  <a href="{{url('invoice/delete-medicine', $inv2->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('You want to delete?')" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                            </td>
                        </tr>
                    @endforeach
                    @php $j++; @endphp
                    @endif
                </tbody>
            </table>
            </div>
            
      </div>
    

      <h6 class="text-primary">ការបង់ប្រាក់
        @if($invoice->due_amount>0)
        <button class="btn btn-success btn-xs" data-toggle='modal' data-target='#createModal' id='btnCreate'>
            <i class="fa fa-plus-circle"></i> {{__('lb.add')}}
        </button>
        @endif
    </h6>
    @if(count($payments)>0)
    @else
        <p class="text-danger">មិនទាន់មានការបង់ប្រាក់នៅឡើយទេ!</p>
    @endif
    <div class="row">
        <div class="col-sm-7">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>&numero;</th>
                        <th>{{__('lb.date')}}</th>
                        <th>{{__('lb.total')}}</th>
                        <th>{{__('lb.note')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach($payments as $pay)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{date_format(date_create($pay->pay_date),'d/m/Y')}}</td>
                            <td>$ {{number_format($pay->amount,2)}}</td>
                            <td>{{$pay->note}}</td>
                            <td>
                                <a href="{{url('payment/delete/'.$pay->id)}}" class='btn btn-danger btn-oval btn-xs'
                                 onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


      </div>
	</div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form action="{{url('invoice/request/save')}}" method="POST">
  <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
  <input type="hidden" value="{{$invoice->pid}}" name="patient_id">
          @csrf
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">{{__('lb.request_more')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="sms"></div>
                <div class="form-group row">
                    <label for="section_id" class="col-sm-3">{{__('lb.body_part')}} <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
						<select name="section_id" class="form-control chosen-select" id="section_id" required onchange="getItem()">
							<option value="0">{{__('lb.select_one')}}</option>
							@foreach ($sections as $s)
                                <option value="{{$s->id}}" name="{{$s->name}}">{{$s->name}}</option>
                            @endforeach
						</select>
                    </div>
                </div>
                
                <input type="hidden" id="section_name" name="section_name">
                <div class="form-group row">
                    <label for="item_id" class="col-sm-3">{{__('lb.items')}}<span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                    
						<select name="item_id" class="form-control chosen-select" id="item_id" required onchange="getPrice()">
							<option value="">{{__('lb.select_one')}}</option>
							@foreach ($items as $item)
                                <option value="{{$item->id}}" price="{{$item->price}}" name="{{$item->name}}">{{$item->name}}</option>
                            @endforeach
						</select>
                    </div>
                </div>
                <input type="hidden" id="item_name" name="item_name">
                <div class="form-group row">
                    <label for="price" class="col-sm-3">{{__('lb.price')}} ($)<span class="text-danger">*</span></label>
                    <div class="col-sm-3">
                        <input type="number" step="0.1" min="0" class="form-control input-xs" name="price" id="price">
                    </div>
                </div>       
                <div class="form-group row">
                    <label for="discount" class="col-sm-3">{{__('lb.discount')}} ($)</label>
                    <div class="col-sm-3">
                        <input type="number" step="0.1" min="0" class="form-control input-xs" name="discount" id="discount" 
                         value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date" class="col-sm-3" title="">{{__('lb.date')}}</label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control input-xs" name="request_date" id="request_date" 
                         value="{{date('Y-m-d')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="time" class="col-sm-3" title="">{{__('lb.time')}}</label>
                    <div class="col-sm-4">
                        <input type="time" class="form-control input-xs" name="request_time" id="request_time" 
                         value="{{date('H:m')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="request_note" class="col-sm-3" title="">{{__('lb.note')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-xs" name="request_note" id="request_note">
                    </div>
                </div>
                <?php $percent1_code = DB::table('users')->where('id',Auth::user()->id)->first();?>
                <div class="form-group row">
                    <label for="percent1" class="col-sm-3">{{__('lb.percent1')}}<span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                    
						<select name="percent1" class="form-control chosen-select" id="percent1" required onchange="getCode()">
							<option value="">{{__('lb.select_one')}}</option>
							@foreach ($doctors as $d)
                                <option value="{{$d->id}}" code="{{$d->code}}" {{$d->id==Auth::user()->id?'selected':''}}>{{$d->first_name}} {{$d->last_name}} ( {{$d->phone}} ) </option>
                            @endforeach
						</select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="percent1_code" class="col-sm-3" title="">{{__('lb.code')}}</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control input-xs" name="percent1_code" value="{{$percent1_code->code}}" id="percent1_code">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div style='padding: 5px'>
                    <button class="btn btn-primary btn-sm" id="btn"><i class="fa fa-save"></i> {{__('lb.save')}}</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-times"></i> {{__('lb.close')}}</button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>      

{{-- medicine --}}

<!-- create model -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog" aria-labelledby="medicineModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{url('invoice/medicine/save')}}" method="POST">
      @csrf
      <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.add_medicine')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <div id="sms1">
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>
                            {{__('lb.body_part')}} <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                    <select name="category_id" id="category_id" class="chosen-select" onchange="getMedicine()">
                        <option value="0">{{__('lb.select_one')}}</option>
                        @foreach ($categories as $cat)
                        <option value="{{$cat->id}}" name="{{$cat->name}}">{{$cat->name}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="category_name" name="category_name">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>
                            {{__('lb.medicine')}} <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                    <select name="medicine_name" id="medicine_name" class="chosen-select" required>
                        <option value="">{{__('lb.select_one')}}</option>
                        @foreach ($medicines as $m)
                        <option value="{{$m->id}}">{{$m->name}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
               
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>
                            {{__('lb.qty')}} 
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" value="1" step="1" min="0" name="medicine_qty" id="medicine_qty" class="form-control input-xs">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>
                            {{__('lb.price')}} ($) <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" min="0" value="0" name="medicine_price" id="medicine_price" class="input-xs form-control" placeholder="$">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>
                            {{__('lb.discount')}} ($) <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" min="0" value="0" name="medicine_discount" id="medicine_discount" class="input-xs form-control" placeholder="$">
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
 <!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('payment/save')}}" method="POST">
          @csrf
          <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
          <input type="hidden" name="customer_id" value="{{$invoice->patient_id}}">
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_position')}}</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
    
                    <div class="form-group row">
                        <label for="pay_date" class="col-sm-3">{{__('lb.date')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control input-xs" required name="pay_date" id="pay_date" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amount" class="col-sm-3">{{__('lb.total')}}($) <span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="number" step="0.1" min="0" class="form-control input-xs" name="amount"
                                value="{{$invoice->due_amount}}" id="amount" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amount" class="col-sm-3">{{__('lb.note')}}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-xs" name="note"
                                 id="note">
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
            $("#menu_invoice").addClass("active");
        });
        // function to get sub category
        function getItem()
        {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/item/get-item/" + $("#section_id").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var note = data[i].code;
                        var description = data[i].name;
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].id + "' price='" + data[i].price +"' name='" + data[i].name +"' >" + data[i].code + '-' + data[i].name + "</option>";
                    }
                    $("#item_id").html(opts);
                    $("#item_id").trigger("chosen:updated");
                }
            });
        }
        function getPrice()
        { 
            let price = $("#item_id :selected").attr('price');
            let item_name = $("#item_id :selected").attr('name');
            let section_name = $("#section_id :selected").attr('name');
            $("#item_name").val(item_name);
            $("#section_name").val(section_name);
            $("#price").val(price);
        }
         // function to get sub category
         function getMedicine()
        {
            // get medicine
            $.ajax({
                type: "GET",
                url: burl + "/invoice/get-medicine/" + $("#category_id").val(),
                success: function (data) {
                    opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var i=0; i<data.length; i++)
                    {  
                        var note = data[i].code;
                        if(data[i].note == null)
                        {
                            note = '';
                        }
                        if(data[i].description == null)
                        {
                            description = '';
                        }
                        opts +="<option value='" + data[i].name + "'>" + data[i].name + "</option>";
                    }
                    $("#medicine_name").html(opts);
                    $("#medicine_name").trigger("chosen:updated");
                }
            });
        }
         
        function getCode()
        { 
            let code = $("#percent1 :selected").attr('code');
        
            $("#percent1_code").val(code);
        }
        function getRequest()
            {
                let id = $("#request_id").val();
                var row;
                for(a=0; a<id.length; a++)
                {   
                    $.ajax({
                        type: "GET",
                        async: false,
                        url : burl + "/invoice/get-request/" + id[a],
                        success: function(sms)
                        {

                            let data = JSON.parse(sms);
                            
                            if(data!=null)
                            {
                                let items = data.details;
                                
                                for(i=0; i<items.length; i++)
                                {
                                    let percent2 = items[i].percent2;
                                    let percent3 = items[i].percent3;
                                    let request_note = items[i].request_note;
                                    let sub_total = items[i].price - items[i].discount;
                                    if(request_note==null) {
                                        request_note = '';
                                    } 
                                    if(percent2 == null) {
                                        percent2 = '';
                                    }
                                    if(percent3 == null) {
                                        percent3 = '';
                                    }
                                    row +=`
                                        <tr>
                                            <td>
                                            <input type="hidden" value="${items[i].code}" name="request_code[]">
                                                <input type="hidden" value="${items[i].date}" name="request_date[]">
                                                <input type="hidden" value="${items[i].time}" name="request_time[]">
                                                <input type="hidden" value="${items[i].section_id}" name="section_id[]"> 
                                                <input type="hidden" value="${items[i].section_name}" name="section_name[]"> 
                                                <input type="hidden" value="${items[i].item_id}" name="item_id[]"> 
                                                <input type="hidden" value="${items[i].item_name}" name="item_name[]"> 
                                                <input type="hidden" value="${items[i].price}" name="price[]"> 
                                                <input type="hidden" value="${sub_total}" name="sub_total[]"> 
                                                <input type="hidden" value="${items[i].discount}" name="discount[]"> 
                                                <input type="hidden" value="${items[i].percent1}" name="percent1[]"> 
                                                <input type="hidden" value="${items[i].percent1_code}" name="percent1_code[]"> 
                                                <input type="hidden" value="${percent2}" name="percent2[]"> 
                                                <input type="hidden" value="${percent3}" name="percent3[]"> 
                                                <input type="hidden" value="${items[i].id}" name="request_detail_id[]"> 
                                                
                                                ${items[i].request_code}</td>
                                                <td>${items[i].section_name}</td>
                                            <td>${items[i].item_name}</td>
                                            <td>${items[i].price}</td>
                                            <td>${items[i].discount}</td>
                                            <td class="sub">${sub_total.toFixed(2)}</td>
                                        
                                        </tr>
                                    `;
                                }
                            
                            }
                            
                        }
                    });
                }
                if(id.length==0)
                {
                    $("#data1").html("");
                }
                else{
                    $("#data1").html(row);
                }
                getTotal();
                $('#patiend_id').val("");
                $("#patiend_id").trigger("chosen:updated");
            }
            function getTotal() {
            let tds = $("td.sub");
            let total = 0;
            for (let i = 0; i < tds.length; i++) {
                total += Number(tds[i].innerHTML);
            }

            total = total.toFixed(2);
            $("#total").html(total);
            document.getElementById("grand_total").value = total;
        }
    </script>
@endsection