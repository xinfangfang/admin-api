<?php
/**
 * Created by PhpStorm.
 * User: xinfangfang
 * Date: 2020/11/30
 * Time: 8:30 PM
 */

namespace App\Http\Controllers;


use App\models\AttributeItemValue;
use App\models\Labour;
use App\models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        if (isset($userInfo[0]['role_id'])) {
            if ($userInfo[0]['role_id'] != 5) {
                return $this->error(['没有权限,请联系管理员！']);
            }
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
        //1.筛选||列表   2.导出
        $type = $request['type'];
        $userInfo = $this->getUserRole($uid);
        if (isset($userInfo['code'])) {
            return $userInfo;
        }
        $technology = [];
        if ($userInfo[0]['role_id'] == 5) {
            $technology = [
                'id',
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
                $labourHarm = AttributeItemValue::where('attribute_item_id', 2)->get()->toArray();
                $labourHarm = array_column($labourHarm, null, 'name');
                $labourContact = AttributeItemValue::where('attribute_item_id', 3)->get()->toArray();
                $labourContact = array_column($labourContact, null, 'name');
                $all = [];
                $emptySign = [];
                Excel::load($fileName,
                    $re = function ($reader) use (
                        &$all,
                        $technology,
                        $labourHarm,
                        $labourContact,
                        $type,
                        $uid,
                        &
                        $emptySign
                    ) {
                        foreach ($reader->get() as $k => $item) {
                            $info = array_values($item->toArray());
                            if (empty($info)) {
                                continue;
                            }
                            foreach ($info as $infoNum => $oneInfo) {
                                $cheJian = $info[$infoNum]['车间'];
                                $gongZhong = $info[$infoNum]['工种'];
                                $peopleNum = $info[$infoNum]['人数'];
                                $sheBei = $info[$infoNum]['使用设备'];
                                $touch = $info[$infoNum]['接触水平'];
                                if (empty($touch)) {
                                    $touch = '';
                                }
                                $labour_harmStr = $info[$infoNum]['危害因素_安全风险'];
                                $labour_harm = explode('、', $labour_harmStr);
                                $number = $info[$infoNum]['数量'];
                                foreach ($labour_harm as $harmNUm => $harm) {
                                    $labour_harm[$harmNUm] = isset($labourHarm[$harm]) ? $labourHarm[$harm]['id'] : '';
                                }
                                $labour_contactStr = $info[$infoNum]['接触_部位伤害部位'];
                                $labour_contact = explode('、', $labour_contactStr);
                                foreach ($labour_contact as $contactNum => $contact) {
                                    $labour_contact[$contactNum] = isset($labourContact[$contact]) ? $labourContact[$contact]['id'] : '';
                                }
                                if (!empty(array_filter($labour_harm)) && !empty(array_filter($labour_contact))) {
                                    $re = Labour::where('delete_flag', 2)->whereIn('labour_harm',
                                        array_values(array_filter($labour_harm)))->whereIn('labour_contact',
                                        array_values(array_filter($labour_contact)))->get()->toArray();
                                } elseif (!empty(array_filter($labour_harm)) && empty(array_filter($labour_contact))) {
                                    $re = Labour::where('delete_flag', 2)->whereIn('labour_harm',
                                        array_values(array_filter($labour_harm)))->get()->toArray();
                                } elseif (empty(array_filter($labour_harm)) && !empty(array_filter($labour_contact))) {
                                    $re = Labour::where('delete_flag', 2)->whereIn('labour_contact',
                                        array_values(array_filter($labour_contact)))->get()->toArray();
                                } elseif (empty(array_filter($labour_harm)) && empty(array_filter($labour_contact))) {
                                    $re = Labour::where('delete_flag', 2)->get()->toArray();
                                }
                                $data = [];
                                foreach ($re as $kk => $vv) {
                                    //默认一个小模块只有第一行有数据
                                    $data[$kk]['id'] = $vv['id'];
                                    if ($kk == 0) {
                                        $data[$kk]['che_jian'] = $cheJian;
                                        $data[$kk]['gong_zhong'] = $gongZhong;
                                        $data[$kk]['people_num'] = $peopleNum;
                                        $data[$kk]['she_bei'] = $sheBei;
                                        $data[$kk]['touch'] = $touch;
                                        $data[$kk]['labour_harm'] = $labour_harmStr;
                                        $data[$kk]['labour_contact'] = $labour_contactStr;

                                    } else {
                                        $data[$kk]['che_jian'] = '';
                                        $data[$kk]['gong_zhong'] = '';
                                        $data[$kk]['people_num'] = '';
                                        $data[$kk]['she_bei'] = '';
                                        $data[$kk]['touch'] = '';
                                        $data[$kk]['labour_harm'] = '';
                                        $data[$kk]['labour_contact'] = '';
                                    }
                                    $data[$kk]['labour_type'] = $vv['labour_type'];
                                    $data[$kk]['labour_name'] = $vv['labour_name'];
                                    $data[$kk]['labour_requirement'] = $vv['labour_requirement'];
                                    $data[$kk]['brand'] = $vv['brand'];
                                    $data[$kk]['brand_type'] = $vv['brand_type'];
                                    $data[$kk]['labour_model'] = $vv['labour_model'];
                                    $data[$kk]['labour_protected'] = $vv['labour_protected'];
                                    if (empty($technology)) {
                                        $data[$kk]['labour_price'] = $vv['labour_price'];
                                    }
                                    $data[$kk]['number'] = $number;
                                    if (empty($technology)) {
                                        $data[$kk]['all_price'] = $number * (isset($vv['labour_price']) ? $vv['labour_price'] : '');
                                        $data[$kk]['supplier'] = $vv['supplier'];
                                        $data[$kk]['address'] = $vv['address'];
                                        $data[$kk]['contacts'] = $vv['contacts'];
                                        $data[$kk]['contac_phone'] = $vv['contac_phone'];
                                    }
                                    if (empty($vv['remarks'])) {
                                        $vv['remarks'] = '';
                                    }
                                    $data[$kk]['remarks'] = $vv['remarks'];
                                }
                                if (!empty($data)) {
                                    array_push($all, $data);
                                }
                            }
                        }
                    });

                $loopNun = count($all);
                $arr = [];
                for ($i = 0; $i < $loopNun; $i++) {
                    foreach ($all[$i] as $k => $v) {
                        array_push($arr, $v);
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
            $labourType = AttributeItemValue::where('attribute_item_id', 4)->get()->toArray();
            $labourType = array_column($labourType, null, 'id');
            $labourRequirement = AttributeItemValue::where('attribute_item_id', 5)->get()->toArray();
            $labourRequirement = array_column($labourRequirement, null, 'id');
            $brandType = AttributeItemValue::where('attribute_item_id', 6)->get()->toArray();
            $brandType = array_column($brandType, null, 'id');
            foreach ($arr as $num => $single) {
                $arr[$num]['brand'] = isset($brand[$single['brand']]['name']) ? $brand[$single['brand']]['name'] : '';
                $arr[$num]['labour_type'] = isset($labourType[$single['labour_type']]['name']) ? $labourType[$single['labour_type']]['name'] : '';
                $arr[$num]['labour_requirement'] = isset($labourRequirement[$single['labour_requirement']]['name']) ? $labourRequirement[$single['labour_requirement']]['name'] : '';
                $arr[$num]['brand_type'] = isset($brandType[$single['brand_type']]['name']) ? $brandType[$single['brand_type']]['name'] : '';
                unset($arr[$num]['updated_at']);
                unset($arr[$num]['create_at']);
                unset($arr[$num]['delete_flag']);
            }
        }
        if ($type == 1) {
            Session::put($uid . 'x', $arr);
            Session::save();
        }
        return $this->success($arr);
    }

    public function getUserRole($uid)
    {
        if (empty($uid)) {
            return $this->error(['没有用户id！']);
        }
        $userInfo = RoleUser::where('user_id', $uid)->where('delete_flag', 1)->get()->toArray();
        if (empty($userInfo)) {
            return $this->error(['用户信息异常,请联系管理员']);
        }

        return $userInfo;

    }

    function exportExcel(Request $request)
    {
        $uid = $request['uid'];
        $userInfo = $this->getUserRole($uid);
        $title = '劳动防护用品信息导出' . "日期" . date("Y-m-d   H：i：s");
        $width = array('A' => 30, 'B' => 15, 'C' => 15, 'D' => 15, 'E' => 20);
        $head = [
            '车间',
            '工种',
            '人数',
            '使用设备',
            '接触水平',
            '危害因素/安全风险',
            '接触部位/伤害部位',
            '个人防护类别',
            '个人防护用品名称',
            '参数要求',
            '品牌',
            '品牌类型',
            '型号',
            '防护参数',
            '单价',
            '数量',
            '总价',
            '供应商',
            '地址',
            '联系人',
            '联系方式',
            '备注'
        ];
        $list = Session::get($uid . 'x');
        foreach ($list as $k => $v) {
            unset($list[$k]['id']);
        }
        if ($userInfo[0]['role_id'] == 5) {
            $head = [
                '车间',
                '工种',
                '人数',
                '使用设备',
                '接触水平',
                '危害因素/安全风险',
                '接触部位/伤害部位',
                '个人防护类别',
                '个人防护用品名称',
                '参数要求',
                '品牌',
                '品牌类型',
                '型号',
                '防护参数',
                '数量',
                '备注'
            ];
        }
        array_unshift($list, $head);
        Excel::create(iconv('UTF-8', 'GBK', $title), function ($excel) use ($list, $width) {
            $excel->sheet('score', function ($sheet) use ($list, $width) {
                $sheet->rows($list);
                $sheet->setWidth($width);
            });
        })->export('xls');

        return [];
    }

    function exportUqExcel(Request $request)
    {
        $uid = $request['uid'];
        $title = '劳动防护用品信息报告导出' . "日期" . date("Y-m-d   H：i：s");
        $width = array('A' => 30, 'B' => 15, 'C' => 15, 'D' => 15, 'E' => 20);
        $list = Session::get($uid . 'x');
        $arr = [];
        foreach ($list as $k => $v) {
            $arr[$k]['che_jian'] = $v['che_jian'];
            $arr[$k]['gong_zhong'] = $v['gong_zhong'];
            $arr[$k]['labour_harm'] = $v['labour_harm'];
            $arr[$k]['labour_name'] = $v['labour_name'];
            $arr[$k]['labour_requirement'] = $v['labour_requirement'];
        }
        $key = '';
        $re_arr = [];
        foreach ($arr as $k => $v) {
            if (!empty($v['che_jian'])) {
                $key = $k;
                $re_arr[$key] = [];
                array_push($re_arr[$key], $v);
            } else {
                array_push($re_arr[$key], $v);
            }
        }
        $re = [];
        foreach ($re_arr as $kk => $vv) {
            $list = $this->array_unset_tt($vv, 'labour_name');
            array_push($re, $list);

        }
        $re_list = [];
        foreach ($re as $kkk => $vvv) {
            foreach ($vvv as $kkkk => $vvvv) {
                array_push($re_list, $vvvv);
            }
        }

        $head = [
            '车间',
            '工种',
            '职业病危害因素',
            '个人防护用品名称',
            '参数要求',
        ];
        array_unshift($re_list, $head);
        Excel::create(iconv('UTF-8', 'GBK', $title), function ($excel) use ($re_list, $width) {
            $excel->sheet('score', function ($sheet) use ($re_list, $width) {
                $sheet->rows($re_list);
                $sheet->setWidth($width);
            });
        })->export('xls');

        return [];
    }


    public function array_unset_tt($arr, $key)
    {
        $res = array();
        foreach ($arr as $value) {
            if (isset($res[$value[$key]])) {
                unset($value[$key]);
            } else {
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }
}