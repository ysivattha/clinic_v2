<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app()->setLocale(Auth::user()->language);
            return $next($request);
        });
        
    }
    public function index()
    {


        $data['customers'] = DB::table('customers')->where('active', 1)->get();
        $data['count_patients'] = count($data['customers']);

        $data['requestchecks'] = DB::table('requestchecks')->where('active', 1)->get();
        $data['count_requestchecks'] = count($data['requestchecks']);

        $date = date('Y-m-d', (strtotime ( '-2 day' , strtotime (date('Y-m-d')) ) ));
        $data['appointments'] = DB::table('appointments')
            ->where('appointments.active', 1)
            ->orderBy('appointments.id', 'desc')
            ->where('appointments.meet_date', '>=', $date)
            ->get();
        $data['count_appointments'] = count($data['appointments']);

        $data['invoices'] = DB::table('invoices')->where('active', 1)->get();

        $data['count_invoices'] = count($data['invoices']);
        return view('dashboard', $data);
    }
    public function print() {
        $user = DB::table('users')->where('id', Auth::user()->id)->first();

        $data['hospital'] = DB::table('hospitals')->where('id', $user->hospital_id)->first();
        return view('print', $data);
    }
}
