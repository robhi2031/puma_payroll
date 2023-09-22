<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\ManPowerImport;
use App\Models\BankAccount;
use App\Models\JobPosition;
use App\Models\ManPower;
use App\Models\Project;
use App\Traits\Select2Common;
use App\Traits\SystemCommon;
use GuzzleHttp\Client;
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
            '/dist/js/jquery.mask.min.js',
            '/dist/js/backend_app.init.js',
            '/dist/scripts/backend/manage_manpower.init.js'
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
                $getRow = ManPower::selectRaw("man_power.*, b.name AS project_name, c.name AS job_position, d.bank_name, d.account_name, d.account_number, d.is_active")
                    ->leftJoin('project AS b', 'b.id', '=', 'man_power.fid_last_project')
                    ->leftJoin('job_position AS c', 'c.id', '=', 'man_power.fid_job_position')
                    ->leftJoin('bank_account AS d', 'd.id', '=', 'man_power.fid_bank_account')
                    ->where('man_power.id', $request->idp)
                    ->first();
                if($getRow != null){
                    $getRow->rp_daily_basic = numberFormat($getRow->daily_basic);
                    $getRow->rp_basic_salary = numberFormat($getRow->basic_salary);
                    $getRow->rp_ot_rate = numberFormat($getRow->ot_rate);
                    $getRow->rp_attendance_fee = numberFormat($getRow->attendance_fee);
                    $getRow->rp_leave_day = numberFormat($getRow->leave_day);
                    $getRow->rp_premi_sore = numberFormat($getRow->premi_sore);
                    $getRow->rp_premi_malam = numberFormat($getRow->premi_malam);
                    $getRow->rp_thr = numberFormat($getRow->thr);
                    $getRow->rp_transport = numberFormat($getRow->transport);
                    $getRow->rp_uang_cuti = numberFormat($getRow->uang_cuti);
                    $getRow->rp_uang_makan = numberFormat($getRow->uang_makan);
                    $getRow->rp_bonus = numberFormat($getRow->bonus);
                    $getRow->rp_interim_location = numberFormat($getRow->interim_location);
                    $getRow->rp_tunjangan_jabatan = numberFormat($getRow->tunjangan_jabatan);
                    $getRow->rp_p_biaya_fasilitas = numberFormat($getRow->p_biaya_fasilitas);
                    $getRow->rp_pengobatan = numberFormat($getRow->pengobatan);

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
                ->leftJoin('project AS b', 'b.id', '=', 'man_power.fid_last_project')
                ->leftJoin('job_position AS c', 'c.id', '=', 'man_power.fid_job_position')
                ->leftJoin('bank_account AS d', 'd.id', '=', 'man_power.fid_bank_account')
                ->orderByDesc('man_power.id')->get();

            $output = Datatables::of($data)->addIndexColumn()
                ->addColumn('bn', function ($row) {
                    $bnCustom = 'PJU: '.$row->pju_bn.'<br/>Ext.: '.$row->ext_bn;
                    return $bnCustom;
                })
                ->editColumn('department', function ($row) {
                    return $row->department ? $row->department : '-';
                })
                ->editColumn('shift_code', function ($row) {
                    $shiftCode = '-';
                    if($row->shift_code != '' || $row->shift_code != null) {
                        $shiftCode = $row->shift_code;
                    }
                    $shiftGroup = '';
                    if($row->shift_group != '' || $row->shift_group != null) {
                        $shiftGroup = ' (' .$row->shift_group. ')';
                    }
                    $payCode = '-';
                    if($row->pay_code != '' || $row->pay_code != null) {
                        $payCode = $row->pay_code;
                    }
                    $shiftCustom = $shiftCode.$shiftGroup.'<br/>Pay Code: '.$payCode;
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
                ->rawColumns(['bn', 'department', 'shift_code', 'work_status', 'action'])
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
            'nik' => 'required|max:20',
            'name' => 'required|max:150',
            'email' => 'required',
            'project_code' => 'required',
            'jobposition_code' => 'required',
            'department' => 'required|max:150',
            'npwp' => 'required|max:25',
            'kpj' => 'required|max:25',
            'kis' => 'required|max:25',
            'marital_status' => 'required',
            'shift_code' => 'required',
            'pay_code' => 'required',
            'basic_salary' => 'required',
            'bank_name' => 'required|max:150',
            'account_name' => 'required|max:150',
            'account_number' => 'required|max:16',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $nik = $request->nik;
            if(ManPower::where('nik', $nik)->exists()) {
                addToLog('Data cannot be saved, the same manpower NIK already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, NIK yang sama sudah ada pada sistem. Coba gunakan NIK yang berbeda', 200, array('error_code' => 'nik_available'));
            }
            //Array Akun Bank
            $dataBankAccount = array(
                'bank_name' => strtoupper($request->bank_name),
                'account_name' => strtoupper($request->account_name),
                'account_number' => $request->account_number,
                'user_add' => $userSesIdp
            );
            $bankAccountId = BankAccount::insertGetId($dataBankAccount);
            addToLog('Bank account has been successfully added');
            //array data
            $data = array(
                'pju_bn' => $this->generated_pjubn(),
                'ext_bn' => $request->ext_bn !='' ? strtoupper($request->ext_bn) : '-',
                'nik' => $request->nik,
                'name' => $request->name,
                'email' => $email,
                'fid_last_project' => $request->project_code,
                'fid_job_position' => $request->jobposition_code,
                'department' => $request->department,
                'npwp' => $request->npwp,
                'kpj' => $request->kpj,
                'kis' => $request->kis,
                'marital_status' => $request->marital_status,
                'shift_code' => $request->shift_code,
                'pay_code' => $request->pay_code,
                'shift_group' => $request->shift_group,
                'is_daily' => isset($request->is_daily) ? 'Y' : 'N',
                'daily_basic' => $request->daily_basic !='' ? str_replace(".", "", $request->daily_basic) : 0,
                'basic_salary' => $request->basic_salary !='' ? str_replace(".", "", $request->basic_salary) : 0,
                'ot_rate' => $request->ot_rate !='' ? str_replace(".", "", $request->ot_rate) : 0,
                'attendance_fee' => $request->attendance_fee !='' ? str_replace(".", "", $request->attendance_fee) : 0,
                'leave_day' => $request->leave_day !='' ? str_replace(".", "", $request->leave_day) : 0,
                'premi_sore' => $request->premi_sore !='' ? str_replace(".", "", $request->premi_sore) : 0,
                'premi_malam' => $request->premi_malam !='' ? str_replace(".", "", $request->premi_malam) : 0,
                'thr' => $request->thr !='' ? str_replace(".", "", $request->thr) : 0,
                'transport' => $request->transport !='' ? str_replace(".", "", $request->transport) : 0,
                'uang_cuti' => $request->uang_cuti !='' ? str_replace(".", "", $request->uang_cuti) : 0,
                'uang_makan' => $request->uang_makan !='' ? str_replace(".", "", $request->uang_makan) : 0,
                'bonus' => $request->bonus !='' ? str_replace(".", "", $request->bonus) : 0,
                'interim_location' => $request->interim_location !='' ? str_replace(".", "", $request->interim_location) : 0,
                'tunjangan_jabatan' => $request->tunjangan_jabatan !='' ? str_replace(".", "", $request->tunjangan_jabatan) : 0,
                'p_biaya_fasilitas' => $request->p_biaya_fasilitas !='' ? str_replace(".", "", $request->p_biaya_fasilitas) : 0,
                'pengobatan' => $request->pengobatan !='' ? str_replace(".", "", $request->pengobatan) : 0,
                'fid_bank_account' => $bankAccountId,
                'work_status' => 'ACTIVE',
                'user_add' => $userSesIdp
            );
            ManPower::insert($data);
            addToLog('Manpower has been successfully added');
            DB::commit();
            return jsonResponse(true, 'Data Karyawan berhasil ditambahkan', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }    
    /**
     * syncManpower
     *
     * @param  mixed $request
     * @return void
     */
    public function syncManpower(Request $request) {
        $userSesIdp = Auth::user()->id;
        DB::beginTransaction();
        try {
            $client = new Client(
                ['auth' => [config('app.manpower_userkey'), config('app.manpower_passkey')]]
            );
            $url  = config('app.manpower_host').'api/sync_manpower?access_token='.config('app.manpower_synctoken');
            $res = $client->get($url);
            $res = json_decode($res->getBody()->getContents(), TRUE);
            if($res['status'] == true) {
                $employees = $res['data'];
                if (count($employees) > 0) {
                    foreach ($employees as $row) {
                        //Work Status
                        $workStatus = 'ACTIVE';
                        // if ($row['work_status'] == 0) {
                        //     $workStatus = 'NON ACTIVE';
                        // }
                        //Last Project
                        $getProject = Project::whereName(strtoupper($row['project_name']))->first();
                        if($getProject == true) {
                            $fid_last_project = $getProject->id;
                        } else {
                            $newProject = [
                                'name' => strtoupper($row['project_name']),
                                'desc' => '-',
                                'location' => '-',
                                'client' => '-',
                                'user_add' => $userSesIdp,
                            ];
                            $fid_last_project = Project::insertGetId($newProject);
                        }
                        //Job Position
                        $getJobPosition = JobPosition::whereName(strtoupper($row['position']))->first();
                        if($getJobPosition == true) {
                            $fid_job_position = $getJobPosition->id;
                        } else {
                            $newJobPosition = [
                                'name' => strtoupper($row['position']),
                                'user_add' => $userSesIdp,
                            ];
                            $fid_job_position = JobPosition::insertGetId($newJobPosition);
                        }
                        //PJU BN
                        if($row['badge_number_internal'] != null || $row['badge_number_internal'] != '') {
                            $getPjuBn = ManPower::wherePjuBn($row['badge_number_internal'])->first();
                            if($getPjuBn == true) {
                                $pju_bn = $this->generated_pjubn();
                            } else {
                                $pju_bn = $row['badge_number_internal'];
                            }
                        } else {
                            $pju_bn = $this->generated_pjubn();
                        }
                        //Marital Status
                        $marital_status = 'M3';
                        if($row['martial_status'] == '1') {
                            $marital_status = 'S/L';
                        }
                        //Array Manpower
                        $dataManpower = array(
                            'pju_bn' => $pju_bn,
                            'ext_bn' => $row['badge_number'],
                            'nik' => $row['ktp_number'],
                            'name' => $row['employee_name'],
                            'email' => $row['email'],
                            'fid_last_project' => $fid_last_project,
                            'fid_job_position' => $fid_job_position,
                            'marital_status' => $marital_status,
                            'work_status' => $workStatus,
                        );
                        //Array Akun Bank
                        $dataBankAccount = array(
                            'bank_name' => strtoupper($row['bank_name']),
                            'account_name' => strtoupper($row['bank_account_name']),
                            'account_number' => $row['bank_account_no'],
                        );
                        //cek & get manpower
                        $manpower = ManPower::where('nik', $row['ktp_number'])->first();
                        if($manpower == true) {
                            // Update Manpower
                            $dataManpower['user_updated'] = $userSesIdp;
                            ManPower::whereId($manpower->id)->update($dataManpower);
                            addToLog('Manpower by NIK ' .$row['ktp_number']. ' has been successfully updated from synchronization by Manpower App');
                            // Update Bank Account
                            $dataBankAccount['user_updated'] = $userSesIdp;
                            BankAccount::whereId($manpower->fid_bank_account)->update($dataBankAccount);
                            addToLog('Bank account by name ' .strtoupper($row['bank_name']). ' has been successfully updated from synchronization by Manpower App');
                        } else {
                            // Insert Bank Account
                            $dataBankAccount['user_add'] = $userSesIdp;
                            $bankAccountId = BankAccount::insertGetId($dataBankAccount);
                            addToLog('Bank account by name ' .strtoupper($row['bank_name']). ' has been successfully inserted from synchronization by Manpower App');
                            // Insert Manpower
                            $dataManpower['fid_bank_account'] = $bankAccountId;
                            $dataManpower['user_add'] = $userSesIdp;
                            ManPower::insert($dataManpower);
                            addToLog('Manpower by NIK ' .$row['ktp_number']. ' has been successfully inserted from synchronization by Manpower App');
                        }
                    }
                }
                addToLog('Manpower data has been successfully synchronization');
                DB::commit();
                return jsonResponse(true, 'Data karyawan berhasil disinkronisasikan dari Aplikasi Manpower', 200);
            }
        } catch (Exception $exception) {
            DB::rollBack();
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
                    //Last Project
                    $getProject = Project::whereName(strtoupper($row['project_name']))->first();
                    if($getProject == true) {
                        $fid_last_project = $getProject->id;
                    } else {
                        $newProject = [
                            'name' => strtoupper($row['project_name']),
                            'desc' => '-',
                            'location' => '-',
                            'client' => '-',
                            'user_add' => $userSesIdp,
                        ];
                        $fid_last_project = Project::insertGetId($newProject);
                    }
                    //Job Position
                    $getJobPosition = JobPosition::whereName(strtoupper($row['job_position']))->first();
                    if($getJobPosition == true) {
                        $fid_job_position = $getJobPosition->id;
                    } else {
                        $newJobPosition = [
                            'name' => strtoupper($row['job_position']),
                            'user_add' => $userSesIdp,
                        ];
                        $fid_job_position = JobPosition::insertGetId($newJobPosition);
                    }
                    //PJU BN
                    $getPjuBn = ManPower::wherePjuBn($row['int_bn'])->first();
                    if($getPjuBn == true) {
                        $pju_bn = $this->generated_pjubn();
                    } else {
                        $pju_bn = $row['int_bn'];
                    }
                    //Array Manpower
                    $dataManpower = array(
                        'pju_bn' => $pju_bn,
                        'ext_bn' => $row['ext_bn'],
                        'nik' => $row['nik'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'fid_last_project' => $fid_last_project,
                        'fid_job_position' => $fid_job_position,
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
                        'bank_name' => strtoupper($row['bank_name']),
                        'account_name' => strtoupper($row['account_name']),
                        'account_number' => $row['account_number'],
                    );
                    //cek & get manpower
                    $manpower = ManPower::where('nik', $row['nik'])->first();
                    if($manpower == true) {
                        // Update Manpower
                        $dataManpower['user_updated'] = $userSesIdp;
                        ManPower::whereId($manpower->id)->update($dataManpower);
                        addToLog('Manpower by NIK ' .$row['nik']. ' has been successfully updated from import file');
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
            'nik' => 'required|max:20',
            'name' => 'required|max:150',
            'email' => 'required',
            'project_code' => 'required',
            'jobposition_code' => 'required',
            'department' => 'required|max:150',
            'npwp' => 'required|max:25',
            'kpj' => 'required|max:25',
            'kis' => 'required|max:25',
            'marital_status' => 'required',
            'shift_code' => 'required',
            'pay_code' => 'required',
            'basic_salary' => 'required',
            'bank_name' => 'required|max:150',
            'account_name' => 'required|max:150',
            'account_number' => 'required|max:16',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $nik = $request->nik;
            if(ManPower::where('nik', $nik)->where('id', '!=' , $request->id)->exists()) {
                addToLog('Data cannot be updated, the same manpower NIK already exists in the system');
                return jsonResponse(false, 'Gagal memperbarui data, NIK yang sama sudah ada pada sistem. Coba gunakan NIK yang berbeda', 200, array('error_code' => 'nik_available'));
            }
            //Array Akun Bank
            $dataBankAccount = array(
                'bank_name' => strtoupper($request->bank_name),
                'account_name' => strtoupper($request->account_name),
                'account_number' => $request->account_number,
                'user_updated' => $userSesIdp
            );
            BankAccount::whereId($request->fid_bank_account)->update($dataBankAccount);
            addToLog('Bank account has been successfully updated');
            //array data
            $data = array(
                'ext_bn' => $request->ext_bn !='' ? strtoupper($request->ext_bn) : '-',
                'nik' => $request->nik,
                'name' => $request->name,
                'email' => $email,
                'fid_last_project' => $request->project_code,
                'fid_job_position' => $request->jobposition_code,
                'department' => $request->department,
                'npwp' => $request->npwp,
                'kpj' => $request->kpj,
                'kis' => $request->kis,
                'marital_status' => $request->marital_status,
                'shift_code' => $request->shift_code,
                'pay_code' => $request->pay_code,
                'shift_group' => $request->shift_group,
                'is_daily' => isset($request->is_daily) ? 'Y' : 'N',
                'daily_basic' => $request->daily_basic !='' ? str_replace(".", "", $request->daily_basic) : 0,
                'basic_salary' => $request->basic_salary !='' ? str_replace(".", "", $request->basic_salary) : 0,
                'ot_rate' => $request->ot_rate !='' ? str_replace(".", "", $request->ot_rate) : 0,
                'attendance_fee' => $request->attendance_fee !='' ? str_replace(".", "", $request->attendance_fee) : 0,
                'leave_day' => $request->leave_day !='' ? str_replace(".", "", $request->leave_day) : 0,
                'premi_sore' => $request->premi_sore !='' ? str_replace(".", "", $request->premi_sore) : 0,
                'premi_malam' => $request->premi_malam !='' ? str_replace(".", "", $request->premi_malam) : 0,
                'thr' => $request->thr !='' ? str_replace(".", "", $request->thr) : 0,
                'transport' => $request->transport !='' ? str_replace(".", "", $request->transport) : 0,
                'uang_cuti' => $request->uang_cuti !='' ? str_replace(".", "", $request->uang_cuti) : 0,
                'uang_makan' => $request->uang_makan !='' ? str_replace(".", "", $request->uang_makan) : 0,
                'bonus' => $request->bonus !='' ? str_replace(".", "", $request->bonus) : 0,
                'interim_location' => $request->interim_location !='' ? str_replace(".", "", $request->interim_location) : 0,
                'tunjangan_jabatan' => $request->tunjangan_jabatan !='' ? str_replace(".", "", $request->tunjangan_jabatan) : 0,
                'p_biaya_fasilitas' => $request->p_biaya_fasilitas !='' ? str_replace(".", "", $request->p_biaya_fasilitas) : 0,
                'pengobatan' => $request->pengobatan !='' ? str_replace(".", "", $request->pengobatan) : 0,
                'user_updated' => $userSesIdp
            );
            ManPower::whereId($request->id)->update($data);
            addToLog('Manpower has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Data karyawan berhasil diperbarui', 200);
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