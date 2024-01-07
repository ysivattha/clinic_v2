<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('lb.invoice')}}</title>
    <style>
        @font-face{
            font-family: kh;
            src: url("{{asset('fonts/KhmerOSmuollight.ttf')}}");
        }
        @font-face{
            font-family: khos;
            src: url("{{asset('fonts/KhmerOSsiemreap.ttf')}}");
        }
        html, body{
            padding: 0;
            margin: 0;
            font-size: 14px;
            font-family: khos;
            color: #333;
        }
        td, span, th, p, strong, u{
            font-family: khos;
        }
        .box{
            width: 93.5%;
            min-height: 895px;
            border: 0px solid #ccc;
            margin: 0 auto;
            padding: 0;
            position: relative;
        }
        .tbl{
            width: 100%;
            font-size: 14px;
        }
        .table{
            width: 100%;
            border-spacing: 0;
            border: 1px solid #ccc;
        }
        .table thead tr th{
            font-size: 14px;
            text-align: center;
            border-bottom: 1px solid #ccc;
            border-right: 1px solid #ccc;
            /* padding: 2px 4px; */
            font-weight: bolder !important;
            background: #e6e6e6
        }
        tr.thead1 th {
            outline: 1px solid #ccc;
        }
        .table thead tr th span{
            font-size: 14px;
        }
        .table thead tr th:last-child{
            border-right: none;
        }
        .table tbody tr td{
            font-size: 14px;
            padding-top: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
            border-right: 1px solid #ccc;
            padding-left: 4px;
        }
        .table tbody tr td:last-child{
            border-right: none;
        }
        .table tbody tr:last-child td{
            border-bottom: none;
        }
        tr.total td{
            font-weight: bold;
            padding: 2px 4px;
            border-bottom: none!important;
        }
        .h1{
            text-align: center;
            font-size: 32px;
            letter-spacing: 3px;
            padding: 0;
            font-family: kh;
            color: #333;
        }
        .h3{
            font-size: 22px;
            text-align: center;
            letter-spacing: 2px;
            margin-top: -25px;
            color: #2a5fa4;
        }
        
       .watermark{
           position: absolute;
           top: 150px;
           left: 20px;
           z-index: -9999;
           opacity: 0.2;
       }
       .p1{
           font-weight: bold;
           color: #333;
           font-size: 14px;
       }
  
    </style>
</head>
<body>
        
    <div class="box">
          
        <table class="tbl tbl1" style="margin-top: -10px">
            <tr>
                <td colspan="2">
                    <img src="{{asset($hospital->letterhead)}}" alt="" width="100%">    
                </td>
            </tr>
            <tr>
                <td width="50%">អត្តលេខ / H.N : <b> {{$inv->code}}</b></td>
                <td>កាលបរិច្ឆេទ / Date : <b>{{$inv->start_date}}</b></td>
            </tr>
            <tr>
                <td>ឈ្មោះ  : <b>{{$inv->kh_first_name}} {{$inv->kh_last_name}} </td>
                <td>អាយុ / Age : <b>{{$dob}}</b> &nbsp;&nbsp;&nbsp;ភេទ / Gender : <b>{{$inv->gender}}</b></td>
            </tr>
            <tr>
            <td>Ptn. Name: <b>{{$inv->en_first_name}} {{$inv->en_last_name}} </b></td>
            <td>លេខវិក្កយបត្រ / Invoice &numero;: <b>{{$inv->invoice_no}}</b> </td>
            </tr>
            <tr>
                <td>លេខទូរស័ព្ទ / Phone: <b>{{$inv->phone}} </b></td>
                <td>អ្នកគិតលុយ / Cashier : <b>{{$user->first_name}} {{$user->last_name}} </b></td>
            </tr>
        </table>
        <h2 style="text-align:center;"><u><b>វិក្កយបត្រ / Invoice</b></u></h2>
      
        <table width="100%">
            <tbody>
               <tr>
                   <td>
                       <table width="100%" class="table" style="">
                            <thead>
                                <tr class="">
                                    <th width="60" class="font-weight-bold" style="text-align: center; "> 
                                        <span style="font-weight: bolder;">
                                        លេខរៀង 
                                        <br>
                                        Number
                                        </span>
                                    </th>
                                    <th classt="text-center font-weight-bold"> បរិយាយសេវា និងមុខទំនិញ  <br> Description</th>
                                    <th classt="text-center font-weight-bold">បរិមាណ <br> Quantity </th>
                                    <th classt="text-center font-weight-bold">តម្លៃ <br> Price </th>
                                    <th classt="text-center font-weight-bold">បញ្ចុះតម្លៃ <br> Discount </th>
                                    <th classt="text-center font-weight-bold">សរុប <br> Amount</th>
                                </tr>
                            </thead>
                                @php($i=1)
                                <?php
                                    $sub_total = 0;
                                ?>
                                @foreach($lines as $b)
                                <?php $sub_total = $b->price - $b->discount;?>
                                    <tr>
                                        <td style="text-align: center;">{{$i++}}</td>
                                        <td>
                                    {{$b->item_name}}
                                        <td style="text-align:center;">1</td>
                                        <td style="text-align:right; padding-right: 5px;">$ {{number_format($b->price,2)}} </td>
                                        <td style="text-align:right; padding-right: 5px;">$ {{number_format($b->discount,2)}} </td>
                                        <td style="text-align:right; padding-right: 5px;">$ {{number_format($sub_total,2)}} </td>
                                    </tr>
                                @endforeach
                                @if(count($invoice_detail2)>0)
                                @foreach($invoice_detail2 as $inv2)
                                
                            
                                <?php 
                                    $total = $inv2->qty * $inv2->price - $inv2->discount;
                                
                                ?>
                                <tr>
                                    <td style="text-align: center;">{{$i++}}</td>
                                    <td>
                                    {{$inv2->name}}
                                    </td>
                                    <td style="text-align:center;">{{$inv2->qty}} </td>
                                    <td style="text-align:right; padding-right: 5px;">$ {{number_format($inv2->price,2)}} </td>
                                    <td style="text-align:right;  padding-right: 5px;">$ {{number_format($inv2->discount,2)}} </td>
                                    <td style="text-align:right;  padding-right: 5px;">$ {{number_format($total,2)}} </td>
                                </tr>
                                @endforeach
                                
                                <tr class='total'>
                                    
                                <td colspan="2" style='text-align:right'> សរុបទាំងអស់ / Grand Total </td>
                                    <td colspan="3" style="text-align:right;">៛ {{number_format($inv->total*$exc->khr,2)}} </td>
                                    <td style="text-align:right;">$ {{number_format($inv->total,2)}} </td>
                                </tr>
                                <tr class="total">
                                <td colspan="2" style='text-align:right'> ប្រាក់បង់រួច / Deposit</td>
                                    <td colspan="3" style="text-align:right;"> ៛ {{number_format($inv->paid*$exc->khr,2)}} </td>
                                    <td style="text-align:right;">$ {{number_format($inv->paid,2)}} </td>
                                </tr>
                                <tr class='total'>
                                    <td colspan="2" style='text-align:right'> ប្រាក់ដែលត្រូវបង់ / Due Balance </td>
                                    <td colspan="3" style="text-align:right;"> ៛ {{number_format($inv->due_amount*$exc->khr,2)}} </td>
                                    <td style="text-align:right;">$ {{number_format($inv->due_amount,2)}} </td>
                                </tr>
                                @endif
                                @if(count($invoice_detail2)<=0)
                                <tr class='total'>
                               
                                <td colspan="2" style='text-align:right'>  សរុបទាំងអស់ / Grand Total </td>
                                    <td colspan="3" style="text-align:right;"> ៛ {{number_format($inv->total*$exc->khr,2)}} </td>
                                    <td style="text-align:right;"> $ {{number_format($inv->total,2)}} </td>
                                </tr>
                                <tr class="total">
                                    <td colspan="2" style='text-align:right'> ប្រាក់បង់រួច / Deposit</td>
                                    <td colspan="3" style="text-align:right;">៛ {{number_format($inv->paid*$exc->khr,2)}} </td>
                                    <td style="text-align:right;">$ {{number_format($inv->paid,2)}} </td>
                                </tr>
                                <tr class='total'>
                                    <td colspan="2" style='text-align:right'>ប្រាក់ដែលត្រូវបង់ / Due Balance </td>
                                    <td colspan="3" style="text-align:right;">៛ {{number_format($inv->due_amount*$exc->khr,2)}} </td>
                                    <td style="text-align:right;">$ {{number_format($inv->due_amount,2)}} </td>
                                </tr>
                                @endif
                       </table>
                   </td>
               </tr>

                <tr>
                    <td>
                        <table class="table" style="border: 0!important;">
                            <tr style="border:0;">
                                <td style="border:0;">
                                    <p  style="text-align: center; ">
                                        ហត្ថលេខា និងឈ្មោះ / Customer's signature 
                                        <br><br><br>
                                        _____________________
                                    </p>
                                </td>
                                <td style="border:0;">
                                    <p  style="text-align: center;">
                                        រៀបចំដោយ / Prepared by 
                                        <br><br><br>
                                        _____________________
                                    </p>
                                </td>
                            </tr>
                        </table> 
                    </td>
                </tr>
            </tbody>
         
        </table>
     
    </div>
        

    <!-- <footer style="position: fixed; width: 100%; bottom: 0; text-align: center;">
        <hr  width="90%">
        អគារលេខ : {{$hospital->address}} ។ ទូរស័ព្ទលេខ {{$hospital->phone}}
    </footer> -->
        
   
    <script>
        window.onload = function(){
            print();
        }
    </script>
</body>
</html>