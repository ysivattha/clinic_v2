<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app()->setLocale(Auth::user()->language);
            return $next($request);
        });
    }
    // load company index
    public function index()
    {
        if(!check('company', 'l')){
            return view('permissions.no');
        }
        $data['company'] = DB::table('companies')
            ->where('id', 1)
            ->first();
        $data['branches'] = DB::table('branches')
            ->get();
        return view('companies.index', $data);
    }
    // edit company form
    public function edit($id)
    {
        if(!check('company', 'u')){
            return view('permissions.no');
        }
        $data['company'] = DB::table('companies')
            ->where('id', $id)
            ->first();

        return view('companies.edit', $data);
    }
    public function save(Request $r)
    {
        if(!check('company', 'u')){
            return view('permissions.no');
        }
        $data = $r->except('_token', 'id', 'logo');

        if($r->hasFile('logo'))
        {
            $data['logo'] = $r->file('logo')->store('uploads/logos/', 'custom');
        }
        $i = DB::table('companies')->where('id', $r->id)->update($data);
        if($i)
        {
            return redirect('company')
                ->with('success', config('app.success'));
        }
        else{
            return redirect('company/edit/'.$r->id)
                ->with('error', config('app.error'));
        }
    }
}
