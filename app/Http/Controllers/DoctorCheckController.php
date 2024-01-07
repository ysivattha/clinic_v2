<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestDetail;
use DB;
use DataTables;
use Auth;
use Illuminate\Support\Arr;
class DoctorCheckController extends Controller
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
        if(!check('doctor_check', 'l')){
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
        $data['h']= $hid;
        $data['hospitals'] = DB::table('hospitals')->whereIn('id',$data['h_id'])->get();
        $data['sections'] = DB::table('sections')->whereIn('id',$data['s_id'])->get();

        $data['section'] = DB::table('sections')->whereIn('department_id',[auth()->user()->department_id])->get();
        $sec_id = Arr::pluck($data['section'], 'id');
     if($r->doctor_translate == null && $r->start_date == null && $r->end_date == null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
    {


        $data['start_date'] = '';
        $data['end_date'] = '';
        $data['keyword'] = $r->keyword;
        $keyword1 = $r->keyword;
        $data['section'] = $r->section_id;
        $data['status'] = $r->status;
        $data['h'] = $r->hospital_id;

        $data['doc_tran'] = $r->doctor_translate;
        $doc_tran1 =  $r->doctor_translate;
        $data['technicals'] = DB::table('request_details')
            ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
            ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
            ->where('requestchecks.active', 1)
            ->where('request_details.active', 1)
            ->where(function($query) use ($doc_tran1){
                $tdoc_tran = trim($doc_tran1);
                $query


                    ->orwhereNull('request_details.percent3')
                    ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



            })
//                ->where('request_details.date', '>=', $r->start_date)
//                ->where('request_details.date', '<=', $r->end_date)
            ->whereIn('requestchecks.hospital_id',$data['h'])
            ->whereIn('request_details.section_id', $data['section'])
            ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
            ->orderBy('request_details.id', 'desc')
            ->paginate(100000);
        return view('doctor_checks.index', $data);
        }
        if($r->doctor_translate == null && $r->start_date== null && $r->end_date== null && $r->section_id == null && $r->keyword== null && $r->hospital_id == null && $r->status == null)
        {


            $data['doc_tran'] = auth()->user()->id;
            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $data['section'] =  $sec_id;
            $data['status'] = $r->status;
            $doc_tran1 =  auth()->user()->id;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')

                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })

                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->whereIn('request_details.section_id', $sec_id)
                ->whereIn('requestchecks.hospital_id',$data['h_id'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(config('app.row'));

            return view('doctor_checks.index', $data);

        }

        if($r->doctor_translate != null && $r->start_date != null && $r->end_date != null && $r->section_id != null && $r->keyword != null && $r->hospital_id != null && $r->status != null)
        {

            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date ;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['status'] = $r->status;
            $data['section'] = $r->section_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
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
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status', $data['status'])
                ->whereIn('request_details.section_id', $data['section'])
                ->where('requestchecks.hospital_id',Auth()->user()->hospital_id)
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(config('app.row'));

            return view('doctor_checks.index', $data);

        }

        else if($r->doctor_translate != null && $r->start_date == null && $r->end_date== null  && $r->section_id != null && $r->keyword== null && $r->hospital_id != null && $r->status != null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')

                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status',$r->status)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        else if($r->doctor_translate == null && $r->start_date== null && $r->end_date== null  && $r->section_id != null && $r->keyword== null && $r->hospital_id != null && $r->status != null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')

                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status',$r->status)
//                ->where(function($query) use ($doc_tran1){
//                    $tdoc_tran = trim($doc_tran1);
//                    $query
//                        ->orWhere('request_details.percent3' , $tdoc_tran)
//                        ->orWhere('request_details.percent3' , "");
//
//
//                })
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //this one
        else if($r->doctor_translate == null && $r->start_date== null && $r->end_date== null  && $r->section_id != null && $r->keyword != null && $r->hospital_id != null && $r->status != null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
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
                ->where('request_details.request_status',$r->status)
//                ->where(function($query) use ($doc_tran1){
//                    $tdoc_tran = trim($doc_tran1);
//                    $query
//                        ->orWhere('request_details.percent3' , $tdoc_tran)
//                        ->orWhere('request_details.percent3' , "");
//
//
//                })
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //here
        else if($r->doctor_translate == null && $r->start_date != null && $r->end_date != null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status != null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status',$r->status)
//                ->where(function($query) use ($doc_tran1){
//                    $tdoc_tran = trim($doc_tran1);
//                    $query
//                        ->orWhere('request_details.percent3' , $tdoc_tran)
//                        ->orWhere('request_details.percent3' , "");
//
//
//                })
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
            //here 1
        else if($r->doctor_translate != null && $r->start_date != null && $r->end_date != null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status != null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.request_status',$r->status)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //here2
        else if($r->doctor_translate != null && $r->start_date == null && $r->end_date == null  && $r->section_id != null && $r->keyword != null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
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
//                ->where('request_details.request_status',$r->status)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })
//                ->where('request_details.date', '>=', $r->start_date)
//                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //here3
        else if($r->doctor_translate == null && $r->start_date == null && $r->end_date == null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
//                ->where('request_details.request_status',$r->status)
//                ->where(function($query) use ($doc_tran1){
//                    $tdoc_tran = trim($doc_tran1);
//                    $query
//                        ->orWhere('request_details.percent3' , $tdoc_tran)
//                        ->orWhere('request_details.percent3' , "");
//
//
//                })
//                ->where('request_details.date', '>=', $r->start_date)
//                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',[$r->hospital_id])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //here4
        else if($r->doctor_translate == null && $r->start_date == null && $r->end_date == null  && $r->section_id != null && $r->keyword != null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
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
//                ->where('request_details.request_status',$r->status)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query
                        ->orWhere('request_details.percent3' , $tdoc_tran)
                        ->orWhere('request_details.percent3' , "");


                })
//                ->where('request_details.date', '>=', $r->start_date)
//                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //here5
        else if($r->doctor_translate == null && $r->start_date != null && $r->end_date != null  && $r->section_id != null && $r->keyword != null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
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
//                ->where('request_details.request_status',$r->status)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query
                        ->orWhere('request_details.percent3' , $tdoc_tran)
                        ->orWhere('request_details.percent3' , "");


                })
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }
        //here6
        else if($r->doctor_translate != null && $r->start_date == null && $r->end_date == null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
//                ->where('request_details.request_status',$r->status)

//                ->where('request_details.date', '>=', $r->start_date)
//                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }


        else if($r->doctor_translate == null && $r->start_date != null && $r->end_date != null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;
            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);
        }

        else if($r->doctor_translate != null && $r->start_date == null && $r->end_date == null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
        {

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
//                ->where('request_details.request_status',$r->status)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })

                ->whereIn('requestchecks.hospital_id',$data['h'])
                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000);
            return view('doctor_checks.index', $data);


        }
        else if($r->doctor_translate == null && $r->start_date == null && $r->end_date == null  && $r->section_id == null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
        {


            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
//                ->where('request_details.request_status',$r->status)
//                ->where(function($query) use ($doc_tran1){
//                    $tdoc_tran = trim($doc_tran1);
//                    $query
//                        ->orWhere('request_details.percent3' , $tdoc_tran)
//                        ->orWhere('request_details.percent3' , "");
//
//
//                })

                ->whereIn('requestchecks.hospital_id',$data['h'])
//                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000000);
            return view('doctor_checks.index', $data);


        }

        else if($r->doctor_translate != null && $r->start_date != null && $r->end_date != null  && $r->section_id != null && $r->keyword == null && $r->hospital_id != null && $r->status == null)
        {


            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
//                ->leftJoin('customers', 'requestchecks.patient_id', 'customers.id')
//                ->where(function($query) use ($keyword1){
//                    $tkeyword = trim($keyword1);
//                    $query
//                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('hospital_reference', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
//                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
//
//                        ->orWhere(DB::raw("concat(customers.kh_first_name, ' ', customers.kh_last_name)"), 'LIKE', "%".$tkeyword."%")
//                        ->orWhere(DB::raw("concat(customers.en_first_name, ' ', customers.en_last_name)"), 'LIKE', "%".$tkeyword."%");
//
//                })
                ->where('requestchecks.active', 1)
                ->where('request_details.active', 1)
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })

                ->whereIn('requestchecks.hospital_id',$data['h'])
//                ->whereIn('request_details.section_id', $data['section'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000000);
            return view('doctor_checks.index', $data);


        }

        else if($r->doctor_translate != null && $r->start_date != null && $r->end_date != null  && $r->section_id == null && $r->keyword == null && $r->hospital_id == null && $r->status == null)
        {


            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })


//                ->whereIn('requestchecks.hospital_id',$data['h'])
//                ->whereIn('request_details.section_id', $data['section'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000000);
            return view('doctor_checks.index', $data);


        }

        else if($r->doctor_translate == null && $r->start_date != null && $r->end_date != null  && $r->section_id == null && $r->keyword == null && $r->hospital_id == null && $r->status == null)
        {


            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')



//                ->whereIn('requestchecks.hospital_id',$data['h'])
//                ->whereIn('request_details.section_id', $data['section'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000000);
            return view('doctor_checks.index', $data);


        }

        else if($r->doctor_translate == null && $r->start_date != null && $r->end_date != null  && $r->section_id == null && $r->keyword == null && $r->hospital_id == null && $r->status != null)
        {


            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')



//                ->whereIn('requestchecks.hospital_id',$data['h'])
//                ->whereIn('request_details.section_id', $data['section'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->where('request_details.request_status', $data['status'])
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000000);
            return view('doctor_checks.index', $data);


        }

        else if($r->doctor_translate != null && $r->start_date != null && $r->end_date != null  && $r->section_id == null && $r->keyword == null && $r->hospital_id == null && $r->status != null)
        {


            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['keyword'] = $r->keyword;
            $keyword1 = $r->keyword;
            $data['section'] = $r->section_id;
            $data['status'] = $r->status;
            $data['h'] = $r->hospital_id;

            $data['doc_tran'] = $r->doctor_translate;
            $doc_tran1 =  $r->doctor_translate;
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->join('hospitals','requestchecks.hospital_id','hospitals.id')
                ->where(function($query) use ($doc_tran1){
                    $tdoc_tran = trim($doc_tran1);
                    $query


                        ->orwhereNull('request_details.percent3')
                        ->orWhere('request_details.percent3' , 'like', "%{$tdoc_tran}%");



                })


//                ->whereIn('requestchecks.hospital_id',$data['h'])
//                ->whereIn('request_details.section_id', $data['section'])
                ->where('request_details.date', '>=', $r->start_date)
                ->where('request_details.date', '<=', $r->end_date)
                ->where('request_details.request_status',$r->status)
                ->select('request_details.*', 'requestchecks.patient_id','hospitals.name')
                ->orderBy('request_details.id', 'desc')
                ->paginate(100000000);
            return view('doctor_checks.index', $data);


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
        if(!check('doctor_check', 'l') || !check('request','d')){
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
            ->select('requestchecks.*', 'customers.id as pid', 'customers.kh_first_name as pfirst_name', 'customers.dob', 'customers.kh_last_name as plast_name', 'customers.phone', 'customers.gender')
            ->where('requestchecks.id',  $data['request_detail']->request_id)
            ->first();
        $data['dob'] = \Carbon\Carbon::parse( $data['request']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['doctors'] = DB::table('users')->where('active', 1)->get();
        $data['protocols'] = DB::table('protocols')->where('active',1)->get();
        $data['fils'] = DB::table('fils')->where('active',1)->get();

        $data['sections'] = DB::table('sections')->where('active',1)->orderBy('code')->get();
        $data['percent3'] = DB::table('users')->where('id', $data['request_detail']->percent3)->first();
        $data['percent3_approvor'] = DB::table('users')->where('id', $data['request_detail']->percent3_approvor)->first();
        $data['templates'] = DB::table('templates')
        ->where('section_id', $data['request_detail']->section_id)
        ->where('templates.created_by', Auth::user()->id)
        ->where('templates.active',1)
        ->orwhere('templates.status',1)
        ->where('templates.status', 2)  
        ->orderBy('templates.id', 'desc')
        ->get();

        $data['protocol_categories'] = DB::table('protocol_categories')->where('active', 1)->get();
        return view('doctor_checks.edit', $data);
        
    }

    public function edit_detail($id)
    {
        if(!check('doctor_check', 'l')){
            return view('permissions.no');
        }

        DB::table('request_details')->where('id', $id)->update(['request_status'=> 9]);
    
        $data['request_detail'] = DB::table('request_details')
            ->leftJoin('users', 'users.id', 'request_details.percent1')
            ->select('users.first_name as dfirst_name', 'users.last_name as dlast_name', 'users.phone', 'request_details.*')
            ->where('request_details.id', $id)
            ->where('request_details.active', 1)
            ->first();
        
        $data['request'] = DB::table('requestchecks')
            ->join('customers', 'customers.id', 'requestchecks.patient_id')
            ->select('requestchecks.*', 'customers.id as pid', 'customers.kh_first_name as pfirst_name', 'customers.dob', 'customers.kh_last_name as plast_name', 'customers.phone', 'customers.gender')
            ->where('requestchecks.id',  $data['request_detail']->request_id)
            ->first();
            $data['dob'] = \Carbon\Carbon::parse( $data['request']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['doctors'] = DB::table('users')->where('active', 1)->get();
        $data['protocols'] = DB::table('protocols')->where('active',1)->get();
        $data['fils'] = DB::table('fils')->where('active',1)->get();

        $data['sections'] = DB::table('sections')
            ->where('active', 1)
            ->get();
       
            $data['templates'] = DB::table('templates')
            ->where('section_id', $data['request_detail']->section_id)
            ->where('templates.created_by', Auth::user()->id)
            ->where('templates.active',1)
            ->orwhere('templates.status',1)
            ->where('templates.status', 2)  
            ->orderBy('templates.id', 'desc')
            ->get();

         
        $data['protocol_categories'] = DB::table('protocol_categories')->where('active', 1)->get();
        return view('doctor_checks.detail', $data); 
    }

    public function create($id)
    {
        if(!check('doctor_check', 'i')){
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
            ->select('requestchecks.*', 'customers.id as pid', 'customers.kh_first_name as pfirst_name', 'customers.dob', 'customers.kh_last_name as plast_name', 'customers.phone', 'customers.gender')
            ->where('requestchecks.id',  $data['request_detail']->request_id)
            ->first();
            $data['dob'] = \Carbon\Carbon::parse( $data['request']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['doctors'] = DB::table('users')->where('active', 1)->get();
        $data['protocols'] = DB::table('protocols')->where('active',1)->get();
        $data['fils'] = DB::table('fils')->where('active',1)->get();

        $data['sections'] = DB::table('sections')->where('active',1)->orderBy('code')->get();
        $data['percent3'] = DB::table('users')->where('id', $data['request_detail']->percent3)->first();
        $data['percent3_approvor'] = DB::table('users')->where('id', $data['request_detail']->percent3_approvor)->first();
        $data['templates'] = DB::table('templates')
            ->where('section_id', $data['request_detail']->section_id)
            ->where('templates.active',1)
            ->orderBy('templates.id', 'desc')
            ->get();

        $data['protocol_categories'] = DB::table('protocol_categories')->where('active', 1)->get();
   
        return view('doctor_checks.create', $data);
    }
   
    public function reviewing($id)
    {
        if(!check('doctor_check', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 9,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 

        $i = DB::table('request_details')->where('id', $id)->update($data);
        if($i) {
            return redirect('doctor-check/create/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('doctor-check/create/'.$id)
            ->with('error', config('app.error'));
        }
    }

    public function validated($id)
    {
        if(!check('doctor_check', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 11,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
        $request_detail = DB::table('request_details')
        ->where('id', $id)
        ->first();
        if($request_detail->doctor_description!=null) {
            $i = DB::table('request_details')->where('id', $id)->update($data);
            return redirect('doctor-check/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('doctor-check/detail/'.$id)
            ->with('error', config('app.error'));
        }
    }


    public function save($id, Request $r) {
        if(!check('doctor_check', 'i')){
            return view('permissions.no');
        }

        $data = array(
            'request_status' => 9,
            'percent3' => $r->percent3,
            'percent3_approvor' => $r->percent3_approvor,
            'doctor_description' => $r->description,
            'time_translate' => $r->time_translate,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);

        if ($r->input('submit') === 'save') {
            if($i) {
                return redirect('doctor-check/create/'.$id)
                    ->with('success', config('app.success'));
            } else {
        
                return redirect('doctor-check/create/'.$id)
                ->with('error', config('app.error'))
                ->withInput();
            }
        } elseif ($r->input('submit') === 'saveAndValidated') {
            return $this->validated($id);      
        }
    }

    public function get_template($id, Request $r) {
        if(!check('doctor_check', 'i')){
            return view('permissions.no');
        }
        $data = DB::table('templates')
            ->where('section_id', $id)
            ->orderBy('id', 'desc')
            ->where('active', 1)
            ->get();
       
        
        return $data;
    }
    public function update_status($id) {
        if(!check('doctor_check', 'i')){
            return view('permissions.no');
        }
        
        $data =  DB::table('request_details')->where('id', $id)->update(['request_status'=> 9]);
        
        return $data;
    }

    public function today() {
        if(!check('doctor_check', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->orderBy('code')->get();
        $data['start_date'] = "";
        $data['end_date'] = "";
        $data['section'] = '';
        $data['status'] = '';
        $data['keyword'] = '';
        $today = date('Y-m-d');
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->where('request_details.request_status', '>', 7)
            ->where('request_details.active', 1)
            ->where('request_details.date', '=',$today )
            ->select('request_details.*', 'requestchecks.patient_id')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('doctor_checks.tyw', $data);
    }

    public function yesterday() {
        if(!check('doctor_check', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->orderBy('code')->get();
        $data['start_date'] = "";
        $data['end_date'] = "";
        $data['keyword'] = '';
        $data['section'] = '';
        $data['status'] = '';
        $yesterday =  date('Y-m-d',strtotime("-1 days"));
        $data['technicals'] = DB::table('request_details')
            ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
            ->where('request_details.request_status', '>', 7)
            ->where('request_details.active', 1)
            ->where('request_details.date', '=',$yesterday)
            ->select('request_details.*', 'requestchecks.patient_id')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('doctor_checks.tyw', $data);
    }

    public function week() {
        if(!check('doctor_check', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->orderBy('code')->get();
          $data['start_date'] = "";
        $data['end_date'] = "";
        $data['section'] = '';
        $data['status'] = '';
        $data['keyword'] = '';
        $week =  date('Y-m-d',strtotime("-7 days"));
        $week2 =  date('Y-m-d',strtotime("-14 days"));
        $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
            ->where('request_details.active', 1)
            ->where('request_details.request_status', '>', 7)
            ->where('request_details.date', '<=',$week)
            ->where('request_details.date', '>=',$week2)
            ->select('request_details.*', 'requestchecks.patient_id')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('doctor_checks.tyw', $data);
    }
    public function print($id) {
        if(!check('doctor_check', 'l')){
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
        
        $data['hospital'] = DB::table('hospitals')
            ->where('id',$data['requestcheck']->hospital_id)
            ->where('active', 1)
            ->first();
       
        $data['patient'] = DB::table('customers')
            ->where('id', $data['requestcheck']->patient_id)
            ->first();
        $data['reciept'] = DB::table('users')->where('id', $data['requestcheck']->created_by)
            ->first();
        $data['dob'] = \Carbon\Carbon::parse( $data['patient']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');
        $data['percent3'] = DB::table('users')->where('id',$data['request_detail']->percent3)->first();
        $data['percent3_approvor'] = DB::table('users')->where('id',$data['request_detail']->percent3_approvor)->first();
      
        return view('doctor_checks.print2', $data);
    }
}
