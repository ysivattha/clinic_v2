<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('lb.doctor_check')}}</title>
    <style>
        @font-face{
            font-family: kh;
            src: url("{{asset('fonts/KhmerOSmuollight.ttf')}}");
        }
        @font-face{
            font-family: khos;
            src: url("{{asset('fonts/KhmerOSsiemreap.ttf')}}");
        }
        td, span, th, p {
            font-weight: 551;
            padding: 0;
            margin: 0;
        }
        u {
            font-weight: 900;
            font-weight: inherit;
        }
        html, body{
            margin: 0;
            font-size: 13px;
         
            font-family: khos;
            color: #333;
        }
        
        td, span, th, p, strong, u{
            font-family: khos;
        }
        
        .box p {
            margin-bottom: 0px;
            margin-top: 3px;
        }
    
        .tbl{
            width: 100%;
            font-size: 14px;
        }
        h2 > u {
            font-family: kh;
        }
        .table{
            width: 100%;
            border-spacing: 0;
            border: 1px solid #ccc;
        }
        .table thead tr th{
            font-size: 14px;
            text-align: left;
            border-bottom: 1px solid #888;
  
            border-right: 1px solid #ccc;
            padding: 2px 4px;
        }
        .table thead tr th span{
            font-size: 11px;
        }
        .table thead tr th:last-child{
            border-right: none;
        }
        .table tbody tr td{
            font-size: 14px;
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
            color: #cfb44c;
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
           color: #fb5353;
           font-size: 14px;
       }
    </style>
</head>
<body>
<table width="100%" style="padding-left: 25px; padding-right: 25px;">
    <thead>
        <tr>
        <th colspan="2">
            <img src="{{asset($hospital->letterhead)}}" alt="" width="100%">
        </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td width="47%">អត្តលេខ / H.N : <b> {{$patient->code}}</b></td>
                        <td>កាលបរិច្ឆេទ / Date : <b>{{$requestcheck->date}}</b></td>
                    </tr>

                   
                    <tr>
                        <td width="47%">ឈ្មោះ  : <b>{{$patient->kh_first_name}} {{$patient->kh_last_name}} </td>
                        <td>អាយុ / Age : <b>{{$dob}}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ភេទ / Gender : <b>{{$patient->gender}}</b></td>
                    </tr>
                    <tr>
                        <td width="47%">Ptn. Name: <b>{{$patient->en_first_name}} {{$patient->en_last_name}} </b></td>
                        <td>លេខស្នើសុំ / Request &numero;: <b>{{$requestcheck->code}}</b> </td>
                    </tr>
                    <tr>
                        <td width="47%">លេខយោង / Ref Number  : <b>{{$requestcheck->hospital_reference}} </b> </td>
                        <td>អ្នកស្នើសុំ / Request By : <b>{{$reciept->first_name}} {{$reciept->last_name}}<b></td>
                    </tr>
                </table>
            </td>
            
            
        </tr>
        <tr>
            <td colspan="2">
                <hr width="100%"> 
            </td>
        </tr>
        <tr>
            <td colspan="2">
            {!!$request_detail->doctor_description!!}
            </td>
        </tr>
    </tbody>
    
    <?php 
     $translator = DB::table('users')->where('id', $request_detail->percent3)->first();
     $approvor = DB::table('users')->where('id', $request_detail->percent3_approvor)->first();
    ?>
  
    <tr>
      <td>
            <div style="">
                <div style="min-width: 220px; float:left;">
                    <p style="margin:0; margin-left:80px;"> 
                        <img src="{{asset($translator->signature)}}" alt="" width="100">
                    </p>
                    វេជ្ជបណ្ឌិត / Dr . {{$translator->first_name}} {{$translator->last_name}}
                </div>  
            </div>
            <div style="">
                <div style="min-width: 220px; float:left;">
                    <p style="margin:0; margin-left:80px;"> 
                        <img src="{{asset($approvor->signature)}}" alt="" width="100">
                    </p>
                    វេជ្ជបណ្ឌិត / Dr . {{$approvor->first_name}} {{$approvor->last_name}}
                </div>  
            </div>
        </td>
    </tr>
</table>
<script>
        window.onload = function(){
            print();
        }
    </script>
</body>
</html>