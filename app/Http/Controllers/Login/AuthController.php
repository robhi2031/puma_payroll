<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Traits\SystemInfoCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;


class AuthController extends Controller
{
    use SystemInfoCommon;
    
    /**
     * index
     *
     * @return void
     */
    public function index() {
        $getSystemInfo = $this->get_systeminfo();
        $data = array(
            'title' => 'Login',
            'url' => url()->current(),
            'thumb' => $getSystemInfo->url_thumb,
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'app_desc' => $getSystemInfo->description,
            'app_keywords' => $getSystemInfo->keyword
        );

        return view('login.index', compact('data'));
    }
}