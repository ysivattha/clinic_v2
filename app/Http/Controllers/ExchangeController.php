<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;
use DataTables;
use Auth;
class ExchangeController extends Controller
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
        if(!check('exchange', 'l')){
            return view('permissions.no');
        }
        
        $data['active'] = DB::table('exchanges')
        ->leftJoin('users', 'exchanges.create_by', 'users.id')
        ->where('exchanges.active', 1)
        ->select('exchanges.*', 'users.first_name', 'users.last_name')
        ->get();
    $data['old'] = DB::table('exchanges')
        ->leftJoin('users', 'exchanges.create_by', 'users.id')
        ->where('exchanges.active', 0)
    
        ->orderBy('id', 'desc')
        ->select('exchanges.*', 'users.first_name', 'users.last_name')
        ->paginate(config('app.row'));
        return view('exchanges.index', $data);
    }
    public function edit($id)
    {
        if(!check('exchange', 'u')){
            return view('permissions.no');
        }
        $data['exc'] = DB::table('exchanges')->find($id);
        return view('exchanges.edit', $data);
    }
    public function update(Request $r, $id)
    {
        if(!check('exchange', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'dollar' => 1,
            'khr' => $r->khr,
            'create_by' => Auth::user()->id,
            'create_at' => date('Y-m-d H:i')
        );
        $i = DB::table('exchanges')->insert($data);
        if($i)
        {
            DB::table('exchanges')
                ->where('id', $id)
                ->update(['active'=>0]);
            return redirect('exchange')
                ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('exchange.edit', $id)
                ->with('error', config('app.error'));
        }
    }
   
}
