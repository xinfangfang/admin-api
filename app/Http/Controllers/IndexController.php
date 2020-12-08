<?php

namespace App\Http\Controllers;
use App\Models\Labour as adminlist;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
//use App\Http\Requests;
class IndexController extends Controller
{
//    public function __construct()
//    {
//        $result = $this->checkLoginGlobal();
////        print_r($result);
//        if ($result == 1) {
////            dd(4);
////            需要跳转到登录页面
//            redirect('api/login')->send();
//        }
//    }
    /*检测登录
     * */
    public function checkLoginGlobal()
    {
        $check_success = 1;
        $name = Session::get('user_id');
        $rname = Session::get('user_name');
//        $premession = Session::get('lwj_premession');
        if (!empty($name) && !empty($rname)) {
            $check_success = 2;
        }
        // return true;
        return $check_success;
    }
    public function index()
    {


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
    /*劳动防护用品添加
     * */
    public function labour_add()
    {
        $data = app(adminlist::class)->maintainAttr();
//        dd($data);
        return view('index.labour_add',['data'=>$data['data']]);
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
    public function excel_add()
    {
        return view('index.excel_add');
    }
    public function testt()
    {
        $file = input::file('file');
        return $file;
    }
    public function user_admin()
    {
        $data = app(adminlist::class)->user_list();
        return view('index.useradmin',['data'=>$data]);
    }
    public function register()
    {
        return view('index.register');
    }
}

