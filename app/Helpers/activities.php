<?php

use App\Models\LogActivities;

/* start:Log Activities */
if (! function_exists('addToLog')) {
    function addToLog($desc) {
        $log = [];
        $log['ip_address'] = getUserIp();
        $log['description'] = $desc;
        $log['fid_user'] = auth()->check() ? auth()->user()->id : NULL;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['agent'] = Request::header('user-agent');
        LogActivities::create($log);
    }
}
/* end:Log Activities */

/* start:get User IP Addresss */
if (! function_exists('getUserIp')) {
    function getUserIp() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = null;    
        return $ipaddress;
    }
}
/* end:get User IP Addresss */