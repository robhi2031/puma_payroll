<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\SystemCommon;
use App\Traits\UserSessionCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CommonController extends Controller
{
    use SystemCommon;
    use UserSessionCommon;
    /**
     * system_info
     *
     * @param  mixed $request
     * @return void
     */
    protected function system_info(Request $request) {
        try {
            $getRow = $this->get_systeminfo();
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
    /**
     * user_info
     *
     * @param  mixed $request
     * @return void
     */
    protected function user_info(Request $request) {
        $username = Auth::user()->username;
        try {
            $getRow = $this->get_userinfo($username);
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
    /**
     * update_userprofile
     *
     * @param  mixed $request
     * @return void
     */
    protected function update_userprofile(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'name' => 'required|max:100',
            'username' => 'required|max:100',
            'email' => 'required|max:225',
            'phone_number' => 'required|max:13',
            'avatar' => 'mimes:png,jpg,jpeg|max:2048',
            
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $data = array(
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'user_updated' => $userSesIdp
            );
            $cekUser = User::where('username', $request->username)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                addToLog('Data cannot be updated, the same username already exists in the system');
                return jsonResponse(false, 'Gagal memperbarui data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
            } else {
                $cekUser = User::where('email', $request->email)->where('id', '!=' , $userSesIdp)->first();
                if($cekUser==true) {
                    addToLog('Data cannot be updated, the same email already exists in the system');
                    return jsonResponse(false, 'Gagal memperbarui data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                } else {
                    //If Update Avatar User
                    if(!empty($_FILES['avatar']['name'])) {
                        $avatarDestinationPath = public_path('/dist/img/users-img');
                        $getUser = User::select()->whereId($userSesIdp)->first();
                        $getAvatarFile = $avatarDestinationPath.'/'.$getUser->thumb;

                        if(file_exists($getAvatarFile) && $getUser->thumb)
                            unlink($getAvatarFile);

                        $avatarFile = $request->file('avatar');
                        $avatarExtension = $avatarFile->getClientOriginalExtension();
                        //Cek and Create Avatar Destination Path
                        if(!is_dir($avatarDestinationPath)){ mkdir($avatarDestinationPath, 0755, TRUE); }

                        $avatarOriginName = $avatarFile->getClientOriginalName();
                        $avatarNewName = strtolower(Str::slug($request->username.bcrypt(pathinfo($avatarOriginName, PATHINFO_FILENAME)))) . time();
                        $avatarNewNameExt = $avatarNewName . '.' . $avatarExtension;
                        $avatarFile->move($avatarDestinationPath, $avatarNewNameExt);

                        $data['thumb'] = $avatarNewNameExt;
                    }

                    User::whereId($userSesIdp)->update($data);
                    addToLog('The user profile data has been successfully updated');
                }
            }
            DB::commit();
            return jsonResponse(true, 'Profil User berhasil diperbarui', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }    
    /**
     * update_userpassprofil
     *
     * @param  mixed $request
     * @return void
     */
    protected function update_userpassprofil(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'pass_lama' => 'required|min:6|max:50',
            'pass_baru' => 'required|min:6|max:50',
            'repass_baru' => 'required|min:6|max:50'
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //Check Old Password
            $user = User::whereId($userSesIdp)->first();
            $hashedPassword = $user->password;
            if (!Hash::check($request->pass_lama, $hashedPassword)) {
                addToLog('Password cannot be updated, The inputted old password is not found in the system');
                return jsonResponse(false, 'Gagal memperbarui data! Password lama tidak valid, coba lagi dengan isian yang benar', 200, array('error_code' => 'invalid_passold'));
            }
            // Update the new Password
            User::whereId($userSesIdp)->update([
                'password' => Hash::make($request->repass_baru),
                'user_updated' => $userSesIdp
            ]);
            DB::commit();
            return jsonResponse(true, 'Success', 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}