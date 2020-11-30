<?php

namespace App\Http\Controllers;
use App\Models\Labour as adminlist;
use DB;
class IndexController extends Controller
{
    public function login()
    {
        return view('index.login');
    }
    public function index()
    {
//        $a = app(adminlist::class)->getlabourlist();
//        dump($a);die;
        return view('index.index');
    }
    /*劳动防护用品列表
     * */
    public function labourlist()
    {
        $data = Db::table('labour as l')
            ->leftJoin('labour_brand as la','l.brand_id','=','la.id')
            ->leftJoin('labour_type as lt','l.labour_type','=','lt.id')
            ->select('l.id as l_id','l.*','la.id as la_id','la.*','lt.id as lt_id','lt.*')
            ->paginate(15);
//        dd($data);die;
        echo 12345;
        $num = Db::table('labour')->count();
        return view('index.labourlist',['data'=>$data,'num'=>$num]);
    }
}

