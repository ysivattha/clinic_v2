<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use DB;
use DataTables;
use Auth;
class AppointmentController extends Controller
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

        if(!check('appointment', 'l')){
            return view('permissions.no');
        }
        
        if ($r->ajax()) 
        {
            $date = date('Y-m-d', (strtotime ( '-2 day' , strtotime (date('Y-m-d')) ) ));
            $data = Appointment::join('customers', 'appointments.patient_id', 'customers.id')
                ->leftJoin('users', 'users.id', 'appointments.doctor_id')
                ->where('appointments.active', 1)
                ->orderBy('appointments.id', 'desc')
                ->where('appointments.meet_date', '>=', $date)
                ->orderBy('appointments.meet_date', 'asc')
                ->select('appointments.*', 'customers.code','users.phone',  'customers.kh_first_name', 'customers.kh_last_name', 'users.first_name as dfirst_name', 'users.last_name as dlast_name');
            return Datatables::of($data)
                ->addColumn('check', function($row){
                    $input = "<input type='checkbox' id='ch{$row->id}' value='{$row->id}'>";
                    return $input;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = btn_actions($row->id, 'appointments', 'appointment');
                    return $btn;
                })
                ->rawColumns(['action', 'check'])
                ->make(true);
        }
            $data['patients'] = DB::table('customers')
                ->where('active', 1)
                ->get();
            $data['users'] = DB::table('users')
                ->where('active', 1)
                ->get();
        return view('appointments.index', $data);
    }
    public function schedule(){
        if(!check('appointment', 'l')){
            return view('permissions.no');
        }
        $events = [];
        $date = date('Y-m-d', (strtotime ( '-2 day' , strtotime (date('Y-m-d')) ) ));
        $apps = DB::table('appointments')
            ->join('customers', 'appointments.patient_id', 'customers.id')
            ->leftJoin('users', 'users.id', 'appointments.doctor_id')
            ->where('appointments.meet_date', '>=', $date)
            ->orderBy('appointments.meet_date', 'asc')
            ->select('appointments.*', 'customers.code', 'customers.kh_first_name', 'customers.kh_last_name', 'users.first_name as dfirst_name', 'users.last_name as dlast_name')
            ->get();
        foreach($apps as $c){ 
            $meetdate = $c->meet_date .'T'.$c->meet_time;
            $events[] = \Calendar::event(
               $c->code. '-'. $c->kh_last_name.' '.$c->kh_first_name.' | ' . $c->topic.' | '.$c->dfirst_name.' '.$c->dlast_name, //event title
                $c->meet_time==''?true:false, //full day event?
                $meetdate,
                $meetdate, //end time (you can also use Carbon instead of DateTime)
                $c->id, //optionally, you can specify an event ID

                [
                    'color' => 'green',
                    'description' => $c->description,
                    'textColor' => '#FFF'
                ]
            );
        }

        $calendar = \Calendar::addEvents($events) //add an array with addEvents
            ->setOptions([ //set fullcalendar options
                'locale' => 'km',
                'firstDay' => 0,
                'displayEventTime' => true,
                'selectable' => true,
                'initialView' => 'timeGridWeek',
                'headerToolbar' => [
                    'end' => 'today prev,next dayGridMonth timeGridWeek timeGridDay'
                ]
            ])->setCallbacks([
            ]);
        return view('appointments.schedule', compact('calendar'));
    }
}
