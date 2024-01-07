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
        <p>{{__('lb.expense_report')}} ពី: {{$start_date}} ដល់: {{$end_date}}</p>
        <hr color="black">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('lb.date')}}</th>
                    <th>{{__('lb.expense_for')}}</th>
                    <th>{{__('lb.description')}}</th>
                    <th>{{__('lb.amount')}}</th>
                </tr>
            </thead>
            <?php
                $i = 1;
                $total = 0;
            ?>
            @foreach($expenses as $ex)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$ex->expense_date}}</td>
                    <td>{{$ex->item}}</td>
                    <td>{{$ex->description}}</td>
                    <td>$ {{$ex->amount}}</td>
                    <?php
                        $total += $ex->amount;
                    ?>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class='text-right text-danger'>{{__('lb.total')}}</td>
                <td><span id="total">$ {{$total}}</span></td>
            </tr>
        </table>
    </div>
    <script>
        print();
    </script>
</body>
</html>