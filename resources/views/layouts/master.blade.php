<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name')) | Vdoo ERP</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
   
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    
    @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
         <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-primary">
            <a class="nav-link text-light" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>

            

            <strong class="text-light">
                @yield('header', config('app.name'))
            </strong>

            <div class="ml-4">
                @yield('btn')
            </div>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <div class="mt-2">
                    {{-- <strong class="text-light">
                        @yield('position', config('app.name'))
                    </strong> --}}


                    
                    <strong class="text-light">
                        {{@Auth::user()->role->name }}
                    </strong>
                    &nbsp;&nbsp;
                    <strong class="text-light">
                        {{@Auth::user()->hospital->name }}
                    </strong>
                </div>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link text-light" data-toggle="dropdown" href="#">
                    <img src="{{asset(@Auth::user()->photo)}}" alt="" width="27" height="25"
                        class="brand-image img-circle elevation-3 mr-2">
                    {{@Auth::user()->username}} <i class="right fas fa-caret-down"></i>
                </a>
                
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{url('lang/km')}}" class="dropdown-item">
                        <img src="{{asset('img/khmer.png')}}" alt="" width="20"> ភាសាខ្មែរ
                    </a>
                    <a href="{{url('lang/en')}}" class="dropdown-item">
                        <img src="{{asset('img/english.png')}}" alt="" width="20"> English
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{route('user.profile')}}" class="dropdown-item">
                        <i class="fas fa-id-card mr-2 text-success"></i> {{__('lb.profile')}}
                    </a>
                    <a href="{{route('user.logout')}}" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2 text-danger"></i> {{__('lb.logout')}}
                    </a>
                </div>
            </li>
            
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{url('/')}}" class="brand-link">
            <img src="{{asset('img/logo.png')}}" alt="" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-bold">Vdoo ERP</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" id="sidebar"
                        role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                        <li class="nav-item" >
                            <a href="{{url('/')}}" class="nav-link" id="menu_home">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                {{__('lb.dashboard')}}
                            </p>
                            </a>
                        </li>
                        @canview('invoice')
                        <li class="nav-item" >
                            <a href="{{route('invoice.index')}}" class="nav-link" id="menu_invoice">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>
                                {{__('lb.invoice')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('patient')
                        <li class="nav-item" >
                            <a href="{{route('patient.index')}}" class="nav-link" id="menu_patient">
                            <i class="nav-icon fas fa-user-injured"></i>
                            <p>
                                {{__('lb.patients')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('request')
                        <li class="nav-item" >
                            <a href="{{route('request.index')}}" class="nav-link" id="menu_request">
                            <i class="nav-icon fas fa-hand-paper"></i>
                            <p>
                                {{__('lb.request')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('front_office')
                        <li class="nav-item" >
                            <a href="{{route('front_office.index')}}" class="nav-link" id="menu_front_office">
                                <i class="nav-icon fas fa-check-square"></i>
                            <p>
                                {{__('lb.front_office')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('technical_check')
                        <li class="nav-item" >
                            <a href="{{route('technical.index')}}" class="nav-link" id="menu_technical_check">
                                <i class="nav-icon fas fa-camera"></i>
                            <p>
                                {{__('lb.technical_checks')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('doctor_check')
                        <li class="nav-item" >
                            <a href="{{route('doctor_check.index')}}" class="nav-link" id="menu_doctor_check">
                                <i class="nav-icon fa fa-user-md"></i>
                            <p>
                                {{__('lb.doctor_check')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('treatment')
                        <li class="nav-item" >
                            <a href="{{route('treatment.index')}}" class="nav-link" id="menu_treatment">
                            <i class="nav-icon fas fa-medkit"></i>
                            <p>
                                {{__('lb.treatments')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('appointment')
                        <li class="nav-item" >
                            <a href="{{route('appointment.index')}}" class="nav-link" id="menu_appointment">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>
                                {{__('lb.appointments')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                     
                        @canview('appointment')
                        <li class="nav-item" >
                            <a href="{{route('paraclinical.index')}}" class="nav-link" id="menu_paraclinical">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>
                                {{__('lb.paraclinical')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('expense')
                        <li class="nav-item" >
                            <a href="{{route('expense.index')}}" class="nav-link" id="menu_expense">
                            <i class="nav-icon fas fa-hand-holding-usd"></i><i class=""></i>
                            <p>
                                {{__('lb.expense')}}
                            </p>
                            </a>
                        </li>
                        @endcanview
                        @canview('expense_report')
                        <li class="nav-item has-treeview" id='menu_report'>
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-print"></i>
                            <p>
                                {{__('lb.report')}}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('income.index')}}" class="nav-link" id='menu_revenue'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.revenue')}}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('due.index')}}" class="nav-link" id='menu_due'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.indebted')}}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('report/expense')}}" class="nav-link" id='menu_report_expense'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.expense')}}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('profit.index')}}" class="nav-link" id='menu_profit'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.profit')}}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('category.index')}}" class="nav-link" id='menu_dividend'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.dividends')}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcanview
                        <li class="nav-item has-treeview" id='menu_config'>
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                {{__('lb.setting')}}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                            </a>
                            <ul class="nav nav-treeview">
                                
                                @canview('category')
                                <li class="nav-item">
                                    <a href="{{route('category.index')}}" class="nav-link" id='menu_category'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.categories')}}
                                    </a>
                                </li>
                                @endcanview
                                
                                @canview('medicine_library')
                                <li class="nav-item">
                                    <a href="{{route('medicine_library.index')}}" class="nav-link" id='menu_medicine_library'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.medicine_libraries')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('position')
                                <li class="nav-item">
                                    <a href="{{route('position.index')}}" class="nav-link" id='menu_position'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.positions')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('department')
                                <li class="nav-item">
                                    <a href="{{route('department.index')}}" class="nav-link" id='menu_department'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.departments')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('section')
                                <li class="nav-item">
                                    <a href="{{route('section.index')}}" class="nav-link" id='menu_section'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.body_parts')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('item')
                                <li class="nav-item">
                                    <a href="{{route('item.index')}}" class="nav-link" id='menu_item'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.items')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('template')
                                <li class="nav-item">
                                    <a href="{{route('template.index')}}" class="nav-link" id='menu_template'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.templates')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('diagnosis_template')
                                <li class="nav-item">
                                    <a href="{{route('diagnosis_template.index')}}" class="nav-link" id='menu_diagnosis_template'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.diagnosis_template')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('protocol_category')
                                <li class="nav-item">
                                    <a href="{{route('protocol_category.index')}}" class="nav-link" id='menu_protocol_category'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.protocol_category')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('protocol')
                                <li class="nav-item">
                                    <a href="{{route('protocol.index')}}" class="nav-link" id='menu_protocol'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.protocols')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('fil')
                                <li class="nav-item">
                                    <a href="{{route('fil.index')}}" class="nav-link" id='menu_fil'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.fils')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('hospital')
                                <li class="nav-item">
                                    <a href="{{route('hospital.index')}}" class="nav-link" id='menu_hospital'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.hospitals')}}
                                    </a>
                                </li>
                                @endcanview
                                
                                @canview('exchange')
                                <li class="nav-item">
                                    <a href="{{url('exchange')}}" class="nav-link" id='menu_exchange'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.exchange')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('company')
                                <li class="nav-item">
                                    <a href="{{url('company')}}" class="nav-link" id='menu_company'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.company_info')}}
                                    </a>
                                </li>
                                @endcanview
                            </ul>
                        </li>
                        <li class="nav-item has-treeview" id='menu_security'>
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shield-alt"></i>
                            <p>
                                {{__('lb.security')}}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                            </a>
                            <ul class="nav nav-treeview" id='security_collapse'>
                                @canview('user')
                                <li class="nav-item">
                                    <a href="{{route('user.index')}}" class="nav-link" id="menu_user">
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.users')}}
                                    </a>
                                </li>
                                @endcanview
                                @canview('role')
                                <li class="nav-item">
                                    <a href="{{route('role.index')}}" class="nav-link" id='menu_role'>
                                        <i class="fas fa-angle-double-right nav-icon ml-3"></i> 
                                        {{__('lb.roles')}}
                                    </a>
                                </li>
                                @endcanview
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                   @yield('content')
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            Copyright &copy; {{date('Y')}} <a href="#">Vdoo ERP</a>
            <div class="float-right d-none d-sm-inline-block">
                Version 1.0.0
            </div>
        </footer> 
    </div>
    @yield('modal')
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    var burl = "{{url('/')}}";
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- overlayScrollbars -->
    <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/adminlte.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    
    @yield('js')
</body>
</html>