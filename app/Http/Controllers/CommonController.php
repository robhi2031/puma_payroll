<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\MenusCommon;
use App\Traits\SystemCommon;
use App\Traits\UserSessionCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommonController extends Controller
{
    use SystemCommon;
    use UserSessionCommon;
    use MenusCommon;
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
     * user_menus
     *
     * @param  mixed $request
     * @return void
     */
    protected function user_menus(Request $request) {
        $idp = Auth::user()->id;
        try {
            $getRow = $this->get_menusforsession($idp);
            return $getRow;

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