<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class NotFoundController extends Controller
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
        return view('errors.404');
    }
}
