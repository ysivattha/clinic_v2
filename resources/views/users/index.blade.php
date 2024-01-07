@extends('layouts.master')
@section('title')
    {{__('lb.users')}}
@endsection
@section('header')
    {{__('lb.users')}}
@endsection
@section('content')
<!-- js chosen -->
<link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
<link rel="stylesheet" href="{{ asset('multiselect/css/bootstrap-multiselect.css') }}">
<div class="toolbox pt-1 pb-1">
    @cancreate('user')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target='#createModal'>
        <i class="fa fa-plus-circle"></i> {{__('lb.create')}}
    </button>
    @endcancreate
    @candelete('user')
    <button class="btn btn-danger btn-sm" title="Delete selected users" id='btnDelete' 
        table='users' permission='user' token="{{csrf_token()}}">
        <i class="fa fa-trash"></i> {{__('lb.delete')}}
    </button>
    @endcandelete
</div>

<div class="card">
    <div class="card-body">
        @component('coms.alert')
        @endcomponent
            <table class="table table-sm table-bordered datatable" id='dataTable' style="width: 100%">
                <thead class="bg-light">
                    <tr>
                        <th>
                            <input type="checkbox" onclick="check(this)" value="off">
                        </th>
                        <th>#</th>
                       
                        <th>{{__('lb.photo')}}</th>
                        <th>{{__('lb.signature')}}</th>
                        <th>{{__('lb.code')}}</th>
                        <th>{{__('lb.first_name')}}</th>
                        <th>{{__('lb.last_name')}}</th>
                        <th>{{__('lb.gender')}}</th>
                        <th>{{__('lb.username')}}</th>
                        <th>{{__('lb.email')}}</th>
                        <th>{{__('lb.phone')}}</th>
                        <th>{{__('lb.role')}}</th>
                        <th>{{__('lb.hospitals')}}</th>
                        <th>{{__('lb.action')}}</th>
                    </tr>
                </thead>
                
            </table>
    </div>
</div>
@endsection
@section('modal')
<!-- create model -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form action="#" method="POST" id='create_form' onsubmit="create_user(event)" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="tbl" value="users">
          <input type="hidden" name="per" value="user">
          <div class="modal-content">
              <div class="modal-header bg-success">
                    <strong class="modal-title">{{__('lb.create_user')}}</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              <div class="modal-body">
                <div id="sms">
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group mb-1">
                            <label for="efirst_name">
                                {{__('lb.code')}}
                            </label>
                            <input type="text" id="code" name="code" class="form-control input-xs">
                        </div>
                        <div class="form-group mb-1">
                            <label for="first_name">
                                {{__('lb.first_name')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" required class="form-control input-xs">
                        </div>
                        <div class="form-group mb-2">
                            <label for="last_name">
                                {{__('lb.last_name')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" required class="form-control input-xs">
                        </div>
                        
                        <div class="form-group mb-1">
                            <label>{{__('lb.gender')}} <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control input-xs" required>
                                <option value="">{{__('lb.select_one')}}</option>
                                <option value="Male">{{__('lb.male')}}</option>
                                <option value="Female">{{__('lb.female')}}</option>
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="username">
                                {{__('lb.username')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="username" name="username" required class="form-control input-xs">
                        </div>
                        <div class="form-group mb-1">
                            <label for="password">
                                {{__('lb.password')}} <span class="text-danger">*</span>
                            </label>
                            <input type="password" id="password" name="password" required class="form-control input-xs">
                        </div>
                        <div class="form-group mb-1">
                            <label for="email">
                                {{__('lb.email')}} <span class="float-right">:</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-control input-xs">
                        </div>
                       
                    </div>
                    <div class="col-sm-6">
                        
                        <div class="form-group mb-1">
                            <label for="phone">
                                {{__('lb.phone')}} <span class="float-right">:</span>
                            </label>
                            <input type="text" id="phone" name="phone" class="form-control input-xs">
                        </div>
                        <div class="form-group mb-1">
                            <label for="role_id">
                               សិទ្ធក្នុងប្រព័ន្ធ{{__('lb.role')}} {{-- {{__('lb.role')}} --}}  <span class="text-danger">*</span>
                            </label>
                            <select name="role_id" id="role_id" required class="form-control input-xs">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($roles as $r)
                                    <option value="{{$r->id}}">{{$r->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @cancreate('user_hospital')
                        <div class="form-group mb-1">
                            <label for="hospital_id">
                                ពត៌មានកន្លែងធ្វើការ  {{-- {{__('lb.hospitals')}}  --}}
                            </label>
                            <select name="hospital_id" id="hospital_id" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($hospitals as $s)
                                    <option value="{{$s->id}}">{{$s->code}} - {{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endcancreate
                        <div class="form-group mb-1">
                            <label for="department_id">
                                 ពត៌មានកន្លែងធ្វើការ​ {{-- {{__('lb.departments')}}   --}}
                            </label>
                            <select name="department_id" id="department_id" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($departments as $d)
                                    <option value="{{$d->id}}">{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>
                      
                        <div class="form-group mb-1">
                            <label for="position_id">
                                 ពត៌មានកន្លែងធ្វើការ​ {{-- {{__('lb.positions')}}  --}}
                            </label>
                            <select name="position_id" id="position_id" required class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($positions as $p)
                                    <option value="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="signature">
                                {{__('lb.signature')}}
                            </label>
                            <input type="file" id="signature" name="signature" onchange="preview(event)" class="form-control input-xs">
                           
                        </div>
                        <div class="form-group mb-1">
                            <label for="photo">
                                {{__('lb.photo')}}
                            </label>
                            <input type="file" id="photo" name="photo" onchange="preview(event)" class="form-control input-xs">
                            <div class="mt-3">
                                <img src="" alt="" id="img" width="99">
                            </div>
                        </div>
                    </div>
                </div> 
              </div>
              <div class="modal-footer pb-1 pt-1">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> {{__('lb.save')}}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" 
                        onclick="reset('#create_form')">
                        <i class="fa fa-times"></i> {{__('lb.close')}}
                    </button>
              </div>
          </div>
      </form>
    </div>
</div>     
<!-- edit model -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form action="#" method="POST" id='edit_form' onsubmit="update_user(event)" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="tbl" value="users">
          <input type="hidden" name="per" value="user">
          <input type="hidden" name="id" id='eid' value="">
          <div class="modal-content">
              <div class="modal-header bg-success">
                    <strong class="modal-title">{{__('lb.edit_user')}}</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              <div class="modal-body">
                <div id="esms">
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group mb-1">
                            <label for="efirst_name">
                                {{__('lb.code')}}
                            </label>
                            <input type="text" id="ecode" name="code" class="form-control input-xs">
                        </div>
                        <div class="form-group mb-1">
                            <label for="efirst_name">
                                {{__('lb.first_name')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="efirst_name" name="first_name" required class="form-control input-xs">
                        </div>
                        <div class="form-group mb-2">
                            <label for="elast_name">
                                {{__('lb.last_name')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="elast_name" name="last_name" required class="form-control input-xs">
                        </div>
                        
                        <div class="form-group mb-1">
                            <label>{{__('lb.gender')}} <span class="text-danger">*</span></label>
                            <select name="gender" id="egender" class="form-control" required>
                                <option value="">{{__('lb.select_one')}}</option>
                                <option value="Male">{{__('lb.male')}}</option>
                                <option value="Female">{{__('lb.female')}}</option>
                            </select>
                        </div>
                       
                        <div class="form-group mb-1">
                            <label for="eusername">
                                {{__('lb.username')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="eusername" name="username" required class="form-control input-xs">
                        </div>
                        <div class="form-group mb-1">
                            <label for="epassword">
                                {{__('lb.password')}}
                            </label>
                            <input type="password" id="epassword" name="password" class="form-control input-xs">
                            <small class="d-block">{{__('lb.old_pass')}}</small>
                        </div>
                        <div class="form-group mb-1">
                            <label for="eemail">
                                {{__('lb.email')}} <span class="float-right">:</span>
                            </label>
                            <input type="email" id="eemail" name="email" class="form-control input-xs">
                        </div>

                        <div class="form-group mb-1">
                            <label for="ephone">
                                Phone <span class="float-right">:</span>
                            </label>
                            <input type="text" id="ephone" name="phone" class="form-control input-xs">
                        </div>
                        
                    </div>
                    <div class="col-sm-6">

                       
                        <div class="form-group mb-1">
                            <label for="erole_id">
                                តួនាទីក្នុងប្រព័ន្ធ {{-- {{__('lb.role')}}--}} <span class="text-danger">*</span>
                            </label>
                            <select name="role_id" id="erole_id" required class="form-control input-xs">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($roles as $r)
                                    <option value="{{$r->id}}">{{$r->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        

                        <div class="form-group mb-1">
                            <label for="ehospital_id">
                              សិទ្ធចូលមន្ទីពេទ្យ  {{-- {{__('lb.hospitals')}}  --}}
                            </label>
                            <select   name="mulhospital_id[]" id="mulhospital_id"  class="mt-1 input-xs "   multiple="multiple">
        
                     
                                @foreach ($hospitals as $s)
                                   
                                      <option   
                                      
                                  
                                        value="{{ $s->id }}">{{ $s->name }}</option>
                                 
        
                              
                
                                @endforeach
                          
                            </select>
                        </div>

                        <div class="form-group mb-1">
                            <label for="section">
                                សិទ្ធចូលផ្នែក{{-- {{__('lb.section')}}  --}}
                            </label>
                            <select   name="mulsection_id[]" id="mulsection_id"  class="mt-1 input-xs "   multiple="multiple">
        
                     
                                @foreach ($sections as $sec)
                                   
                                      <option   
                                      
                                  
                                        value="{{ $sec->id }}">{{ $sec->name }}</option>
                                 
        
                              
                
                                @endforeach
                          
                            </select>
                        </div>

                        @canedit('user_hospital')
                        <div class="form-group mb-1">
                            <label for="ehospital_id">
                                ពត៌មានកន្លែងធ្វើការ {{-- {{__('lb.hospitals')}}  --}}
                            </label>
                            <select name="hospital_id" id="ehospital_id" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($hospitals as $s)
                                    <option value="{{$s->id}}">{{$s->code}} - {{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endcanedit

                        <div class="form-group mb-1">
                            <label for="edepartment_id">
                                ពត៌មានកន្លែងធ្វើការ{{-- {{__('lb.departments')}}   --}}
                            </label>
                            <select name="department_id" id="edepartment_id" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($departments as $d)
                                    <option value="{{$d->id}}">{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>
                       
                        <div class="form-group mb-1">
                            <label for="eposition_id">
                              ពត៌មានកន្លែងធ្វើការ  {{-- {{__('lb.positions')}}  --}}
                            </label>
                            <select name="position_id" id="eposition_id" class="form-control chosen-select">
                                <option value="">{{__('lb.select_one')}}</option>
                                @foreach($positions as $p)
                                    <option value="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="esignature">
                                {{__('lb.signature')}}
                            </label>
                            <input type="file" id="esignature" name="signature" onchange="epreview(event)" class="form-control">
                          
                        </div>
                        <div class="form-group mb-1">
                            <label for="photo">
                                {{__('lb.photo')}}
                            </label>
                            <input type="file" id="ephoto" name="photo" onchange="epreview(event)" class="form-control">
                            <div class="mt-3">
                                <img src="" alt="" id="eimg" width="99">
                            </div>
                        </div>
                    </div>
                </div> 
              </div>
              <div class="modal-footer pb-1 pt-1">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> {{__('lb.save')}}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" 
                        onclick="reset('#create_form')">
                        <i class="fa fa-times"></i> {{__('lb.close')}}
                    </button>
              </div>
          </div>
      </form>
    </div>
</div> 
@endsection
@section('js')
<script src="{{ asset('multiselect/js/bootstrap-multiselect.js') }}"></script>

<script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(".chosen-select").chosen({width: "100%"});
            $("#sidebar li a").removeClass("active");
            $("#menu_security>a").addClass("active");
            $("#menu_security").addClass("menu-open");
            $("#menu_user").addClass("myactive");
           
            $("#edepartment_id").change(function () {
                eget_section();
            });
            $('#mulhospital_id').multiselect({
                enableHTML:true,
             
                includeSelectAllOption:true,
                selectAllText:' គ្រប់មន្ទីពេទ្យ',
                selectAllValue:'multiselect-all',
                selectAllNumber:false,
                selectAllJustVisible:true,
                maxHeight:false,
         
                buttonWidth: '100%',
               
              




            });
            $('#mulsection_id').multiselect({
           
             
                includeSelectAllOption:true,
                selectAllText:' គ្រប់ផ្នែក',
                selectAllValue:'multiselect-all',
                selectAllNumber:false,
                selectAllJustVisible:true,
                maxHeight:false,
         
                buttonWidth: '100%',
               
              




            });


			var table = $('#dataTable').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('user.index') }}",
                columns: [
                    {data: 'check', name: 'check', searchable: false, orderable: false},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                    {
                        data: 'photo', 
                        name: 'photo', 
                        orderable: false, 
                        searchable: false
                    },
                    {data: 'signature', name: 'signature'},
                    {data: 'code', name: 'code'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'gender', name: 'gender'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'rname', name: 'roles.name'},
                    {data: 'hname', name: 'hospitals.name'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    },
                ],
                
            });
        });
    // function to get sub category
   
    function preview(e){
        var img = document.getElementById('img');
        img.src = URL.createObjectURL(e.target.files[0]);
    }
    function epreview(e){
        var img = document.getElementById('eimg');
        img.src = URL.createObjectURL(e.target.files[0]);
    }
    // function to save data from a form
    function create_user(evt)
    {   
        var form = $('#create_form')[0];
        var formData = new FormData(form);
        evt.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{route('user.store')}}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(sms){
                
                if(sms>0)
                {
                    let txt = `<div class='alert alert-success p-2' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <div>
                            Data has been saved successfully!
                        </div>
                    </div>`;
                    $('#sms').html(txt);
                    var img = document.getElementById('img');
                    img.src = null;
                    $('#hospital_id').val('');
                    $('#department_id').val('');
                    $('#position_id').val('');
                    $("#hospital_id").trigger("chosen:updated");
                    $("#department_id").trigger("chosen:updated");
                    $("#position_id").trigger("chosen:updated");
                    $("#create_form")[0].reset();
                    $('#dataTable').DataTable().ajax.reload();
                }
                else{
                    let txt = `<div class='alert alert-danger p-2' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <div>
                            Fail to save data, please check again!
                        </div>
                    </div>
                    `;
                    $('#sms').html(txt);
                }
                
            }
        });
    }
    function edit(id, obj)
    {
        $('#esms').html('');
        let tbl = $(obj).attr('table');
        $.ajax({
            type: 'GET',
            url: burl + '/bulk/get/' + id + '?tbl=' + tbl,
            success: function(sms)
            {
                let data = JSON.parse(sms);
                $('#eid').val(data.id);
                $('#efirst_name').val(data.first_name);
                $('#ecode').val(data.code);
                $('#elast_name').val(data.last_name);
                $('#egender').val(data.gender);
                $('#eemail').val(data.email);
                $('#ephone').val(data.phone);
                $('#eusername').val(data.username);
                $('#ehospital_id').val(data.hospital_id);
                $('#edepartment_id').val(data.department_id);
                $('#eposition_id').val(data.position_id);
                $('#eusername').val(data.username);
                $('#erole_id').val(data.role_id);
                $("#ehospital_id").trigger("chosen:updated");
                $("#edepartment_id").trigger("chosen:updated");
                $("#eposition_id").trigger("chosen:updated");
                
                var img = document.getElementById('eimg');
                img.src = burl + '/' + data.photo;
            }
        });
    }
    // function to save data from a form
    function update_user(evt)
    {
        var form = $('#edit_form')[0];
        var formData = new FormData(form);
        evt.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{route('user.update')}}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(sms){
                console.log(sms);
                var img = document.getElementById('img');
                    img.src = null;
                    $("#edit_form")[0].reset();
                    $('#dataTable').DataTable().ajax.reload();
                    $('#editModal').modal('hide');
                // if(sms>0)
                // {
                //     var img = document.getElementById('img');
                //     img.src = null;
                //     $("#edit_form")[0].reset();
                //     $('#dataTable').DataTable().ajax.reload();
                //     $('#editModal').modal('hide');
                // }
                // else{
                //     let txt = `<div class='alert alert-danger p-2' role='alert'>
                //         <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                //             <span aria-hidden='true'>&times;</span>
                //         </button>
                //         <div>
                //             Fail to save data, please check again!
                //         </div>
                //     </div>
                //     `;
                //     $('#esms').html(txt);
                // }
                
            }
        });
    }
    </script>
@endsection