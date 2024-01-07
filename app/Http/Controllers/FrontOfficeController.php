<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;
class FrontOfficeController extends Controller
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
        if(!check('front_office', 'l')){
            return view('permissions.no');
        }
            $data['sections'] = DB::table('sections')->where('active',1)->get();

            if($r->start_date== null && $r->end_date== null && $r->section== null && $r->keyword== null && $r->status==null )  {
                $data['start_date'] = "";
                $data['end_date'] ="";
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
                $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id','requestchecks.code')
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(config('app.row'));
                return view('front_offices.index', $data);
            } elseif($r->start_date!= null && $r->end_date!= null && $r->section!= null && $r->keyword== null && $r->status==null)  {
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
                $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->where('request_details.section_id', $r->section)
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                return view('front_offices.index', $data);
            } elseif($r->start_date!= null && $r->end_date!= null && $r->section!= null  && $r->keyword != null && $r->status==null )  {
                $data['status'] = $r->status;
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $keyword1 = $r->keyword;
                $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->where('request_details.section_id', $r->section)
                    ->where(function($query) use ($keyword1){
                        $tkeyword = trim($keyword1);
                        $query
                        ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
                    })
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                return view('front_offices.index', $data);
            } elseif($r->start_date!= null && $r->end_date!= null && $r->section==null  && $r->keyword != null && $r->status!=null )  {
             
            $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
                $keyword1 = $r->keyword;
                $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('requestchecks.active', 1)
                    ->where('request_details.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.request_status', $r->status)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->where(function($query) use ($keyword1){
                        $tkeyword = trim($keyword1);
                        $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
                    })
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                return view('front_offices.index', $data);
            } 
            elseif($r->start_date!= null && $r->end_date!= null && $r->section==null  && $r->keyword != null && $r->status==null )  {
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
                $keyword1 = $r->keyword;
               
                $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->select('request_details.*','requestchecks.patient_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->where(function($query) use ($keyword1){
                        $tkeyword = trim($keyword1);
                    
                        $query->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
                    })
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                return view('front_offices.index', $data);
            } 
            elseif($r->start_date!= null && $r->end_date!= null && $r->section!==null  && $r->keyword == null && $r->status!=null )  {

                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
                $keyword1 = $r->keyword;
                $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('requestchecks.active', 1)
                    ->where('request_details.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.request_status', $r->status)
                    ->where('request_details.section_id', $r->section)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                return view('front_offices.index', $data);
            } 
            elseif($r->start_date!= null && $r->end_date!=null && $r->section==null  && $r->keyword==null && $r->status!=null )  {
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
    
                if($r->status==9) {
                    $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.request_status', $r->status)
                    ->orWhere('request_details.request_status', 10)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                } else {
                    $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('requestchecks.active', 1)
                    ->where('request_details.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.request_status', $r->status)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                }
               
                return view('front_offices.index', $data);
            } 
            elseif($r->start_date!= null && $r->end_date!=null && $r->section!==null && $r->keyword!=null && $r->status!=null)  {
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
                $keyword1 = $r->keyword;
                if($r->status==9) {
                    $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.request_status', $r->status)
                    ->where('request_details.section_id', $r->section)
                    ->orWhere('request_details.request_status', 10)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->where(function($query) use ($keyword1){
                        $tkeyword = trim($keyword1);
                        $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
                    })
                    ->orderBy('request_details.id', 'desc')
                    ->paginate(1000);
                } else {
                    $data['technicals'] = DB::table('request_details')
                    ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->where('request_details.date', '>=', $r->start_date)
                    ->where('request_details.request_status', $r->status)
                    ->where('request_details.date', '<=', $r->end_date)
                    ->where(function($query) use ($keyword1){
                        $tkeyword = trim($keyword1);
                        $query
                        ->orWhere('requestchecks.code', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.section_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%")
                        ->orWhere('request_details.item_name', 'like', "%{$tkeyword}%");
                    })
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                }
             
                return view('front_offices.index', $data);
            } 
            else {
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['keyword'] = $r->keyword;
                $data['section'] = $r->section;
                $data['status'] = $r->status;
     
                $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
                    ->where('request_details.active', 1)
                    ->where('requestchecks.active', 1)
                    ->where('request_details.date', '>=', $data['start_date'])
                    ->where('request_details.date', '<=', $data['end_date'])
                    ->select('request_details.*', 'requestchecks.patient_id')
                    ->orderBy('requestchecks.code', 'desc')
                    ->paginate(1000);
                    return view('front_offices.index', $data);
            }
    }
    
    public function edit($id) {
        if(!check('front_office', 'u')){
            return view('permissions.no');
        }
        
        $data['sections'] = DB::table('sections')
            ->where('active', 1)
            ->get();
        $data['items'] = DB::table('items')
            ->where('active', 1)
            ->get();
        $data['request_detail'] = DB::table('request_details')
            ->where('id', $id)
            ->first();
        $data['request'] = DB::table('requestchecks')
            ->where('id', $data['request_detail']->request_id)
            ->first();
        $data['patient'] = DB::table('customers')
            ->where('id', $data['request']->patient_id)
            ->first();
        $data['users'] = DB::table('users')
            ->where('active', 1)
            ->get();
        return view('front_offices.edit', $data);
    }
    public function update(Request $r) {
        if(!check('front_office', 'u')){
            return view('permissions.no');
        }
        $section = DB::table('sections')
            ->where('id', $r->section_id)
            ->first();
        $item = DB::table('items')
            ->where('id', $r->item_id)
            ->first();
        $item = DB::table('items')->where('id', $r->item_id)->first();
        $section = DB::table('sections')->where('id', $r->section_id)->first();

       $request_detail = DB::table('request_details')->where('id', $r->id)
        ->first();

        if($request_detail->date!=$r->date || $request_detail->time!=$r->time) {
            DB::table('request_details')->where('id', $r->id)->update(['request_status' => 4]);
        }
        $data = array(
                'date' => $r->date,
                'time' => $r->time,
                'section_id' => $r->section_id,
                'section_name' => $section->name,
                'request_note' => $r->request_note,
                'item_id' => $r->item_id,
                'item_name' => $item->name,
                'price' => $r->price,
                'discount' => $r->discount,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i'),
        );

        $i = DB::table('request_details')->where('id', $r->id)->update($data);
        
        if($i) {
            return redirect('front-office/detail/'.$r->id)
            ->with('success', config('app.success'))
            ->withInput();

        } else {
            return redirect('front-office/detail/'.$r->id)
            ->with('error', config('app.error'))
            ->withInput();
        }
    }
    public function detail($id)
    {
        if(!check('front_office', 'l')){
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
        $data['protocol_categories'] = DB::table('protocol_categories')->where('active', 1)->get();
        return view('front_offices.detail', $data);
    }
   
    public function confirm($id)
    {
        if(!check('front_office', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 2,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 

        $i = DB::table('request_details')->where('id', $id)->update($data);
        if($i) {
            return redirect('front-office/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('front-office/detail/'.$id)
            ->with('error', config('app.error'));
        }
    }

    public function arrived($id)
    {
        if(!check('front_office', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 3,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
        $request_detail = DB::table('request_details')
            ->where('id', $id)
            ->where('active', 1)
            ->first();
        
        $i = DB::table('request_details')->where('id', $id)->update($data);
        if($i) {
            return redirect('front-office/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('front-office/detail/'.$id)
            ->with('error', config('app.error'));
        }

    }
    public function today() {
        if(!check('front_office', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->get();
        $data['start_date'] = "";
        $data['end_date'] = "";
        $data['keyword'] = '';
        $data['section'] = '';
        $data['status'] = '';
        $today = date('Y-m-d');
            $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
            ->where('request_details.active', 1)
            ->where('request_details.date', '=',$today )
            ->select('request_details.*', 'requestchecks.patient_id')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('front_offices.dyw', $data);
    }

    public function yesterday() {
      
        if(!check('front_office', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->get();
        $data['start_date'] = "";
        $data['end_date'] = "";
        $data['keyword'] = '';
        $data['section'] = '';
        $data['status'] = '';
        $yesterday =  date('Y-m-d',strtotime("-1 days"));
        $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
            ->where('request_details.active', 1)
            ->where('request_details.date', '=',$yesterday)
            ->select('request_details.*', 'requestchecks.patient_id')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('front_offices.dyw', $data);
    }

    public function week() {
      
        if(!check('front_office', 'l')){
            return view('permissions.no');
        }
        $data['sections'] = DB::table('sections')->where('active',1)->get();
        $data['start_date'] = "";
        $data['end_date'] = "";
        $data['keyword'] = '';
        $data['section'] = '';
        $data['status'] = '';
        $week =  date('Y-m-d',strtotime("-7 days"));
        $week2 =  date('Y-m-d',strtotime("-14 days"));
        $data['technicals'] = DB::table('request_details')
                ->join('requestchecks', 'requestchecks.id', 'request_details.request_id')
            ->where('request_details.active', 1)
            ->where('request_details.date', '<=',$week)
            ->where('request_details.date', '>=',$week2)
            ->select('request_details.*', 'requestchecks.patient_id')
            ->orderBy('request_details.id', 'desc')
            ->paginate(config('app.row'));
            return view('front_offices.dyw', $data);
    }

    public function confirm_finished($id)
    {
        if(!check('technical_check', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i'),
            'status' => 3,
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
    public function request_delete($id) {
        if(!check('front_office', 'd')){
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
            return redirect('/front-office')
            ->with('success', config('app.del_success'));
        } else {
            return redirect('front-office/detail/'.$id)
            ->with('error', config('app.error'));
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
            return redirect('/front-office/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('front-office/detail/'.$id)
            ->with('error', config('app.error'));
        }
       
    }
    
    public function send_technical($id) {
        if(!check('front_office', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 5,
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
     
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
        if($i) {
            return redirect('front-office/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('front-office/detail/'.$id)
            ->with('error', config('app.error'));
        }
    }
    public function send_doctor($id) {
        if(!check('front_office', 'u')){
            return view('permissions.no');
        }
        $data = array(
            'request_status' => 8,
            'updated_at' => date('Y-m-d H:i'),
            'time_translate' => date('Y-m-d H:i'),
            'updated_by' => Auth::user()->id,
        ); 
     
        $i = DB::table('request_details')
            ->where('id', $id)
            ->update($data);
        if($i) {
            return redirect('front-office/detail/'.$id)
            ->with('success', config('app.success'));
        } else {
            return redirect('front-office/detail/'.$id)
            ->with('error', config('app.error'));
        }
       
    }

    public function delete($id)
    {
        if(!check('technical_check', 'd')){
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
            $con = $r->contrast_enhancement.' : '.$po.$iv.$enema.$other;
        
        }   
        $protocol = $r->protocol;
        if($protocol == null) {
            $protocol = 0;
        } else {
            $protocol = count($r->protocol);
        }
        $data = array(
            'protocol_qty' => $protocol,
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
        $data['com'] = DB::table('companies')
             ->where('id', 1)
             ->first();
        return view('technical_checks.print', $data);
    }
}
