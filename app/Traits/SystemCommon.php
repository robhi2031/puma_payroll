<?php

namespace App\Traits;

use App\Models\SystemInfo;

trait SystemCommon {    
    /**
     * get_systeminfo
     *
     * @return void
     */
    protected function get_systeminfo()
    {
        $getRow = SystemInfo::where('id', 1)->first();
        if($getRow==true){
            //Thumb Site
            $thumb = $getRow->thumb;
            if($thumb==''){
                $getRow->url_thumb = NULL;
            } else {
                if (!file_exists('./dist/img/system-img/'.$thumb)){
                    $getRow->url_thumb = NULL;
                    $getRow->thumb = NULL;
                }else{
                    $getRow->url_thumb = url('dist/img/system-img/'.$thumb);
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
            //Backend Logo Icon
            $backend_logo_icon = $getRow->backend_logo_icon;
            if($backend_logo_icon==''){
                $getRow->url_backendLogoIcon = NULL;
            } else {
                if (!file_exists('./dist/img/system-img/'.$backend_logo_icon)){
                    $getRow->url_backendLogoIcon = NULL;
                    $getRow->backend_logo_icon = NULL;
                }else{
                    $getRow->url_backendLogoIcon = url('dist/img/system-img/'.$backend_logo_icon);
                }
            }
            //Keyword to Explode
            $getRow->keyword_explode = explode(',', $getRow->keyword);

            return $getRow;
        } else {
            return null;
        }
    }

}