<?php

namespace App\Traits;

use App\Models\Menus;
use Illuminate\Support\Facades\Cookie;
use Spatie\Permission\Models\Role;

trait MenusCommon {    
    /**
     * get_userinfo
     *
     * @return void
     */
    protected function get_menusforsession($idp)
    {
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
                    'children' => $array_child
                ];
            }
            return $array;
        } else {
            return $array;
        }
    }

}