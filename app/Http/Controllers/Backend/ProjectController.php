<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    use SystemCommon;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['direct_permission:project-read'])->only(['index', 'show']);
        $this->middleware(['direct_permission:project-create'])->only(['store']);
        $this->middleware(['direct_permission:project-update'])->only(['update']);
        $this->middleware(['direct_permission:project-delete'])->only('delete');
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
            'title' => 'Kelola Data Proyek',
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
            '/scripts/backend/manage_project.init.js'
        );

        addToLog('Mengakses halaman Kelola Data Proyek - Backend');
        return view('backend.manage_project', compact('data'));
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
                $getRow = Project::selectRaw("project.*, DATE_FORMAT(project.start_date, '%d/%m/%Y') AS start_date_indo, DATE_FORMAT(project.end_date, '%d/%m/%Y') AS end_date_indo")->where('id', $request->idp)->first();
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
            $data = Project::selectRaw("project.*, 'action' as action")->orderByDesc('id')->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->addColumn('project_date', function ($row) {
                    $dateCustom = 'Mulai: '.mediumdate_indo($row->start_date).'<br/>Selesai: '.mediumdate_indo($row->end_date);
                    return $dateCustom;
                })
                ->editColumn('status', function ($row) {
                    $statusCustom = '<span data-kt-element="status" class="badge badge-light-warning" data-bs-toggle="tooltip" title="Belum berjalan"><i class="bi bi-dash-circle me-1 text-warning"></i>Not Started</span>';
                    if($row->status == 'In Progress') {
                        $statusCustom = '<span data-kt-element="status" class="badge badge-light-info" data-bs-toggle="tooltip" title="Sedang berjalan"><i class="bi bi-bootstrap-reboot text-info me-1"></i>In Progress</span>';
                    } if($row->status == 'Completed') {
                        $statusCustom = '<span data-kt-element="status" class="badge badge-light-primary" data-bs-toggle="tooltip" title="Selesai"><i class="bi bi-check2-circle text-primary me-1"></i>Completed</span>';
                    } if($row->status == 'Stop') {
                        $statusCustom = '<span data-kt-element="status" class="badge badge-light-danger" data-bs-toggle="tooltip" title="Berhenti"><i class="bi bi-sign-stop text-danger me-1"></i>Stop</span>';
                    }
                    return $statusCustom;
                })
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editProject('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    return $btnEdit;
                })
                ->rawColumns(['project_date', 'status', 'action'])
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
}