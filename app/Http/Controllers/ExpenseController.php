<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use DB;
use DataTables;
use Auth;
class ExpenseController extends Controller
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
        if(!check('expense', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $data = Expense::where('active', 1)
                ->orderBy('id', 'desc');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'expenses', 'expense');
                    return $btn;
                })
                ->rawColumns(['action', 'check'])
                ->make(true);
        }
        $data = Expense::where('active', 1);
        return view('expenses.index');
    }

   
}
