@extends('layouts.master')
@section('title')
    Exchange Rates
@endsection
@section('header')
    <strong>អត្រាប្តូរប្រាក់</strong>
@endsection
@section('css')
    <style>
        .text-muted td{
            color: #aaa;
        }
    </style>
@endsection
@section('content')

<div class="toolbox pt-1 pb-1">
</div>   
<div class="card">
	<div class="card-body">
       @component('coms.alert')
       @endcomponent
       <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ដុល្លារ</th>
                <th>រៀល</th>
                <th>កាលបរិច្ឆេទ</th>
                <th>គណនី</th>
                <th>សកម្មភាព</th>
                
            </tr>
        </thead>
        <tbody>			
            <?php
                $pagex = @$_GET['page'];
                if(!$pagex)
                    $pagex = 1;
                $i = config('app.row') * ($pagex - 1) + 1;
            ?>
            @foreach($active as $a)
                <tr>
                    <td>{{$i++}}</td>
                    <td>$ {{$a->dollar}}</td>
                    <td>KHR {{$a->khr}}</td>
                    <td>{{date_format(date_create($a->create_at), 'Y-m-d h:i:s a')}}</td>
                    <td>{{$a->first_name}} {{$a->last_name}}</td>
                    <td class="action">
                        <a href="{{route('exchange.edit', $a->id)}}" class="text-success" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            <?php
                $pagex = @$_GET['page'];
                if(!$pagex)
                    $pagex = 1;
                $i = config('app.row') * ($pagex - 1) + 1;
            ?>
            @foreach($old as $a)
                <tr class='text-muted'>
                    <td>{{($i++) + 1}}</td>
                    <td>$ {{$a->dollar}}</td>
                    <td>KHR {{$a->khr}}</td>
                    <td>{{date_format(date_create($a->create_at), 'Y-m-d h:i:s a')}}</td>
                    <td>{{$a->first_name}} {{$a->last_name}}</td>
                    <td class="action">
                       
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$old->links()}}
	</div>
</div>
@endsection

@section('js')
	<script>
         $(document).ready(function () {
            $("#sidebar li a").removeClass("active");
            $("#menu_config>a").addClass("active");
            $("#menu_config").addClass("menu-open");
            $("#menu_exchange").addClass("myactive");
        });
    </script>
@endsection
