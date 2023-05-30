<?php

/* start:API RESPONSE */

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->leftJoin('model_has_roles AS b', 'roles.id', '=', 'b.role_id')
            ->leftJoin('role_has_permissions AS c', 'roles.id', '=', 'c.role_id')
            ->leftJoin('permissions AS d', 'c.permission_id', '=', 'd.id')
            ->leftJoin('permission_has_menus AS e', 'd.fid_menu', '=', 'e.id')
            ->where('b.model_id', $idp)
            ->where('e.parent_id', '')->orWhereNull('parent_id')
            ->groupBy('d.fid_menu')
            ->orderBy('e.order_line', 'ASC')
            ->get();
        $array = [];
        if(count($getRows)>0){
            foreach ($getRows as $row) {
                $getChildren = Role::select('e.*')
                    ->leftJoin('model_has_roles AS b', 'roles.id', '=', 'b.role_id')
                    ->leftJoin('role_has_permissions AS c', 'roles.id', '=', 'c.role_id')
                    ->leftJoin('permissions AS d', 'c.permission_id', '=', 'd.id')
                    ->leftJoin('permission_has_menus AS e', 'd.fid_menu', '=', 'e.id')
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
/**
 * assignPermissionToUser
 *
 * @param  mixed $idpRole
 * @param  mixed $permission
 * @return void
 */
if(!function_exists('assignPermissionToUser'))
{    
    function assignPermissionToUser($idpRole, $permission)
    {
        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users_system.id')
        ->select('users_system.*')
        ->where('model_has_roles.role_id', '=', $idpRole)
        ->get();

        if($users) {
            foreach ($users as $user) {
                $user->givePermissionTo($permission->name);
            }
        }
    }
}
if(!function_exists('revokePermissionToUser'))
{    
    function revokePermissionToUser($idpRole, $permission)
    {
        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users_system.id')
        ->select('users_system.*')
        ->where('model_has_roles.role_id', '=', $idpRole)
        ->get();

        if($users) {
            foreach ($users as $user) {
                $user->revokePermissionTo($permission->name);
            }
        }
    }
}
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
/* start:Date Indo */
if ( ! function_exists('tgl_indo')){
    function date_indo($tgl){
        $ubah = gmdate($tgl, time()+60*60*8);
        $pecah = explode("-",$ubah);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal.' '.$bulan.' '.$tahun;
    }
}
if ( ! function_exists('bulan')){
    function bulan($bln){
        switch ($bln){
            case 1:
            return "Januari";
            break;
            case 2:
            return "Februari";
            break;
            case 3:
            return "Maret";
            break;
            case 4:
            return "April";
            break;
            case 5:
            return "Mei";
            break;
            case 6:
            return "Juni";
            break;
            case 7:
            return "Juli";
            break;
            case 8:
            return "Agustus";
            break;
            case 9:
            return "September";
            break;
            case 10:
            return "Oktober";
            break;
            case 11:
            return "November";
            break;
            case 12:
            return "Desember";
            break;
        }
    }
}
//Format Shortdate
if ( ! function_exists('shortdate_indo')){
    function shortdate_indo($tgl){
        $ubah = gmdate($tgl, time()+60*60*8);
        $pecah = explode("-",$ubah);
        $tanggal = $pecah[2];
        $bulan = short_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal.'/'.$bulan.'/'.$tahun;
    }
}
if ( ! function_exists('short_bulan')){
    function short_bulan($bln){
        switch ($bln){
            case 1:
            return "01";
            break;
            case 2:
            return "02";
            break;
            case 3:
            return "03";
            break;
            case 4:
            return "04";
            break;
            case 5:
            return "05";
            break;
            case 6:
            return "06";
            break;
            case 7:
            return "07";
            break;
            case 8:
            return "08";
            break;
            case 9:
            return "09";
            break;
            case 10:
            return "10";
            break;
            case 11:
            return "11";
            break;
            case 12:
            return "12";
            break;
        }
    }
}
//Format Medium date
if ( ! function_exists('mediumdate_indo')){
    function mediumdate_indo($tgl){
        $ubah = gmdate($tgl, time()+60*60*8);
        $pecah = explode("-",$ubah);
        $tanggal = $pecah[2];
        $bulan = medium_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal.' '.$bulan.' '.$tahun;
    }
}
if ( ! function_exists('medium_bulan')){
    function medium_bulan($bln){
        switch ($bln){
            case 1:
            return "Jan";
            break;
            case 2:
            return "Feb";
            break;
            case 3:
            return "Mar";
            break;
            case 4:
            return "Apr";
            break;
            case 5:
            return "Mei";
            break;
            case 6:
            return "Jun";
            break;
            case 7:
            return "Jul";
            break;
            case 8:
            return "Ags";
            break;
            case 9:
            return "Sep";
            break;
            case 10:
            return "Okt";
            break;
            case 11:
            return "Nov";
            break;
            case 12:
            return "Des";
            break;
        }
    }
}
//Long date indo Format
if ( ! function_exists('longdate_indo')){
    function longdate_indo($tanggal){
        $ubah = gmdate($tanggal, time()+60*60*8);
        $pecah = explode("-",$ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];
        $bulan = bulan($pecah[1]);

        $nama = date("l", mktime(0,0,0,$bln,$tgl,$thn));
        $nama_hari = "";
        if($nama=="Sunday") {$nama_hari="Minggu";}
        else if($nama=="Monday") {$nama_hari="Senin";}
        else if($nama=="Tuesday") {$nama_hari="Selasa";}
        else if($nama=="Wednesday") {$nama_hari="Rabu";}
        else if($nama=="Thursday") {$nama_hari="Kamis";}
        else if($nama=="Friday") {$nama_hari="Jumat";}
        else if($nama=="Saturday") {$nama_hari="Sabtu";}
        return $nama_hari.', '.$tgl.' '.$bulan.' '.$thn;
    }
}
/* end:Date Indo */
/* start: Selectpicker */
if ( ! function_exists('get_selectpicker')){
    function get_selectpicker($param){
        if($param == 'marital_status') {
            $getRow = DB::table('master_marital_status')
                ->orderBy('name')->get()->toArray();
        } if($param == 'shift') {
            $getRow = DB::table('master_shift')
                ->orderBy('code')->get()->toArray();
        } if($param == 'paycode') {
            $getRow = DB::table('master_paycode')
                ->orderBy('code')->get()->toArray();
        }

        $result = $getRow;
        return $result;
    }
}
/* end: Selectpicker */