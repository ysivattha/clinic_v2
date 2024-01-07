<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use DB;
use DataTables;
use Auth;
class ItemController extends Controller
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
        if(!check('item', 'l')){
            return view('permissions.no');
        }
        if($r->section==null){
            $data['section'] = '';
        $data['items'] = Item::LeftJoin('sections', 'sections.id', 'items.section_id')
                ->where('items.active', 1)
                ->orderBy('items.id', 'desc')
                ->select('items.*', 'sections.name as sname')
                ->paginate(config('app.row'));
    }else {
        $data['section'] = $r->section;
            $data['items'] = Item::LeftJoin('sections', 'sections.id', 'items.section_id')
            ->where('items.active', 1)
            ->orderBy('items.id', 'desc')
            ->where('section_id', $r->section)
            ->select('items.*', 'sections.name as sname')
            ->paginate(config('app.row'));
        }
            
        $data['sections'] = DB::table('sections')
            ->where('active', 1)
            ->get();
        return view('items.index', $data);
    }
    public function get_item($id) {
        $data = DB::table('items')
            ->where('section_id', $id)
            ->orderBy('name', 'asc')
            ->where('active', 1)
            ->get();
       return $data;
    }
    public function delete($id)
    {
        if(!check('item', 'd')){
            return view('permissions.no');
        }
       
        $i = DB::table('items')->where('id', $id)->update(['active'=>0]);
        if($i)
        {
           
            return redirect()->route('item.index')
                ->with('success', config('app.del_success'))
                ->withInput();
        }
        else{
            
            return redirect()->route('item.del_fail', $id)
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
}
