<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_Hospital;
use App\Models\User_Section;
use Auth;
use DB;
use DataTables;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app()->setLocale(Auth::user()->language);
            return $next($request);
        });
        
    }

    public  function index(Request $r)
    {
        if(!check('user', 'l')){
            return view('permissions.no');
        }
        if ($r->ajax()) 
        {
            $data = User::join('roles', 'users.role_id', 'roles.id')
                ->leftJoin('positions', 'users.position_id', 'positions.id')
                ->leftJoin('departments', 'users.department_id', 'departments.id')
                ->leftJoin('hospitals', 'users.hospital_id', 'hospitals.id')
                ->where('users.active', 1)
                ->where('users.username', '!=', 'root')
                ->OrderBy('roles.name','ASC')
                ->select('users.*', 'roles.name as rname', 'departments.name as dname', 'hospitals.name as hname', 'positions.name as pname');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addColumn('photo', function($row){
                    $url = asset($row->photo);
                    $img = "<img src='{$url}' width='27'>";
                    return $img;
                })
                ->addColumn('signature', function($row){
                    $url2 = asset($row->signature);
                    $signature = "<img src='{$url2}' width='27'>";
                    return $signature;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'users', 'user');
                    return $btn;
                })
                
                ->rawColumns(['action', 'check', 'photo','signature'])
                ->make(true);
        }
        $data['roles'] = DB::table('roles')
            ->where('active', 1)
            ->get();
        $data['departments'] = DB::table('departments')
            ->where('active', 1)
            ->get();
        $data['hospitals'] = DB::table('hospitals')
            ->where('active', 1)
            ->get();
        $data['positions'] = DB::table('positions')
            ->where('active', 1)
            ->get();

        $data['sections'] = DB::table('sections')
            ->where('active', 1)
            ->get();
          
       
           
           
        return view("users.index", $data);
    }
   
    public function save(Request $r)
    {
        if(!check('user', 'i')){
            return 0;
        }
        $data = $r->except('_token', 'photo', 'tbl', 'per');
        $data['password'] = bcrypt($r->password);
        if($r->photo)
        {
            $data['photo'] = $r->file('photo')->store('uploads/users', 'custom');
        }
        if($r->signature)
        {
            $data['signature'] = $r->file('signature')->store('uploads/users/signatures', 'custom');
        }
        $i = DB::table('users')->insertGetId($data);
        if($i)
        {
           return 1;

        }
        else{
            return 0;
        } 
    }
	
	public function update(Request $r)
    {
        if(!check('user', 'u')){
            return 0;
        }

       
        $data = $r->except('_token', 'photo', 'tbl', 'per', 'id', 'password','mulhospital_id','mulsection_id');
        if($r->password!=null)
        {
            $data['password'] = bcrypt($r->password);
        }
        if($r->photo)
        {
            $data['photo'] = $r->file('photo')->store('uploads/users', 'custom');
        }
        if($r->signature)
        {
            $data['signature'] = $r->file('signature')->store('uploads/users/signatures', 'custom');
        }
        $i = DB::table('users')
            ->where('id', $r->id)
            ->update($data);
           


    

        if(!empty($r->mulhospital_id))
        {
        $user = DB::table('user_hospital')->where('user_id', $r->id)->get();
  
            if(count($user)<=0)
            {
                foreach($r->mulhospital_id as $id)
                {
             
                    $data_hos = 
                    [
                        'user_id'=>$r->id,
                        'hospital_id'=>$id
                    ];
                    $hid = DB::table('user_hospital')->insertGetId($data_hos);
                }
               
            }else
            {
                foreach($user as $u)
                {
                    User_Hospital::find($u->id)->delete();

                }

                foreach($r->mulhospital_id as $id)
                {
             
                    $data_hos = 
                    [
                        'user_id'=>$r->id,
                        'hospital_id'=>$id
                    ];
                    $hid = DB::table('user_hospital')->insertGetId($data_hos);
                }


            }
        }
           
            if(!empty($r->mulsection_id))
            {
                
                $sections = DB::table('user_section')->where('user_id', $r->id)->get();

                if(count($sections)<=0)
                {
                    foreach($r->mulsection_id as $sid)
                    {
                 
                        $data_section = 
                        [
                            'user_id'=>$r->id,
                            'section_id'=>$sid
                        ];
                        $sec_id = DB::table('user_section')->insertGetId($data_section);
                    }
                    return $sec_id;
                }else
                {
                    foreach($sections as $sec)
                    {
                        User_Section::find($sec->id)->delete();
                        
                    }
    
                    foreach($r->mulsection_id as $sid)
                    {
                 
                        $data_section = 
                        [
                            'user_id'=>$r->id,
                            'section_id'=>$sid
                        ];
                        $sec_id = DB::table('user_section')->insertGetId($data_section);
                    }
    
    
                }
            }

            return (int)$i;
            // if(count($user)<0)
            // {

            //     return false;
            // }
            // else{
            //     return true;
            // }
        //     $mul_hos[] = $r->mulhospital_id;
            
        //     foreach($mul_hos as $hid)
        //     {
             
        //         DB::table('user_hospital')->updateOrInsert(
        //             [
        //                 'user_id'=>$r->id,
        //                 'hospital_id'=>$hid
        //             ]
                    
                   
        //             );
        //     }

        //    return 1;
        
     
    }
   
    public function profile()
    {
        $id = Auth::user()->id;
        $data['user'] = DB::table("users")
            ->join('roles', 'users.role_id', 'roles.id')
            ->leftJoin('positions', 'users.position_id', 'positions.id')
            ->leftJoin('departments', 'users.department_id', 'departments.id')
            ->where('users.id', $id)
            ->select('users.*', 'roles.name as rname', 'departments.name as dname', 'positions.name as pname')
            ->first();
        return view("users.profile", $data);
    }
    public function save_profile(Request $r)
    {
        $id = Auth::user()->id;
        $data = array(
            'first_name' => $r->first_name,
            'last_name' => $r->last_name,
            'email' => $r->email,
            'gender' => $r->gender,
            'phone' => $r->phone,
        );
        if($r->password)
        {
            $data['password'] = bcrypt($r->password);
        }
        if($r->photo)
        {
            $data['photo'] = $r->file('photo')->store('uploads/users', 'custom');
        }
        $i = DB::table('users')
            ->where('id', $id)
            ->update($data);
        if($i)
        {
            return redirect()->route('user.profile')
                ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('user.profile')
                ->with('error', config('app.error'));
        }
    }
	
    public function change_lang($id)
    {
        $uid = Auth::user()->id;
        DB::table('users')
            ->where('id', $uid)
            ->update([
                'language' => $id
            ]);
        return redirect()->back();
    }
    // user sign out
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
