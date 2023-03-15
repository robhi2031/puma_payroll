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

class UserProfileController extends Controller
{
    use SystemInfoCommon;

    /**
     * index
     *
     * @return void
     */
    public function index($username)
    {
        $getSystemInfo = $this->get_systeminfo();
        $getUserSession = Auth::user();

        $data = array(
            'title' => $getUserSession->name.' on User Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'user_session' => $getUserSession
        );

        addToLog('Mengakses halaman ' .$getUserSession->name. ' on User Profile - Backend');
        return view('backend.user_profile', compact('data'));
    }
}