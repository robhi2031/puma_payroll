<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemInfo;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            '/dist/plugins/summernote/summernote-lite.min.css',
            '/dist/plugins/dropify-master/css/dropify.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/summernote/summernote-lite.min.js',
            '/dist/plugins/summernote/lang/summernote-id-ID.min.js',
            '/dist/plugins/dropify-master/js/dropify.min.js',
            '/dist/js/backend_app.init.js',
            '/scripts/backend/manage_systeminfo.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.manage_systeminfo', compact('data'));
    }    
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        $userSesIdp = Auth::user()->id;
        $form = [
            'name' => 'required|max:255',
            'short_name' => 'required|max:60',
            'description' => 'required|max:160',
            'keyword' => 'required',
            'copyright' => 'required',
            'thumb' => 'mimes:png,jpg,jpeg|max:2048',
            'login_bg' => 'mimes:png,jpg,jpeg|max:2048',
            'login_logo' => 'mimes:png,jpg,jpeg|max:2048',
            'backend_logo' => 'mimes:png,jpg,jpeg|max:2048',
            'backend_logo_icon' => 'mimes:png,jpg,jpeg|max:2048',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //keyword to implode
            if(!empty($request->keyword)){
                $keyword = implode(", ", $request->keyword);
            }
            //array data
            $data = array(
                'name' => $request->name,
                'short_name' => $request->short_name,
                'description' => $request->description,
                'keyword' => $keyword,
                'copyright' => $request->copyright,
                'user_updated' => $userSesIdp
            );
            //UPDATE FILE
            if(!empty($_FILES['thumb']['name']) || !empty($_FILES['login_bg']['name']) || !empty($_FILES['login_logo']['name']) || !empty($_FILES['backend_logo']['name']) || !empty($_FILES['backend_logo_icon']['name'])) {
                $destinationPath = public_path('/dist/img/system-img');
                //Cek and Create Destination Path
                if(!is_dir($destinationPath)){ mkdir($destinationPath, 0755, TRUE); }
                //Get & Cek File
                $getFile = SystemInfo::select()->whereId(1)->first();
                //Thumb
                if(!empty($_FILES['thumb']['name'])){
                    if($getFile==true) {
                        $getFileThumb = $destinationPath.'/'.$getFile->thumb;
                        if(file_exists($getFileThumb) && $getFile->thumb)
                            unlink($getFileThumb);
                    }
                    $doUploadFile = $this->_doUploadFileSystemInfo($request->file('thumb'), $destinationPath, 'thumb');
                    $data['thumb'] = $doUploadFile['file_name'];
                }
                //Login Background
                if(!empty($_FILES['login_bg']['name'])){
                    if($getFile==true) {
                        $getFileLoginBg = $destinationPath.'/'.$getFile->login_bg;
                        if(file_exists($getFileLoginBg) && $getFile->login_bg)
                            unlink($getFileLoginBg);
                    }
                    $doUploadFile = $this->_doUploadFileSystemInfo($request->file('login_bg'), $destinationPath, 'login_bg');
                    $data['login_bg'] = $doUploadFile['file_name'];
                }
                //Login Logo
                if(!empty($_FILES['login_logo']['name'])){
                    if($getFile==true) {
                        $getFileLoginLogo = $destinationPath.'/'.$getFile->login_logo;
                        if(file_exists($getFileLoginLogo) && $getFile->login_logo)
                            unlink($getFileLoginLogo);
                    }
                    $doUploadFile = $this->_doUploadFileSystemInfo($request->file('login_logo'), $destinationPath, 'login_logo');
                    $data['login_logo'] = $doUploadFile['file_name'];
                }
                //Backend Logo
                if(!empty($_FILES['backend_logo']['name'])){
                    if($getFile==true) {
                        $getFileBackendLogo = $destinationPath.'/'.$getFile->backend_logo;
                        if(file_exists($getFileBackendLogo) && $getFile->backend_logo)
                            unlink($getFileBackendLogo);
                    }
                    $doUploadFile = $this->_doUploadFileSystemInfo($request->file('backend_logo'), $destinationPath, 'backend_logo');
                    $data['backend_logo'] = $doUploadFile['file_name'];
                }
                //Backend Icon Logo
                if(!empty($_FILES['backend_logo_icon']['name'])){
                    if($getFile==true) {
                        $getFileBackendLogoIcon = $destinationPath.'/'.$getFile->backend_logo_icon;
                        if(file_exists($getFileBackendLogoIcon) && $getFile->backend_logo_icon)
                            unlink($getFileBackendLogoIcon);
                    }
                    $doUploadFile = $this->_doUploadFileSystemInfo($request->file('backend_logo_icon'), $destinationPath, 'backend_logo_icon');
                    $data['backend_logo_icon'] = $doUploadFile['file_name'];
                }
            }

            SystemInfo::whereId(1)->update($data);
            addToLog('The system info has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Informasi sistem berhasil diperbarui', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    private function _doUploadFileSystemInfo($fileInput, $destinationPath, $nameInput) {
        try {
            $fileExtension = $fileInput->getClientOriginalExtension();
            $fileOriginName = $fileInput->getClientOriginalName();
            $fileNewName = strtolower(Str::slug(pathinfo($nameInput.$fileOriginName, PATHINFO_FILENAME))) . time();
            $fileNewNameExt = $fileNewName . '.' . $fileExtension;
            $fileInput->move($destinationPath, $fileNewNameExt);

            return [
                'file_name' => $fileNewNameExt
            ];
        } catch (\Exception $exception) {
            return [
                "Message" => $exception->getMessage(),
                "Trace" => $exception->getTrace()
            ];
        }
    }
}