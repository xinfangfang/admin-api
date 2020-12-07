<?php
/**
 * Created by PhpStorm.
 * User: xinfangfang
 * Date: 2020/11/30
 * Time: 8:30 PM
 */

namespace App\Http\Controllers;


use App\models\Labour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{

    /**
     * excel添加
     *
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function ImportExcel(Request $request)
    {

        //> 获取上传文件路径 $_FILES
        if ($_FILES['file']['error'] == 0) {
            //> 获取上传文件名称(已便于后面判断是否上传需要后缀文件)
            $name = $_FILES['file']['name'];
            //> 获取上传文件后缀 如(xls exe xlsx 等)
            $ext = strtolower(trim(substr($name, (strpos($name, '.') + 1))));
            //> 判断文件是否为指定的上传文件后缀
            if (!in_array($ext, array('xls', 'xlsx'))) {
                //> 返回上一次请求位置,并携带错误消息
                return redirect()->back()->withErrors('请输入xls或xlsx后缀文件')->withInput();
            }
            //> 获取文件上传路径
            $fileName = $_FILES['file']['tmp_name'];
            //> excel文件导入 上传文件
            $addArr = [];
            Excel::load($fileName, function ($reader)use($addArr) {
                //> 处理上传文件数据 此时 处理多个上传的 sheet 文件
                foreach ($reader->get() as $item) {
                    //> 处理相关上传excel数据
                    $itemArr = array_values($item->toArray());
                    $addArr[] = [
                        'labour_harm' => $itemArr[0],
                        'labour_contact' => $itemArr[1],
                        'labour_type' => $itemArr[2],
                        'labour_name' => $itemArr[3],
                        'labour_requirement' => $itemArr[4],
                        'brand' => $itemArr[5],
                        'brand_type' => $itemArr[6],
                        'labour_model' => $itemArr[7],
                        'labour_protected' => $itemArr[8],
                        'labour_price' => $itemArr[9],
                        'remarks' => $itemArr[10],
                    ];
                }
                Labour::insert($addArr);
            });
        }

        return $this->success([]);
    }

    /**
     * 列表
     *
     * @return mixed
     */
    public function getLabour(){
        $re = Labour::where('delete_flag',2)->paginate(15);

         return $this->success($re->toArray());
    }
}