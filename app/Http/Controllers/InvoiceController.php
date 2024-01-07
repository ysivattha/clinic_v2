<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use DB;
use DataTables;
use Auth;
class InvoiceController extends Controller
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
        if(!check('invoice', 'l')){
            return view('permissions.no');
        }
        if($r->start_date == null && $r->end_date == null && $r->keyword == null) {
            $data['start_date'] = "";
            $data['keyword'] = "";
            $data['end_date'] = "";
            $data['invoices'] = Invoice::leftJoin('customers', 'customers.id', 'invoices.patient_id')
                ->orderBy('invoices.id', 'desc')
                ->select('invoices.*', 'customers.kh_first_name as first_name', 'customers.gender', 'customers.dob', 'customers.phone', 'customers.kh_last_name as last_name', 'customers.code')
                ->where('invoices.active', 1)
                ->paginate(config('app.row'));
        } if($r->start_date != null && $r->end_date != null && $r->keyword == null)  {
          
            $data['start_date'] = $r->start_date;
            $data['keyword'] = "";
            $data['end_date'] = $r->end_date;
            $data['invoices'] = Invoice::leftJoin('customers', 'customers.id', 'invoices.patient_id')
                ->orderBy('invoices.id', 'desc')
                ->select('invoices.*', 'customers.kh_first_name as first_name', 'customers.gender', 'customers.dob', 'customers.phone', 'customers.kh_last_name as last_name', 'customers.code')
                ->where('invoices.active', 1)
                ->where('start_date','>=', $data['start_date'])
                ->where('start_date','<=', $data['end_date'])
                ->paginate(1000);
        }
        if($r->start_date != null && $r->end_date != null && $r->keyword != null)  {
            $data['start_date'] = $r->start_date;
            $data['keyword'] = $r->keyword;
            $data['end_date'] = $r->end_date;
            $keyword1 = $r->keyword;
            $data['invoices'] = Invoice::leftJoin('customers', 'customers.id', 'invoices.patient_id')
                ->orderBy('invoices.id', 'desc')
                ->select('invoices.*', 'customers.kh_first_name as first_name', 'customers.gender', 'customers.phone', 'customers.dob','customers.kh_last_name as last_name', 'customers.code')
                ->where('invoices.active', 1)
                ->where('start_date','>=', $data['start_date'])
                ->where('start_date','<=', $data['end_date'])
                ->where(function($query) use ($keyword1){
                    $tkeyword = trim($keyword1);
                    $query->orWhere('customers.kh_first_name','like', "%{$tkeyword}%")
                        ->orWhere('customers.kh_last_name', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.en_first_name', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.en_last_name', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.code', 'like', "%{$tkeyword}%")
                        ->orWhere('customers.phone', 'like', "%{$tkeyword}%")
                        ->orWhere('invoices.due_amount', 'like', "%{$tkeyword}%")
                        ->orWhere('invoices.total', 'like', "%{$tkeyword}%")
                        ->orWhere('invoices.paid', 'like', "%{$tkeyword}%")
                        ->orWhere('invoices.invoice_no', 'like', "%{$tkeyword}%");
                })
                ->paginate(1000);
        }
        
    
        return view('invoices.index', $data);
    }
    public function detail($id)
    {
        if(!check('invoice', 'l')){
            return view('permissions.no');
        }
        $data['medicines'] = DB::table('medicine_libraries')
            ->where('active', 1)
            ->get();
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        $data['sections'] = DB::table('sections')
            ->where('active', 1)
            ->get();
        $data['doctors'] = DB::table('users')
            ->where('active', 1)
            ->get();
        $data['items'] = DB::table('items')
                ->where('active', 1)
                ->get();
        $data['invoice'] = DB::table('invoices')
            ->leftJoin('customers', 'customers.id', 'invoices.patient_id')
            ->select('invoices.*', 'customers.id as pid', 'customers.kh_first_name as pfirst_name', 'customers.kh_last_name as plast_name', 'customers.phone')
            ->where('invoices.id', $id)
            ->first();
        $data['user'] = DB::table('users')->where('id', $data['invoice']->cashier)->first();
        $data['invoice_details'] = DB::table('invoice_details')
            ->leftJoin('users', 'users.id', 'invoice_details.percent1')
            ->select('invoice_details.*', 'users.first_name', 'invoice_details.code as request_code', 'users.last_name', 'users.phone', 'users.code')
            ->where('invoice_details.invoice_id', $id)
            ->where('invoice_details.active', 1)
            ->get();
        $data['invoice_detail2'] = DB::table('invoice_detail2')
            ->where('invoice_id', $id)
            ->where('active', 1)
            ->get();
        $data['requestchecks'] = DB::table('requestchecks')
            ->where('patient_id', $data['invoice']->patient_id)
            ->where('is_invoiced', 1)
            ->get();
        $data['payments'] = DB::table('payments')->where('invoice_id', $id)->get();
        return view('invoices.detail', $data);
    }
    public function create(Request $r)
    {
        if(!check('invoice', 'i')){
            return view('permissions.no');
        }
        
        $data['users'] = DB::table('users')->where('active', 1)->get();
        $data['patients'] = DB::table('customers')
            ->where('active', 1)
            ->get();
            $data['patient_id'] = $r->query('patient_id');
            $data['patient'] = DB::table('customers')->where('id',$data['patient_id'])->first();
        $data['requests'] = DB::table('requestchecks')
            ->join('customers', 'customers.id', 'requestchecks.patient_id')
            ->select('requestchecks.*', 'customers.kh_first_name', 'customers.kh_last_name', 'customers.phone', 'customers.code as pcode')
            ->where('requestchecks.active', 1)
            ->where('requestchecks.is_invoiced', 1)
            ->orderBy('requestchecks.id', 'desc')
            ->get();
        $data['medicines'] = DB::table('medicine_libraries')->where('active', 1)->get();
        $data['categories'] = DB::table('categories')
            ->where('active', 1)
            ->get();
        $data['patients'] = DB::table('customers')
                ->where('active', 1)
                ->get();
        $data['doctors'] = DB::table('users')
                ->where('active', 1)
                ->get();
        $data['sections'] = DB::table('sections')
                ->where('active', 1)
                ->get();
        $data['items'] = DB::table('items')
                ->where('active', 1)
                ->get();
        return view('invoices.create', $data);
    }

    public function store(Request $r)
    {
        if(!check('invoice', 'i')){
            return view('permissions.no');
        }
        $data = array(
            'patient_id' => $r->patient_id,
            'start_date' => $r->start_date,
            'due_date' => $r->due_date,
            'cashier' => $r->cashier,
            'bank' => $r->bank,
            'paid' => 0,
            'note' => $r->note,
            'total' => $r->grand_total,
            'due_amount' => $r->grand_total,
            'created_by' => Auth::user()->id
        );
        $i = DB::table('invoices')->insertGetId($data);
        if($i)
        {
            $invoice = "INV" . sprintf('%04d', $i);
            DB::table('invoices')
            ->where('id', $i)
            ->update([
                'invoice_no' => $invoice,
            ]);
            $items = $r->item_id;
            $j = 0; 
           if($items!=null) {
               
                    foreach($items as $item) {
                       
                        if($r->request_code[$j]=="") {
                                $request = DB::table('requestchecks')->orderBy('id','desc')->first();
                                if($request!=null) {
                                    $code = "R" . sprintf('%04d', $request->id +1);  
                                } else {
                                    $code = "R" . sprintf('%04d', 1);  
                                }
                               
                                $sub_total = $r->price[$j] - $r->discount[$j];
                                $user = DB::table('users')->where('id', Auth::user()->id)->first();
                                $hospital = DB::table('hospitals')->where('id', $user->hospital_id)->first();
                                $data2 = array(
                                    'patient_id' => $r->patient_id,
                                    'date' => $r->request_date[$j],
                                    'time' => $r->request_time[$j],
                                    'hospital' => $hospital->name,
                                    'is_invoiced' => 0,
                                    'hospital_reference' => $hospital->code,
                                    'hospital_id' => $hospital->id,
                                    'created_by' => Auth::user()->id
                                );
                                $request_id = DB::table('requestchecks')->insertGetId($data2);
                                if($request_id) {
                                    $rcode = "R" . sprintf('%04d', $request_id);
                                    DB::table('requestchecks')
                                    ->where('id', $request_id)
                                    ->update([
                                        'code' => $rcode,
                                    ]);
                                }
                                $data5= array(
                                    'invoice_id' => $i,
                                    'request_id' => $request_id,
                                    'date' => $r->request_date[$j],
                                    'time' => $r->request_time[$j],
                                    'section_id' => $r->section_id[$j],
                                    'section_name' => $r->section_name[$j],
                                    'item_id' => $r->item_id[$j],
                                    'item_name' => $r->item_name[$j],
                                    'price' => $r->price[$j],
                                    'discount' => $r->discount[$j],
                                    'percent1' => $r->percent1[$j],
                                    'percent1' => $r->percent1[$j],
                                    'percent1_code' => $r->percent1_code[$j],
                                    'percent2' => $r->percent2[$j],
                                    'percent3' => $r->percent3[$j],
                                    'created_by' => Auth::user()->id
                                );
                               $request_detail_id = DB::table('request_details')->insertGetId($data5);
                               $data4 = array(
                                    'invoice_id' => $i,
                                    'code' => $rcode,
                                    'date' => $r->request_date[$j],
                                    'time' => $r->request_time[$j],
                                    'section_id' => $r->section_id[$j],
                                    'section_name' => $r->section_name[$j],
                                    'item_id' => $r->item_id[$j],
                                    'item_name' => $r->item_name[$j],
                                    'request_detail_id' => $request_detail_id,
                                    'price' => $r->price[$j],
                                    'discount' => $r->discount[$j],
                                    'percent1' => $r->percent1[$j],
                                    'percent1_code' => $r->percent1_code[$j],
                                    'percent2' => $r->percent2[$j],
                                    'percent3' => $r->percent3[$j],
                                    'created_by' => Auth::user()->id
                                );
                            DB::table('invoice_details')->insert($data4);
                            
                        }  else {
                            $code = $r->request_code[$j];
                            $data2 = array(
                                'invoice_id' => $i,
                                'code' => $code,
                                'date' => $r->request_date[$j],
                                'time' => $r->request_time[$j],
                                'section_id' => $r->section_id[$j],
                                'section_name' => $r->section_name[$j],
                                'item_id' => $r->item_id[$j],
                                'item_name' => $r->item_name[$j],
                                'price' => $r->price[$j],
                                'discount' => $r->discount[$j],
                                'request_detail_id' => $r->request_detail_id[$j],
                                'percent1' => $r->percent1[$j],
                                'percent1_code' => $r->percent1_code[$j],
                                'percent2' => $r->percent2[$j],
                                'percent3' => $r->percent3[$j],
                                'created_by' => Auth::user()->id
                            );
                         
                          $succ =  DB::table('invoice_details')->insert($data2);
                          if($succ) {
                            DB::table('requestchecks')
                            ->where('code', $code)
                            ->update(['is_invoiced' => 0, 'updated_at' =>date('Y-m-d H:i'), 'updated_by' => Auth::user()->id]);

                          }
                        }
                        $j++;   
                    }
            }

            
            $medicines = $r->medicine_name;
            $m = 0; 
            if($medicines!=null) {
              
                foreach($medicines as $medicine) {
                    $data3 = array(
                    'invoice_id' => $i,
                    'name' => $r->medicine_name[$m],
                    'qty' => $r->medicine_qty[$m],
                    'category_id' => $r->category_id[$m],
                    'price' => $r->medicine_price[$m],
                    'discount' => $r->medicine_discount[$m],
                    'created_by' => Auth::user()->id
                    );
                $medicine =  DB::table('invoice_detail2')->insert($data3);
                    $j++;
                }
            }
            return redirect()->route('invoice.detail', $i)
                ->with('success', config('app.success'));
        } else{
            return redirect()->route('invoice.detail', $i)
                ->with('error', config('app.error'))
                ->withInput();
        }
    }
    public function invoice_update(Request $r) {
        $items = $r->item_id;
        $j = 0; 
       if($items!=null) {
        foreach($items as $item) {
                $data2 = array(
                    'invoice_id' => $r->invoice_id,
                    'code' => $r->request_code[$j],
                    'date' => $r->request_date[$j],
                    'time' => $r->request_time[$j],
                    'section_id' => $r->section_id[$j],
                    'section_name' => $r->section_name[$j],
                    'item_id' => $r->item_id[$j],
                    'request_detail_id' => $r->request_detail_id[$j],
                    'item_name' => $r->item_name[$j],
                    'price' => $r->price[$j],
                    'discount' => $r->discount[$j],
                    'percent1' => $r->percent1[$j],
                    'percent1_code' => $r->percent1_code[$j],
                    'percent2' => $r->percent2[$j],
                    'percent3' => $r->percent3[$j],
                    'created_by' => Auth::user()->id
                );
             
              $succ =  DB::table('invoice_details')->insert($data2);
              if($succ) {
                  $amount = $r->price[$j] - $r->discount[$j];
                DB::table('invoices')->where('id', $r->invoice_id)->increment('due_amount', $amount);
                DB::table('invoices')->where('id', $r->invoice_id)->increment('total', $amount);
                DB::table('requestchecks')
                    ->where('code', $r->request_code[$j])
                    ->update(['is_invoiced' => 0, 'updated_at' =>date('Y-m-d H:i'), 'updated_by' => Auth::user()->id]);
                }
            $j++;   
        }
           return redirect()->route('invoice.detail', $r->invoice_id)
                ->with('success', config('app.success'));
        } else {
            return redirect()->route('invoice.detail', $r->invoice_id)
            ->with('error', config('app.error'));
        }
    }
    public function get_request($id)
    {
        $trs = DB::table('requestchecks')->where('active',1)
        ->where('is_invoiced', 1)->find($id);
        if($trs!=null)
        {
            $trs->details = DB::table('request_details')
                ->leftJoin('users', 'users.id', 'request_details.percent1')
                ->leftJoin('requestchecks', 'requestchecks.id', 'request_details.request_id')
                ->select('request_details.*', 'users.first_name', 'requestchecks.code', 'users.last_name', 'users.phone', 'requestchecks.code as request_code')
                ->where('request_details.request_id', $id)
                ->where('requestchecks.is_invoiced', 1)
                ->where('request_details.request_status', '!=',0)
                ->where('request_details.active', 1)
                ->get();
        }
        
        return json_encode($trs);
    }

    // save medicine
    public function save_medicine(Request $r)
    {
        if(!check('invoice', 'i')){
            return view('permissions.no');
        }

        $data = array(
            'invoice_id' => $r->invoice_id,
            'name' => $r->medicine_name,
            'qty' => $r->medicine_qty,
            'category_id' => $r->category_id,
            'price' => $r->medicine_price,
            'discount' => $r->medicine_discount,
            'created_by' => Auth::user()->id
        );
        $i = DB::table('invoice_detail2')
            ->insertGetId($data);
        if($i)
        {
         
            $invoice = DB::table('invoices')->where('id', $r->invoice_id)->first();
            $total = $invoice->total + (($r->medicine_qty * $r->medicine_price) - $r->medicine_discount);
            $due_amount = $invoice->due_amount + ($r->medicine_qty * $r->medicine_price - $r->medicine_discount);
            
            DB::table('invoices')->where('id', $r->invoice_id)->update(['total' => $total, 'due_amount' => $due_amount]);
           
            return redirect('invoice/detail/'.$r->invoice_id)->with('success', config('app.success'));;
        }
        else{
            return redirect('invoice/detail/'.$r->invoice_id)->with('error', config('app.error'));;
        }
    }
     // save payment
     public function save_payment(Request $r)
     {
         if(!check('invoice', 'i')){
             return view('permissions.no');
         }
         $validate = $r->validate([
             'pay_date' => 'required',
             'amount' => 'required|min:1',
             'invoice_id' => 'required'
         ]);
 
         $data = array(
             'invoice_id' => $r->invoice_id,
             'amount' => $r->amount,
             'pay_date' => $r->pay_date,
             'note' => $r->note
         );
         $i = DB::table('payments')
             ->insertGetId($data);
         if($i)
         {
             // update balance in invoice
             DB::table('invoices')->where('id', $r->invoice_id)->decrement('due_amount', $r->amount);
             DB::table('invoices')->where('id', $r->invoice_id)->increment('paid', $r->amount);
             return redirect('invoice/detail/'.$r->invoice_id)->with('success', config('app.success'));;
         }
         else{
             return redirect('invoice/detail/'.$r->invoice_id)->with('success', config('app.error'));;
         }
     }
     public function delete_payment($id)
     {
         if(!check('invoice', 'd')){
             return view('permissions.no');
         }
         $pay = DB::table('payments')->where('id', $id)->first();
         $amount = $pay->amount;
         $inv = $pay->invoice_id;
         $i = DB::table('payments')->where('id', $id)->delete();
         if($i)
         {
             DB::table('invoices')->where('id', $inv)->increment('due_amount', $amount);
             DB::table('invoices')->where('id', $inv)->decrement('paid', $amount);
             
             return redirect('invoice/detail/'.$inv)->with('success', config('app.del_success'));
         }
         else{
             
             return redirect('invoice/detail/'.$inv)->with('error', config('app.del_fail'));
         }
     }

     public function request_save(Request $r) {
        if(!check('invoice', 'i')){
            return view('permissions.no');
        }
        $request = DB::table('requestchecks')->orderBy('id','desc')->first();
        $request_code = "R" . sprintf('%04d', $request->id +1);  
        
        $section = DB::table('sections')->where('id', $r->section_id)->first();
        $item = DB::table('items')->where('id', $r->item_id)->first();
            $data= array(
                'invoice_id' => $r->invoice_id,
                'code' => $request_code,
                'date' => $r->request_date,
                'time' => $r->request_time,
                'section_id' => $r->section_id,
                'section_name' => $r->section_name,
                'item_id' => $r->item_id,
                'item_name' => $r->item_name,
                'price' => $r->price,
                'discount' => $r->discount,
                'percent1' => $r->percent1,
                'percent1_code' => $r->percent1_code,
                'percent2' => $r->percent2,
                'percent3' => $r->percent3,
                'created_by' => Auth::user()->id
            );
          $i =   DB::table('invoice_details')->insert($data);  
        if($i) {
            $sub_total = $r->price - $r->discount;
            $user = DB::table('users')->where('id', Auth::user()->id)->first();
            $hospital = DB::table('hospitals')->where('id', $user->hospital_id)->first();
            $data2 = array(
                'patient_id' => $r->patient_id,
                'date' => $r->request_date,
                'time' => $r->request_time,
                'hospital' => $hospital->name,
                'is_invoiced' => 0,
                'hospital_reference' => $hospital->code,
                'hospital_id' => $hospital->id,
                'created_by' => Auth::user()->id
            );
            $request_id = DB::table('requestchecks')->insertGetId($data2);
            if($request_id) {
                $rcode = "R" . sprintf('%04d', $request_id);
                DB::table('requestchecks')
                ->where('id', $request_id)
                ->update([
                    'code' => $rcode,
                ]);
            }
            $data3= array(
                'invoice_id' => $r->invoice_id,
                'date' => $r->request_date,
                'request_id' => $request_id,
                'time' => $r->request_time,
                'section_id' => $r->section_id,
                'section_name' => $r->section_name,
                'item_id' => $r->item_id,
                'item_name' => $r->item_name,
                'price' => $r->price,
                'discount' => $r->discount,
                'percent1' => $r->percent1,
                'percent1_code' => $r->percent1_code,
                'percent2' => $r->percent2,
                'percent3' => $r->percent3,
                'created_by' => Auth::user()->id
            );
           
            DB::table('request_details')->insert($data3);
            $invoice = DB::table('invoices')->where('id', $r->invoice_id)->first();
            
            $total = $invoice->total + $sub_total;
            $due_amount = $invoice->due_amount + $sub_total;
            if($r->discount > 0) {
                $total = $invoice->total + $sub_total - $r->discount;
                $due_amount = $invoice->due_amount + $sub_total - $r->discount;
            }
            
            DB::table('invoices')->where('id', $r->invoice_id)->update(['total' => $total, 'due_amount' => $due_amount]);
          
            return redirect('invoice/detail/'.$r->invoice_id)->with('success', config('app.success'));
        } else {
            return redirect('invoice/detail/'.$r->invoice_id)->with('error', config('app.error'));
        }
       
      
       
     }
     public function print($id)
     {
        if(!check('invoice', 'l')){
            return view('permissions.no');
        }
         $data['inv'] = DB::table('invoices')
         ->leftJoin('customers', 'customers.id', 'invoices.patient_id')
         ->select('invoices.*', 'customers.id as pid', 'customers.kh_first_name as pfirst_name', 'customers.kh_last_name as plast_name', 'customers.phone')
         ->where('invoices.id', $id)
         ->select('invoices.*', 'customers.kh_first_name','customers.kh_last_name',
         'customers.phone', 'customers.code', 'customers.address', 'customers.en_first_name', 'customers.en_last_name', 'customers.nationality', 'customers.job', 'customers.dob', 'customers.gender', 'customers.id as patient_code')
         ->first();
             $data['user'] = DB::table('users')
                ->where('id', $data['inv']->cashier)
                ->first();
            $data['hospital'] = DB::table('hospitals')
                    ->where('id', $data['user']->hospital_id)
                    ->first(); 
        $data['dob'] = \Carbon\Carbon::parse( $data['inv']->dob)->diff(\Carbon\Carbon::now())->format('%y ឆ្នាំ %m ខែ %d ថ្ងៃ');  
        $data['lines'] = DB::table('invoice_details')
            ->leftJoin('users', 'users.id', 'invoice_details.percent1')
            ->select('invoice_details.*', 'invoice_details.section_id','users.first_name', 'users.last_name', 'users.phone')
            ->where('invoice_details.invoice_id', $id)
            ->orderBy('invoice_details.section_id')
            ->where('invoice_details.active', 1)
            ->get();

    
        
         $data['invoice_detail2'] = DB::table('invoice_detail2')
            ->where('active',1)
            ->where('invoice_id', $id)
            ->get();
        $data['exc'] = DB::table('exchanges')
            ->where('active', 1)
            ->first();
        $user = DB::table('users')->where('id', $data['inv']->cashier)->first();
        $data['hospital'] = DB::table('hospitals')
            ->where('id', $user->hospital_id)
            ->first();

         return view('invoices.print', $data);
     }

     public function delete($id) {
        if(!check('invoice', 'd')){
            return view('permissions.no');
        }
        $payment = DB::table('payments')->where('invoice_id', $id)->get();
        if(count($payment)<=0) {
            $i = DB::table('invoices')->where('id', $id)->update(['active' => 0, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);
            $invoice = DB::table('invoices')->where('id', $id)->first();
   
            $invoice_details = DB::table('invoice_details')->where('invoice_id', $invoice->id)->get();
            if($i) {
                foreach($invoice_details as $inv) {
             
                        DB::table('requestchecks')
                            ->where('code', $inv->code)
                            ->update(['is_invoiced' => 1, 'updated_by' => Auth::user()->id, 'updated_at'=> date('Y-m-d H:i')]);

                }
             
                return redirect()->route('invoice.index')
                ->with('success', config('app.del_success'));
            } 
        } else {
            return redirect()->route('invoice.detail', $id)
            ->with('error', config('app.del_fail'));
        }
     }
     public function delete_request($id) {
        if(!check('invoice', 'd')){
            return view('permissions.no');
        }
        $invoice_detail = DB::table('invoice_details')->where('id', $id)->first();

       $i = DB::table('invoice_details')
            ->where('id', $id)
            ->update(['active'=>0,'updated_by'=>Auth::user()->id,'updated_at'=>date('Y-m-d H:i')]);
            
            if($i) {
                $amount = $invoice_detail->price - $invoice_detail->discount;
                DB::table('invoices')->where('id', $invoice_detail->invoice_id)->decrement('due_amount', $amount);
                DB::table('invoices')->where('id', $invoice_detail->invoice_id)->decrement('total', $amount);
                $succ = DB::table('requestchecks')
                    ->where('code', $invoice_detail->code)
                    ->update(['is_invoiced'=>1,'updated_by'=>Auth::user()->id,'updated_at'=>date('Y-m-d H:i')]);
                return redirect()->route('invoice.detail',$invoice_detail->invoice_id)
                    ->with('success', config('app.del_success'))
                    ->withInput();
            } else {
                return redirect()->route('invoice.detail',$invoice_detail->invoice_id)
                ->with('error', config('app.del_fail'))
                ->withInput();
            }
        
           
    }
    public function delete_medicine($id) {
        if(!check('invoice', 'd')){
            return view('permissions.no');
        }
        $invoice_detail = DB::table('invoice_detail2')->where('id', $id)->first();

       $i = DB::table('invoice_detail2')
            ->where('id', $id)
            ->update(['active'=>0,'updated_by'=>Auth::user()->id,'updated_at'=>date('Y-m-d H:i')]);
            if($i) {
               
                return redirect()->route('invoice.detail',$invoice_detail->invoice_id)
                    ->with('success', config('app.del_success'))
                    ->withInput();
            } else {
                return redirect()->route('invoice.detail',$invoice_detail->invoice_id)
                ->with('error', config('app.del_fail'))
                ->withInput();
            }
    }
     public function get_medicine($id) {
        $data = DB::table('medicine_libraries')
            ->where('category_id', $id)
            ->orderBy('name', 'asc')
            ->where('active', 1)
            ->get();
       return $data;
     }
}
