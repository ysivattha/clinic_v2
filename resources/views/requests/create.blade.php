@extends('layouts.master')
@section('title')
    {{__('lb.create_request')}}
@endsection
@section('header')
    {{__('lb.create_request')}}
    
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
<style>
    input[type=radio] {
       
    width: 17px;
    height: 17px;
}
</style>

<form action="{{route('request.store')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="toolbox pt-1 pb-1">
    <button class="btn btn-primary btn-sm">
        <i class="fa fa-save"></i> {{__('lb.save')}}
    </button>
    <a href="{{route('request.index')}}" class="btn btn-success btn-sm">
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
                    <div class="col-sm-8">
                        <select name="patient_id" id="patient_id" class="form-control chosen-select" onchange="get_code()" required>
                            {{-- <option value=""> {{__('lb.select_one')}} </option>
                            @foreach ($patients as $p)
                            <option  value="{{$patient->id}}"  {{$patient->id==$patient_id?'selected':''}}>{{$p->code}} -{{$p->en_first_name}} {{$p->en_last_name}} - {{$p->kh_first_name}} {{$p->kh_last_name}} ( {{$p->phone}} )</option>
                            @endforeach --}}
                            @if($patient != "")
                            <option  value="{{$patient->id}}"  {{$patient->id==$patient_id?'selected':''}}>{{$patient->code}} -{{$patient->en_first_name}} {{$patient->en_last_name}} - {{$patient->kh_first_name}} {{$patient->kh_last_name}} ( {{$patient->phone}} )</option>
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="date" class="col-sm-3">
                        {{__('lb.symptom')}}
                    </label>
                    <div class="col-sm-8">
                       <textarea name="symptom" id="" class="form-control" cols="3" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="datetime" class="col-md-3">
                        {{__('lb.date')}}
                    </label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-7">
                                <input type="date" required  id="date" 
                                    name="date" value="{{date('Y-m-d')}}" class="form-control input-xs">
                                
                            </div>
                            <div class="col-md-5">
                                <input type="time" required  id="time" 
                                name="time" value="{{date('H:i')}}" class="form-control input-xs">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="hospital" class="col-sm-3">
                        {{__('lb.hospitals')}}
                    </label>
                    <div class="col-sm-6">
                        <select  name="hospital" id="hospital" class="chosen-select" onchange="getHospitalReference()">
                            <option value="">{{__('lb.select_one')}}</option>
                            @foreach ($hospitals as $h) 
                                @if($patient != null)
                                {
                                    <option    value="{{$h->id}}" hospital_reference="{{$h->code}}" hospital_id="{{$h->id}}" {{$patient->hospital_id==$h->id?'selected':''}}>{{$h->name}}</option>    
                                    
                                }
                                @else 
                                {
                                    <option  @if((Auth::user()->role_id==105) && (Auth::user()->hospital_id != $h->id)) hidden @endif  value="{{$h->id}}" hospital_reference="{{$h->code}}" hospital_id="{{$h->id}}" {{Auth::user()->hospital_id==$h->id?'selected':''}}>{{$h->name}}</option>    
                                }
                                @endif
                                
                            @endforeach
                        </select>
                    </div>
                </div>
                <?php $hospital = DB::table('hospitals')->where('id', Auth::user()->hospital_id)->first();
                ?> 
                <div class="form-group row">
                    <label for="hospital_reference" class="col-sm-3">
                        {{__('lb.hospital_code')}}
                    </label>
                    <div class="col-sm-4">
                        {{-- <input type="hidden" id="hospital_id" name="hospital_id" value="@if($hospital!=null){{$hospital->id}}@endif" class="form-control input-xs"> --}}
                        <input value="{{ $patient != null ?"$patient->h_code":'' }}" type="text" id="hospital_reference" name="hospital_reference"  class="form-control input-xs">
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <label for="section_id">
                  {{__('lb.body_part')}}
                </label>
                <select name="section_id" id="section_id" class="form-control chosen-select">
                    <option value="">{{__('lb.select_one')}}</option>
                    @foreach($sections as $s)
                        <option 
                            value="{{$s->id}}"​
                        >
                            {{$s->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-xs-12">
              
                <label for="item">
                    {{__('lb.items')}}
                </label>
                <select name="item_id" id="item_id" class="form-control chosen-select"  onchange="getPrice()">
                    <option value="">{{__('lb.select_one')}}</option>
                    @foreach($items as $i)
                        <option 
                            value="{{$i->id}}"
                            price="{{$i->price}}"​
                        >
                        {{$i->code}} - {{$i->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="row">
                    <label for="price"> {{__('lb.price')}} ($)  </label>
                </div>
                <input {{ Auth::user()->role_id==105 ? 'readonly':'' }} type="number" id="price" style="width: 100px; margin-right: 10px;margin-left: 4px;"  class="form-control input-xs" min="0" step="0.01" name="price" value="0">
            </div>
            <div>
                <div class="row">
                    <label for="discount" class="col-md-12"> {{__('lb.discount')}} ($)  </label>
                </div>
                <input {{ Auth::user()->role_id==105 ? 'readonly':'' }}  type="number" id="discount"  style="width: 90px; margin-right: 4px" class="form-control input-xs" name="discount"  value="0">
            </div>
            <div>
                <div class="row">
                    <label for="request_date" class="col-md-12"> {{__('lb.date')}}  </label>
                </div>
                <input type="date" id="request_date" required
                        name="request_date" style="margin-right: 10px;" value="{{date('Y-m-d')}}" class="form-control input-xs"> 
            </div>
            <div>
                <div class="row">
                    <label for="time" class="col-md-12"> {{__('lb.time')}}  </label>
                </div>
                <input type="time"  id="request_time" required
                            name="request_time" value="{{date('H:i')}}" style="margin-left: 5px;" class="form-control input-xs">
            </div>
        </div><div class="pb-2"></div>
        <div class="row">
            <div class="col-lg-6 col-xs-12">
                <label for="request_note">
                    {{__('lb.note')}}
                </label>
               <input type="text" class="form-control input-xs" name="request_note" id="request_note">
            </div>
            <div class="col-lg-3 col-xs-12">
                <label for="item">
                    {{__('lb.percent1')}}
                </label>
                <select name="percent1" id="percent1" class="form-control chosen-select">
                    <option value="">{{__('lb.select_one')}}</option>
                    @foreach($doctors as $d)
                    <option value="{{$d->id}}" code="{{$d->code}}" {{$d->id==Auth::user()->id?'selected':''}}> {{$d->first_name}} {{$d->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div>
               <div class="row">
                   
                <label class="col-md-12" for="item">
                    {{__('lb.behind_of')}}
                </label>
            </div>
                <input type="text" id="behind_of" name="behind_of" style="width: 150px;"  class="form-control input-xs">
            </div>
            <div class="col-sm-1" style="padding: 2px">
                <div class="row">
                    <label class="col-md-12">&nbsp; </label>
                </div>
                <button type="button" class="btn-xs" style="cursor: pointer; margin-left: 5px; margin-top: -3px;" onclick="addItem()"> {{__('lb.insert')}} </button>
            </div>
        </div>
        <p></p>
        <div class="col-md-12">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th> {{__('lb.body_part')}}</th>
                    <th>{{__('lb.items')}}</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.time')}}</th>
                    <th>{{__('lb.note')}}</th>
                    <th>{{__('lb.doctor_request')}}</th>
                    <th>{{__('lb.percent1')}}</th>
                    <th>{{__('lb.price')}} $</th>
                    <th>{{__('lb.discount')}} $</th>
                    <th>{{__('lb.total')}} $</th>
                    <th style="width:150px">{{__('lb.action')}}</th>
                </tr>
            </thead>
            <tbody id="data">
                
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
    <div class="card">
        <div class="card-header">
            <h6>ពត៌មានលំអិត / Detail</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 d-flex">
                   
                    <div class="mr-2">
                        <label for="" class="">បន្ទាន់ / Urgent :</label> ​​​
                    </div>

                    <div class="">
                   
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="urgent" id="urgent_yes" value="1" autocomplete="off">
                        <label class="form-check-label" for="urgent_yes">Yes</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="urgent" id="urgent_no" value="" autocomplete="off">
                        <label class="form-check-label" for="urgent_no">No</label>
                      </div>
                    </div>

                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="" class="">សម្រាប់អ្នកជំងឺស្រ្តី / For Female Patient :</label> ​​​
                        </div>
    
                        <div class="col-sm-3 d-flex">
                            <div class="mr-2">
                                <label for="" class="">អស់រដូរ / Monopause :</label> ​​​
                            </div>
                            

                            <div>

                           
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="minopause" id="minopause_yes" value="1" autocomplete="off">
                                    <label class="form-check-label" for="minopause_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="minopause" id="minopause_no" value="0" autocomplete="off">
                                    <label class="form-check-label" for="minopause_no">No</label>
                                </div>
                            
                             </div>
                        </div>

                        <div class="col-sm-3 d-flex">
                            <div class="mr-2">
                                <label for="" class="">មានគភ៍ / Pregnancy :</label> ​​​
                            </div>
                            

                            <div>

                           
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pregnancy" id="pregnancy_yes" value="1" autocomplete="off">
                                    <label class="form-check-label" for="pregnancy_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pregnancy" id="pregnancy_no" value="0" autocomplete="off">
                                    <label class="form-check-label" for="pregnancy_no">No</label>
                                </div>
                            
                             </div>
                        </div>
                      
                     </div>


                    {{-- <div class="col-sm-3">

                    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" autocomplete="off">
                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" autocomplete="off">
                            <label class="form-check-label" for="inlineRadio2">No</label>
                        </div>

                    </div> --}}

                    {{-- <div class="mr-2">
                        <label for="" class="">មានគភ៍ / Pregnancy :</label> ​​​
                    </div>

                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" autocomplete="off">
                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" autocomplete="off">
                            <label class="form-check-label" for="inlineRadio2">No</label>
                          </div>
                    </div> --}}

                 
                    
                </div>

                <div class="col-sm-12">
                    <div class="row">

                        <div class="col-sm-6 d-flex">
                            <div class="mr-2">
                                <label for="" class="">បានចុះហត្ថលេខាយល់ព្រម / Consent form signed :</label> ​​​
                            </div>
                            

                            <div class="ml-3">

                           
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="signed" id="signed_yes" value="1" autocomplete="off">
                                    <label class="form-check-label" for="signed_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="signed" id="signed_no" value="0" autocomplete="off">
                                    <label class="form-check-label" for="signed_no">No</label>
                                </div>
                            
                             </div>
                        </div>

                        <div class="col-sm-6 d-flex">
                            <div class="mr-2">
                                <label for="" class="">មានរូបភាពចាស់ / Previous Picture :</label> ​​​
                            </div>
                            

                            <div>

                           
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pre_pic" id="pre_pic_yes" value="1" autocomplete="off">
                                    <label class="form-check-label" for="pre_pic_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pre_pic" id="pre_pic_no" value="0" autocomplete="off">
                                    <label class="form-check-label" for="pre_pic_no">No</label>
                                </div>
                            
                             </div>
                        </div>
                      
                     </div>
                </div>


            </div>

        </div>
    </div>
</form>

<div id="dialog"  class="hide text-center" style="width:600!important;"> 
    <a href="" id="inv_link" class="badge badge-danger text-white"​ style="font-size:12px;"></a><p></p>
  <a href="" id="link_invoice" class="text-white btn btn-primary btn-lg"> 
                </a>
                <a href="#" id="link_invoice2" class="text-white btn btn-danger btn-lg" onclick="closeInv()"> 
                    ទេ
                </a>
            
</div>
@endsection

@section('js')
<script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
	<script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_request").addClass("active");
            $("#section_id").change(function () {
                get_item();
            });
           
  
            
            if($("#patient_id").val() == null)
            {
                           
            getHospitalReference();
            $('#hospital_reference').val('');
            
            } else
            {
                get_code();
            }

    

        });

        function closeInv() {
            $("#dialog").dialog('close');
        }
       
        // function to add item to table
        function addItem()
        {
                let section_id = $("#section_id").val();
                let section = $("#section_id :selected").text();
                section = $.trim(section);
                let item_id = $("#item_id").val();
                let item = $("#item_id :selected").text();
                let price = Number($("#price").val()).toFixed(2);
                let discount = $("#discount").val();
                let behind_of = $("#behind_of").val();
                let request_date = $("#request_date").val();
                let request_time = $("#request_time").val();
                let percent1_id = $("#percent1").val();
                let request_note = $("#request_note").val();
                let percent1 = $("#percent1 :selected").text();
                let sub = price - discount;
                let iv = '';
                let pro = '';
                let enema = '';
                let other = '';
                
               if(item_id=='' || section_id=='' || request_date=='' || percent1=='')
                {
                    alert("Please input data correctly!");
                }
                else{
                  
                    // add item to table
                    let trs = $("#data tr");
                    let html = "<tr section_id='" +  section_id + "'>";
                    html += "<td>" + 
                        '<input type="hidden" value="'+ request_date +'" name="request_date[]">' +
                        '<input type="hidden" value="'+ request_time +'" name="request_time[]">' +
                        '<input type="hidden" value="'+ section_id +'" name="section_id[]">' +
                        '<input type="hidden" value="'+ section +'" name="section_name[]">' +
                        '<input type="hidden" value="'+ item_id +'" name="item_id[]">' +
                        '<input type="hidden" value="'+ request_note +'" name="request_note[]">' +
                        '<input type="hidden" value="'+ item +'" name="item_name[]">' +
                        '<input type="hidden" value="'+ price +'" name="price[]">' +
                        '<input type="hidden" value="'+ sub.toFixed(2) +'" name="sub_total[]">' +
                        '<input type="hidden" value="'+ behind_of +'" name="behind_of[]">' +
                        '<input type="hidden" value="'+ discount +'" name="discount[]">' +
                        '<input type="hidden" value="'+ percent1_id +'" name="percent1[]">' +
                            section + "</td>";
                    html += "<td>" + item + "</td>";
                    html += "<td>" + request_date + "</td>";
                    html += "<td>" + request_time + "</td>";
                    html += "<td>" + request_note + "</td>";
                    html += "<td>" + behind_of + "</td>";
                    html += "<td>" + percent1 + "</td>";
                    html += "<td> " + price + "</td>";
                    html += "<td> " + discount + "</td>";
                    html += "<td class='sub'> " + sub.toFixed(2) + "</td>";
                    html += "<td>" + "<button class='btn btn-danger btn-xs' type='button' onclick='deleteItem(this)'><i class='fa fa-trash'></i></button>&nbsp;";
                    html += "</tr>";
                    if(trs.length>0)
                        {
                            $("#data tr:last").after(html);
                        }
                        else{
                            $("#data").html(html);
                        }
                    $("#section_id").val("");
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

        // function to get sub category
        function get_item()
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
        
        function getPrice()
        { 
            let price = $("#item_id :selected").attr('price');
        
            $("#price").val(price);
        }
        function getHospitalReference()
        { 
            $.ajax({
                type: "GET",
                url: "/request/create/" + $("#hospital").val(),
                success: function (data) {
                    var obj = $.parseJSON(data);
                opts ="<option value='" + '' +"'>" + '{{__('lb.select_one')}}' + "</option>";
         
                for(var i=0; i<obj.length; i++)
                    {  

                        opts +="<option hos_code='"+obj[i].h_code +"'  value='" + obj[i].id + "' >" + obj[i].code + '-' + obj[i].en_first_name +" "+ obj[i].en_last_name +"    "+  obj[i].kh_first_name +" "+ obj[i].kh_last_name +"</option>";
    
                    }
                    console.log(obj);
                    $("#patient_id").html(opts);
                    $("#patient_id").trigger("chosen:updated");
                }
            });

            // let hospital_reference = $("#hospital :selected").attr('hospital_reference');
            // let hospital_id= $("#hospital :selected").attr('hospital_id');
            // let bank= $("#hospital :selected").attr('bank');
            // $("#hospital_reference").val(hospital_reference);
            // $("#hospital_id").val(hospital_id);
        }
        // function to remove an item
        function deleteItem(obj)
        {
            let con = confirm("Do you want to delete?");
            if(con)
            {
                $(obj).parent().parent().remove();
                getTotal();
            }
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
        // function to edit item
        function editItem(obj)
        {
            //remove active class from all row
            $("#data tr").removeClass('active');
            let tr = $(obj).parent().parent();
            // add class active to the current edit row
            $(tr).addClass('active');
            let tds = $(tr).children("td");

            let medicine = $(tr).attr('medicine');
            let description = $(tds[1]).html();
            let note = $(tds[2]).html();
            $("#emedicine").val(medicine);
            $("#emedicine").trigger("chosen:updated");

          
            $("#edescription").val(description);
            $("#enote").val(note);
            
            $("#category1").trigger("chosen:updated");

            $("#editModal").modal('show');
        }

        function check_used() {
            $("#hide_check").addClass('show');
            $("#hide_check").removeClass('hide');
			
        }
        function check_not_used() {
            $("#hide_check").addClass('hide');
            $("#hide_check").removeClass('show');
			
        }
        // save edit item back to table
        function saveItem()
        {
            let medicine = $("#emedicine").val();
            let name = $("#emedicine :selected").text();
            let description = $("#edescription").val();
            let note = $("#enote").val();
            if(medicine=="")
            {
                alert("Please input data correctly");
            }
            else{
                let tr = $("#data tr.active");
                $(tr[0]).attr('medicine', medicine);
                let tds = $("#data tr.active td");
                tds[0].innerHTML = name;
                tds[1].innerHTML = description;
                tds[2].innerHTML = note;
                // clear text box
                $("#ecategory").val("");
                $("#ecategory").trigger("chosen:updated");
                $("#emedicine").val("");
                $("#emedicine").trigger("chosen:updated");
                $("#edescription").val("");
                $("#enote").val("");
                $("#editModal").modal('hide');
            }

            
        }

        function get_code()
        {
            var patientid = $("#patient_id").val();
            if(patientid != "")
            {
                $.ajax({
                type: "GET",
                url: "/request/getpatien/" + $("#patient_id").val(),
                success: function (res) {
            
                                    var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today = yyyy + '-' +mm + '-' +dd ;
                    if(String(res.requests.date) == today)
                    {
                        var curl = burl + '/request/detail/' + res.requests.id;
                            $('#dialog').attr("title","បានស្នើរសុំរួចហើយក្នុងថ្ងៃនេះ - "+res.data.kh_first_name+' '+res.data.kh_last_name)
                                    $( "#dialog" ).dialog();
                                    $("#link_invoice").html("បាទ");
                                   
                                    $("#inv_link").html("តើអ្នកចង់បន្ថែមការស្នើសុំឬ ?")
                                    $("#inv_link").attr('href', curl);
                                    $("#link_invoice").attr('href', curl);
                    }
                   
                // console.log(res.requests);
                    $('#hospital_reference').val(res.data.h_code);
                //     $("#patient_id").html(opts);
                //     $("#patient_id").trigger("chosen:updated");
                }
           
 
            });

            }else
            {
                $('#hospital_reference').val('');
            }
            




           
        }
    </script>
@endsection