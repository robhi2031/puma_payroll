<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LogActivities;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UsersActivityController extends Controller
{
    use SystemCommon;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['direct_permission:logs-activity-read'])->only(['index', 'show']);
        $this->middleware(['direct_permission:logs-activity-delete'])->only('delete');
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
            'title' => 'Kelola Data Aktivitas User',
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
            '/scripts/backend/users_activity.init.js'
        );

        addToLog('Mengakses halaman Kelola aktivitas user - Backend');
        return view('backend.users_activity', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        $query = LogActivities::selectRaw("log_activities.*, DATE_FORMAT(log_activities.timestamp, '%d/%m/%Y') AS datestamp_indo, DATE_FORMAT(log_activities.timestamp, '%H:%i') AS timestamp_indo, b.name, 'action' as action")
            ->leftJoin('users_system AS b', 'b.id', '=', 'log_activities.fid_user');
        if(isset($request->tgl_start) && isset($request->tgl_end)){
            $tgl_start = str_replace('/', '-', $request->tgl_start);
            $tgl_start = date('Y-m-d', strtotime($tgl_start));
            $tgl_end = str_replace('/', '-', $request->tgl_end);
            $tgl_end = date('Y-m-d', strtotime($tgl_end));
            $query = $query->whereRaw('DATE(log_activities.timestamp) BETWEEN "'.$tgl_start. '" AND "'.$tgl_end.'"');
        }
        $data = $query->orderBy('log_activities.timestamp', 'DESC')->get();
        $output = Datatables::of($data)->addIndexColumn()
            ->addColumn('user', function ($row) {
                $userCustom = $row->name.'<br/> <span class="text-muted">'.$row->ip_address.'</span>';
                return $userCustom;
            })
            ->editColumn('description', function ($row) {
                $userCustom = $row->description.'<br/> <span class="text-muted">'.$row->url.'</span>';
                return $userCustom;
            })
            ->editColumn('timestamp', function ($row) {
                $userCustom = $row->datestamp_indo.' '.$row->timestamp_indo.' WIB<br/> <span class="text-muted"><em>'.time_ago($row->timestamp).'</em></span>';
                return $userCustom;
            })
            ->rawColumns(['user', 'description', 'timestamp'])
            ->make(true);

        return $output;
    }    
    /**
     * delete
     *
     * @param  mixed $request
     * @return void
     */
    public function delete(Request $request) {
        if(isset($request->startDate) && isset($request->endDate)){
            DB::beginTransaction();
            try {
                $startDate = str_replace('/', '-', $request->startDate);
                $endDate = str_replace('/', '-', $request->endDate);
                LogActivities::whereRaw('DATE(log_activities.timestamp) BETWEEN "'.date('Y-m-d', strtotime($startDate)). '" AND "'.date('Y-m-d', strtotime($endDate)).'"')->delete();
                addToLog('Delete all user activity log data from the date: '.date('Y-m-d', strtotime($startDate)). ' to '.date('Y-m-d', strtotime($endDate)));
                DB::commit();
                return jsonResponse(true, 'Semua data aktivitas user dari tanggal: <strong>'.$request->startDate.'</strong> sampai dengan <strong>'.$request->endDate.'</strong> berhasil dibersihkan', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            // DB::beginTransaction();
            try {
                LogActivities::truncate();
                addToLog('Delete all user activity logs has been successfully');
                // DB::commit();
                return jsonResponse(true, 'Semua data aktivitas user berhasil dibersihkan', 200);
            } catch (Exception $exception) {
                // DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
}