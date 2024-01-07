@extends('layouts.master')
@section('title')
    {{__('lb.create_invoice')}}
@endsection
@section('header')
    {{__('lb.create_invoice')}}
    @if($patient != null)
    &nbsp; &nbsp; &nbsp;
{{ $patient->code }}&nbsp;&nbsp;|
{{$patient->kh_first_name}}&nbsp;&nbsp;
{{$patient->kh_last_name}}&nbsp;&nbsp;|
{{$patient->en_first_name}}&nbsp;&nbsp;
{{$patient->en_last_name}}&nbsp;&nbsp;|
{{$patient->h_code}}&nbsp;&nbsp;|
{{$patient->phone}}&nbsp;&nbsp;
@endif
@endsection
@section('content')
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<form action="{{route('invoice.store')}}" id="saveForm" name="saveForm" method="POST">
@csrf
<div class="toolbox pt-1 pb-1">
    <button class="btn btn-oval btn-primary btn-sm"> 
        <i class="fa fa-save"></i> {{trans('lb.save')}}
    </button>
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
                <div class="form-group row">
                    <label for="patient_id" class="col-sm-3">
                        {{__('lb.patients')}} <span class="text-danger">*</span>
                    </label>
                    <?php 
                        $patient_id = isset($_GET['patient_id'])? $_GET['patient_id'] : 0;
                    ?>
                    <div class="col-sm-8">
                        <select name="patient_id" id="patient_id" class="form-control chosen-select" required onchange="patientRequest()">
                            <option value="0"> {{__('lb.select_one')}} </option>
                            @foreach ($patients as $p)
                            <option value="{{$p->id}}" {{$p->id==$patient_id?'selected':''}}> {{$p->code}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="patient_id" class="col-sm-3">
                        {{__('lb.cashier')}} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <select name="cashier" id="cashier" class="form-control chosen-select" required​ onchange="getBank()">
                            <option value="0"> {{__('lb.select_one')}} </option>
                            @foreach ($users as $u)
                            <?php  $hospital = DB::table('hospitals')
                ->where('active',1)
                ->where('id', $u->hospital_id)
                ->first(); ?>
                            <option value="{{$u->id}}" bank="{{$hospital->bank}}" {{$u->id==Auth::user()->id?'selected':''}}> {{$u->first_name}} {{$u->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="patient_id" class="col-sm-3">
                        {{__('lb.bank')}} 
                    </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-xs" value="{{$hospital->bank}}" name="bank" id="bank">
                    </div>
                </div>
            </div>
           
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="start_date" class="col-sm-3">
                        {{__('lb.date')}}
                    </label>
                    <div class="col-sm-6">
                        <input type="date" required class="form-control input-xs" id="start_date" 
                            name="start_date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="due_date" class="col-sm-3">
                        {{__('lb.due_date')}}
                    </label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control input-xs" id="due_date" 
                            name="due_date">
                    </div>
                </div>
            </div>
        </div>
        <h6 class="text-success">
                បរិយាយសេវាកម្ម​ និងមុខទំនិញ
            </h6>
            <div class="row">
                <div class="col-md-12">
                <div class="form-group row">
                    <label for="due_date" class="col-sm-2">
                       សេវាកម្មដែលយើងបានស្នើរសុំរួច
                    </label>
                    <div class="col-sm-6">
                        <select name="request_id" multiple id="request_id" class="form-control chosen-select" onchange="getRequest()">
                            <option value="0"> {{__('lb.select_one')}} </option>
                        </select>
                    </div>
                </div>
                </div>
            </div>
        <div class="row">
    
        <div class="col-md-12">
    
            <a href="#" class="btn btn-primary btn-oval btn-xs" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> {{__('lb.request_more')}}
            </a>
    <p></p>
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
            <tbody id="data">
                
            </tbody>
            <tbody id="data3">
                
                </tbody>
        </table>
        <div class="col-sm-12 p-0">
            <a href="#" class="btn btn-primary btn-oval btn-xs" data-toggle="modal" data-target="#medicineModal">
                <i class="fa fa-plus"></i> {{__('lb.add_item')}}
            </a>
        </div>
        <p></p>
        <div class="col-md-12 p-0">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                <th>{{__('lb.body_part')}}</th>
                    <th>{{__('lb.items')}}</th>
                    <th>{{__('lb.qty')}}</th>
                    <th>{{__('lb.price')}}</th>
                    <th>{{__('lb.discount')}}</th>
                    <th>{{__('lb.total')}}</th>
                    <th>{{__('lb.action')}}</th>
                </tr>
            </thead>
            <tbody id="data2">
                
            </tbody>
        </table>
        <div class="col-sm-12">
            <strong class='text-danger'>
                សរុប: $<span id="total">0.00</span> 
            </strong>
            <input type="hidden" name="grand_total" id="grand_total" value="0">
        </div>
        </div>
	</div>
</div>

</form>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="#">
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
                                <option value="{{$s->id}}">{{$s->name}}</option>
                            @endforeach
						</select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="item_id" class="col-sm-3">{{__('lb.items')}}<span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                    
						<select name="item_id" class="form-control chosen-select" id="item_id" required onchange="getPrice()">
							<option value="">{{__('lb.select_one')}}</option>
							@foreach ($items as $item)
                                <option value="{{$item->id}}" price="{{$item->price}}">{{$item->name}}</option>
                            @endforeach
						</select>
                    </div>
                </div>
               
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
                    <button type="button" class="btn btn-primary btn-sm" id="btn" onclick="addItem()"><i class="fa fa-save"></i> {{__('lb.save')}}</button>
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
      <form action="#">
        
          <div class="modal-content">
            <div class="modal-header bg-success">
                <strong class="modal-title">{{__('lb.create_medicine_library')}}</strong>
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
                        <input type="number" value="1"  name="medicine_qty" id="medicine_qty" class="form-control input-xs">
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
                <button type="button" class="btn btn-primary btn-sm" onclick="addMidicine()">
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
<div id="dialog" title="តើអ្នកចង់ប្រើវិក្កយប័ត្រចាស់?"​ class="hide text-center" style="width:600!important;"> 
    <a href="" id="inv_link" class="badge badge-danger text-white"​ style="font-size:12px;"></a><p></p>
  <a href="" id="link_invoice" class="text-white btn btn-primary btn-lg"> 
                </a>
                <a href="#" id="link_invoice2" class="text-white btn btn-danger btn-lg" onclick="closeInv()"> 
                    ទេ
                </a>
            
</div>
@endsection

@section('js')
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
	<script>
       
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_invoice").addClass("active");
        });
        jQuery("#saveForm").submit(function (evt) {
         if($('#patient_id').val() <= 0) {

             alert('សូមធ្វើការជ្រើនរើសអ្នកជំងឺម្នាក់!');
             $("#patient_id :selected").attr('style');
            evt.preventDefault();
         }  
});
        function closeInv() {
            $("#dialog").dialog('close');
        }
        function getCode()
        { 
            let code = $("#percent1 :selected").attr('code');
        
            $("#percent1_code").val(code);
        }
        function addItem()
        {
                let section_id = $("#section_id").val();
                let section = $("#section_id :selected").text();
                    section = $.trim(section);
                let item_id = $("#item_id").val();
                let item = $("#item_id :selected").text();
                let price = $("#price").val();
                let discount = $("#discount").val();
                let percent1_code = $("#percent1_code").val();
                let request_date = $("#request_date").val();
                let request_time = $("#request_time").val();
                let percent1_id = $("#percent1").val();
                let request_note = $("#request_note").val();
                let percent1 = $("#percent1 :selected").text();
                let request_detail_id = "inv1";
                let percent2 = 0;
                let percent3 = 0;
                let sub = price - discount;
                let iv = '';
                let pro = '';
                let enema = '';
                let other = '';
            if(item_id=="" || price==''  || section_id=="")
            {
                alert("សូមបញ្ចូល មុខទំនិញ ផ្នែក ឬតម្លៃ!");
            }
            else{
                let txt = `<div class='alert alert-success p-2' role='alert'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <div>
                                {{__('lb.success')}}
                        </div>
                    </div>`;
                    // add item to table
                    let trs = $("#data3 tr");
                    let html = "<tr section_id='" +  section_id + "'>";
                    html += 
                    " <td></td>" + "<td>" + 
                        '<input type="hidden" value="" name="request_code[]">' +
                        '<input type="hidden" value="'+ request_date +'" name="request_date[]">' +
                        '<input type="hidden" value="'+ request_time +'" name="request_time[]">' +
                        '<input type="hidden" value="'+ section_id +'" name="section_id[]">' +
                        '<input type="hidden" value="'+ section +'" name="section_name[]">' +
                        '<input type="hidden" value="'+ item_id +'" name="item_id[]">' +
                        '<input type="hidden" value="'+ request_note +'" name="request_note[]">' +
                        '<input type="hidden" value="'+ request_detail_id +'" name="request_detail_id[]">' +
                        '<input type="hidden" value="'+ item +'" name="item_name[]">' +
                        '<input type="hidden" value="'+ price +'" name="price[]">' +
                        '<input type="hidden" value="'+ sub.toFixed(2) +'" name="sub_total[]">' +
                        '<input type="hidden" value="'+ percent1_code +'" name="percent1_code[]">' +
                        '<input type="hidden" value="'+ discount +'" name="discount[]">' +
                        '<input type="hidden" value="'+ percent1_id +'" name="percent1[]">' +
                        '<input type="hidden" value="'+ percent2 +'" name="percent2[]">' +
                        '<input type="hidden" value="'+ percent3 +'" name="percent3[]">' +
                        '<input type="hidden" value="'+ request_detail_id +'" name="request_detail_id[]">' +
                            section + "</td>";
                    html += "<td>" + item + "</td>";
                    html += "<td> " + price + "</td>";
                    html += "<td> " + discount + "</td>";
                    html += "<td class='sub'> " + sub.toFixed(2) + "</td>";
                    html += "<td>" + "<button class='btn btn-danger btn-xs' type='button' onclick='deleteItem(this)'><i class='fa fa-trash'></i></button>&nbsp;";
                    html += "</tr>";
                if(trs.length>0)
                {   
                    $("#data3 tr:last").after(html);
                    getTotal();
                }
                else{
                  
                    $("#data3").html(html);
                    getTotal();
                }
                $('#sms').html(txt);
                // clear text box
                $("#section_id").val(0);
                    $("#item_id").val("");
                    $("#price").val(0);
                    $("#request_note").val('');
                    $("#discount").val(0);
                    $("#not_used").attr('checked', 'checked');
                    $("#contrast_enhancement").val("");
                    $("#section_id").trigger("chosen:updated");
                    $("#item_id").trigger("chosen:updated");
                    getTotal();
            }
        }

        function addMidicine()
        {       
                let medicine_id = $("#medicine_name").val();
                let category_id = $("#category_id").val();
                let name = $("#category_id :selected").attr('name');
                let medicine_name = $("#medicine_name :selected").text();
                let medicine_qty = $("#medicine_qty").val();
                let medicine_price = $("#medicine_price").val();
                let medicine_discount = $("#medicine_discount").val();
                
                let sub_total = medicine_price * medicine_qty - medicine_discount;
              
            if(medicine_name=="" || medicine_price=='')
            {
                alert("សូមបញ្ចូលមុខទំនិញ ឬតម្លៃ!");
            }
            else{
                    let txt = `<div class='alert alert-success p-2' role='alert'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            <div>
                                {{__('lb.success')}}
                        </div>
                    </div>`;
                    // add item to table
                    let trs = $("#data2 tr");
                    let html = "<tr medicine_id='" +  medicine_id + "'>";
                    html += "<td>" + 
                        '<input type="hidden" value="'+ medicine_name +'" name="medicine_name[]">' +
                        '<input type="hidden" value="'+ category_id +'" name="category_id[]">' +
                        '<input type="hidden" value="'+ medicine_qty +'" name="medicine_qty[]">' +
                        '<input type="hidden" value="'+ medicine_price +'" name="medicine_price[]">' +
                        '<input type="hidden" value="'+ medicine_discount +'" name="medicine_discount[]">' + name +
                        "</td>";
                    html += "<td>" + medicine_name + "</td>";
                    html += "<td>" + medicine_qty + "</td>";
                    html += "<td>" + medicine_price + "</td>";
                    html += "<td>" + medicine_discount + "</td>";
                    html += "<td class='sub'> " + sub_total.toFixed(2) + "</td>";
                    html += "<td>" + "<button class='btn btn-danger btn-xs' type='button' onclick='deleteMedicine(this)'><i class='fa fa-trash'></i></button>&nbsp;";
                    html += "</tr>";
                if(trs.length>0)
                {
                    $("#data2 tr:last").after(html);
                    getTotal();
                }
                else{
                    
                    $("#data2").html(html);
                    getTotal();
                }
                $('#sms1').html(txt);
                // clear text box
                    $("#medicine_name").val("");
                    $("#medicine_price").val(0);
                    $("#category_id").val('0');
                    $("#category_id").trigger("chosen:updated");
                    $("#medicine_discount").val(0);
                    $("#medicine_qty").val('');     
                    $("#medicine_name").trigger("chosen:updated");
                    getTotal();
            }
        }
        // function to remove an item
        function deleteItem(obj)
        {
            let con = confirm("តើអ្នកពិតជាចង់លុបមែនទេ?");
            if(con)
            {
                $(obj).parent().parent().remove();
                getTotal();
            }
        }
         // function to remove an item
         function deleteMedicine(obj)
        {
            let con = confirm("តើអ្នកពិតជាចង់លុបមែនទេ?");
            if(con)
            {
                $(obj).parent().parent().remove();
                getTotal();
            }
        }
       
        function getPrice()
        { 
            let price = $("#item_id :selected").attr('price');
        
            $("#price").val(price);
        }

        function getBank() {
            let bank = $("#cashier :selected").attr('bank');
            $('#bank').val(bank);
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
                        opts +="<option value='" + data[i].id + "' price='" + data[i].price +"' >" + data[i].code + '-' + data[i].name + "</option>";
                    }
                    $("#item_id").html(opts);
                    $("#item_id").trigger("chosen:updated");
                }
            });
        }

        patientRequest();

        function patientRequest() {
             // get medicine
             $.ajax({
                type: "GET",
                url: burl + "/patient/get-request/" + $("#patient_id").val(),
                success: function (data) {
                    opts ="<option value='" + 0 +"'>" + '{{__('lb.select_one')}}' + "</option>";
                    for(var j=0; j<data.length; j++)
                    {  
                        opts +="<option value='" + data[j].id + "'>" + data[j].code + "</option>";
                    }
                    $.ajax({
                        type: "GET",
                        url: burl + "/patient/invoice-unpaid/" + $("#patient_id").val(),
                        success: function (data) {
                            for(var i=0; i<data.length; i++)
                                {  
                                    var curl = burl + '/invoice/detail/' + data[i].id;
                                    $( "#dialog" ).dialog();
                                    $("#link_invoice").html("បាទ");
                                    $("#inv_link").html("វិក្កយប័ត្រមិនទាន់បានបង់ប្រាក់​ - "+ data[i].invoice_no);
                                    $("#inv_link").attr('href', curl);
                                    $("#link_invoice").attr('href', curl);
                                }
                        }
                    });
                    $("#request_id").html(opts);
                    $("#request_id").trigger("chosen:updated");
                }
            });
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
                                <td>${items[i].price.toFixed(2)}</td>
                                <td>${items[i].discount.toFixed(2)}</td>
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
        $("#data").html("");
    }
    else{
        $("#data").html(row);
    }
    getTotal();
}
</script>

@endsection