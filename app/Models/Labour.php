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
    /*获取可维护字段的值
     * */
    public function maintainAttr()
    {
        $ai_data = Db::table('attribute_item')->get()->map(function ($value) {
            return (array)$value;
        })->toArray();
//        dd($ai_data);
        $av_data = Db::table('attribute_item_value')->get()->map(function ($value) {
            return (array)$value;
        })->toArray();
//        print_r($ai_data);die;
        foreach($ai_data as $k=>$v){
            foreach($av_data as $kk=>$vv){
                if($v['id'] == $vv['attribute_item_id']){
                    $ai_data[$k]['av'][] = $vv;
                }
            }
        }
        return ['data'=>$ai_data];
    }
    public function user_list()
    {
        $data = Db::table('users')->get()->map(function ($value) {
            return (array)$value;
        })->toArray();;
        return $data;
    }
}
