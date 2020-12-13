<?php
/**
 * Created by PhpStorm.
 * User: xinfangfang
 * Date: 2020/11/30
 * Time: 8:30 PM
 */

namespace App\Http\Controllers;


use App\Models\Admin;
use App\models\AttributeItemValue;
use App\models\Labour;
use App\models\Role;
use App\models\RoleUser;
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
        $uid = $request['uid'];
        $userInfo = $this->getUserRole($uid);
        if ($userInfo[0]['role_id'] != 5) {
            return $this->error(['没有权限,请联系管理员！']);
        }

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
            $brand = AttributeItemValue::where('attribute_item_id', 1)->get()->toArray();
            $brand = array_column($brand, null, 'name');
            $labourHarm = AttributeItemValue::where('attribute_item_id', 2)->get()->toArray();
            $labourHarm = array_column($labourHarm, null, 'name');
            $labourContact = AttributeItemValue::where('attribute_item_id', 3)->get()->toArray();
            $labourContact = array_column($labourContact, null, 'name');
            $labourType = AttributeItemValue::where('attribute_item_id', 4)->get()->toArray();
            $labourType = array_column($labourType, null, 'name');
            $labourRequirement = AttributeItemValue::where('attribute_item_id', 5)->get()->toArray();
            $labourRequirement = array_column($labourRequirement, null, 'name');
            $brandType = AttributeItemValue::where('attribute_item_id', 6)->get()->toArray();
            $brandType = array_column($brandType, null, 'name');
            Excel::load($fileName, function ($reader) use (
                $addArr,
                $brand,
                $labourHarm,
                $labourContact,
                $labourType,
                $labourRequirement,
                $brandType
            ) {
                //> 处理上传文件数据 此时 处理多个上传的 sheet 文件
                foreach ($reader->get() as $item) {
                    //> 处理相关上传excel数据
                    $itemArr = array_values($item->toArray());
                    $addArr[] = [
                        'labour_harm' => isset($labourHarm[$itemArr[0]]) ? $labourHarm[$itemArr[0]]['id'] : 0,
                        'labour_contact' => isset($labourContact[$itemArr[1]]) ? $labourContact[$itemArr[1]]['id'] : 0,
                        'labour_type' => isset($labourType[$itemArr[2]]) ? $labourType[$itemArr[2]]['id'] : 0,
                        'labour_name' => $itemArr[3],
                        'labour_requirement' => isset($labourRequirement[$itemArr[4]]) ? $labourRequirement[$itemArr[4]]['id'] : 0,
                        'brand' => isset($brand[rtrim($itemArr[5], " ")]) ? $brand[rtrim($itemArr[5], " ")]['id'] : 0,
                        'brand_type' => isset($brandType[$itemArr[6]]) ? $brandType[$itemArr[6]]['id'] : 0,
                        'labour_model' => $itemArr[7],
                        'labour_protected' => $itemArr[8],
                        'labour_price' => $itemArr[9],
                        'remarks' => $itemArr[10],
                        'supplier' => $itemArr[11],
                        'address' => $itemArr[12],
                        'contacts' => $itemArr[13],
                        'contac_phone' => $itemArr[14],
                        'updated_at' => date("Y-m-d  h:i:s")
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
     * @param Request $request
     * @return array
     */
    public function getLabour(Request $request)
    {
        $uid = $request['uid'];
        $userInfo = $this->getUserRole($uid);
        if (isset($userInfo['code'])) {
            return $userInfo;
        }
        $technology = [];
        if ($userInfo[0]['role_id'] == 5) {
            $technology = [
                'labour_harm',
                'labour_contact',
                'labour_type',
                'labour_name',
                'labour_requirement',
                'brand',
                'brand_type',
                'labour_model',
                'labour_protected',
                'remarks',
                'supplier',
                'address',
                'contacts',
                'contac_phone'
            ];
        }
        if (isset($request['file'])) {


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
                $all = [];
                Excel::load($fileName, $re = function ($reader) use (&$all, $technology) {
                    foreach ($reader->get() as $k => $item) {
                        $info = array_values($item->toArray());

                        $cheJian = $info[0]['车间'];
                        $gongZhong = $info[0]['工种'];
                        $peopleNum = $info[0]['人数'];
                        $sheBei = $info[0]['使用设备'];
                        $labour_harm = $info[0]['危害因素_安全风险'];
                        $labour_harm = explode('、', $labour_harm);
                        $labour_contact = $info[0]['接触_部位伤害部位'];
                        $labour_contact = explode('、', $labour_contact);
                        if (!empty($technology)) {
                            $re = Labour::where('delete_flag', 2)->select($technology)->whereIn('labour_harm',
                                $labour_harm)->whereIn('labour_contact',
                                $labour_contact)->get()->toArray();
                        } else {
                            $re = Labour::where('delete_flag', 2)->whereIn('labour_harm',
                                $labour_harm)->whereIn('labour_contact',
                                $labour_contact)->get()->toArray();
                        }
                        foreach ($re as $kk => $vv) {
                            if ($kk == 0) {
                                $re[$kk]['che_jian'] = $cheJian;
                                $re[$kk]['gong_zhong'] = $gongZhong;
                                $re[$kk]['people_num'] = $peopleNum;
                                $re[$kk]['she_bei'] = $sheBei;
                            } else {
                                $re[$kk]['che_jian'] = '';
                                $re[$kk]['gong_zhong'] = '';
                                $re[$kk]['people_num'] = '';
                                $re[$kk]['she_bei'] = '';
                            }
                        }
                        $all[$k] = $re;
                        break;
                    }
                });
                $re = json_decode(json_encode($all), true);
                $arr = [];
                foreach ($re as $kkk => $vvv) {
                    foreach ($vvv as $a => $b) {
                        array_push($arr, $b);
                    }
                }

            }
        } else {
            if (!empty($technology)) {
                $arr = Labour::where('delete_flag', 2)->select($technology)->get()->toArray();
            } else {
                $arr = Labour::where('delete_flag', 2)->get()->toArray();
            }
            foreach ($arr as $k => $v) {
                $arr[$k]['che_jian'] = '';
                $arr[$k]['gong_zhong'] = '';
                $arr[$k]['people_num'] = '';
                $arr[$k]['she_bei'] = '';
            }
        }
        if (!empty($arr)) {
            $brand = AttributeItemValue::where('attribute_item_id', 1)->get()->toArray();
            $brand = array_column($brand, null, 'id');
            $labourHarm = AttributeItemValue::where('attribute_item_id', 2)->get()->toArray();
            $labourHarm = array_column($labourHarm, null, 'id');
            $labourContact = AttributeItemValue::where('attribute_item_id', 3)->get()->toArray();
            $labourContact = array_column($labourContact, null, 'id');
            $labourType = AttributeItemValue::where('attribute_item_id', 4)->get()->toArray();
            $labourType = array_column($labourType, null, 'id');
            $labourRequirement = AttributeItemValue::where('attribute_item_id', 5)->get()->toArray();
            $labourRequirement = array_column($labourRequirement, null, 'id');
            $brandType = AttributeItemValue::where('attribute_item_id', 6)->get()->toArray();
            $brandType = array_column($brandType, null, 'id');
            foreach ($arr as $num => $single) {
                $arr[$num]['labour_harm'] = isset($labourHarm[$single['labour_harm']]['name']) ? $labourHarm[$single['labour_harm']]['name'] : '';
                $arr[$num]['labour_contact'] = isset($labourContact[$single['labour_contact']]['name']) ? $labourContact[$single['labour_contact']]['name'] : '';
                $arr[$num]['labour_type'] = isset($labourType[$single['labour_type']]['name']) ? $labourType[$single['labour_type']]['name'] : '';
                $arr[$num]['labour_requirement'] = isset($labourRequirement[$single['labour_requirement']]['name']) ? $labourRequirement[$single['labour_requirement']]['name'] : '';
                $arr[$num]['brand_type'] = isset($brandType[$single['brand_type']]['name']) ? $brandType[$single['brand_type']]['name'] : '';
            }
        }

        return $this->success($arr);
    }

    public function getUserRole($uid)
    {
        if (empty($uid)) {
            return $this->error(['没有用户id！']);
        }
        $userInfo = RoleUser::where('user_id', $uid)->get()->toArray();
        if (empty($userInfo)) {
            return $this->error(['用户信息异常,请联系管理员']);
        }

        return $userInfo;

    }

    function exportExcel($title,$list,$width){

        Excel::create(iconv('UTF-8', 'GBK', $title),function($excel) use ($list,$width){
            $excel->sheet('score', function($sheet) use ($list,$width){
                $sheet->rows($list);
                $sheet->setWidth($width);
            });
        })->export('xls');

        return [];
    }
}