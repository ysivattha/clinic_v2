<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;
use Auth;
use DateTime;
class TemplateController extends Controller
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
        if(!check('template', 'l')){
            return view('permissions.no');
        }
        $data['keyworld'] = $r->keyword;
        $keyword1 = $r->keyword;
        if($r->keyword==null) {
            $data['templates'] = DB::table('templates')
            ->leftJoin('sections', 'sections.id', 'templates.section_id')
            ->leftJoin('users', 'users.id', 'templates.created_by')
            ->where('templates.active',1)
            ->where('templates.status',Auth::user()->id)
            ->orWhere('templates.status', 0)
            ->select('templates.*', 'sections.name as dname', 'sections.code as dcode', 'users.first_name', 'users.last_name')
            ->orderBy('templates.id', 'desc')
            ->paginate(config('app.row'));
        } else {
            $data['templates'] = DB::table('templates')
            ->leftJoin('sections', 'sections.id', 'templates.section_id')
            ->join('users', 'users.id', 'templates.created_by')
            ->where('templates.active',1)
            ->where(function($query) use ($keyword1){
                $tkeyword = trim($keyword1);
                $query
                    ->orWhere('templates.code', 'like', "%{$tkeyword}%")
                    ->orWhere('templates.title', 'like', "%{$tkeyword}%")
                    ->orWhere('sections.name', 'like', "%{$tkeyword}%");
            })
            ->select('templates.*', 'sections.name as dname', 'sections.code as dcode', 'users.first_name', 'users.last_name')
            ->orderBy('templates.id', 'desc')
            ->paginate(config('app.row'));
        }
     
        return view('templates.index', $data);
    } 

    public function create()
    {
        if(!check('template', 'i')){
            return view('permissions.no');
        }
    
        $data['sections'] = DB::table('sections')
            ->where('active',1)
            ->orderBy('id', 'desc')
            ->get();
        return view('templates.create', $data);
    } 
    public function save(Request $r)
    {
        if(!check('template', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'title' => $r->title,
            'code' => $r->code,
            'description' => $r->description,
            'status' => $r->status,
            'section_id' => $r->section_id,
            'created_by' => Auth::user()->id,
        );
 
        $i = DB::table('templates')->insertGetId($data);
        if($i){
           return redirect()->route('template.edit',$i)
            ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('template.edit',$i)
                ->with('error', config('app.error'));
        }
    }

    public function edit($id)
    {
        if(!check('template', 'u')){
            return view('permissions.no');
        }
        $data['tem'] = DB::table('templates')->where('active',1)->where('id',$id)->first();
        $data['sections'] = DB::table('sections')
            ->where('active',1)
            ->orderBy('id', 'desc')
            ->get();
        return view('templates.edit', $data);
    }


    public function detail($id)
    {
        if(!check('template', 'l')){
            return view('permissions.no');
        }
        $data['tem'] = DB::table('templates')
            ->leftJoin('sections', 'sections.id', 'templates.section_id')
            ->select('templates.*', 'sections.name as dname', 'sections.code as dcode')
            ->where('templates.active',1)
            ->where('templates.id',$id)
            ->first();
        return view('templates.detail', $data);
    }

    public function update(Request $r)
    {
        if(!check('template', 'u')){
            return view('permissions.no');
        }
        $r->validate([
            'title' => 'required',
        ]);
        $data = array(
            'title' => $r->title,
            'code' => $r->code,
            'description' => $r->description,
            'status' => $r->status,
            'section_id' => $r->section_id,
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i')
        );
 
        $i = DB::table('templates')->where('id', $r->id)->update($data);
        if($i){
            return redirect()->route('template.edit', $r->id)
                ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('template.edit', $r->id)
                ->with('error', config('app.error'));
        }
    }

    public function delete(Request $r)
    {
        if(!check('template', 'd')){
            return view('permissions.no');
        }
        DB::table('templates')
            ->where('id', $r->id)
            ->update(['active'=>0]);
        
        return redirect('template')
            ->with('success', config('app.del_success'));
    }
}
