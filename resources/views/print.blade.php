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
        
        html, body{
            margin: 0;
            font-size: 14px;
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
<table width="100%" style="padding-left: 25px; padding-top: 25px; padding-right: 25px;">
    <thead>
        
        <tr>
        <th colspan="2"> <img src="{{asset($hospital->letterhead)}}" alt="" width="100%">   </th>
    
        </tr>
    </thead>
    
</table>
<script>
        window.onload = function(){
            print();
        }
    </script>
</body>
</html>