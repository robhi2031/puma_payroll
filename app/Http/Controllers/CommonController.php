<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\SystemInfoCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;


class CommonController extends Controller
{
    use SystemInfoCommon;

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
                //Return Response
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