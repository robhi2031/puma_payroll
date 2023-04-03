<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\Select2Common;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    use SystemCommon;
    use Select2Common;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('permission:roles-read', ['only' => ['index', 'show', 'show_permissions', 'select2_permissions']]);
        $this->middleware('permission:roles-create', ['only' => ['store', 'store_permissionrole']]);
        $this->middleware('permission:roles-update', ['only' => ['update', 'update_permissionbyrole']]);
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
            'title' => 'Kelola Roles System',
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
            '/scripts/backend/manage_roles.init.js'
        );

        addToLog('Mengakses halaman Kelola Roles System - Backend');
        return view('backend.manage_roles', compact('data'));
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
                $getRow = Role::select('id', 'name')->where('id', $request->idp)->first();
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
            $data = Role::selectRaw("id, name, 'action' as action")->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btnPermissions = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-warning mb-1 me-1" data-bs-toggle="tooltip" title="Setting Permissions?" onclick="_settingPermissions('."'".$row->id."'".', '."'".$row->name."'".');"><i class="las la-tasks fs-3"></i></button>';
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editRole('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    return $btnPermissions.$btnEdit;
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
            'name' => 'required|max:50',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'guard_name' => 'web',
                'user_add' => $userSesIdp
            );
            Role::insert($data);
            addToLog('Role has been successfully added');
            DB::commit();
            return jsonResponse(true, 'Role berhasil ditambahkan', 200);
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
            //array data
            $data = array(
                'name' => $request->name,
                'user_updated' => $userSesIdp
            );
            Role::whereId($request->id)->update($data);
            addToLog('Role has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Role berhasil diperbarui', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * show_permissions
     *
     * @param  mixed $request
     * @return void
     */
    public function show_permissions(Request $request) {
        $idp = $request->idp;
        $data = DB::table('permission_has_menus AS a')
            ->selectRaw("a.id, a.name, c.role_id")
            ->leftJoin('permissions AS b', 'b.fid_menu', '=', 'a.id')
            ->leftJoin('role_has_permissions AS c', 'c.permission_id', '=', 'b.id')
            ->leftJoin('roles AS d', 'd.id', '=', 'c.role_id')
            ->where('c.role_id', $idp)
            // ->where('a.has_child', 'N')
            ->groupBy('b.fid_menu')
            ->orderBy('a.order_line', 'ASC')
            ->get();

        $output = Datatables::of($data)->addIndexColumn()
            ->addColumn('read', function($row) use ($idp) {
                $subRow = DB::table('permissions AS a')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->where('b.role_id', $idp)
                ->where('a.fid_menu', $row->id)
                ->whereRaw('RIGHT(a.name, 4)="read"')
                ->first();
                $check = '<button type="button" class="btn btn-sm btn-light mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'false'".', '."'read'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                if($subRow==true){
                    $check = '<button type="button" class="btn btn-sm btn-info mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'true'".', '."'read'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                }
                return $check;
            })
            ->addColumn('create', function($row) use ($idp) {
                $subRow = DB::table('permissions AS a')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->where('b.role_id', $idp)
                ->where('a.fid_menu', $row->id)
                ->whereRaw('RIGHT(a.name, 6)="create"')
                ->first();
                $check = '<button type="button" class="btn btn-sm btn-light mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'false'".', '."'create'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                if($subRow==true){
                    $check = '<button type="button" class="btn btn-sm btn-info mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'true'".', '."'create'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                }
                return $check;
            })
            ->addColumn('update', function($row) use ($idp) {
                $subRow = DB::table('permissions AS a')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->where('b.role_id', $idp)
                ->where('a.fid_menu', $row->id)
                ->whereRaw('RIGHT(a.name, 6)="update"')
                ->first();
                $check = '<button type="button" class="btn btn-sm btn-light mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'false'".', '."'update'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                if($subRow==true){
                    $check = '<button type="button" class="btn btn-sm btn-info mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'true'".', '."'update'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                }
                return $check;
            })
            ->addColumn('delete', function($row) use ($idp) {
                $subRow = DB::table('permissions AS a')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->where('b.role_id', $idp)
                ->where('a.fid_menu', $row->id)
                ->whereRaw('RIGHT(a.name, 6)="delete"')
                ->first();
                $check = '<button type="button" class="btn btn-sm btn-light mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'false'".', '."'delete'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                if($subRow==true){
                    $check = '<button type="button" class="btn btn-sm btn-info mb-1" onclick="_updatePermission('."'".$row->id."'".', '."'".$idp."'".', '."'true'".', '."'delete'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                }
                return $check;
            })
            ->rawColumns(['read', 'create', 'update', 'delete'])
            ->make(true);

        return $output;
    }    
    /**
     * select2_permissions
     *
     * @param  mixed $request
     * @return void
     */
    public function select2_permissions(Request $request)
    {
        try {
            $output = $this->select2_permission($request->search, $request->page, '');
            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * store_permissionrole
     *
     * @param  mixed $request
     * @return void
     */
    public function store_permissionrole(Request $request)
    {
        $userSesIdp = Auth::user()->id;
        $form = [
            'cbo_permission' => 'required',
        ];
        $idpRole = $request->idpRole;
        $IdpPermission = $request->cbo_permission;
        $create = isset($request->create) ? (int)$request->create : 0;
        $read = isset($request->read) ? (int)$request->read : 0;
        $update = isset($request->update) ? (int)$request->update : 0;
        $delete = isset($request->delete) ? (int)$request->delete : 0;
        DB::beginTransaction();
        $request->validate($form);
        try {
            //Create
            $this->_storePermissionToRole($idpRole, $IdpPermission, $create, 'create');
            //Read
            $this->_storePermissionToRole($idpRole, $IdpPermission, $read, 'read');
            //Update
            $this->_storePermissionToRole($idpRole, $IdpPermission, $update, 'update');
            //Delete
            $this->_storePermissionToRole($idpRole, $IdpPermission, $delete, 'delete');

            addToLog('Assign permission to Role has been successfully Add');
            $textMsg = 'Permission baru berhasil ditambahkan pada role';
            DB::commit();
            return jsonResponse(true, $textMsg, 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    private function _storePermissionToRole($idpRole, $IdpPermission, $value, $type)
    {
        $lengthType = 6;
        if($type=='read') {
            $lengthType = 4;
        }
        $permission = Permission::where('fid_menu', $IdpPermission)->whereRaw('RIGHT(name, '.$lengthType.')="'.$type.'"')->first();
        $role = Role::where('id', $idpRole)->first();
        if ($value == 1) {
            if($permission == true) {
                $role->givePermissionTo($permission->name);
                assignPermissionToUser($idpRole, $permission);
            }
        } else {
            if($permission == true) {
                $role->revokePermissionTo($permission->name);
                revokePermissionToUser($idpRole, $permission);
            }
        }
    }
    /**
     * update_permissionbyrole
     *
     * @param  mixed $request
     * @return void
     */
    public function update_permissionbyrole(Request $request)
    {
        $userSesIdp = Auth::user()->id;
        $idpMenu = $request->idpMenu;
        $idpRole = $request->idpRole;
        $value = $request->value;
        $type = $request->type;
        $lengthType = 6;
        if($type=='read') {
            $lengthType = 4;
        }
        DB::beginTransaction();
        try {
            $permission = Permission::where('fid_menu', $idpMenu)->whereRaw('RIGHT(name, '.$lengthType.')="'.$type.'"')->first();
            $role = Role::where('id', $idpRole)->first();
            if ($value == 'false') {
                $role->givePermissionTo($permission->name);
                assignPermissionToUser($idpRole, $permission);
                addToLog('Assign permission to Role has been successfully updated');
                $textMsg = 'Enable permission <strong>' .$type. '</strong> berhasil';
            } else {
                $role->revokePermissionTo($permission->name);
                revokePermissionToUser($idpRole, $permission);
                addToLog('Revoke permission from Role has been successfully updated');
                $textMsg = 'Disable permission <strong>' .$type. '</strong> berhasil';
            }
            DB::commit();
            return jsonResponse(true, $textMsg, 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}