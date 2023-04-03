<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\Select2Common;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class PermissionsController extends Controller
{
    use SystemCommon;
    use Select2Common;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('permission:permissions-read', ['only' => ['index', 'show', 'select2_parentpermissions']]);
        $this->middleware('permission:permissions-create', ['only' => ['store']]);
        $this->middleware('permission:permissions-update', ['only' => ['update']]);
        $this->middleware('permission:permissions-delete', ['only' => ['delete']]);
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
            'title' => 'Kelola Permissions System',
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
            '/dist/js/jquery.mask.min.js',
            '/dist/js/backend_app.init.js',
            '/scripts/backend/manage_permissions.init.js'
        );

        addToLog('Mengakses halaman Kelola Permissions System - Backend');
        return view('backend.manage_permissions', compact('data'));
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
                $getRow = DB::table('permission_has_menus')
                    ->where('id', $request->idp)
                    ->first();
                //Parent Custom
                $getRow->parent = null;
                if($getRow->parent_id != null || $getRow->parent_id != '') {
                    $getParent = DB::table('permission_has_menus')
                    ->where('id', $getRow->parent_id)
                    ->first();
                    if($getParent==true) {
                        $getRow->parent = $getParent;
                    }
                }
                //Crud Custom
                $getRow->create = 0;
                $getRow->read = 0;
                $getRow->update = 0;
                $getRow->delete = 0;
                $getCrud = Permission::where('fid_menu', $getRow->id)
                    ->get();
                if($getCrud) {
                    foreach ($getCrud as $crud) {
                        if(substr($crud->name, strrpos($crud->name, "-") + 1)=='create') {
                            $getRow->create = 1;
                        } if(substr($crud->name, strrpos($crud->name, "-") + 1)=='read') {
                            $getRow->read = 1;
                        } if(substr($crud->name, strrpos($crud->name, "-") + 1)=='update') {
                            $getRow->update = 1;
                        } if(substr($crud->name, strrpos($crud->name, "-") + 1)=='delete') {
                            $getRow->delete = 1;
                        }
                    }
                }
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
            $data = DB::table('permission_has_menus')
                ->selectRaw("permission_has_menus.*, 'action' as action")
                ->get();

            $output = Datatables::of($data)->addIndexColumn()
                ->editColumn('icon', function ($row) {
                    $iconCustom = '-';
                    if($row->icon!=null || $row->icon!='') {
                        $iconCustom = '<span class="badge badge-light fs-1"><i class="bi '.$row->icon.' text-dark"></i></span>';
                    }
                    return $iconCustom;
                })
                ->editColumn('route_name', function ($row) {
                    $routeCustom = '-';
                    if($row->has_route=='Y' || $row->has_route=='Y') {
                        $routeCustom = $row->route_name;
                    }
                    return $routeCustom;
                })
                ->addColumn('parent', function ($row) {
                    $parentCustom = '-';
                    if($row->parent_id != null || $row->parent_id != '') {
                        $getParent = DB::table('permission_has_menus')
                        ->selectRaw("permission_has_menus.*, 'action' as action")
                        ->where('id', $row->parent_id)
                        ->first();
                        $parentCustom = $getParent->name;
                    }
                    return $parentCustom;
                })
                ->addColumn('crud', function ($row) {
                    $crudCustom = '';
                    $getCrud = Permission::where('fid_menu', $row->id)
                        ->get();
                    if($getCrud) {
                        foreach ($getCrud as $crud) {
                            $crudCustom .= '<span class="badge badge-light-success me-1 mb-1">'.substr($crud->name, strrpos($crud->name, "-") + 1).'</span>';
                        }
                    }
                    return $crudCustom;
                })
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editPermission('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    return $btnEdit;
                })
                ->rawColumns(['icon', 'crud', 'action'])
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
            'order_line' => 'required|max:6',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'icon' => isset($request->icon) ? $request->icon : NULL,
                'has_route' => isset($request->has_route) ? 'Y' : 'N',
                'route_name' => isset($request->has_route) || $request->route_name !='' ? $request->route_name : NULL,
                'parent_id' => isset($request->has_parent) || $request->cbo_parent !='' ? $request->cbo_parent : NULL,
                'has_child' => isset($request->has_child) ? 'Y' : 'N',
                'is_crud' => isset($request->is_crud) ? 'Y' : 'N',
                'order_line' => $request->order_line,
                'user_add' => $userSesIdp
            );
            $insertPermissionMenu = DB::table('permission_has_menus')->insertGetId($data);
            addToLog('Permission menu has been successfully added');
            //If Crud or Not
            $nameSlug = Str::slug($request->name);
            if(isset($request->is_crud)) {
                if(isset($request->create)) {
                    $this->store_crudpermission('', $nameSlug.'-create', $insertPermissionMenu);
                } if(isset($request->read)) {
                    $this->store_crudpermission('', $nameSlug.'-read', $insertPermissionMenu);
                } if(isset($request->update)) {
                    $this->store_crudpermission('', $nameSlug.'-update', $insertPermissionMenu);
                } if(isset($request->delete)) {
                    $this->store_crudpermission('', $nameSlug.'-delete', $insertPermissionMenu);
                }
            }
            DB::commit();
            return jsonResponse(true, 'Permission/ Menu berhasil ditambahkan', 200);
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
            'order_line' => 'required|max:6',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'icon' => isset($request->icon) ? $request->icon : NULL,
                'has_route' => isset($request->has_route) ? 'Y' : 'N',
                'route_name' => isset($request->has_route) || $request->route_name !='' ? $request->route_name : NULL,
                'parent_id' => isset($request->has_parent) || $request->cbo_parent !='' ? $request->cbo_parent : NULL,
                'has_child' => isset($request->has_child) ? 'Y' : 'N',
                'is_crud' => isset($request->is_crud) ? 'Y' : 'N',
                'order_line' => $request->order_line,
                'user_updated' => $userSesIdp
            );
            DB::table('permission_has_menus')->whereId($request->id)->update($data);
            addToLog('Permission menu has been successfully updated');
            //If Crud or Not
            $nameSlug = Str::slug($request->name);
            $oldNameSlug = Str::slug($request->old_name);
            if(isset($request->is_crud)) {
                if(isset($request->create)) {
                    $this->store_crudpermission($oldNameSlug.'-create', $nameSlug.'-create', $request->id);
                } if(isset($request->read)) {
                    $this->store_crudpermission($oldNameSlug.'-read', $nameSlug.'-read', $request->id);
                } if(isset($request->update)) {
                    $this->store_crudpermission($oldNameSlug.'-update', $nameSlug.'-update', $request->id);
                } if(isset($request->delete)) {
                    $this->store_crudpermission($oldNameSlug.'-delete', $nameSlug.'-delete', $request->id);
                }
            }
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
     * store_crudpermission
     *
     * @param  mixed $name
     * @param  mixed $fid_menu
     * @return void
     */
    private function store_crudpermission($oldName, $name, $fid_menu) {
        $userSesIdp = Auth::user()->id;
        DB::beginTransaction();
        try {
            $data = array(
                'name' => $name,
                'fid_menu' => $fid_menu,
                'guard_name' => 'web'
            );
            if($oldName == '') {
                $data['user_add'] = $userSesIdp;
                Permission::insert($data);
            } else {
                $data['user_update'] = $userSesIdp;
                Permission::where('name', $oldName)->update($data);
            }
            DB::commit();
            Artisan::call("cache:forget spatie.permission.cache");
            Artisan::call("cache:clear");
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
    /**
     * select2_parentpermissions
     *
     * @param  mixed $request
     * @return void
     */
    public function select2_parentpermissions(Request $request)
    {
        try {
            $output = $this->select2_permission($request->search, $request->page, 'Y');
            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}