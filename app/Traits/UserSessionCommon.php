<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;

trait UserSessionCommon {    
    /**
     * get_userinfo
     *
     * @return void
     */
    protected function get_userinfo($username)
    {
        $getRow = User::where('username', $username)->first();
        if($getRow==true){
            //Thumb Symbol
            $expName = explode(" ", $getRow->name);
            if(count($expName)>1) {
                $textSymbol = $expName[0][0].$expName[1][0];
            } else {
                $textSymbol = $expName[0][0];
            }
            $getRow->text_symbol = $textSymbol;
            $thumbSymbolColor = Cookie::get('userThumb_color');
            $getRow->symbol_color = $thumbSymbolColor;
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

            return $getRow;
        } else {
            return null;
        }
    }

}