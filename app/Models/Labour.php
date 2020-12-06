<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Labour extends Model
{
    /*劳动防护用品列表
     * */
    public function getlabourlist()
    {
        $data = Db::table('labour as l')
//            ->leftJoin('labour_brand as la','l.brand_id','=','la.id')
//            ->leftJoin('labour_type as lt','l.labour_type','=','lt.id')
//            ->select('l.id as l_id','l.*','la.id as la_id','la.*','lt.id as lt_id','lt.*')
            ->paginate(15);
        $num = Db::table('labour')->count();
        return ['data'=>$data,'num'=>$num];
    }
}
