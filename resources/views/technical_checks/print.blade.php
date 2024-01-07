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
            font-weight: 900;
            font-family: khos;
            color: #000;
        }
        td, span, th, p, strong, u{
            font-family: khos;
        }
        
        .tbl{
            width: 100%;
            font-size: 14px;
        }
        .table{
            width: 100%;
            border-spacing: 0;
            border: 1px solid #000;
        }
        .table thead tr th{
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid #000;

            border-right: 1px solid #000;
            padding: 2px 4px;
        }
        .table thead tr th span{
            font-size: 14px;
        }
        .table thead tr th:last-child{
            border-right: none;
        }
        .table tbody tr td{
            font-size: 11px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            padding-left: 1px;
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
            color: #000;
        }
        .h3{
            font-size: 22px;
            text-align: center;
            letter-spacing: 2px;
            margin-top: -25px;
            color: #000;
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
           color: #000;
           font-size: 14px;
       }
       .border {
           border: 1px solid #000;
           padding: 10px;
           width: 100%;
           border-radius: 5px;
           float: left;
           margin-right: 20px;
       }
       .border2 {
           border: 1px solid #000;
           padding: 10px;
           width:61%;
           float: right;
           border-radius: 5px;
           
       }
       .div1 {
           float: left;
           background-color: yellow;
       }
       .hr-custom {
        border-top: 1px solid #000;
       }
       .page{
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 20px;
        }
        .box{
            width: 100%;
            margin-left: 0;
            margin-right: 0;
            margin-top: 0;
            margin-left: 0;
            border: 1px solid #000;
            border-radius: 7px;
        }
        .text{
            padding: 12px;
        }
    </style>
</head>
<body>
        
       <div class="page">
          <div class="box">
              <div class="text">
                    <div>
                    H.N : {{$patient->code}}
                    </div>
                    <div>
                      {{$patient->kh_first_name}} {{$patient->kh_last_name}} - {{$patient->en_first_name}} {{$patient->en_last_name}}
                    </div> 
                  
                    <div>
                        {{__('lb.gender')}} : {{$patient->gender}} , {{__('lb.age')}} : {{$dob}}
                    </div>
                   
                    <div>
                        {{$request_detail->item_name}}
                    </div>

                       Date: {{date('d', strtotime($request_detail->date))}} {{date('F', strtotime($request_detail->date))}} {{date('Y', strtotime($request_detail->date))}} 
                </div>
            </div>  
        
        </div>  
       
    
   
    <script>
        window.onload = function(){
            print();
        }
    </script>
</body>
</html>