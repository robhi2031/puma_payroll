<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Company;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CompanyAboutController extends Controller
{
    use SystemCommon;
    public function __construct()
    {
        $this->middleware(['direct_permission:about-read'])->only(['index', 'show']);
        $this->middleware(['direct_permission:about-update'])->only('update');
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
            'title' => 'Kelola Informasi Perusahaan',
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
            '/dist/js/jquery.mask.min.js',
            '/dist/js/backend_app.init.js',
            '/dist/scripts/backend/manage_companyabout.init.js'
        );

        addToLog('Mengakses halaman Kelola Informasi Perusahaan - Backend');
        return view('backend.manage_companyabout', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        try {
            $getRow = Company::whereId(1)->first();
            if($getRow != null){
                //Logo Company
                $companyLogo = $getRow->logo;
                if($companyLogo==''){
                    $getRow->url_logo = NULL;
                } else {
                    if (!file_exists(public_path(). '/dist/img/company-img/'.$companyLogo)){
                        $getRow->url_logo = NULL;
                        $getRow->logo = NULL;
                    }else{
                        $getRow->url_logo = url('dist/img/company-img/'.$companyLogo);
                    }
                }
                $getRow->bank = null;
                $getBank = BankAccount::whereId($getRow->fid_bank_account)->first();
                if($getBank != null){
                    $getRow->bank = $getBank;
                }
                return jsonResponse(true, 'Success', 200, $getRow);
            } else {
                return jsonResponse(false, "Credentials not match", 401);
            }
        } catch (Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
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
            'name' => 'required|max:225',
            'short_description' => 'required|max:255',
            'logo' => 'mimes:png,jpg,jpeg|max:2048',
            'profile' => 'required',
            'email' => 'required',
            'phone_number' => 'required|max:15',
            'office_address' => 'required|max:225',
            'office_lat_coordinate' => 'required|max:100',
            'office_long_coordinate' => 'required|max:100',
            'npwp' => 'required|max:25',
            'no_jamsostek' => 'required|max:15',
            'bank_name' => 'required|max:150',
            'account_name' => 'required|max:150',
            'account_number' => 'required|max:17',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'short_description' => $request->short_description,
                'profile' => $request->profile,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'office_address' => $request->office_address,
                'office_address_coordinate' => $request->office_lat_coordinate.','.$request->office_long_coordinate,
                'npwp' => $request->npwp,
                'no_jamsostek' => $request->no_jamsostek,
                'user_updated' => $userSesIdp
            );
            //UPDATE FILE
            if(!empty($_FILES['logo']['name'])) {
                $destinationPath = public_path('/dist/img/company-img');
                //Cek and Create Destination Path
                if(!is_dir($destinationPath)){ mkdir($destinationPath, 0755, TRUE); }
                //Get & Cek File
                $getFile = Company::whereId(1)->first();
                if($getFile==true) {
                    $getFileLogo = $destinationPath.'/'.$getFile->logo;
                    if(file_exists($getFileLogo) && $getFile->logo)
                        unlink($getFileLogo);
                }
                $logoFile = $request->file('logo');
                $logoExtension = $logoFile->getClientOriginalExtension();
                $logoOriginName = $logoFile->getClientOriginalName();
                $logoNewName = strtolower(Str::slug('logo-'.bcrypt(pathinfo($logoOriginName, PATHINFO_FILENAME)))) . time();
                $logoNewNameExt = $logoNewName . '.' . $logoExtension;
                $logoFile->move($destinationPath, $logoNewNameExt);

                $data['logo'] = $logoNewNameExt;
            }
            //Fid Bank
            if($request->fid_bank_account == '' || $request->fid_bank_account == null) {
                $request->fid_bank_account = 1;
                $data['fid_bank_account'] = $request->fid_bank_account;
            }
            Company::updateOrCreate(['id' => 1], $data);
            //array data account bank
            $dataBank = array(
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'user_updated' => $userSesIdp
            );
            BankAccount::updateOrCreate(['id' => $request->fid_bank_account], $dataBank);
            addToLog('The company info has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Informasi perusahaan berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}