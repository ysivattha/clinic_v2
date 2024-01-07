<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineLibrary;
use DB;
use DataTables;
use Auth;
class MedicineLibraryController extends Controller
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
        if(!check('medicine_library', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = MedicineLibrary::leftJoin('categories', 'medicine_libraries.category_id', 'categories.id')
                ->where('medicine_libraries.active', 1)
                ->orderBy('medicine_libraries.id', 'desc')
                ->select('medicine_libraries.*', 'categories.name as cname');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'medicine_libraries', 'medicine_library');
                    return $btn;
                })
                ->rawColumns(['action', 'check'])
                ->make(true);
        }
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        return view('medicine_libraries.index', $data);
    }
}
