<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('lb.treatments')}}</title>
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
            font-size: 12px;
            font-family: khos;
            color: #333;
        }
        td, span, th, p, strong, u{
            font-family: khos;
        }
        
        .tbl{
            width: 100%;
            font-size: 12px;
        }
        .table{
            width: 100%;
            border-spacing: 0;
            border: 1px solid #ccc;
        }
        .diagnosis {
            min-height: 500px;
        }
        .table thead tr th{
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid #ccc;
  
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
            font-size: 12px;
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
        h2 > u {
            font-family: kh;
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
           font-size: 13px;
       }
    </style>
</head>
<body>
<table>
  <thead>
    <tr>
      <th colspan="2">    <img src="{{asset($hospital->letterhead)}}" alt="" width="100%">   </th>
   
    </tr>
  </thead>
  <tbody >
  <table>
            <tr>
                <td width="50%">អត្តលេខ / H.N : <b> {{$t->code}}</b></td>
            </tr>
            <tr>
                <td width="50%">ឈ្មោះ  : <b>{{$t->kh_first_name}} {{$t->kh_last_name}} </td>
                <td width="50%">អាយុ / Age : <b>{{$dob}}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ភេទ / Gender : <b>{{$t->gender}}</b></td>
            </tr>
            <tr>
            <td width="40%">Ptn. Name: <b>{{$t->en_first_name}} {{$t->en_last_name}} </b></td>
            <td>លេខស្នើសុំ / Request &numero;: <b>{{$t->treatment_code}}</b> </td>
            </tr>
            <table>
            <td colspan="2">
              <hr width="100%"> 
            </td>
        
            <tr>
             
              <td colspan="2">
              {!!$t->diagnosis!!}
              </td>
            </tr>
    <script>
        window.onload = function(){
            print();
        }
    </script>
</body>
</html>