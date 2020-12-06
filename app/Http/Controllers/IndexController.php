<?php

namespace App\Http\Controllers;
use App\Models\Labour as adminlist;
use Illuminate\Support\Facades\Input;
class IndexController extends Controller
{
    public function login()
    {

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
        $res = app(adminlist::class)->getlabourlist();
        $data = $res['data'];
        $num = $res['num'];
        return view('index.labourlist',['data'=>$data,'num'=>$num]);
    }
    /*目视化列表
 * */
    public function vislist()
    {
        return view('index.vislist');
    }
    public function labour_edit()
    {
//        dd(input::get());
        return view('index.labour_edit');
    }
}

