<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paraclinical;
use DB;
use DataTables;
use Auth;
class ParaclinicalController extends Controller
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
        if(!check('paraclinical', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = Paraclinical::LeftJoin('customers', 'customers.id', 'paraclinicals.patient_id')
                ->where('paraclinicals.active', 1)
                ->orderBy('paraclinicals.id', 'desc')
                ->select('paraclinicals.*', 'customers.kh_first_name as kh_first_name', 'customers.kh_last_name as kh_last_name', 'customers.code', 'customers.phone');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addColumn('result', function($row){
                    $url = asset($row->result);
                    $result = "<a href='{$url}' target='_blank' class='btn btn-xs btn-primary'><i class='fa fa-file-pdf'></i></a>";
                    return $result;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $url = route('paraclinical.result', $row->id);
                    $url2 = route('paraclinical.edit', $row->id);
                    $url3 = url('paraclinical/delete/'.$row->id);
                    $btn = btn_actions($row->id, 'paraclinicals', 'paraclinical');
                    return $btn;
                })
                ->rawColumns(['action', 'check', 'result'])
                ->make(true);
        }
        $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->get();
        return view('paraclinicals.index', $data);
    }

    public function result($id)
    {
        if(!check('paraclinical', 'l')){
            return view('permissions.no');
        }
        $data['paraclinical'] = DB::table('paraclinicals')
            ->where('id', $id)
            ->first();
        return view('paraclinicals.result', $data);
    }
    public function store(Request $r)
    {
        if(!check('paraclinical', 'i')){
            return view('permissions.no');
        }
        $validate = $r->validate([
            'patient_id' => 'required'
        ]);
        if(!check('paraclinical', 'i')){
            return view('permissions.no');
        }
        $data = $r->except('_token', 'result', 'tbl', 'per');
        $data['created_by'] = Auth::user()->id;
        if($r->result)
        {
            $data['result'] = $r->file('result')->store('uploads/paraclinicals', 'custom');
        }
        $i = DB::table('paraclinicals')->insertGetId($data);
        if($i)
        {
           return 1;

        }
        else{
            return 0;
        } 
    }
   
    public function update(Request $r)
    {
        if(!check('paraclinical', 'u')){
            return 0;
        }
        $data = $r->except('_token', 'result', 'tbl', 'per', 'id');
        $data['updated_by'] = Auth::user()->id;
        $data['updated_at'] = Date('Y-m-d h:i:s');
        if($r->result)
        {
            $data['result'] = $r->file('result')->store('uploads/paraclinicals', 'custom');
        }
        $i = DB::table('paraclinicals')
            ->where('id', $r->id)
            ->update($data);
        if($i){
           return 1;
        }
        else{
            return 0;
        }
    }

    public function delete($id)
    {
        if(!check('paraclinical', 'd')){
            return view('permissions.no');
        }
        DB::table('paraclinicals')->where('id', $id)->delete();
        
        return redirect()->route('paraclinical.index')
            ->with('success', config('app.success'))
            ->withInput();
    }
}
