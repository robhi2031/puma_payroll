<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\ManPowerImport;
use App\Models\BankAccount;
use App\Models\ManPower;
use App\Traits\Select2Common;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
// use Excel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class ManpowerController extends Controller
{
    use SystemCommon;
    use Select2Common;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['direct_permission:manpower-read'])->only(['index', 'show', 'select2_project', 'select2_jobposition']);
        $this->middleware(['direct_permission:manpower-create'])->only(['store', 'import']);
        $this->middleware(['direct_permission:manpower-update'])->only(['update']);
        $this->middleware(['direct_permission:manpower-delete'])->only('delete');
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
            'title' => 'Kelola Data Karyawan',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.v817.css',
            '/dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            '/dist/plugins/bootstrap-file-input/css/fileinput.min.css',
            '/dist/plugins/bootstrap-file-input/themes/explorer-fa5/theme.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.v817.js',
            '/dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            '/dist/plugins/bootstrap-file-input/js/plugins/piexif.min.js',
            '/dist/plugins/bootstrap-file-input/js/plugins/sortable.min.js',
            '/dist/plugins/bootstrap-file-input/js/fileinput.min.js',
            '/dist/plugins/bootstrap-file-input/themes/bs5/theme.min.js',
            '/dist/plugins/bootstrap-file-input/themes/explorer-fa5/theme.min.js',
            '/dist/js/backend_app.init.js',
            '/scripts/backend/manage_manpower.init.js'
        );

        addToLog('Mengakses halaman Kelola Data Karyawan - Backend');
        return view('backend.manage_manpower', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        if(isset($request->idp)){
            try {
                $getRow = ManPower::selectRaw("man_power.*")
                    ->leftJoin('project AS b', 'b.code', '=', 'man_power.project_code')
                    ->leftJoin('job_position AS c', 'c.code', '=', 'man_power.jobposition_code')
                    ->leftJoin('bank_account AS d', 'd.id', '=', 'man_power.fid_bank_account')
                    ->where('man_power.id', $request->idp)
                    ->first();
                if($getRow != null){
                    return jsonResponse(true, 'Success', 200, $getRow);
                } else {
                    return jsonResponse(false, "Credentials not match", 401);
                }
            } catch (\Exception $exception) {
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            $data = ManPower::selectRaw("man_power.*, b.name AS project, c.name AS job_position, 'action' as action")
                ->leftJoin('project AS b', 'b.code', '=', 'man_power.project_code')
                ->leftJoin('job_position AS c', 'c.code', '=', 'man_power.jobposition_code')
                ->leftJoin('bank_account AS d', 'd.id', '=', 'man_power.fid_bank_account')
                ->orderByDesc('man_power.id')->get();

            $output = Datatables::of($data)->addIndexColumn()
                ->addColumn('bn', function ($row) {
                    $bnCustom = 'PJU: '.$row->pju_bn.'<br/>Ext.: '.$row->ext_bn;
                    return $bnCustom;
                })
                ->editColumn('shift_code', function ($row) {
                    $shiftGroup = '';
                    if($row->shift_group != '' || $row->shift_group != null) {
                        $shiftGroup = ' (' .$row->shift_group. ')';
                    }
                    $payCode = '-';
                    if($row->pay_code != '' || $row->pay_code != null) {
                        $payCode = $row->pay_code;
                    }
                    $shiftCustom = $row->shift_code.$shiftGroup.'<br/>Pay Code: '.$payCode;
                    return $shiftCustom;
                })
                ->editColumn('work_status', function ($row) {
                    $statusCustom = '<span class="badge badge-light-primary" data-bs-toggle="tooltip" title="Karyawan aktif">' .$row->work_status. '</span>';
                    if($row->work_status == 'NON ACTIVE') {
                        $statusCustom = '<span class="badge badge-light-danger" data-bs-toggle="tooltip" title="Karyawan tidak aktif (Resign)">' .$row->work_status. '</span>';
                    }
                    return $statusCustom;
                })
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editManpower('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    $btnDtl = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Detail data!" onclick="_dtlManpower('."'".$row->id."'".');"><i class="la la-search fs-3"></i></button>';
                    return $btnEdit.$btnDtl;
                })
                ->rawColumns(['bn', 'shift_code', 'work_status', 'action'])
                ->make(true);
    
            return $output;
        }
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'code' => 'required|max:12',
            'name' => 'required|max:225',
            'desc' => 'required|max:255',
            'client' => 'required|max:225',
            'location' => 'required|max:225',
            'start_date' => 'required|max:10',
            'end_date' => 'required|max:10',
            'status' => 'required',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $code = strtoupper($request->code);
            if(Project::where('code', $code)->exists()) {
                addToLog('Data cannot be saved, the same project code already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, kode proyek yang sama sudah ada pada sistem. Coba gunakan kode yang berbeda', 200, array('error_code' => 'code_available'));
            }
            $start_date = str_replace('/', '-', $request->start_date);
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = str_replace('/', '-', $request->end_date);
            $end_date = date('Y-m-d', strtotime($end_date));
            //array data
            $data = array(
                'code' => $code,
                'name' => $request->name,
                'desc' => $request->desc,
                'client' => $request->client,
                'location' => $request->location,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $request->status,
                'user_add' => $userSesIdp
            );
            Project::insert($data);
            addToLog('Project has been successfully added');
            DB::commit();
            return jsonResponse(true, 'Proyek berhasil ditambahkan', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }    
    /**
     * import
     *
     * @param  mixed $request
     * @return void
     */
    public function import(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'file_import' => 'mimes:xlsx,xls,csv|max:8192',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $rows = Excel::toArray(new ManPowerImport, $request->file('file_import'));
            // $collection = Excel::toCollection(new ManPowerImport, $request->file('file_import'));
            if (count($rows[0]) > 0) {
                foreach ($rows[0] as $row) {
                    //Work Status
                    $workStatus = 'ACTIVE';
                    if ($row['work_status'] == 0) {
                        $workStatus = 'NON ACTIVE';
                    }
                    //Array Manpower
                    $dataManpower = array(
                        'pju_bn' => $this->generated_pjubn(),
                        'ext_bn' => $row['ext_bn'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'project_code' => $row['project_code'],
                        'jobposition_code' => $row['jobposition_code'],
                        'department' => $row['department'],
                        'npwp' => $row['npwp'],
                        'kpj' => $row['kpj'],
                        'kis' => $row['kis'],
                        'marital_status' => $row['marital_status'],
                        'shift_code' => $row['shift_code'],
                        'pay_code' => $row['pay_code'],
                        'shift_group' => $row['shift_group'],
                        'is_daily' => $row['is_daily'],
                        'daily_basic' => $row['daily_basic'],
                        'basic_salary' => $row['basic_salary'],
                        'ot_rate' => $row['ot_rate'],
                        'attendance_fee' => $row['attendance_fee'],
                        'leave_day' => $row['leave_day'],
                        'premi_sore' => $row['premi_sore'],
                        'premi_malam' => $row['premi_malam'],
                        'thr' => $row['thr'],
                        'transport' => $row['transport'],
                        'uang_cuti' => $row['uang_cuti'],
                        'uang_makan' => $row['uang_makan'],
                        'bonus' => $row['bonus'],
                        'interim_location' => $row['interim_location'],
                        'tunjangan_jabatan' => $row['tunjangan_jabatan'],
                        'p_biaya_fasilitas' => $row['p_biaya_fasilitas'],
                        'pengobatan' => $row['pengobatan'],
                        'work_status' => $workStatus,
                    );
                    //Array Akun Bank
                    $dataBankAccount = array(
                        'bank_name' => $row['bank_name'],
                        'account_name' => $row['account_name'],
                        'account_number' => $row['account_number'],
                    );
                    //cek & get manpower
                    $manpower = ManPower::where('email', $row['email'])->first();
                    if($manpower == true) {
                        // Update Manpower
                        $dataManpower['user_updated'] = $userSesIdp;
                        ManPower::whereId($manpower->id)->update($dataManpower);
                        addToLog('Manpower by email ' .$row['email']. ' has been successfully updated from import file');
                        // Update Bank Account
                        $dataBankAccount['user_updated'] = $userSesIdp;
                        BankAccount::whereId($manpower->fid_bank_account)->update($dataBankAccount);
                        addToLog('Bank account by name ' .$row['account_name']. ' has been successfully updated from import file');
                    } else {
                        // Insert Bank Account
                        $dataBankAccount['user_add'] = $userSesIdp;
                        $bankAccountId = BankAccount::insertGetId($dataBankAccount);
                        addToLog('Bank account by name ' .$row['account_name']. ' has been successfully inserted from import file');
                        // Insert Manpower
                        $dataManpower['fid_bank_account'] = $bankAccountId;
                        $dataManpower['user_add'] = $userSesIdp;
                        ManPower::insert($dataManpower);
                        addToLog('Manpower by email ' .$row['email']. ' has been successfully inserted from import file');
                    }
                }
            }
            addToLog('Import Manpower data has been successfully');
            DB::commit();
            return jsonResponse(true, 'Import data karyawan berhasil', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * generated_pjubn
     *
     * @return void
     */
    private function generated_pjubn() {
        $mNow = date('m');
        $yNow = date('y');
        $yearNow = date('Y');

        $pjuBn = 0;

        $manpower = ManPower::selectRaw('MAX(pju_bn) AS pju_bn')
        ->whereMonth('created_at', $mNow)
        ->whereYear('created_at', $yearNow)
        ->first();

        $pjuBn = $manpower->pju_bn;
        $btn	= intval(substr($pjuBn,4,4));
        $btx	= "";

        $btn	= $btn+1;
        if(strlen($btn)==1){
        $btx	= $yNow.$mNow."000".$btn;
        }else if(strlen($btn)==2){
        $btx	= $yNow.$mNow."00".$btn;
        }else if(strlen($btn)==3){
        $btx	= $yNow.$mNow."0".$btn;
        }else{
        $btx	= $yNow.$mNow.$btn;
        }

        $pjuBn = $btx;

        return $pjuBn;
    }
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'name' => 'required|max:50',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $code = strtoupper($request->code);
            if(Project::where('code', $code)->where('id', '!=' , $request->id)->exists()) {
                addToLog('Data cannot be updated, the same project code already exists in the system');
                return jsonResponse(false, 'Gagal memperbarui data, kode proyek yang sama sudah ada pada sistem. Coba gunakan kode yang berbeda', 200, array('error_code' => 'code_available'));
            }
            $start_date = str_replace('/', '-', $request->start_date);
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = str_replace('/', '-', $request->end_date);
            $end_date = date('Y-m-d', strtotime($end_date));
            //array data
            $data = array(
                'code' => $code,
                'name' => $request->name,
                'desc' => $request->desc,
                'client' => $request->client,
                'location' => $request->location,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $request->status,
                'user_updated' => $userSesIdp
            );
            Project::whereId($request->id)->update($data);
            addToLog('Project has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Project berhasil diperbarui', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * select2_project
     *
     * @param  mixed $request
     * @return void
     */
    public function select2_project(Request $request)
    {
        try {
            $output = $this->select2_project_rows($request->search, $request->page);
            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * select2_jobposition
     *
     * @param  mixed $request
     * @return void
     */
    public function select2_jobposition(Request $request)
    {
        try {
            $output = $this->select2_jobposition_rows($request->search, $request->page);
            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}