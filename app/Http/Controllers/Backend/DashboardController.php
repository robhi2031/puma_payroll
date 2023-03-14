<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\SystemInfoCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;
use Hash;
use Session;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    use SystemInfoCommon;

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $getSystemInfo = $this->get_systeminfo();
        $data = array(
            'title' => 'Dashboard',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name
        );

        addToLog('Mengakses halaman Dashboard - Backend');
        return view('backend.index', compact('data'));
    }
}