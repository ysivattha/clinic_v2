<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use DB;
use DataTables;
use Auth;
class HospitalController extends Controller
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
        if(!check('hospital', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = Hospital::where('active', 1)
                ->orderBy('id', 'desc');
            return Datatables::of($data)
            ->addColumn('check', function($row){
                $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                return $input;
            })
            ->addColumn('logo', function($row){
                $url = asset($row->logo);
                $b = "<img src='{$url}' width='27'>";
                return $b;
            })
            ->addColumn('letterhead', function($row){
                $url = asset($row->letterhead);
                $b = "<img src='{$url}' width='27'>";
                return $b;
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = btn_actions($row->id, 'hospitals', 'hospital');
                return $btn;
            })
            ->rawColumns(['action', 'check', 'logo','letterhead'])
            ->make(true);
        }
        return view('hospitals.index');
    }
    public function save(Request $r)
    {
        if(!check('hospital', 'i')){
            return view('permissions.no');
        }
        $data = $r->except('_token', 'logo', 'tbl', 'per');
        $data['created_by'] = Auth::user()->id;
        if($r->logo)
        {
            $data['logo'] = $r->file('logo')->store('uploads/hospitals', 'custom');
        }
        if($r->letterhead)
        {
            $data['letterhead'] = $r->file('letterhead')->store('uploads/letterheads', 'custom');
        }
        $i = DB::table('hospitals')->insertGetId($data);
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
        if(!check('hospital', 'u')){
            return 0;
        }
        $data = $r->except('_token', 'photo', 'tbl', 'per', 'id');
        $data['updated_by'] = Auth::user()->id;
        $data['updated_at'] = Date('Y-m-d h:i:s');
        if($r->logo)
        {
            $data['logo'] = $r->file('logo')->store('uploads/hospitals', 'custom');
        }
        if($r->letterhead)
        {
            $data['letterhead'] = $r->file('letterhead')->store('uploads/letterheads', 'custom');
        }
        $i = DB::table('hospitals')
            ->where('id', $r->id)
            ->update($data);
        if($i){
           return 1;
        }
        else{
            return 0;
        }
    }
}
