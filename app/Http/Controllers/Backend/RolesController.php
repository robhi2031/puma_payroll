<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;
use Hash;
use Session;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use SystemCommon;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('permission:roles-read', ['only' => ['index']]);
        $this->middleware('permission:roles-create', ['only' => ['store']]);
        $this->middleware('permission:roles-update', ['only' => ['update']]);
        $this->middleware('permission:roles-delete', ['only' => ['delete']]);
    }
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $getSystemInfo = $this->get_systeminfo();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => $getUserSession->name.' on User Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/Magnific-Popup/magnific-popup.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            '/dist/js/backend_app.init.js',
            '/scripts/backend/roles.init.js'
        );

        addToLog('Mengakses halaman Kelola Roles - Backend');
        return view('backend.roles', compact('data'));
    }
}