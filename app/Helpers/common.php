<?php

/* start:API RESPONSE */

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

if (! function_exists('jsonResponse')) {      
    /**
     * jsonResponse
     *
     * @param  mixed $data
     * @param  mixed $message
     * @param  mixed $code
     * @return Illuminate\Http\JsonResponse
     */
    function jsonResponse(bool $status = true, string $message = 'Success', int $code = 200, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'row' => $data
        ], $code);
    }
}
/* end:API RESPONSE */
/* start:get User Menus */
if (! function_exists('userMenus')) {
    /**
     * userMenus
     *
     * @return void
     */
    function userMenus()
    {
        $idp = Auth::user()->id;
        $getRows = Role::select('e.*')
            ->join('model_has_roles AS b', 'roles.id', '=', 'b.role_id', 'LEFT OUTER')
            ->join('role_has_permissions AS c', 'roles.id', '=', 'c.role_id', 'LEFT OUTER')
            ->join('permissions AS d', 'c.permission_id', '=', 'd.id', 'LEFT OUTER')
            ->join('permission_has_menus AS e', 'd.fid_menu', '=', 'e.id', 'LEFT OUTER')
            ->where('b.model_id', $idp)
            ->where('e.parent_id', '')->orWhereNull('parent_id')
            ->groupBy('d.fid_menu')
            ->orderBy('e.order_line', 'ASC')
            ->get();
        $array = [];
        if(count($getRows)>0){
            foreach ($getRows as $row) {
                $getChildren = Role::select('e.*')
                    ->join('model_has_roles AS b', 'roles.id', '=', 'b.role_id', 'LEFT OUTER')
                    ->join('role_has_permissions AS c', 'roles.id', '=', 'c.role_id', 'LEFT OUTER')
                    ->join('permissions AS d', 'c.permission_id', '=', 'd.id', 'LEFT OUTER')
                    ->join('permission_has_menus AS e', 'd.fid_menu', '=', 'e.id', 'LEFT OUTER')
                    ->where('b.model_id', $idp)
                    ->where('e.parent_id', $row->id)
                    ->groupBy('d.fid_menu')
                    ->orderBy('e.order_line', 'ASC')
                    ->get();

                $array_child = [];
                if(count($getChildren)>0){
                    foreach ($getChildren as $child) {
                        $array_child[] = [
                            'menu' => $child->name,
                            'icon' => $child->icon,
                            'route_name' => $child->route_name,
                        ];
                    }
                }

                $array[] = [
                    'menu' => $row->name,
                    'icon' => $row->icon,
                    'route_name' => $row->route_name,
                    'has_child' => $row->has_child,
                    'children' => $array_child
                ];
            }
            return $array;
        } else {
            return $array;
        }
    }
}
/* end:get User Menus */
/* start:Time a Go */
if(!function_exists('time_ago'))
{
    function time_ago($datetime, $full = false)
    {
        $time_ago     = strtotime($datetime);
        $current_time = strtotime(date("Y-m-d H:i:s"));
        $time_difference = $current_time - $time_ago;
        $seconds      = $time_difference;
        $minutes      = round($seconds / 60 );           // value 60 is seconds
        $hours        = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec
        $days         = round($seconds / 86400);          //86400 = 24 * 60 * 60;
        $weeks        = round($seconds / 604800);          // 7*24*60*60;
        $months       = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60
        $years        = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60
        if($seconds <= 60){
            return "Baru Saja";
        }else if($minutes <=60){
            if($minutes==1){
                return "1 Menit yang lalu";
            }else{
                return $minutes." Menit yang lalu";
            }
        }else if($hours <=24){
            if($hours==1){
                return "1 Jam yang lalu";
            }else{
                return $hours." Jam yang lalu";
            }
        }else if($days <= 7){
            if($days==1){
                return "Kemarin";
            }else{
                return $days." Hari yang lalu";
            }
        }else if($weeks <= 4.3){ //4.3 == 52/12
            if($weeks==1){
                return "1 Minggu yang lalu";
            }else{
                return $weeks." Minggu yang lalu.";
            }
        }else if($months <=12){
            if($months==1){
                return "1 Bulan yang lalu";
            }else{
                return $months." Bulan yang lalu";
            }
        }else{
            if($years==1){
                return "1 Tahun yang lalu";
            }else{
                return $years." Tahun yang lalu";
            }
        }
    }
}
/* end:Time a Go */