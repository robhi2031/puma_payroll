<?php

use App\Http\Controllers\Backend\CompanyAboutController;
use App\Http\Controllers\Backend\UserProfileController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\JobPositionController;
use App\Http\Controllers\Backend\ManpowerController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SystemInfoController;
use App\Http\Controllers\Backend\UsersActivityController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Login\AuthController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth Login
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout_sessions'])->name('logout_sessions');
});
//Api Ajax
Route::group(['prefix' => 'api'], function () {
    Route::get('/system_info', [CommonController::class, 'system_info'])->name('system_info');
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/first_login', [AuthController::class, 'first_login'])->name('first_login');
        Route::post('/second_login', [AuthController::class, 'second_login'])->name('second_login');
    });
});
// Dashboard Backend
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Auth Logout
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/logout', [AuthController::class, 'logout_sessions'])->name('logout_sessions');
    });
    Route::get('/logout', [AuthController::class, 'logout_sessions'])->name('logout_sessions');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manage_systeminfo', [SystemInfoController::class, 'index'])->name('manage_systeminfo');
    Route::get('/manage_roles', [RolesController::class, 'index'])->name('manage_roles');
    Route::get('/manage_permissions', [PermissionsController::class, 'index'])->name('manage_permissions');
    Route::get('/manage_users', [UsersController::class, 'index'])->name('manage_users');
    Route::get('/logs_activity', [UsersActivityController::class, 'index'])->name('users_activity');
    Route::get('/manage_companyabout', [CompanyAboutController::class, 'index'])->name('manage_companyabout');
    Route::get('/manage_project', [ProjectController::class, 'index'])->name('manage_project');
    Route::get('/manage_jobposition', [JobPositionController::class, 'index'])->name('manage_jobposition');
    Route::get('/manage_manpower', [ManpowerController::class, 'index'])->name('manage_manpower');
    Route::get('/{username}', [UserProfileController::class,'index'])->name('user_profile');
    //Api Ajax
    Route::group(['prefix' => 'api'], function () {
        //Manage System Info
        Route::post('/manage_systeminfo/update', [SystemInfoController::class, 'update'])->name('update_systeminfo');
        //Manage Roles
        Route::get('/manage_roles/show', [RolesController::class, 'show'])->name('show_roles');
        Route::post('/manage_roles/store', [RolesController::class, 'store'])->name('store_roles');
        Route::post('/manage_roles/update', [RolesController::class, 'update'])->name('update_roles');
        Route::get('/manage_roles/show_permissions', [RolesController::class, 'show_permissions'])->name('show_permissions_role');
        Route::get('/manage_roles/select2_permissions', [RolesController::class, 'select2_permissions'])->name('select2_permissions');
        Route::post('/manage_roles/store_permissionrole', [RolesController::class, 'store_permissionrole'])->name('store_permissionrole');
        Route::post('/manage_roles/update_permissionbyrole', [RolesController::class, 'update_permissionbyrole'])->name('update_permissionbyrole');
        //Manage Permissions
        Route::get('/manage_permissions/show', [PermissionsController::class, 'show'])->name('show_permissions');
        Route::post('/manage_permissions/store', [PermissionsController::class, 'store'])->name('store_permissions');
        Route::post('/manage_permissions/update', [PermissionsController::class, 'update'])->name('update_permissions');
        Route::get('/manage_permissions/select2_parentpermissions', [PermissionsController::class, 'select2_parentpermissions'])->name('select2_parentpermissions');
        Route::post('/manage_permissions/delete', [PermissionsController::class, 'delete'])->name('delete_permissions');
        //Manage Users
        Route::get('/manage_users/show', [UsersController::class, 'show'])->name('show_users');
        Route::get('/manage_users/selectpicker_role', [UsersController::class, 'selectpicker_role'])->name('selectpicker_role');
        Route::post('/manage_users/store', [UsersController::class, 'store'])->name('store_users');
        Route::post('/manage_users/update', [UsersController::class, 'update'])->name('update_users');
        Route::post('/manage_users/update_statususers', [UsersController::class, 'update_statususers'])->name('update_statususers');
        Route::post('/manage_users/reset_userpass', [UsersController::class, 'reset_userpass'])->name('reset_userpass');
        //Users Activity
        Route::get('/users_activity/show', [UsersActivityController::class, 'show'])->name('show_logs');
        Route::post('/users_activity/delete', [UsersActivityController::class, 'delete'])->name('delete_logs');
        //User Company About
        Route::get('/manage_companyabout/show', [CompanyAboutController::class, 'show'])->name('show_companyabout');
        Route::post('/manage_companyabout/update', [CompanyAboutController::class, 'update'])->name('update_companyabout');
        //Manage Project
        Route::get('/manage_project/show', [ProjectController::class, 'show'])->name('show_project');
        Route::post('/manage_project/store', [ProjectController::class, 'store'])->name('store_project');
        Route::post('/manage_project/update', [ProjectController::class, 'update'])->name('update_project');
        //Manage Job Position
        Route::get('/manage_jobposition/show', [JobPositionController::class, 'show'])->name('show_jobposition');
        Route::post('/manage_jobposition/store', [JobPositionController::class, 'store'])->name('store_jobposition');
        Route::post('/manage_jobposition/update', [JobPositionController::class, 'update'])->name('update_jobposition');
        //Manage Manpower
        Route::get('/manage_manpower/show', [ManpowerController::class, 'show'])->name('show_manpower');
        Route::get('/manage_manpower/select2_project', [ManpowerController::class, 'select2_project'])->name('select2_project_manpower');
        Route::get('/manage_manpower/select2_jobposition', [ManpowerController::class, 'select2_jobposition'])->name('select2_jobposition_manpower');
        Route::post('/manage_manpower/store', [ManpowerController::class, 'store'])->name('store_manpower');
        Route::post('/manage_manpower/update', [ManpowerController::class, 'update'])->name('update_manpower');
        Route::post('/manage_manpower/import', [ManpowerController::class, 'import'])->name('import_manpower');
        Route::get('/manage_manpower/sync_manpower_api', [ManpowerController::class, 'syncManpower'])->name('sync_manpower_api');
        //User Profil
        Route::get('/user_info', [CommonController::class, 'user_info'])->name('user_info');
        Route::post('/update_userprofile', [CommonController::class, 'update_userprofile'])->name('update_userprofile');
        Route::post('/update_userpassprofil', [CommonController::class, 'update_userpassprofil'])->name('update_userpassprofil');
        //Image Upload with Summernote Editor
        Route::post('/ajax_upload_imgeditor', [CommonController::class, 'upload_imgeditor'])->name('upload_imgeditor');
    });
});