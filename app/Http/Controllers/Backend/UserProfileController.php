<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use SystemCommon;
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request, $username)
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