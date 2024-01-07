<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('lb.result')}}</title>
    <style>
    @page {

      margin: 10mm 10mm 10mm 10mm;
    }
    @media print{
        thead {display: table-row-group;}
        body {
            transform: scale(1);
            
               
        }
        table {page-break-inside: avoid;}
    }
        @font-face{
            font-family: khos;
           src: url("{{asset('fonts/KhmerOS_battambang.ttf')}}");
        }

        html, body{
            margin: 0;
            font-size: 13px;
            font-family: khos!important;
            color: #333;
        }
        td, span, th, p, strong, u{
            font-family:khos;
            
        }
        .box{
            width: 90%;
            min-height: 950px;
            padding-top: 20px!important;
            border: 0px solid #ccc;
            margin: 0 auto;
            padding: 0;
            position: relative;
        }
        .tbl{
            width: 100%;
            font-size: 12px;
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
            font-size: 13px;
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
            font-family: khos;
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
        
    <table width="100%" style="padding-left: 25px; padding-top: 25px; padding-right: 10px;">
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
                    <table width="100%" class="header">
                  
                    <tr>
                        <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">អត្តលេខ / H.N</td>
                        <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$patient->code}}</b></td>
                        <td width="135" style="padding:0!important;margin:0!important; font-size: 13px!important;">កាលបរិច្ឆេទ / Date</td>
                        <td style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{date('d/m/Y', strtotime($requestcheck->date))}}</b> </td>
                    </tr>
                    <tr>
                        <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">ឈ្មោះ</td>
                        <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$patient->kh_first_name}} {{$patient->kh_last_name}}</b></td>
                        <td width="135" style="padding:0!important;margin:0!important; font-size: 13px!important;">អាយុ / Age </td>
                        <td style="padding:0!important;margin:0!important;font-size: 13px!important;font-size: 13px!important;">: <b>{{$dob}}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ភេទ / Gender : <b>{{$patient->gender}}</b></td>
                       
                    </tr>
                    <tr>
                        <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">Ptn. Name </td>
                        <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$patient->en_first_name}} {{$patient->en_last_name}}</b></td>
                        <td width="135" style="padding:0!important;margin:0!important; font-size: 13px!important;">លេខស្នើសុំ / Request.N</td>
                        <td style="padding:0!important;margin:0!important;font-size: 13px!important;font-size: 13px!important;">: <b>{{$requestcheck->code}}</b></td>
                    </tr>
                    <tr>
                        <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">លេខយោង / Ref.N</td>
                        <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$requestcheck->hospital_reference}}</b></td>
                        <td width="160" style="padding:0!important;margin:0!important; font-size: 13px!important;">អ្នកស្នើសុំ / Request Dr. / Pr.</td>
                        <td style="padding:0!important;margin:0!important;font-size: 13px!important;font-size: 13px!important;">: <b>{{$request_detail->behind_of}}</td>
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
                <td  colspan="2">
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
                    <div style="min-width: 180px; float:left; margin-right: 50px;">
                        <p style="margin:0; margin-left:80px!important;"> 
                            <img src="{{asset($translator->signature)}}" alt="" width="100">
                        </p>
                        <p style="margin-top:-15px;">
                        វេជ្ជបណ្ឌិត / Dr . {{$translator->first_name}} {{$translator->last_name}}
                        </p>
                    </div>  
                </div>
                <div style="">
                    <div style="min-width: 220px; float:left;">
                        <p style="margin:0; margin-left:80px!important;"> 
                            <img src="{{asset($approvor->signature)}}" alt="" width="100">
                        </p>
                        <p style="margin-top:-15px;">
                            វេជ្ជបណ្ឌិត / Dr . {{$approvor->first_name}} {{$approvor->last_name}}
                        </p>
                        
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