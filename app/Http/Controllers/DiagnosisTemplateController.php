<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosisTemplate;
use DB;
use DataTables;
use Auth;
class DiagnosisTemplateController extends Controller
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
        if(!check('diagnosis_template', 'l')){
            return view('permissions.no');
        }
        $data['keyworld'] = $r->keyword;
        $keyword1 = $r->keyword;
        if($r->keyword==null) {
            $data['diagnosis_templates'] = DB::table('diagnosis_templates')
            ->leftJoin('sections', 'sections.id', 'diagnosis_templates.section_id')
            ->leftJoin('users', 'users.id', 'diagnosis_templates.created_by')
            ->where('diagnosis_templates.active',1)
            ->where('diagnosis_templates.status',Auth::user()->id)
            ->orWhere('diagnosis_templates.status', 0)
            ->select('diagnosis_templates.*', 'sections.name as dname', 'sections.code as dcode', 'users.first_name', 'users.last_name')
            ->orderBy('diagnosis_templates.id', 'desc')
            ->paginate(config('app.row'));
        } else {
            $data['diagnosis_templates'] = DB::table('diagnosis_templates')
            ->leftJoin('sections', 'sections.id', 'diagnosis_templates.section_id')
            ->join('users', 'users.id', 'diagnosis_templates.created_by')
            ->where('diagnosis_templates.active',1)
            ->where(function($query) use ($keyword1){
                $tkeyword = trim($keyword1);
                $query
                    ->orWhere('diagnosis_templates.code', 'like', "%{$tkeyword}%")
                    ->orWhere('diagnosis_templates.title', 'like', "%{$tkeyword}%")
                    ->orWhere('sections.name', 'like', "%{$tkeyword}%");
            })
            ->select('diagnosis_templates.*', 'sections.name as dname', 'sections.code as dcode', 'users.first_name', 'users.last_name')
            ->orderBy('diagnosis_templates.id', 'desc')
            ->paginate(1000);
        }
     
        return view('diagnosis_templates.index', $data);
    } 

    public function create()
    {
        if(!check('diagnosis_template', 'i')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')
            ->where('active',1)
            ->orderBy('id', 'desc')
            ->get();
        
        return view('diagnosis_templates.create', $data);
    } 
    public function save(Request $r)
    {
        if(!check('diagnosis_template', 'i')){
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

        $i = DB::table('diagnosis_templates')->insertGetId($data);
        if($i){
           return redirect()->route('diagnosis_template.detail',$i)
            ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('diagnosis_template.detail',$i)
                ->with('error', config('app.error'));
        }
    }

    public function edit($id)
    {
        if(!check('diagnosis_template', 'u')){
            return view('permissions.no');
        }
        $data['tem'] = DB::table('diagnosis_templates')->where('active',1)->where('id',$id)->first();
        $data['sections'] = DB::table('sections')
            ->where('active',1)
            ->orderBy('id', 'desc')
            ->get();
        return view('diagnosis_templates.edit', $data);
    }


    public function detail($id)
    {
        if(!check('diagnosis_template', 'l')){
            return view('permissions.no');
        }
        $data['tem'] = DB::table('diagnosis_templates')
            ->leftJoin('sections', 'sections.id', 'diagnosis_templates.section_id')
            ->select('diagnosis_templates.*', 'sections.name as dname', 'sections.code as dcode')
            ->where('diagnosis_templates.active',1)
            ->where('diagnosis_templates.id',$id)
            ->first();
        return view('diagnosis_templates.detail', $data);
    }

    public function update(Request $r)
    {
        if(!check('diagnosis_template', 'u')){
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
 
        $i = DB::table('diagnosis_templates')->where('id', $r->id)->update($data);
        if($i){
            return redirect()->route('diagnosis_template.edit', $r->id)
                ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('diagnosis_template.edit', $r->id)
                ->with('error', config('app.error'));
        }
    }

    public function delete(Request $r)
    {
        if(!check('diagnosis_templates', 'd')){
            return view('permissions.no');
        }
        DB::table('diagnosis_templates')
            ->where('id', $r->id)
            ->update(['active'=>0]);
        
        return redirect('diagnosis_templates')
            ->with('success', config('app.del_success'));
    }
}
