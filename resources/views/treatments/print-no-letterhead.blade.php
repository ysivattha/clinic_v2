<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('lb.treatments')}}</title>
    <style>

        @page {
          
          margin: 10mm 10mm 10mm 10mm;
        }
        @media print{
            thead {display: table-row-group;}
            body {transform: scale(1);}
            table {page-break-inside: avoid;}
        }
    
    
        @font-face{
                font-family: khos;
               src: url("{{asset('fonts/KhmerOS_battambang.ttf')}}");
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
                font-family: khos;
            }
            .table{
                width: 100%;
                border-spacing: 0;
                border: 1px solid #ccc;
            }
            /* }
            .table thead tr th{
                font-size: 13px;
                text-align: left;
                border-bottom: 1px solid #888;
      
                border-right: 1px solid #ccc;
                padding: 2px 4px;
            }
            .table thead tr th span{
                font-size: 11px;
            } */
            /* .table thead tr th:last-child{
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
            } */
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
       .sign_area{
            position: relative;
            float: left;
            left:50px;
            top:5px;

            /* bottom: 250px; */

        }

        @media print {
            .sign_area {
                break-inside: avoid;
            }
        }

    </style>
</head>
<body >
    <table width="100%">
        {{-- <thead>
            <tr>
                <th>
                    <img src="{{asset($hospital->letterhead)}}" alt="" width="100%">   
                </th>
            </tr>
        </thead> --}}
        <tbody>
            <tr>
                <td>
                    <table width="100%" class="header">
              
                        <tr>
                            <td  style="width=:5px;font-size:13px;">អត្តលេខ / H.N</td>
                            <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$t->code}}</b></td>
                            <td width="135" style="padding:0!important;margin:0!important; font-size: 13px!important;">កាលបរិច្ឆេទ / Date</td>
                            <td style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{date('d/m/Y', strtotime($t->date))}}</b> </td>
                        </tr>
                        <tr>
                            <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">ឈ្មោះអ្នកជំងឺ</td>
                            <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$t->kh_first_name}} {{$t->kh_last_name}}</b></td>
                            <td width="100px" style="padding:0!important;margin:0!important; font-size: 13px!important;">អាយុ / Age </td>
                            <td style="padding:0!important;margin:0!important;font-size: 13px!important;font-size: 13px!important;">: <b>{{$dob}}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ភេទ / Gender : <b>{{$t->gender}}</b></td>
                           
                        </tr>
                        <tr>
                            <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">Patient Name </td>
                            <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$t->en_first_name}} {{$t->en_last_name}}</b></td>
                            <td width="135" style="padding:0!important;margin:0!important; font-size: 13px!important;">លេខវេជ្ជបញ្ជា/Pres N</td>
                            <td style="padding:0!important;margin:0!important;font-size: 13px!important;font-size: 13px!important;">: <b>{{$t->treatment_code}}</b></td>
                        </tr>
                        <tr>
                            <td width="110" style="padding:0!important;margin:0!important; font-size: 13px!important;">លេខយោង / Ref.N</td>
                            <td width="150"  style="padding:0!important;margin:0!important; font-size: 13px!important;">: <b>{{$hospital->code}}</b></td>
                            <td  style="width:12px;padding:0!important;margin:0!important; font-size: 13px!important;">វេជ្ជបណ្ឌិត /  Dr. / Pr.</td>
                            <td style="padding:0!important;margin:0!important;font-size: 13px!important;font-size: 13px!important;">: <b>{{$t->dfirst_name}} {{$t->dlast_name}}</td>
                        </tr>
                        <tr>
                            <td  width="130px" style="vertical-align: top;">
                                រោគវិនិច្ឆ័យ / Diagnosis
                                <td colspan="6" style="white-space:pre-wrap; padding:0!important;margin:0!important; font-size: 13px!important;"><b>: {!!$t->diagnosis1!!}</b>
                            </td>
                           
                            
                        </tr>

                        </table>
                        <hr>

                    <h2 style="text-align:center; font-size:15px; font-family:khos" ><u>វេជ្ជបញ្ជា / Prescription</u></h2>
                                            {{-- @if(count($tds)>0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('lb.name')}}{{__('lb.medicine')}}</th>
                                        <th>{{__('lb.usage')}}</th>
                                        <th>{{__('lb.note')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i=1)
                                    @foreach($tds as $td)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$td->name}}</td>
                                            <td>{{$td->description}}</td>
                                            <td>{{$td->note}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            
                            </table>
                        @endif --}}
                    <div>
                        {!!$t->diagnosis!!}
                    </div>
                    <div style="width: 100%" id="sign_area_cotainer">
                        <div class="sign_area" >
                            <?php
                                $doctor = DB::table('users')->where('id', $t->doctor_id)->first();
                            ?>
                            
                            <p  style="white-space: pre-wrap"><strong>{{ $t->note }}</strong></p>
                         
                            <p style="margin-left:8%;"> <img src="{{asset($doctor->signature)}}" alt="" width="100"></p>
                                វេជ្ជបណ្ឌិត / Dr.{{$doctor->first_name}} {{$doctor->last_name}}
                            <p>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <script>
        window.onload = function(){
            print();
        }
    </script>
</body>
</html>