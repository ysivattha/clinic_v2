<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disease;
use DB;
use DataTables;
use Auth;
class DiseaseController extends Controller
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
        if(!check('disease', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = Disease::where('active', 1)
                ->orderBy('id', 'desc');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'diseases', 'disease');
                    return $btn;
                })
                ->rawColumns(['action', 'check'])
                ->make(true);
        }
        return view('diseases.index');
    }

   
}
