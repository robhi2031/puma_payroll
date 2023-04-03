<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Select2Common {    
    /**
     * select2_permission
     *
     * @return void
     */
    protected function select2_permission($search, $page, $child)
    {
        // Search term
        $searchTerm = $search;
        $page = $page;
        $result = array();
        $query = DB::table('permission_has_menus AS a')
            ->selectRaw("a.id, a.name AS text")
            ->leftJoin('permissions AS b', 'b.fid_menu', '=', 'a.id')
            ->where('a.has_child', $child)
            ->where('a.name','LIKE','%'.$searchTerm.'%');
        $start=0;
        $limit=20;
        if($page!=''){
            $start=20*$page-20;
            $limit=20;
            $getResult = $query->offset($start)->limit($limit)->groupBy('b.fid_menu')->orderBy('a.order_line', 'ASC');
        }else{
            $getResult = $query->groupBy('b.fid_menu')->orderBy('a.order_line', 'ASC');
        }

        $getArray = $getResult->get()->toArray();
        $countResult = $query->groupBy('b.fid_menu')->orderBy('a.order_line', 'ASC')->count();
        $result['results'] = $getArray;
        $pagination = array("more" => true);
        if($countResult < 20 ){
            $pagination = array("more" => false);
        }
        $result['pagination'] = $pagination;
        $result['count'] = $countResult;
        return $result;
    }
}