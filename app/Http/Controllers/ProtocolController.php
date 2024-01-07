<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Protocol;
use DB;
use DataTables;
use Auth;
class ProtocolController extends Controller
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
        if(!check('protocol', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = Protocol::join('protocol_categories', 'protocols.protocol_category_id', 'protocol_categories.id')
                ->select('protocols.*', 'protocol_categories.name as cname')
                ->where('protocols.active', 1)
                ->orderBy('protocols.id', 'desc');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'protocols', 'protocol');
                    return $btn;
                })
                ->rawColumns(['action', 'check'])
                ->make(true);
        }
        $data['protocol_categories'] = DB::table('protocol_categories')->where('active', 1)->get();
        return view('protocols.index', $data);
    }
}
