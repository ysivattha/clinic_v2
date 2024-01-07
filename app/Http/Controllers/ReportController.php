<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class ReportController extends Controller
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
       
        $data['start_date'] = date('Y-m-d');
        $data['end_date'] = date('Y-m-d');
        $data['invoices'] = [];
        return view('reports.index', $data);
    }
    public function search(Request $r)
    {
        if(!check('Report', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = $r->start_date;
        $data['end_date'] = $r->end_date;
        $data['invoices'] = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.invoice_date', '>=' , $r->start_date)
            ->where('invoices.invoice_date', '<=' , $r->end_date)
            ->select('invoices.*', 'customers.name')
            ->get();
        return view('reports.index', $data);
    }
    public function print(Request $r)
    {
        if(!check('Report', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = $r->start_date;
        $data['end_date'] = $r->end_date;
        $data['invoices'] = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.invoice_date', '>=' , $r->start)
            ->where('invoices.invoice_date', '<=' , $r->end)
            ->select('invoices.*', 'customers.name')
            ->get();
        $data['com'] = DB::table('companies')->find(1);
        return view('reports.print', $data);
    }

    public function profit(Request $r) {
        if($r->start_date!=null && $r->end_date !=null) {
                $data['start_date'] = $r->start_date;
                $data['end_date'] = $r->end_date;
                $data['income'] = DB::table('invoices')
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' , $data['end_date'])
                ->where('active',1)
                    ->sum('paid');
                $data['expense'] = DB::table('expenses')
                ->where('expense_date', '>=' , $data['start_date'])
                ->where('expense_date', '<=' , $data['end_date'])
                ->where('active',1)
                ->sum('amount');
            } else {
                $data['start_date'] = date('Y-m-d');
                $data['end_date'] = date('Y-m-d');
                $data['income'] = null;
                $data['expense'] = null;
     
            }
        
        return view('reports.profit', $data);
    }
    public function due(Request $r)
    {
        if($r->start_date!=null && $r->end_date !=null) {
        $data['start_date'] = $r->start_date;
        $data['end_date'] = $r->end_date;

        $data['due'] = DB::table('invoices')
            ->where('start_date', '>=' , $data['start_date'])
            ->where('start_date', '<=' , $data['end_date'])
            ->where('due_amount', '!=', 0)
            ->get();
        $data['total_due_amount'] = DB::table('invoices')
            ->where('active',1)
            ->where('start_date', '>=' , $data['start_date'])
            ->where('start_date', '<=' ,  $data['end_date'])
            ->sum('due_amount');
        $data['total'] = DB::table('invoices')
            ->where('active',1)
            ->where('start_date', '>=' , $data['start_date'])
            ->where('start_date', '<=' ,  $data['end_date'])
            ->sum('total');
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
            $data['due'] = array();
            $data['total_paid'] = null;
            $data['total_due_amount'] = null;
            $data['total'] = null;
        }
        return view('reports.due', $data);
    }
    public function due_print(Request $r)
    {
        if($r->start_date!=null && $r->end_date !=null) {
            $data['start_date'] = $r->start_date;
        $data['end_date'] = $r->end_date;

        $data['due'] = DB::table('invoices')
            ->where('invoices.start_date', '>=' , $data['start_date'])
            ->where('invoices.start_date', '<=' , $data['end_date'])
            ->where('invoices.due_amount', '!=', 0)
            ->get();
        $data['total_due_amount'] = DB::table('invoices')
            ->where('active',1)
            ->where('start_date', '>=' , $data['start_date'])
            ->where('start_date', '<=' ,  $data['end_date'])
            ->sum('due_amount');
        $data['total'] = DB::table('invoices')
            ->where('active',1)
            ->where('start_date', '>=' , $data['start_date'])
            ->where('start_date', '<=' ,  $data['end_date'])
            ->sum('total');
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
            $data['incomes'] = array();
            $data['total_paid'] = null;
            $data['total_due_amount'] = null;
            $data['total'] = null;
        }
        $data['com'] = DB::table('companies')->find(1);
        return view('reports.due-print', $data);
    }
    public function expense()
    {
        if(!check('expense_report', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = date('Y-m-d');
        $data['end_date'] = date('Y-m-d');
        $data['expenses'] = [];
        return view('reports.expense', $data);
    }
    public function search_expense(Request $r)
    {
        if(!check('expense_report', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = $r->start_date;
        $data['end_date'] = $r->end_date;
        $data['expenses'] = DB::table('expenses')
            ->where('expenses.expense_date', '>=' , $r->start_date)
            ->where('expenses.expense_date', '<=' , $r->end_date)
            ->where('active', 1)
            ->orderBy('id', 'desc')
            ->get();
        return view('reports.expense', $data);
    }
    public function print_expense(Request $r)
    {
        if(!check('expense_report', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = $r->start;
        $data['end_date'] = $r->end;
        $data['expenses'] = DB::table('expenses')
            ->where('expenses.expense_date', '>=' , $r->start)
            ->where('expenses.expense_date', '<=' , $r->end)
            ->where('active', 1)
            ->orderBy('id', 'desc')
            ->get();
        $data['com'] = DB::table('companies')->find(1);
        return view('reports.expense-print', $data);
    }
    public function income(Request $r)
    {
        if($r->start_date!=null && $r->end_date !=null) {
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['incomes'] = DB::table('invoices')
            ->where('active', 1)
            ->where('start_date', '>=' , $data['start_date'])
            ->where('start_date', '<=' ,  $data['end_date'])
            ->get();
            $data['total_paid'] = DB::table('invoices')
                ->where('active',1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->sum('paid');
            $data['total_due_amount'] = DB::table('invoices')
                ->where('active',1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->sum('due_amount');
            $data['total'] = DB::table('invoices')
                ->where('active',1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->sum('total');
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
            $data['incomes'] = array();
            $data['total_paid'] = null;
            $data['total_due_amount'] = null;
            $data['total'] = null;
        }
        return view('reports.income', $data);
    }
    public function income_print(Request $r)
    {
        if($r->start_date!=null && $r->end_date !=null) {
            $data['start_date'] = $r->start_date;
            $data['end_date'] = $r->end_date;
            $data['incomes'] = DB::table('invoices')
                ->where('active', 1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->get();
            $data['total_paid'] = DB::table('invoices')
                ->where('active',1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->sum('paid');
            $data['total_due_amount'] = DB::table('invoices')
                ->where('active',1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->sum('due_amount');
            $data['total'] = DB::table('invoices')
                ->where('active',1)
                ->where('start_date', '>=' , $data['start_date'])
                ->where('start_date', '<=' ,  $data['end_date'])
                ->sum('total');
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
            $data['incomes'] = array();
            $data['total_paid'] = null;
            $data['total_due_amount'] = null;
            $data['total'] = null;
        }
        $data['com'] = DB::table('companies')->find(1);
        return view('reports.income-print', $data);
    }

    public function commission()
    {
        if(!check('commission', 'l')){
            return view('permissions.no');
        }
        $data['emps'] = DB::table('employees')
            ->where('active', 1)
            ->get();
        $data['start_date'] = date('Y-m-d');
        $data['end_date'] = date('Y-m-d');
        $data['invoices'] = [];
        $data['em'] = 'all';
        return view('reports.commission', $data);
    }
    public function search_commission(Request $r)
    {
        if(!Right::check('commission', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = $r->start_date;
        $data['end_date'] = $r->end_date;
        $data['emps'] = DB::table('employees')
            ->where('active', 1)
            ->get();
        $data['em'] = $r->employee;
        $query = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', 'customers.id')
            ->leftJoin('employees', 'invoices.doctor_id', 'employees.id')
            ->where('invoices.invoice_date', '>=' , $r->start_date)
            ->where('invoices.invoice_date', '<=' , $r->end_date);

        if($r->employee!='all')
        {
            $query = $query->where('invoices.doctor_id', $r->employee);
        }
        
        $data['invoices'] = $query
            ->select('invoices.*', 'customers.name', 'employees.first_name as fname', 
                'employees.last_name as lname', 'employees.percent')
            ->get();
        $data['doctor'] = DB::table('employees')->find($r->employee);
        return view('reports.commission', $data);
    }
    public function print_commission(Request $r)
    {
        if(!check('commission', 'l')){
            return view('permissions.no');
        }
        $data['start_date'] = $r->start;
        $data['end_date'] = $r->end;
        
        $query = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', 'customers.id')
            ->leftJoin('employees', 'invoices.doctor_id', 'employees.id')
            ->where('invoices.invoice_date', '>=' , $r->start)
            ->where('invoices.invoice_date', '<=' , $r->end);

        if($r->emp!='all')
        {
            $query = $query->where('invoices.doctor_id', $r->emp);
        }
       
        $data['invoices'] = $query
            ->select('invoices.*', 'customers.name', 'employees.first_name as fname', 
                'employees.last_name as lname', 'employees.percent')
            ->get();
        $data['com'] = DB::table('companies')->find(1);
        return view('reports.commission-print', $data);
    }
}
