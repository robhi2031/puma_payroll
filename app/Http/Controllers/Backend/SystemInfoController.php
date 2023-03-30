<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemInfoController extends Controller
{
    use SystemCommon;
    public function __construct()
    {
        $this->middleware(['direct_permission:system-info-read'])->only(['index']);
        $this->middleware(['direct_permission:system-info-update'])->only('update');
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
        //Data Page Info
        $data = array(
            'title' => 'Kelola Informasi Sistem',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/dropify-master/css/dropify.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/dropify-master/js/dropify.min.js',
            '/dist/js/backend_app.init.js',
            '/scripts/backend/manage_systeminfo.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.manage_systeminfo', compact('data'));
    }
}