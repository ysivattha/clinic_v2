<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Role;
use App\DataTables\ExportRoleDataTable;
use DB;
use Auth;
use DataTables;
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app()->setLocale(Auth::user()->language);
            return $next($request);
        });
        
    }
    public function index(Request $r)
    {
        if(!check('role', 'l')){
            return view('permissions.no');
        }
        if ($r->ajax()) 
        {
            $data = Role::where('active', 1);
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addColumn('rname', function($row){
                    $url = route('role.permission', $row->id);
                    $a = "<a href='{$url}'>{$row->name}</a> ";
                    return $a;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'roles', 'role');
                    return $btn;
                })
                ->rawColumns(['action', 'check', 'rname'])
                ->make(true);
        }
        return view('roles.index');
    }
    public function create()
    {
        if(!check('role', 'i')){
            return view('permissions.no');
        }
        return view('roles.create');
    }
    public function store(Request $r){
        if(!check('role', 'i')){
            return view('permissions.no');
        }
        $data = array(
            "name" => $r->name
        );
        $i = DB::table('roles')->insert($data);
        if($i){
            return redirect('role/create')
                ->with('success', 'Data has been saved!');
        }
        else {
            return redirect('role/create')
                ->with('error', 'Fail to save data!')
                ->withInput();
        }
    }
    public function edit($id)
    {
        if(!check('role', 'u'))
        {
            return view('permissions.no');
        }
        $data['role'] = DB::table("roles")->find($id);
    	return view("roles.edit",$data);
    }
    public function update(Request $r, $id)
    {
        if(!check('role', 'u')){
            return view('permissions.no');
        }
        $data = array(
            "name" => $r->name
        );
        $i = DB::table("roles")->where("id", $id)->update($data);
        if ($i)
        {
            return redirect()->route('role.index')
                ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('role.edit', $id)
                ->with('error', config('app.error'));
        }
    }
    public function delete($id)
    {
        if(!Right::check('Role', 'd')){
            return view('permissions.no');
        }
        $i = DB::table('roles')->where('id', $id)->update(["active"=>0]);
        
        return redirect()->route('role.index')
            ->with('success', config('app.del_success'));
    }
    public function export(ExportRoleDataTable $dataTable)
    {
        return $dataTable->render('roles.export');
    }
}
