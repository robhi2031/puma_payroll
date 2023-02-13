<?php

namespace App\Traits;

use App\Models\SystemInfo;

trait SystemInfoCommon {
    protected function get_systeminfo()
    {
        $getRow = SystemInfo::where('id', 1)->first();
        if($getRow==true){
            //Thumb Site
            $thumb_site = $getRow->thumb;
            if($thumb_site==''){
                $getRow->url_thumb = NULL;
            } else {
                if (!file_exists('./dist/img/system-img/'.$thumb_site)){
                    $getRow->url_thumb = NULL;
                    $getRow->thumb_site = NULL;
                }else{
                    $getRow->url_thumb = url('dist/img/system-img/'.$thumb_site);
                }
            }
            //Login Background
            $login_bg = $getRow->login_bg;
            if($login_bg==''){
                $getRow->url_loginBg = NULL;
            } else {
                if (!file_exists('./dist/img/system-img/'.$login_bg)){
                    $getRow->url_loginBg = NULL;
                    $getRow->login_bg = NULL;
                }else{
                    $getRow->url_loginBg = url('dist/img/system-img/'.$login_bg);
                }
            }
            //Login Logo
            $login_logo = $getRow->login_logo;
            if($login_logo==''){
                $getRow->url_loginLogo = NULL;
            } else {
                if (!file_exists('./dist/img/system-img/'.$login_logo)){
                    $getRow->url_loginLogo = NULL;
                    $getRow->login_logo = NULL;
                }else{
                    $getRow->url_loginLogo = url('dist/img/system-img/'.$login_logo);
                }
            }
            //Backend Logo
            $backend_logo = $getRow->backend_logo;
            if($backend_logo==''){
                $getRow->url_backendLogo = NULL;
            } else {
                if (!file_exists('./dist/img/system-img/'.$backend_logo)){
                    $getRow->url_backendLogo = NULL;
                    $getRow->backend_logo = NULL;
                }else{
                    $getRow->url_backendLogo = url('dist/img/system-img/'.$backend_logo);
                }
            }

            return $getRow;
        } else {
            return null;
        }
    }

}