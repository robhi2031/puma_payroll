<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\SystemInfoCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Hash;
use Session;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use SystemInfoCommon;
    
    /**
     * index
     *
     * @return void
     */
    public function index() {
        $getSystemInfo = $this->get_systeminfo();
        $data = array(
            'title' => 'Login',
            'url' => url()->current(),
            'thumb' => $getSystemInfo->url_thumb,
            'app_version' => config('app.version'),
            'app_name' => $getSystemInfo->name,
            'app_desc' => $getSystemInfo->description,
            'app_keywords' => $getSystemInfo->keyword
        );

        return view('login.index', compact('data'));
    }    
    /**
     * first_login
     *
     * @param  mixed $request
     * @return void
     */
    public function first_login(Request $request) {
        try {
            $username = $request->username;
            $user = User::where('email', $username)->first();
            if($user) {
                $output = array(
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                );
            } else {
                $user = User::where('username', $username)->first();
                if($user) {
                    $output = array(
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_active' => $user->is_active,
                    );
                } else {
                    return jsonResponse(false, 'Sistem tidak dapat menemukan akun user', 200);
                }
            }
            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }    
    /**
     * second_login
     *
     * @param  mixed $request
     * @return void
     */
    public function second_login(Request $request) {
        try {
            $email = $request->hideMail;
            $password = $request->password;
            $user = User::where('email', $email)->first();
            $hashedPassword = $user->password;
            if (!Hash::check($password, $hashedPassword)) {
                return jsonResponse(false, 'Password yang dimasukkan tidak sesuai, coba lagi dengan password yang benar!', 200);
            }

            $data = array(
                'id' => $user->id,
                'name' => $user->id,
                'username' => $user->id,
                'email' => $user->id,
                'phone_number' => $user->id,
                'thumb' => $user->id,
                'is_active' => $user->id
            );

            Cookie::queue('owt-cookie', 'Setting Cookie from Online Web Tutor', 120);

            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}