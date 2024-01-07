<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use DB;
use DataTables;
use Auth;
class PatientController extends Controller
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
        if(!check('patient', 'l')){
            return view('permissions.no');
        }
        
        $data['q'] = '';
        $data['h']= Auth()->user()->hospital_id;
      
        $data['patients'] = Customer::where('active', 1)
        ->where('hospital_id', Auth::user()->hospital_id)
        ->orderBy('customers.id', 'desc')
        ->paginate(config('app.row'));

        $data['hospital'] = DB::table('hospitals')->get();

       
        
        return view('patients.index', $data);
    }
    public function search(Request $r) {
     
     
        $q = $r->q;
        $h = $r->hospital_id;
   
        $data['h']=$h;
  
     
        $data['q'] = $q;
        $data['hospital'] = DB::table('hospitals')->get();

        if($data['h'] != "")
        {
            $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->Where('hospital_id',$h)
            ->where(function($query) use ($q){
                $query->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('kh_first_name', 'like', "%{$q}%")
                    ->orWhere('en_first_name', 'like', "%{$q}%")
                    ->orWhere('kh_last_name', 'like', "%{$q}%")
                    ->orWhere('en_last_name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('h_code','like',"%{$q}%")
                    ->orWhere('note','like',"%{$q}%");                 
            })
           
            ->orderBy('id', 'desc')
            ->paginate(200);
        }else{
            $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->where(function($query) use ($q){
                $query->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('kh_first_name', 'like', "%{$q}%")
                    ->orWhere('en_first_name', 'like', "%{$q}%")
                    ->orWhere('kh_last_name', 'like', "%{$q}%")
                    ->orWhere('en_last_name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('h_code','like',"%{$q}%")  
                    ->orWhere('note','like',"%{$q}%");                
            })
            ->orderBy('id', 'desc')
            ->paginate(200);
        }

        
         
        return view('patients.index', $data);
    }
    public function detail($id)
    {
        if(!check('patient', 'l')){
            return view('permissions.no');
        }
        $data['p'] = DB::table('customers')->find($id);

        $data['hospital'] = DB::table('hospitals')->select('name')->find( $data['p']->hospital_id);

      
        $data['dob'] = \Carbon\Carbon::parse( $data['p']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['pgs'] = DB::table('customer_pregnancies')
            ->where('customer_id', $id)
            ->where('active', 1)
            ->get();
        $data['appointments'] = DB::table('appointments')
            ->leftJoin('users', 'users.id', 'appointments.doctor_id')
            ->where('appointments.active',1)
            ->where('appointments.patient_id',$data['p']->id)
            ->select('appointments.*', 'users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone')
            ->orderBy('appointments.id', 'desc')
            ->get();
        $data['users'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['treatments'] = DB::table('treatments')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->where('treatments.patient_id', $data['p']->id)
            ->where('treatments.active', 1)
            ->select('treatments.*','users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone')
            ->orderBy('treatments.id', 'desc')
            ->get();
        $data['diseases'] = DB::table('customer_diseases')
            ->where('active', 1)
            ->where('customer_id', $id)
            ->get();
        $data['request_details'] = DB::table('request_details')
            ->rightJoin('requestchecks', 'request_details.request_id', 'requestchecks.id')
            ->where('requestchecks.patient_id',$data['p']->id)
            ->orderBy('request_details.id', 'desc')
            ->where('request_details.active', 1)
            ->select('request_details.*')
            ->get();
        

        $data['paraclinicals'] = DB::table('paraclinicals')
            ->where('paraclinicals.active', 1)
            ->where('paraclinicals.patient_id', $data['p']->id)
            ->orderBy('paraclinicals.id', 'desc')
            ->get();
        $data['invoices'] = DB::table('invoices')
            ->leftJoin('customers', 'customers.id', 'invoices.patient_id')
            
            ->orderBy('invoices.id', 'desc')
            ->select('invoices.*', 'customers.kh_first_name as first_name', 'customers.kh_last_name as last_name', 'customers.code')
            ->where('invoices.active', 1)
            ->where('invoices.patient_id', $id)
            ->get();
        return view('patients.detail', $data);
    }
    public function summary($id)
    {
        if(!check('patient', 'l')){
            return view('permissions.no');
        }
        $data['p'] = DB::table('customers')->find($id);
      
        $data['hospital'] = DB::table('hospitals')->select('name')->find( $data['p']->hospital_id);

       
    
       

        $data['dob'] = \Carbon\Carbon::parse( $data['p']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['pgs'] = DB::table('customer_pregnancies')
            ->where('customer_id', $id)
            ->where('active', 1)
            ->get();
        $data['diseases'] = DB::table('customer_diseases')
            ->where('active', 1)
            ->where('customer_id', $id)
            ->get();
        $data['appointments'] = DB::table('appointments')
            ->leftJoin('users', 'users.id', 'appointments.doctor_id')
            ->where('appointments.active',1)
            ->where('appointments.patient_id',$data['p']->id)
            ->select('appointments.*', 'users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone')
            ->orderBy('appointments.id', 'desc')
            ->get();
        $data['users'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['treatments'] = DB::table('treatments')
            ->leftJoin('users', 'users.id', 'treatments.doctor_id')
            ->where('treatments.patient_id', $data['p']->id)
            ->where('treatments.active', 1)
            ->select('treatments.*','users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone')
            ->orderBy('treatments.id', 'desc')
            ->get();
            $data['request_details'] = DB::table('request_details')
            ->rightJoin('requestchecks', 'request_details.request_id', 'requestchecks.id')
            ->where('requestchecks.patient_id',$data['p']->id)
            ->orderBy('request_details.id', 'desc')
            ->select('request_details.*')
            ->where('request_details.active', 1)
            ->get();
        
        $data['paraclinicals'] = DB::table('paraclinicals')
            ->where('paraclinicals.active', 1)
            ->where('paraclinicals.patient_id', $data['p']->id)
            ->orderBy('paraclinicals.id', 'desc')
            ->get();
        $data['invoices'] = DB::table('invoices')
            ->leftJoin('customers', 'customers.id', 'invoices.patient_id')
            ->orderBy('invoices.id', 'desc')
            ->select('invoices.*', 'customers.kh_first_name as first_name', 'customers.kh_last_name as last_name', 'customers.code')
            ->where('invoices.active', 1)
            ->where('invoices.patient_id', $id)
            ->get();
        return view('patients.summary', $data);
    }
    public function create()
    {
        if(!check('patient', 'i')){
            return view('permissions.no');
        }
        $data['diseases'] = DB::table('diseases')
            ->where('active', 1)
            ->orderBy('name')
            ->get();
        $data['hospitals']=DB::table('hospitals')->get();
        
        
     
       
        return view('patients.create', $data);
    }

    public function save_appointment(Request $r) {
        if(!check('appointment', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'patient_id' => $r->id,
            'doctor_id' => $r->doctor_id,
            'meet_date' => $r->meet_date,
            'meet_time' => $r->meet_time,
            'topic' => $r->topic,
            'description' => $r->description,            
            'created_by' => Auth::user()->id
        );
        $i = DB::table('appointments')->insert($data);
        if($i) {
            return redirect('patient/summary/'.$r->id .'?tab=3')
            ->with('success', config('app.success'))
            ->withInput();
        } else {
            return redirect('request/summary/'.$r->id .'?tab=3')
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
    public function store(Request $r)
    {
        if(!check('patient', 'i')){
            return view('permissions.no');
        }

        $r->validate([
            'dob'=>'date'
 
        ],
        [
            'dob.date' => 'Date of Birth invalid format',
        ]);
        //back to old
        $data = array(
            'kh_first_name' => $r->kh_first_name,
            'kh_last_name' => $r->kh_last_name,
            'en_first_name' => $r->en_first_name,
            'en_last_name' => $r->en_last_name,
            'gender' => $r->gender,
            'dob' => $r->dob,
            'address' => $r->address,
            'phone' => $r->phone,
            'job' => $r->job,
            'nationality' => $r->nationality,
            'note' => $r->note,
            'blood' => $r->blood,
            'reference' => $r->reference,
            'reference_phone' => $r->reference_phone,
            'social' => $r->social,
            'child_number' => $r->child_number,
            'born_number' => $r->born_number,
            'h_code' => $r->h_code,
            'hospital_id' =>$r->hospital_id,
            'created_by' => Auth::user()->id
        );

     
      
        if($r->photo)
        {
            $data['photo'] = $r->file('photo')->store('uploads/patients', 'custom');
        }
        $i = DB::table('customers')->insertGetId($data);
        if($r->input_disease!=null) {
        
            DB::table('customers')
                ->where('id', $i)
                ->update([
                    'other_disease' => $r->input_disease
                ]);
        }
        if($i)
        {
            $code = "P" . sprintf('%04d', $i);
            DB::table('customers')
                ->where('id', $i)
                ->update([
                    'code' => $code
                ]);
            // add disease
            if($r->ch=='yes')
            {
                $diseases = $r->diseases;
                if($diseases!=null) {
                    foreach($diseases as $d)
                    {
                        DB::table('customer_diseases')
                            ->insert([
                                'customer_id' => $i,
                                'disease_id' => $d
                            ]);
                    }
                }
            }
            // add pregnancy
            if($r->pg=='yes')
            {
                $dd = array(
                    'customer_id' => $i,
                    'note' => $r->pg_note
                );
                if($r->last_monthly_blood)
                {
                    $d2 = date_create(date('Y-m-d'));
                    $d1 = date_create($r->last_monthly_blood);
                    $d = date_diff($d1, $d2);
                    $y = $d->y;
                    $m = $d->m;
                    $d = $d->d;
                    $total = $y*365 + $m*30 + $d;
                    $w = (int)($total/7);
                    $day = ($total/7 - $w)*7;
                    $dd['week'] = $w;
                    $dd['day'] = $day;
                    $to_born = date('Y-m-d', strtotime($r->last_monthly_blood . " + 280 days"));
                    $dd['date_born'] = $to_born;
                    $dd['last_monthly_blood'] = $r->last_monthly_blood;
                }
                elseif($r->week || $r->day)
                {
                    $total = $r->week * 7 + $r->day;
                    $remain = 280 - $total;
                    $to_born = date('Y-m-d', strtotime(date('Y-m-d') . " + {$remain} days"));
                    $dd['week'] = $r->week;
                    $dd['day'] = $r->day;
                    $dd['date_born'] = $to_born;
                }
                elseif($r->date_born)
                {
                    $dd['date_born'] = $r->date_born;
                    $d1 = date_create(date('Y-m-d'));
                    $d2 = date_create($r->date_born);
                    $d = date_diff($d1, $d2);
                    $y = $d->y;
                    $m = $d->m;
                    $d = $d->d;
                    $total = $y*365 + $m*30 + $d;
                    $remain = 280 - $total;
                    $w = (int)($remain/7);
                    $day = ($remain/7 - $w) * 7;
                    $dd['week'] = $w;
                    $dd['day'] = $day;
                }
                DB::table('customer_pregnancies')->insert($dd);
            }
            return redirect()->route('patient.summary', $i);
        }
        else{
            return redirect()->route('patient.create')
                ->with('error', config('app.error'))
                ->withInput();
        }
    }
    public function delete_pregnancy($id)
    {
        if(!check('patient', 'u')){
            return view('permissions.no');
        }
        $pg = DB::table('customer_pregnancies')->find($id);
        DB::table('customer_pregnancies')
            ->where('id', $id)
            ->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
        return redirect()->route('patient.summary', $pg->customer_id .'?tab=2')
        ->with('success', config('app.del_success'));
    }
      
    public function delete_invoice($id)
    {
        if(!check('invoice', 'd')){
            return view('permissions.no');
        }
        $i = DB::table('invoices')->where('id', $id)->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
        $invoice = DB::table('invoices')->where('id', $id)->first();
        
        if($i) {
            DB::table('requestchecks')->where('code', $invoice->reference_no)->update(['is_invoiced' => 1, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
            return $i;
        }
      
    }
    public function delete($id) {
        if(!check('invoice', 'd')){
            return view('permissions.no');
        }
       

       $i = DB::table('customers')
            ->where('id', $id)
            ->update(['active'=>0,'updated_by'=>Auth::user()->id,'updated_at'=>date('Y-m-d H:i')]);
            if($i) {
               
                return redirect()->route('patient.index')
                    ->with('success', config('app.del_success'))
                    ->withInput();
            } else {
                return redirect()->route('patient.index')
                ->with('error', config('app.del_fail'))
                ->withInput();
            }
    }
    // delete appointment real time on patient
    public function delete_appointment($id)
    {
        if(!check('appointment', 'd')){
            return view('permissions.no');
        }
        $i = DB::table('appointments')->where('id', $id)->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
        return $i;
    }
 // delete appointment real time on patient
 public function delete_request($id)
 {
     if(!check('request', 'd')){
         return view('permissions.no');
     }
     $i = DB::table('request_details')->where('id', $id)->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
     return $i;
 }
     // delete treatment real time on patient
     public function delete_treatment($id)
     {
         if(!check('treatment', 'd')){
             return view('permissions.no');
         }
         $i = DB::table('treatments')->where('id', $id)->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
         return $i;
     }
 
    public function save_pregnancy(Request $r)
    {
        if(!check('patient', 'u')){
            return view('permissions.no');
        }
       
        DB::table('customer_pregnancies')->insert([
            'customer_id' => $r->id,
            'pregnancy' => $r->pregnancy,
            'date' => $r->date,
            'created_by' => Auth::user()->id
        ]);
        return redirect()->route('patient.summary', $r->id . '?tab=2')
        ->with('success', config('app.success'));
    }
    public function save_disease(Request $r)
    {
        if(!check('patient', 'u')){
            return view('permissions.no');
        }
            DB::table('customer_diseases')
            ->where('id', $r->customer_id)
            ->insert([
                'customer_id' => $r->customer_id,
                'disease' => $r->input_disease,
                'date' => $r->date,
                'created_by' => Auth::user()->id
            ]);
            return redirect()->route('patient.summary', $r->customer_id .'?tab=1')
            ->with('success', config('app.successs'));
    }
    public function delete_disease($id)
    {
        if(!check('patient', 'd')){
            return view('permissions.no');
        }
        $pg = DB::table('customer_diseases')->find($id);
        DB::table('customer_diseases')
            ->where('id', $id)
            ->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
        // return redirect()->route('patient.summary', $pg->customer_id )
        // ->with('success', config('app.del_success'));
        return redirect()->back()->with('success', config('app.del_success'));
    }
    public function delete_pdisease($id)
    {
        if(!check('patient', 'd')){
            return view('permissions.no');
        }
        DB::table('customers')
            ->where('id', $id)
            ->update(['other_disease'=>null]);
        return redirect()->route('patient.summary', $id);
    }

    public function delete_paraclinical($id)
    {
        if(!check('paraclinical', 'd')){
            return view('permissions.no');
        }
        if(!check('paraclinical', 'd')){
            return view('permissions.no');
        }
        $i = DB::table('paraclinicals')->where('id', $id)->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
        return $i;
    }
    public function edit($id)
    {
        if(!check('patient', 'u')){
            return view('permissions.no');
        }
        $data['p'] = DB::table('customers')->find($id);
        $data['hospitals'] = DB::table('hospitals')->get();
        return view('patients.edit', $data);
    }
    public function update(Request $r, $id)
    {
        if(!check('patient', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'kh_first_name' => $r->kh_first_name,
            'kh_last_name' => $r->kh_last_name,
            'en_first_name' => $r->en_first_name,
            'en_last_name' => $r->en_last_name,
            'gender' => $r->gender,
            'dob' => $r->dob,
            'address' => $r->address,
            'phone' => $r->phone,
            'job' => $r->job,
            'nationality' => $r->nationality,
            'note' => $r->note,
            'blood' => $r->blood,
            'reference' => $r->reference,
            'reference_phone' => $r->reference_phone,
            'social' => $r->social,
            'child_number' => $r->child_number,
            'born_number' => $r->born_number,
            'h_code' => $r->h_code,
            'hospital_id' =>$r->hospital_id,
            'updated_by' => Auth::user()->id
        );
        if($r->photo)
        {
            $data['photo'] = $r->file('photo')->store('uploads/patients', 'custom');
        }
        $i = DB::table('customers')
            ->where('id', $id)
            ->update($data);
        if($i)
        {
            return redirect()->route('patient.summary', $id)
                ->with('success', config('app.success'));
        }
        else{
            return redirect()->route('patient.edit', $id)
                ->with('error', config('app.error'));
        }
    }
    public function result_print($id) {
        if(!check('patient', 'l')){
            return view('permissions.no');
        }
        $data['request_detail'] = DB::table('request_details')
            ->leftJoin('users', 'users.id', 'request_details.percent1')
            ->select('users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone', 'request_details.*')
            ->where('request_details.id', $id)
            ->where('request_details.active', 1)
            ->first();
          
        $data['requestcheck'] = DB::table('requestchecks')
            ->where('active',1)
            ->where('id', $data['request_detail']->request_id)
            ->first();
        $data['reciept'] = DB::table('users')->where('id', $data['requestcheck']->created_by)
            ->first();
        $data['hospital'] = DB::table('hospitals')
            ->where('id',$data['requestcheck']->hospital_id)
            ->where('active', 1)
            ->first();
       
        $data['patient'] = DB::table('customers')
            ->where('id', $data['requestcheck']->patient_id)
            ->first();
        $data['dob'] = \Carbon\Carbon::parse( $data['patient']->dob)->diff($data['requestcheck']->created_at)->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');
        $data['percent3'] = DB::table('users')->where('id',$data['request_detail']->percent3)->first();
        $data['percent3_approvor'] = DB::table('users')->where('id',$data['request_detail']->percent3_approvor)->first();
 
        return view('patients.result-print', $data);
    }
    public function get_request($id)
    {
        $data= DB::table('requestchecks')
            ->where('active', 1)
            ->where('is_invoiced', 1)
            ->where('patient_id', $id)
            ->get();
        return $data;
    }
    public function get_invoice_unpaid($id)
    {
        $data= DB::table('invoices')
            ->where('active', 1)
            ->where('patient_id', $id)
            ->where('due_amount', '>', 0)
            ->get();

        
        return $data;
    }
    public function get_disease($id)
    {
        $data = DB::table('customer_diseases')->where('id', $id)->first();
        return json_encode($data);
    }
    public function get_para($id)
    {
        $data = DB::table('paraclinicals')->where('id', $id)->first();
        return json_encode($data);
    }
    public function update_disease(Request $r)
    {
        $data = array(
            'date' => $r->date,
            'disease' => $r->input_disease,
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i')
        );
        $i = DB::table('customer_diseases')->where('id',$r->disease_id)->update($data);
        if($i){
            return redirect()->route('patient.summary',$r->customer_id)
                    ->with('success', config('app.success'));
        }else {
            return redirect()->route('patient.summary',$r->customer_id)
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
    public function get_pregnancy($id)
    {

        $data = DB::table('customer_pregnancies')->where('id', $id)->first();
        return json_encode($data);
    }

    public function update_para(Request $r) {
        $data = array(
            'patient_id' => $r->patient_id,
            'date' => $r->paraclinical_date,
            'title' => $r->paraclinical_title,
            'note' => $r->paraclinical_note,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id
        );
        if($r->paraclinical_result)
        {
            $data['result'] = $r->file('paraclinical_result')->store('paraclinicals', 'custom');
        }
        $i = DB::table('paraclinicals')->where('id',$r->para_id)->update($data);
        if($i){
            return redirect()->route('patient.summary',$r->patient_id)
                    ->with('success', config('app.success'));
        }else {
            return redirect()->route('patient.summary',$r->patient_id)
            ->with('error', config('app.error'))
            ->withInput();
        }  
    }
    public function update_pregnancy(Request $r)
    {
        $data = array(
            'date' => $r->date,
            'pregnancy' => $r->pregnancy,
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i')
        );
        $i = DB::table('customer_pregnancies')->where('id',$r->pregnancy_id)->update($data);
        if($i){
            return redirect()->route('patient.summary',$r->customer_id .'?tab=2')
                    ->with('success', config('app.success'));
        }else {
            return redirect()->route('patient.summary',$r->customer_id .'?tab=2')
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
    public function paraclinical_save(Request $r) {
        $data = array(
            'patient_id' => $r->patient_id,
            'date' => $r->paraclinical_date,
            'title' => $r->paraclinical_title,
            'note' => $r->paraclinical_note,
            'created_by' => Auth::user()->id
        );

        if($r->paraclinical_result)
        {
            $data['result'] = $r->file('paraclinical_result')->store('paraclinicals', 'custom');
        }
        $i = DB::table('paraclinicals')->insert($data);
        if($i){
            return redirect()->route('patient.summary',$r->patient_id .'?tab=6')
                    ->with('success', config('app.success'));
        }else {
            return redirect()->route('patient.summary',$r->patient_id . '?tab=6')
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
}
