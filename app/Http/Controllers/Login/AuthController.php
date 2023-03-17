<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\SystemCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;
use Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    use SystemCommon;
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

        addToLog('Mengakses halaman login');
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
                    addToLog('First step login failed, System cannot find user according to the Username or Email entered !');
                    return jsonResponse(false, 'Sistem tidak dapat menemukan akun user', 200);
                }
            }
            if($user->is_active=='N') {
                addToLog('First step login failed, The user is currently disabled !');
                return jsonResponse(false, 'Akun user sedang dinonactifkan, silahkan coba lagi nanti atau hubungi admin untuk mengatasi masalah ini.', 200);
            }
            addToLog('First step login was successful, username and email found');
            return jsonResponse(true, 'Success', 200, $output);
        } catch (\Exception $exception) {
            addToLog($exception->getMessage());
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
                addToLog('Second step login failed, System cannot find user according to the Password entered !');
                return jsonResponse(false, 'Password yang dimasukkan tidak sesuai, coba lagi dengan password yang benar!', 200, ['error_code' => 'PASSWORD_NOT_VALID']);
            }
            Auth::login($user);
            //Created Token Sanctum
            $request->user()->createToken('api-token')->plainTextToken;
            addToLog('Second step login successful, the user session has been created');
            //Update Data User Session
            User::where('id', auth()->user()->id)->update([
                'is_login' => 'Y',
                'ip_login' => getUserIp(),
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            //Set Cookie
            $arrColor= ["text-primary", "text-success", "text-info", "text-warning", "text-danger", "text-dark"];
            $randomColor = array_rand($arrColor,2);
            $expCookie = 86400; //24Jam
            Cookie::queue('username', Auth::user()->username, $expCookie);
            Cookie::queue('email', Auth::user()->email, $expCookie);
            Cookie::queue('userThumb_color', $arrColor[$randomColor[0]], $expCookie);
            Cookie::queue('remember', TRUE, $expCookie);
            return jsonResponse(true, 'Success', 200);
        } catch (\Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }    
    /**
     * logout_sessions
     *
     * @param  mixed $request
     * @return void
     */
    public function logout_sessions(Request $request) {
        $arrayCookie = array();
        foreach (Cookie::get() as $key => $item){
            $arrayCookie []= cookie($key, null, -2628000, null, null);
        }
        auth()->user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flush();
        Artisan::call('cache:clear');
        return redirect('/auth');
    }
}