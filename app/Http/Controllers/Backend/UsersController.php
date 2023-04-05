<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    use SystemCommon;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('permission:users-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:users-create', ['only' => ['store']]);
        $this->middleware('permission:users-update', ['only' => ['update']]);
        $this->middleware('permission:users-delete', ['only' => ['delete']]);
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
            'title' => 'Kelola Data User',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.v817.css',
            '/dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            '/dist/plugins/Magnific-Popup/magnific-popup.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.v817.js',
            '/dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            '/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            '/dist/js/jquery.mask.min.js',
            '/dist/js/backend_app.init.js',
            '/scripts/backend/manage_users.init.js'
        );

        addToLog('Mengakses halaman Kelola Users - Backend');
        return view('backend.manage_users', compact('data'));
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
                $getRow = User::selectRaw("users_system.id, users_system.name, users_system.username, users_system.email, users_system.phone_number, users_system.thumb, users_system.is_active,
                    users_system.is_login, users_system.ip_login, users_system.last_login, c.name AS role, b.role_id")
                    ->leftJoin('model_has_roles AS b', 'b.model_id', '=', 'users_system.id')
                    ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                    ->where('users_system.id', $request->idp)
                    ->first();
                if($getRow != null){
                    //Thumb Site
                    $thumb = $getRow->thumb;
                    if($thumb==''){
                        $getRow->url_thumb = NULL;
                    } else {
                        if (!file_exists('./dist/img/users-img/'.$thumb)){
                            $getRow->url_thumb = NULL;
                            $getRow->thumb = NULL;
                        }else{
                            $getRow->url_thumb = url('dist/img/users-img/'.$thumb);
                        }
                    }
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
            $data = User::selectRaw("users_system.id, users_system.name, users_system.username, users_system.email, users_system.phone_number, users_system.thumb, users_system.is_active,
                users_system.is_login, users_system.ip_login, users_system.last_login, c.name AS role, b.role_id, 'action' as action")
                ->leftJoin('model_has_roles AS b', 'b.model_id', '=', 'users_system.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->orderBy('b.role_id', 'ASC')
                ->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $user_thumb = $row->thumb;
                    $getHurufAwal = $row->username[0];
                    $symbolThumb = strtoupper($getHurufAwal);
                    if($user_thumb == ''){
                        $url_userThumb = url('dist/img/default-user-img.jpg');
                        $userThumb = '<span class="symbol-label bg-secondary text-danger fw-bold fs-1">'.$symbolThumb.'</span>';
                    } else if (!file_exists('./dist/img/users-img/'.$user_thumb)){
                        $url_userThumb = url('dist/img/default-user-img.jpg');
                        $user_thumb = NULL;
                        $userThumb = '<span class="symbol-label bg-secondary text-danger fw-bold fs-1">'.$symbolThumb.'</span>';
                    }else{
                        $url_userThumb = url('dist/img/users-img/'.$user_thumb);
                        $userThumb = '<a class="image-popup" href="'.$url_userThumb.'" title="'.$user_thumb.'">
                            <div class="symbol-label">
                                <img alt="'.$user_thumb.'" src="'.$url_userThumb.'" class="w-100" />
                            </div>
                        </a>';
                    }
                    $userCustom = '<div class="d-flex align-items-center">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-circle symbol-50px overflow-hidden">
                            '.$userThumb.'
                        </div>
                        <!--end::Avatar-->
                        <div class="ms-2">
                            <a href="javascript:void(0);" class="fw-bold text-gray-900 text-hover-primary mb-2">'.$row->name.'</a>
                            <div class="fw-bold text-muted">'.$row->username.'</div>
                        </div>
                    </div>';
                    if($row->icon != null || $row->icon != '') {
                        $userCustom = '<span class="badge badge-light fs-1"><i class="bi '.$row->icon.' text-dark"></i></span>';
                    }
                    return $userCustom;
                })
                ->editColumn('is_active', function ($row) {
                    if($row->is_active == 'Y'){
                        $activeCustom = '<button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="tooltip" title="User Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".', '."'users_system'".', '."'status'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                    } else {
                        $activeCustom = '<button type="button" class="btn btn-sm btn-light mb-1" data-bs-toggle="tooltip" title="User Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".', '."'users_system'".', '."'status'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                    }
                    return $activeCustom;
                })
                ->editColumn('last_login', function ($row) {
                    $last_login = $row->ip_login.' <br/><div class="fw-bold text-muted">'.time_ago($row->last_login).'</div>';
                    return $last_login;
                })
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editUser('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    return $btnEdit;
                })
                ->rawColumns(['name', 'is_active', 'last_login', 'action'])
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
            'role' => 'required',
            'name' => 'required|max:150',
            'username' => 'required|max:50',
            'email' => 'required|max:225',
            'phone_number' => 'required|max:13',
            'pass_user' => 'required|min:6',
            'repass_user' => 'required|min:6',
            'avatar' => 'mimes:png,jpg,jpeg|max:2048',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => bcrypt($request->password),
                'user_add' => $userSesIdp
            );
            $cekUser = User::where('username', $request->username)->first();
            if($cekUser==true) {
                addToLog('Data cannot be saved, the same username already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
            } else {
                $cekUser = User::where('email', $request->email)->first();
                if($cekUser==true) {
                    addToLog('Data cannot be saved, the same email already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                } else {
                    //If Update Avatar User
                    if(!empty($_FILES['avatar']['name'])) {
                        $avatarDestinationPath = public_path('/dist/img/users-img');
                        $avatarFile = $request->file('avatar');
                        $avatarExtension = $avatarFile->getClientOriginalExtension();
                        //Cek and Create Avatar Destination Path
                        if(!is_dir($avatarDestinationPath)){ mkdir($avatarDestinationPath, 0755, TRUE); }

                        $avatarOriginName = $avatarFile->getClientOriginalName();
                        $avatarNewName = strtolower(Str::slug(pathinfo($avatarOriginName, PATHINFO_FILENAME))) . time();
                        $avatarNewNameExt = $avatarNewName . '.' . $avatarExtension;
                        $avatarFile->move($avatarDestinationPath, $avatarNewNameExt);

                        $data['thumb'] = $avatarNewNameExt;
                    }

                    $insertUser = User::insertGetId($data);
                    addToLog('User has been successfully added');
                }
            }
            DB::commit();
            return jsonResponse(true, 'Data user berhasil ditambahkan', 200);
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
     * assignRoleToUser
     *
     * @param  mixed $idpUser
     * @param  mixed $idpRole
     * @return void
     */
    private function assignRoleToUser($idpUser, $idpRole) {
        $userSesIdp = Auth::user()->id;
        DB::beginTransaction();
        try {
            $getPermissions = Permission::select('permissions.*')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'permissions.id')
                ->where('b.role_id', $idpRole)
                ->get();
            $getUser = User::whereId($idpUser)->first();
            if($getPermissions) {
                foreach ($getPermissions as $row) {
                    $getUser->givePermissionTo($row->name);
                }
            }
            $getUser->assignRole([$idpRole]);
            DB::commit();
        } catch (\Exception $exception) {
            // \dd($exception);
            DB::rollback();
        }
    }
    /**
     * selectpicker_role
     *
     * @param  mixed $request
     * @return void
     */
    public function selectpicker_role(Request $request) {
        try {
            $getRow = Role::get();
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
    }
}