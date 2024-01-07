<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use DB;
use DataTables;
use Auth;
class SectionController extends Controller
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
        if(!check('section', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = Section::LeftJoin('departments', 'sections.department_id', 'departments.id')
                ->where('sections.active', 1)
                ->orderBy('id','desc')
                ->select('sections.*', 'departments.name as dname');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'sections', 'section');
                    return $btn;
                })
                ->rawColumns(['action', 'check'])
                ->make(true);
        }
        $data['departments'] = DB::table('departments')
            ->where('active', 1)
            ->get();
        return view('sections.index', $data);
    }
    public function get_section($id) {
        $data = DB::table('sections')
            ->where('department_id', $id)
            ->orderBy('name', 'asc')
            ->where('active', 1)
            ->get();
       return $data;
    }
}
