<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('lb.expense_report')}}</title>
    <style>
        @font-face{
            font-family: kh;
            src: url("{{asset('fonts/KhmerOSmuollight.ttf')}}");
        }
        @font-face{
            font-family: khos;
            src: url("{{asset('fonts/KhmerOSsiemreap.ttf')}}");
        }

    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="header text-center">
            <img src="{{asset('companies/'.$com->logo)}}" alt="" width="100">
            <h3 class='h3'>{{$com->kh_name}}</h3>
            <h4 class="h4">{{$com->en_name}}</h4>
        </div>
        <p>{{__('lb.income')}} ពី: {{$start_date}} ដល់: {{$end_date}}</p>
        <hr color="black">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.invoice_no')}}</th>
                    <th>{{__('lb.patients')}}</th>
                    <th>{{__('lb.paid')}}</th>
                    <th>{{__('lb.due_amount')}}</th>
                    <th>{{__('lb.total')}}</th>
                </tr>
            </thead>
            <?php
                $i = 1;
                $total = 0;
            ?>
            @foreach($incomes as $ex)
                <?php 
                    $patient = DB::table('customers')->where('id', $ex->patient_id)->first();
                ?>
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$ex->start_date}}</td>
                    <td>{{$ex->invoice_no}}</td>
                    <td>{{$patient->kh_first_name}} {{$patient->kh_last_name}} </td>
                    <td>$ {{number_format($ex->paid,2)}}</td>
                    <td>$ {{number_format($ex->due_amount,2)}}</td>
                    <td>$ {{number_format($ex->total,2)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class='text-right text-danger'>{{__('lb.total')}}</td>
                <td><b>$ {{number_format($total_paid,2)}}</b></td>
                <td><b >$ {{number_format($total_due_amount,2)}}</b></td>
                <td><b >$ {{number_format($total,2)}}</b></td>
            </tr>
        </table>
    </div>
    <script>
        print();
    </script>
</body>
</html>