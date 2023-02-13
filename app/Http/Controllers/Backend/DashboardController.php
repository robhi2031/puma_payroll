<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;


class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.index');
    }
}