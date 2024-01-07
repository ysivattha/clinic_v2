<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestDetail;
use DB;
use DataTables;
use Auth;
class TechnicalController extends Controller
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
        if(!check('technical_check', 'l')){
            return view('permissions.no');
        }

        // $data['hospitals']=DB::table('hospitals')->where('active',1)->get();

        $hid=[];
        $sid=[];
         $data['u']=DB::table('user_hospital')->where('user_id',Auth()->user()->id)->get();
         $data['s']=DB::table('user_section')->where('user_id',Auth()->user()->id)->get();
 
        foreach($data['u'] as $h_id)
        {
          
                $hid[]=$h_id->hospital_id;
            
        }
        foreach($data['s'] as $s_id)
        {
          
                $sid[]=$s_id->section_id;
            
        }

        $data['h_id']=$hid;
        $data['s_id']=$sid;
       $data['h']= [Auth()->user()->hospital_id];
       $data['hospitals'] = DB::table('hospitals')->whereIn('id',$data['h_id'])->get();
       $data['sections'] = DB::table('sections')->whereIn('id',$data['s_id'])->get();

       if($r->start_date== null && $r->end_date== null && $r->section_id == null && $r->keyword== null && $r->hospital_id == null && $r->status == null)
       {

                 $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
  
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
                ->where('requestchecks.hospital_id',Auth()->user()->hospital_id)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(config('app.row'));

            return view('technical_checks.index', $data);

       }

       if($r->start_date != null && $r->end_date != null && $r->section_id != null && $r->keyword != null && $r->hospital_id != null && $r->status != null)
       {
                 $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
        
      
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['section'] = $r->section_id;
      
  
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                        
                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->where('requestchecks.hospital_id',Auth()->user()->hospital_id)
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(config('app.row'));

            return view('technical_checks.index', $data);

       }
       
       else if($r->start_date== null && $r->end_date== null  && $r->section_id == null && $r->keyword== null && $r->hospital_id != null && $r->status == null)
       {
            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
       else if($r->start_date == null && $r->end_date== null  && $r->keyword== null && $r->section_id != null && $r->hospital_id != null && $r->status == null)
       {
      
            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date != null && $r->end_date != null  && $r->keyword == null && $r->section_id == null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])

                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date != null && $r->end_date != null  && $r->keyword == null && $r->section_id != null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date != null && $r->end_date != null  && $r->keyword == null && $r->section_id == null && $r->hospital_id == null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
      
   
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date != null && $r->end_date != null  && $r->keyword == null && $r->section_id == null && $r->hospital_id != null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
   
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

        else if($r->start_date == null && $r->end_date == null  && $r->keyword == null && $r->section_id == null && $r->hospital_id != null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->whereIn('requestchecks.hospital_id',$data['h'])
                
             
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
       else if($r->start_date == null && $r->end_date == null  && $r->keyword == null && $r->section_id == null && $r->hospital_id == null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
             
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
   
       else if($r->start_date == null && $r->end_date == null  && $r->keyword != null && $r->section_id == null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                        
                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
       
       else if($r->start_date != null && $r->end_date != null  && $r->keyword != null && $r->section_id == null && $r->hospital_id == null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                        
                })
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
       //ling
       else if($r->start_date != null && $r->end_date != null  && $r->keyword != null && $r->section_id == null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                        
                })
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date != null && $r->end_date != null  && $r->keyword == null && $r->section_id != null && $r->hospital_id != null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       
       else if($r->start_date == null && $r->end_date == null  && $r->keyword == null && $r->section_id != null && $r->hospital_id == null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
   
    
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
      
       else if($r->start_date == null && $r->end_date == null  && $r->keyword == null && $r->section_id != null && $r->hospital_id != null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
           
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
   
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date == null && $r->end_date == null  && $r->keyword != null && $r->section_id != null && $r->hospital_id != null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1= $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }
//works
       else if($r->start_date != null && $r->end_date != null  && $r->keyword != null && $r->section_id != null && $r->hospital_id != null && $r->status != null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1= $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date != null && $r->end_date != null  && $r->keyword != null && $r->section_id != null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1= $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
       
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date == null && $r->end_date == null  && $r->keyword != null && $r->section_id == null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
     
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                        
                })
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id',$data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }

       else if($r->start_date == null && $r->end_date == null  && $r->keyword != null && $r->section_id != null && $r->hospital_id != null && $r->status == null)
       {

      
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', '>=', 5)
     
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                     
                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
                        
                })
                ->whereIn('requestchecks.hospital_id',$data['h'])
   
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
                return view('technical_checks.index', $data);
       }


    //    $sic=[];
    //    $data['u']=DB::table('user_hospital')->where('user_id',Auth()->user()->id)->get();

        // if($r->start_date== null && $r->end_date== null && $r->section== null && $r->keyword== null && $r->hospital_id == null )  {
        //     dd('here');
        //     $data['start_date'] = '';
        //     $data['end_date'] = '';
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.active', 1)
        //         ->where('request_details.request_status', '>=', 5)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(config('app.row'));
                
        //     return view('technical_checks.index', $data);
        // }
       
        // elseif($r->start_date!= null && $r->end_date!= null && $r->section== null && $r->keyword== null && $r->status!= null)  {
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     if($r->status == 8) {
        //         $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.request_status', '>=', 5)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where('request_details.request_status', '>=',$r->status)
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     } else {
        //         $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.request_status', '>=', 5)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where('request_details.request_status', $r->status)
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     }
        //     return view('technical_checks.index', $data);
        // }
        // elseif($r->start_date!= null && $r->end_date!= null && $r->section!= null && $r->keyword== null && $r->status== null)  {
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.request_status', '>=', 5)
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where('request_details.section_name', $r->section)
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     return view('technical_checks.index', $data);
        // } elseif($r->start_date!= null && $r->end_date!= null && $r->section!= null  && $r->keyword != null && $r->status== null )  {
            
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $keyword1 = $r->keyword;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.request_status', '>=', 5)
        //         ->where('request_details.active', 1)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where('request_details.section_name', $r->section)
        //         ->where(function($query) use ($keyword1){
        //             $tkeyword = trim($keyword1);
        //             $query
        //             ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
        //         })
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     return view('technical_checks.index', $data);
        // } elseif($r->start_date!= null && $r->end_date!= null && $r->section== null  && $r->keyword != null && $r->status!= null)  {
    
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $keyword1 = $r->keyword;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.request_status', '>=', 5)
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where('request_details.request_status', $r->status)
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     return view('technical_checks.index', $data);
        // } 
        // elseif($r->start_date!= null && $r->end_date!= null && $r->section!= null  && $r->keyword != null && $r->status!= null)  {
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $keyword1 = $r->keyword;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.request_status', '>=', 5)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where('request_details.request_status', $r->status)
        //         ->where(function($query) use ($keyword1){
        //             $tkeyword = trim($keyword1);
        //             $query
        //             ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
        //         })
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     return view('technical_checks.index', $data);
        // } 
        // elseif($r->start_date!= null && $r->end_date!= null && $r->section== null  && $r->keyword != null && $r->status== null)  {
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $keyword1 = $r->keyword;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.request_status', '>=', 5)
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->where('request_details.date', '>=', $r->start_date)
        //         ->where('request_details.date', '<=', $r->end_date)
        //         ->where(function($query) use ($keyword1){
        //             $tkeyword = trim($keyword1);
        //             $query
        //             ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
        //             ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
        //         })
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //     return view('technical_checks.index', $data);
        // } 
        // else {
         
        //     $data['start_date'] = $r->start_date;
        //     $data['end_date'] = $r->end_date;
        //     $data['keyword'] = $r->keyword;
        //     $data['section'] = $r->section;
        //     $data['status'] = $r->status;
        //     $data['technicals'] = DB::table('request_details')
        //         ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
        //         ->join('hospitals','requestchecks.hospital_id','hospitals.id')
        //         ->where('request_details.request_status', '>=', 5)
        //         ->where('request_details.active', 1)
        //         ->where('requestchecks.active', 1)
        //         ->where('request_details.date', '>=', $data['start_date'])
        //         ->where('request_details.date', '<=', $data['end_date'])
        //         ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
        //         ->orderBy('request_details.id', 'desc')
        //         ->paginate(1000);
        //         return view('technical_checks.index', $data);
        // }
    }
    
    public function detail($id)
    {
        if(!check('technical_check', 'l')){
            return view('permissions.no');
        }
        $data['request_detail'] = DB::table('request_details')
            ->leftJoin('users', 'users.id', 'request_details.percent1')
            ->select('users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone', 'request_details.*')
            ->where('request_details.id', $id)
            ->where('request_details.active', 1)
            ->first();
        $data['request'] = DB::table('requestchecks')
            ->join('customers', 'customers.id', 'requestchecks.patient_id')
            ->select('requestchecks.*', 'customers.id as pid','customers.en_first_name as en_first_name','customers.en_last_name as en_last_name', 'customers.kh_first_name as pfirst_name', 'customers.dob', 'customers.kh_last_name as plast_name', 'customers.phone', 'customers.gender')
            ->where('requestchecks.id',  $data['request_detail']->request_id)
            ->first();
            $data['dob'] = \Carbon\Carbon::parse( $data['request']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['doctors'] = DB::table('users')->where('active', 1)->get();
        $data['protocols'] = DB::table('protocols')->where('active',1)->get();
        $data['fils'] = DB::table('fils')->where('active',1)->get();
        $data['protocol_categories'] = DB::table('protocol_categories')->where('active', 1)->get();
        $data['sections']=DB::table('sections')->where('active',1)->where('id',$data['request_detail']->section_id)->first();
        $data['departments']=DB::table('departments')->where('active',1)->get();
        $data['users']=DB::table('users')->where('active',1)->where('department_id', $data['sections']->department_id)->get();
        return view('technical_checks.detail', $data);
    }
   
    public function confirm($id)
    {
        if(!check('technical_check', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 6,
            'updated_at' => date('Y-m-d H:i'),
            'time_technical' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 

        $i = DB::table('request_details')->where('id', $id)->update($data);
        if($i) {
            return redirect('technical/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('technical/detail/'.$id)
            ->with('error', config('app.error'));
        }
    }

    public function done($id)
    {
        if(!check('technical_check', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 8,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
        $request_detail = DB::table('request_details')
        ->where('id', $id)
        ->where('active', 1)
        ->first();
        if($request_detail->percent2==null) {
            return redirect('technical/detail/'.$id)
            ->with('error', config('app.error'));   
        } else {
        $i = DB::table('request_details')->where('id', $id)->update($data);
        if($i) {
            return redirect('technical/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('technical/detail/'.$id)
            ->with('error', config('app.error'));
        }
    }
    }

    public function canceled($id) {
        if(!check('front_office', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 0,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
     
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
        if($i) {
            return redirect('/technical/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('technical/detail/'.$id)
            ->with('error', config('app.error'));
        }
       
    }
    public function today() {
        if(!check('request', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->get();
        $data['start_date'] = '';
        $data['end_date'] = '';
        $data['section'] = '';
        $data['keyword'] = '';
        $data['status'] = '';
        $today = date('Y-m-d');
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
            ->where('request_details.active', 1)
            ->where('request_details.request_status', '>=', 5)
            ->where('request_details.date', '=',$today )
            ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('technical_checks.index', $data);
    }

    public function yesterday() {
        if(!check('request', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->get();
        $data['start_date'] = '';
        $data['end_date'] = '';
        $data['section'] = '';
        $data['status'] = '';
        $data['keyword'] = '';
        $yesterday =  date('Y-m-d',strtotime("-1 days"));
        $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
            ->where('request_details.active', 1)
            ->where('request_details.date', '=',$yesterday)
            ->where('request_details.request_status', '>=', 5)
            ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('technical_checks.index', $data);
    }

    public function week() {
        if(!check('request', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->get();
        $data['start_date'] = '';
        $data['end_date'] = '';
        $data['section'] = '';
        $data['status'] = '';
        $data['keyword'] = '';
        $week =  date('Y-m-d',strtotime("-7 days"));
        $week2 =  date('Y-m-d',strtotime("-14 days"));
        $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
            ->where('request_details.active', 1)
            ->where('request_details.request_status', '>=', 5)
            ->where('request_details.date', '<=',$week)
            ->where('request_details.date', '>=',$week2)
            ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('technical_checks.index', $data);
    }

   
    public function delete_request($id) {
        if(!check('technical_check', 'd')){
            return view('permissions.no');
        }
        $data = array(
            'active' => 0,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
     
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
        if($i) {
            return redirect('/technical')
            ->with('success', config('app.del_success'));
        } else {
            return redirect('technical/detail/'.$id)
            ->with('error', config('app.error'));
        }
       
    }

    public function delete($id)
    {
        if(!check('technical_check', 'u')){
            return view('permissions.no');
        }
        $protocol = null;
        $con = null;
        $note = null;
        $percent2 = null;
        $fil_qty = null;
        $percent2_ass = null;
        $fil_size = null;

        $data = array(
            'protocol_qty' => $protocol,
            'fil_size' => $fil_size,
            'fil_qty' => $fil_qty,
            'percent2' => $percent2,
            'percent2_ass' => $percent2_ass,
            'contrast_enhancement' => $con,
            'technical_note' => $note,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        );

        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
            DB::table('request_protocols')->where('request_detail_id', $id)
            ->update(['active'=> 0, 'updated_at' => date('Y-m-d H:i'),'updated_by' => Auth::user()->id,]);
            if($i) {
        
                return redirect('technical/detail/'.$id)
                    ->with('success', config('app.success'));
            } else {
                return redirect('technical/detail/'.$id)
                ->with('error', config('app.error'))
                ->withInput();
            }    
    }
    public function save($id, Request $r) {
        if(!check('technical_check', 'i')){
            return view('permissions.no');
        }
        if($r->contrast_enhancement=='មិនប្រើ' || $r->contrast_enhancement=='No') {
            $con = $r->contrast_enhancement;
        } else {
           
           
            if($r->po==null) {
                $po = '';
            } else {
                $po = $r->po.', ';
            }
          
            if($r->iv==null) {
                $iv = '';
            } else {
                $iv = $r->iv.', ';;
            }
           
            if($r->enema==null) {
                $enema = '';
            } else {
                $enema = $r->enema.', ';
            }
          
            if($r->other==null) {
                $other = '';
            } else {
                $other = $r->other;
            }
            if($r->quantity==null) {
                $quantity = '';
            } else {
                $quantity = $r->quantity;
            }
            $con = $r->contrast_enhancement.' : '.$po.$iv.$enema.$other.$quantity;
        
        }   
        $protocol = $r->protocol;
        if($protocol == null) {
            $protocol = 0;
        } else {
            $protocol = count($r->protocol);
        }
        $data = array(
            'protocol_qty' => $protocol,
            'time_technical' => $r->time_technical,
            'fil_size' => $r->fil_size,
            'fil_qty' => $r->fil_qty,
            'percent2' => $r->percent2,
            'percent2_ass' => $r->percent2_ass,
            'protocol_category_name' => trim($r->protocol_category_name),
            'contrast_enhancement' => $con,
            'technical_note' => $r->note,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
            $protocols = $r->protocol;
            $j = 0; 
            if($protocols !== null) {
                
                foreach($protocols as $pro) {
                   $data2 = array(
                    'name' => $r->protocol[$j],
                    'request_detail_id' => $id,
                    'created_by' => Auth::user()->id
                    );
        
                    DB::table('request_protocols')->insert($data2);
                    
                    $j++;
                }
            }
        if($i) {
        
            return redirect('technical/detail/'.$id)
                ->with('success', config('app.success'));
        } else {
    
            return redirect('technical/detail/'.$id)
            ->with('error', config('app.error'))
            ->withInput();
        }
    
    }
    public function get_protocol($id) {
        if(!check('technical_check', 'i')){
            return view('permissions.no');
        }
        $date = DB::table('protocols')
            ->where('protocol_category_id', $id)
            ->where('active', 1)
            ->get();
        return $date;
    }
    public function print($id) {
        if(!check('technical_check', 'l')){
            return view('permissions.no');
        }
        $data['request_detail'] = DB::table('request_details')
            ->where('id', $id)
            ->where('active', 1)
            ->first();
        $data['request'] = DB::table('requestchecks')
            ->where('id', $data['request_detail']->request_id)
            ->first();
        $data['patient'] = DB::table('customers')
            ->where('id', $data['request']->patient_id)
            ->where('active', 1)
            ->first();
        $data['dob'] = \Carbon\Carbon::parse( $data['patient']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  

        $data['percent2'] = DB::table('users')
            ->where('id',  $data['request_detail']->percent2)
            ->first();
        $data['percent2_ass'] = DB::table('users')
            ->where('id',  $data['request_detail']->percent2_ass)
            ->first();
        $data['request_protocol']  = DB::table('request_protocols')
            ->where('request_detail_id', $data['request_detail']->id)
            ->where('active', 1)
            ->get();
        $data['hospital'] = DB::table('hospitals')
            ->where('id',$data['request']->hospital_id)
            ->where('active', 1)
            ->first();
       
        return view('technical_checks.print', $data);
    }

    public function send_doctor($id) {
        if(!check('request', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 8,
            'updated_at' => date('Y-m-d H:i'),
            'time_translate' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        );
        $request_detail = DB::table('request_details')
            ->where('id', $id)
            ->first();
        $request = DB::table('requestchecks')
            ->where('id', $request_detail->request_id)
            ->first();
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
        if($i) {
            return redirect('technical/detail/'.$id)
                ->with('success', config('app.success'));
        } else {
            return redirect('technical/detail/'.$id)
                ->with('error', config('app.error'));
        }

    }

    public function send_doc(Request  $r) {


        if(!check('request', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 8,
            'updated_at' => date('Y-m-d H:i'),
            'time_translate' => date('Y-m-d H:i'),
            'percent3'=>$r->translate,
            'updated_by' => Auth::user()->id,
        );
        $request_detail = DB::table('request_details')
            ->where('id', $r->id)
            ->first();
        $request = DB::table('requestchecks')
            ->where('id', $request_detail->request_id)
            ->first();
        $i = DB::table('request_details')
            ->where('id', $r->id)
            ->update($data);
        if($i) {
            return redirect('technical/detail/'.$r->id)
                ->with('success', config('app.success'));
        } else {
            return redirect('technical/detail/'.$r->id)
                ->with('error', config('app.error'));
        }

    }
}
