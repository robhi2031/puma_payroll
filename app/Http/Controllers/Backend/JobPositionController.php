<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JobPosition;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class JobPositionController extends Controller
{
    use SystemCommon;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['direct_permission:job-position-read'])->only(['index', 'show']);
        $this->middleware(['direct_permission:job-position-create'])->only(['store']);
        $this->middleware(['direct_permission:job-position-update'])->only(['update']);
        $this->middleware(['direct_permission:job-position-delete'])->only('delete');
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
            'title' => 'Kelola Data Posisi Pekerjaan',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.v817.css',
            '/dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.v817.js',
            '/dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            '/dist/js/backend_app.init.js',
            '/dist/scripts/backend/manage_jobposition.init.js'
        );

        addToLog('Mengakses halaman Kelola Data Posisi Pekerjaan - Backend');
        return view('backend.manage_jobposition', compact('data'));
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
                $getRow = JobPosition::whereId($request->idp)->first();
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
            $data = JobPosition::selectRaw("job_position.*, 'action' as action")->orderByDesc('id')->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editJobPosition('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
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
            'code' => 'required|max:8',
            'name' => 'required|max:150',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $code = strtoupper($request->code);
            $name = strtoupper($request->name);
            if(JobPosition::where('code', $code)->exists()) {
                addToLog('Data cannot be saved, the same job position code already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, kode posisi pekerjaan yang sama sudah ada pada sistem. Coba gunakan kode yang berbeda', 200, array('error_code' => 'code_available'));
            } if(JobPosition::where('name', $name)->exists()) {
                addToLog('Data cannot be saved, the same job position name already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, nama posisi pekerjaan yang sama sudah ada pada sistem. Coba gunakan nama yang berbeda', 200, array('error_code' => 'name_available'));
            }
            //array data
            $data = array(
                'code' => $code,
                'name' => $name,
                'user_add' => $userSesIdp
            );
            JobPosition::insert($data);
            addToLog('Job position has been successfully added');
            DB::commit();
            return jsonResponse(true, 'Posisi pekerjaan berhasil ditambahkan', 200);
        } catch (\Exception $exception) {
            DB::rollback();
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
    public function update(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'code' => 'required|max:8',
            'name' => 'required|max:150',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $code = strtoupper($request->code);
            $name = strtoupper($request->name);
            if(JobPosition::where('code', $code)->where('id', '!=' , $request->id)->exists()) {
                addToLog('Data cannot be updated, the same job position code already exists in the system');
                return jsonResponse(false, 'Gagal memperbarui data, kode posisi pekerjaan yang sama sudah ada pada sistem. Coba gunakan kode yang berbeda', 200, array('error_code' => 'code_available'));
            } if(JobPosition::where('name', $name)->where('id', '!=' , $request->id)->exists()) {
                addToLog('Data cannot be updated, the same job position name already exists in the system');
                return jsonResponse(false, 'Gagal memperbarui data, nama posisi pekerjaan yang sama sudah ada pada sistem. Coba gunakan nama yang berbeda', 200, array('error_code' => 'name_available'));
            }
            //array data
            $data = array(
                'code' => $code,
                'name' => $name,
                'user_updated' => $userSesIdp
            );
            JobPosition::whereId($request->id)->update($data);
            addToLog('Job position has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Posisi pekerjaan berhasil diperbarui', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}