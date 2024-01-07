<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use DB;
use DataTables;
use Auth;
use DateTime;
class TreatmentController extends Controller
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
            if(!check('treatment', 'l')){
                return view('permissions.no');
            }
            $data['q'] = '';
            $data['date'] = '';
            $data['treatments'] = Treatment::join('customers',  'customers.id', 'treatments.patient_id')
                    ->leftJoin('users', 'users.id', 'treatments.doctor_id')
                    ->select(
                        'treatments.*', 
                        'customers.code', 
                        'customers.kh_first_name', 
                        'customers.kh_last_name', 
                        'customers.en_first_name', 
                        'customers.en_last_name', 
                        'users.last_name', 
                        'users.first_name',
                        'users.phone as phone'
                    )
                    ->orderBy('treatments.id', 'desc')
                    ->where('treatments.active', 1)
                    ->paginate(config('app.row'));
           
        return view('treatments.index',$data);
    }
    public function search(Request $r) {
        $q = $r->q;
        $data['q'] = $q;
        $data['date'] = $r->date;
        if($r->date!=null) {
        $data['treatments'] = DB::table('treatments')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->leftJoin('customers','customers.id', 'treatments.patient_id')
            ->select(
                'treatments.*', 
                'customers.code', 
                'customers.kh_first_name', 
                'customers.kh_last_name', 
                'customers.en_first_name', 
                'customers.en_last_name', 
                'users.last_name', 
                'customers.dob',
                'users.first_name',
                'users.phone as phone'
            )
            ->where('treatments.active', 1)
            ->where('treatments.date', $r->date)
            ->where(function($query) use ($q){
                $query->orWhere('customers.phone', 'like', "%{$q}%")
                    ->orWhere('customers.kh_first_name', 'like', "%{$q}%")
                    ->orWhere('treatments.diagnosis1', 'like', "%{$q}%")
                    ->orWhere('treatments.note', 'like', "%{$q}%")
                    ->orWhere('customers.en_first_name', 'like', "%{$q}%")
                    ->orWhere('customers.kh_last_name', 'like', "%{$q}%")
                    ->orWhere('customers.en_last_name', 'like', "%{$q}%")
                    ->orWhere('customers.code', 'like', "%{$q}%")
                    ->orWhere('customers.address', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(200);
        } else {
            $data['treatments'] = DB::table('treatments')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->leftJoin('customers','customers.id', 'treatments.patient_id')
            ->select(
                'treatments.*', 
                'customers.code', 
                'customers.kh_first_name', 
                'customers.kh_last_name', 
                'customers.en_first_name', 
                'customers.en_last_name', 
                'users.last_name', 
                'customers.dob',
                'users.first_name',
                'users.phone as phone'
            )
            ->where('treatments.active', 1)
            
            ->where(function($query) use ($q){
                $query->orWhere('customers.phone', 'like', "%{$q}%")
                    ->orWhere('customers.kh_first_name', 'like', "%{$q}%")
                    ->orWhere('treatments.diagnosis1', 'like', "%{$q}%")
                    ->orWhere('treatments.note', 'like', "%{$q}%")
                    ->orWhere('customers.en_first_name', 'like', "%{$q}%")
                    ->orWhere('customers.kh_last_name', 'like', "%{$q}%")
                    ->orWhere('customers.en_last_name', 'like', "%{$q}%")
                    ->orWhere('customers.code', 'like', "%{$q}%")
                    ->orWhere('customers.address', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(200);
         }
        return view('treatments.index', $data);
    }
    public function detail($id)
    {
        if(!check('treatment', 'l')){
            return view('permissions.no');
        }
        $data['requests'] = DB::table('requestchecks')
            ->where('active',1)
            ->orderBy('id', 'desc')
            ->get();
        $data['sections'] = DB::table('sections')
            ->where('active',1)
            ->orderBy('code', 'asc')
            ->get();
        $data['templates'] = DB::table('diagnosis_templates')
            ->leftJoin('sections', 'sections.id', 'diagnosis_templates.section_id')
            ->leftJoin('users', 'users.id', 'diagnosis_templates.created_by')
            ->where('diagnosis_templates.active',1)
            ->where('diagnosis_templates.status',Auth::user()->id)
            ->orWhere('diagnosis_templates.status', 0)
            ->select('diagnosis_templates.*', 'sections.name as dname', 'sections.code as dcode', 'users.first_name', 'users.last_name')
            ->orderBy('diagnosis_templates.id', 'desc')
            ->paginate(config('app.row'));
        $data['t'] = DB::table('treatments')
            ->join('customers',  'customers.id', 'treatments.patient_id')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->where('treatments.id', $id)
            ->select(
                'treatments.*', 
                'customers.code', 
                'customers.id as pid',
                'customers.phone as cphone',
                'customers.kh_first_name as pfirst_name', 
                'customers.kh_last_name as plast_name', 
                'customers.en_first_name as efirst_name', 
                'customers.en_last_name as elast_name', 
                'customers.h_code',
                'users.last_name as dlast_name', 
                'users.first_name as dfirst_name',
                'users.phone as phone'
            )
        ->where('treatments.active', 1)
        ->first();
        $data['tds'] = DB::table('treatment_detail')
                ->where('treatment_id', $id)
                ->where('active', 1)
                ->get();
            $data['patients'] = DB::table('customers')
                ->where('active', 1)
                ->get();
            $data['doctors'] = DB::table('users')
                ->where('active', 1)
                ->get();
            $data['categories'] = DB::table('categories')
                ->where('active', 1)
                ->get();
            $data['medicine_libraries'] = DB::table('medicine_libraries')
                ->where('active', 1)
                ->get();
        return view('treatments.detail', $data);
    }
    public function create(Request $r)
    {
        if(!check('patient', 'i')){
            return view('permissions.no');
        }


        $data['requests'] = DB::table('requestchecks')
            ->where('active',1)
            ->orderBy('id', 'desc')
            ->get();
        $data['patient_id'] = $r->query('patient_id');
        $data['patient'] = DB::table('customers')->find($data['patient_id']);
    //   dd($data['patient']);
        $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->get();
        $data['sections'] = DB::table('sections')
            ->where('active',1)
            ->orderBy('code', 'asc')
            ->get();
        $data['templates'] = DB::table('diagnosis_templates')
            ->leftJoin('sections', 'sections.id', 'diagnosis_templates.section_id')
            ->leftJoin('users', 'users.id', 'diagnosis_templates.created_by')
            ->where('diagnosis_templates.active',1)
            ->where('diagnosis_templates.status',Auth::user()->id)
            ->orWhere('diagnosis_templates.status', 0)
            ->select('diagnosis_templates.*', 'sections.name as dname', 'sections.code as dcode', 'users.first_name', 'users.last_name')
            ->orderBy('diagnosis_templates.id', 'desc')
            ->paginate(config('app.row'));
        $data['doctors'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        $data['medicine_libraries'] = DB::table('medicine_libraries')
            ->where('active', 1)
            ->get();
        return view('treatments.create', $data);
    }

    public function get_template($id, Request $r) {
        if(!check('treatment', 'i')){
            return view('permissions.no');
        }
        $data = DB::table('diagnosis_templates')
            ->where('section_id', $id)
            ->where('active', 1)
            ->get();
        return $data;
    }
    public function store(Request $r)
    {
        if(!check('treatment', 'i')){
            return view('permissions.no');
        }
       $doctor =  DB::table('users')->where('id', $r->doctor_id)->first();
        $data = array(
            'patient_id' => $r->patient_id,
            'doctor_id' => $r->doctor_id,
            'hospital_id' => $doctor->hospital_id,
            'date' => $r->date,
            'note' => $r->prescription_note,
          
            'diagnosis1' => $r->diagnosis1,
            'diagnosis' => $r->diagnosis,
            'created_by' => Auth::user()->id
        );
       
        $i = DB::table('treatments')->insertGetId($data);
        $code = sprintf('%04d', $i);
        DB::table('treatments')
        ->where('id', $i)
        ->update([
            'treatment_code' => $code,
        ]);
        if($i)
        {
            $medicines = $r->medicines;
            $name = $r->name;
            $description = $r->description;
            $note = $r->note;
            $m = 0;
            if($medicines!=null) {
                foreach($medicines as $medicine_id)
                {  
                    $product = DB::table('treatments')
                        ->where('id', $medicine_id)->where('active', 1)->first();
                    $treatment_detail = array(
                        'treatment_id' => $i,
                        'name' => $name[$m],
                        'description' => $description[$m],
                        'note' => $note[$m],
                        'created_by' => Auth::user()->id,
                    );
                    
                    DB::table('treatment_detail')->insertGetId($treatment_detail);
                    $m++;
                }
            }
            return redirect()->route('treatment.detail', $i)
            ->with('success', config('app.success'));
        }
        else {
            return redirect()->route('treatment.create')
            ->with('error', config('app.error'))
            ->withInput();
        }
      
    }

    public function save_medicine(Request $r) {
        if(!check('treatment', 'u')) {
            return view('permission.no');
        }
        $data = array(
            'treatment_id' => $r->treatment_id,
            'name' => $r->name,
            'description' => $r->description,
            'note' => $r->note,
            'created_by' => Auth::user()->id,
        );

        $i = DB::table('treatment_detail')->insert($data);
        if($i) {
            return redirect()->route('treatment.detail', $i)
            ->with('success', config('app.success'));
        }
        else {
            return redirect()->route('treatment.create')
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
    public function update_medicine(Request $r) {
        if(!check('treatment', 'u')) {
            return view('permission.no');
        }
        $data = array(
            'name' => $r->medicine,
            'description' => $r->description,
            'note' => $r->note,
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i')
        );

        $i = DB::table('treatment_detail')->where('id', $r->treatment_detail_id)->update($data);
        if($i) {
            return redirect()->route('treatment.detail', $i)
            ->with('success', config('app.success'));
        }
        else {
            return redirect()->route('treatment.create')
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
    public function update(Request $r)
    {
        if(!check('treatment', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'patient_id' => $r->patient_id,
            'doctor_id' => $r->doctor_id,
            'date' => $r->date,
            'diagnosis' => $r->diagnosis,
            'note' => $r->prescription_note,
            'diagnosis1' => $r->diagnosis1,
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i')
        );
        $i = DB::table('treatments')->where('id', $r->id)->update($data);
        if($i)
        {
            return redirect('treatment/detail/'.$r->id)
            ->with('success', config('app.success'));
        }
        else {
            return redirect('treatment/detail/'.$r->id)
            ->with('error', config('app.error'))
            ->withInput();
        }
      
    }
    public function print($id) {
    
        if(!check('treatment', 'l')){
            return view('permissions.no');
        }
        $data['t'] = DB::table('treatments')
            ->join('customers',  'customers.id', 'treatments.patient_id')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->where('treatments.id', $id)
            ->select(
                'treatments.*', 
                'customers.code', 
                'customers.en_first_name',
                'customers.en_last_name',
                'customers.id as pid',
                'customers.gender as gender',
                'customers.kh_first_name as kh_first_name', 
                'customers.kh_last_name as kh_last_name', 
                'users.last_name as dlast_name', 
                'users.first_name as dfirst_name',
                'users.phone as phone',
                'customers.dob',
                'customers.nationality',
                'customers.job',
                'customers.address',
            )
            ->where('treatments.active', 1)
            ->first();
    $data['request'] = DB::table('requestchecks')
            ->where('code', $data['t']->request_code)
            ->first();
    $data['hospital'] = DB::table('hospitals')
            ->where('id', $data['request']->hospital_id)
            ->first();

    $data['dob'] = \Carbon\Carbon::parse( $data['t']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
    $data['tds'] = DB::table('treatment_detail')
            ->where('treatment_id', $id)
            ->where('active', 1)
            ->get();
        $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->get();
        $data['doctors'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        $data['medicine_libraries'] = DB::table('medicine_libraries')
            ->where('active', 1)
            ->get();
            $data['com'] = DB::table('companies')
            ->where('id', 1)
            ->first();
            return view('treatments.print', $data);
    }

    public function print3($id) {
        if(!check('treatment', 'l')){
            return view('permissions.no');
        }
            
        $data['t'] = DB::table('treatments')
            ->join('customers',  'customers.id', 'treatments.patient_id')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->where('treatments.id', $id)
            ->select(
                'treatments.*', 
                'customers.code', 
                'treatments.treatment_code as treatment_code',
                'customers.id as pid',
                'customers.gender as gender',
                'customers.kh_first_name as kh_first_name', 
                'customers.kh_last_name as kh_last_name', 
                'customers.en_first_name as en_first_name', 
                'customers.en_last_name as en_last_name', 
                'users.last_name as dlast_name', 
                'users.first_name as dfirst_name',
                'users.phone as phone',
                'customers.dob',
                'customers.nationality',
                'customers.job',
                'customers.address',
            )
            ->where('treatments.active', 1)
            ->first();

    $data['dob'] = \Carbon\Carbon::parse( $data['t']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
    $data['tds'] = DB::table('treatment_detail')
            ->where('treatment_id', $id)
            ->where('active', 1)
            ->get();
        $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->get();
        $data['doctors'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['hospital'] = DB::table('hospitals')->where('id', $data['t']->hospital_id)
                ->first();
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        $data['medicine_libraries'] = DB::table('medicine_libraries')
            ->where('active', 1)
            ->get();
            $data['com'] = DB::table('companies')
            ->where('id', 1)
            ->first();
            return view('treatments.print', $data);
    }
    
    public function print2($id) {

        if(!check('treatment', 'l')){
            return view('permissions.no');
        }
        $data['t'] = DB::table('treatments')
            ->join('customers',  'customers.id', 'treatments.patient_id')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->where('treatments.id', $id)
            ->select(
                'treatments.*', 
                'customers.code', 
                'customers.id as pid',
                'customers.gender as gender',
                'customers.kh_first_name as kh_first_name', 
                'customers.kh_last_name as kh_last_name', 
                'customers.en_first_name as en_first_name', 
                'customers.en_last_name as en_last_name', 
                'users.last_name as dlast_name', 
                'users.first_name as dfirst_name',
                'users.phone as phone',
                'customers.dob',
                'customers.nationality',
                'customers.job',
                'customers.address',
            
                
            )
            ->where('treatments.active', 1)
            ->first();

           
            $data['hospital'] = DB::table('hospitals')->where('id', $data['t']->hospital_id)
            ->first();
    $data['dob'] = \Carbon\Carbon::parse( $data['t']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
    $data['tds'] = DB::table('treatment_detail')
            ->where('treatment_id', $id)
            ->where('active', 1)
            ->get();
        $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->get();
        $data['doctors'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        $data['medicine_libraries'] = DB::table('medicine_libraries')
            ->where('active', 1)
            ->get();
            $data['com'] = DB::table('companies')
            ->where('id', 1)
            ->first();
            return view('treatments.print-no-letterhead', $data);
    }
    

    public function delete($id) {
        $datetime = new DateTime();
        $i = DB::table('treatments')
        ->where('id', $id)
        ->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at' => $datetime]);
        if($i) {
            return redirect()->route('treatment.index')
            ->with('success', config('app.success'));
        } else {
            return redirect()->route('treatment.index')
            ->with('error', config('app.error'))
            ->withInput();
        } 
       
    }
    public function delete_td($id, Request $r) {
        $datetime = new DateTime();
        $i = DB::table('treatment_detail')
        ->where('id', $id)
        ->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at' => $datetime]);
        if($i) {
            return redirect()->route('treatment.detail', $r->query('treatment_id'))
            ->with('success', config('app.success'));
        } else {
            return redirect()->route('treatment.detail',  $r->query('treatment_id'))
            ->with('error', config('app.error'))
            ->withInput();
        } 
       
    }
    public function get_medicine_library($id) {
        $data = DB::table('medicine_libraries')
        ->where('category_id', $id)
        ->orderBy('name', 'asc')
        ->where('active', 1)
        ->get();
    return $data;
    }
    public function eget_medicine_library($id) {
        $data = DB::table('medicine_libraries')
        ->where('category_id', $id)
        ->orderBy('name', 'asc')
        ->where('active', 1)
        ->get();
    return $data;
    }

    public function get_request($id) {
        $data = DB::table('requestchecks')
        ->where('patient_id', $id)
        ->orderBy('id', 'desc')
        ->where('active', 1)
        ->get();
        return $data;
    }
}
