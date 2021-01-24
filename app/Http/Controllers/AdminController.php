<?php
/**
 * Created by PhpStorm.
 * User: xinfangfang
 * Date: 2020/11/27
 * Time: 1:24 AM
 */

namespace App\Http\Controllers;

use App\models\Admin;
use App\models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\models\Role;
use App\models\Permission;
use App\User;
use Illuminate\Support\Facades\Session;
use Zizaco\Entrust\Traits\EntrustUserTrait;


class AdminController extends Controller
{
    use EntrustUserTrait;

    /**
     * 添加角色
     *
     * @param Request $request
     * @return array
     */
    public function addRole(Request $request)
    {
        $param = $request->only(
            'role_name',
            'display_name'
        );
        $owner = new Role();
        $owner->name = $param['role_name'];
        $owner->display_name = $param['display_name']; // optional
        $owner->description = 'User is the owner of a given project'; // optional
        $owner->save();

        return $this->success(["添加成功"]);
    }

    /**
     * 添加用户
     */
    public function addUser(Request $request)
    {
        $param = $request->only(
            'role_id',
            'name'
        );
        $user = User::where('name', '=', $param['name'])->first();
        $user->attachRole($param['role_id']);


        return $this->success(["添加用户成功"]);
    }


    /**
     *  添加权限
     */
    public function addPermission()
    {
        $admin = new Role();
        $owner = new Role();

        $createPost = new Permission();
        $createPost->name = 'create-post';
        $createPost->display_name = 'Create Posts'; // optional
// Allow a user to...
        $createPost->description = 'create new blog posts'; // optional
        $createPost->save();

        $editUser = new Permission();
        $editUser->name = 'edit-user';
        $editUser->display_name = 'Edit Users'; // optional
// Allow a user to...
        $editUser->description = 'edit existing users'; // optional
        $editUser->save();

        $admin->attachPermission($createPost);
// equivalent to $admin->perms()->sync(array($createPost->id));
        $owner->attachPermissions(array($createPost, $editUser));

        return $this->success(["添加权限成功"]);
    }

    public function saveAdmin(Request $request)
    {
        //1、验证
        $data = $request->only('username', 'password');
        $saveData = [
            'username' => $data['username'],
            'password' => Hash::make($data['password'])
        ];
        if (!empty(Admin::where('username', $data['username'])->get()->toArray())) {
            return $this->error(['用户已注册']);
        } else {
            Admin::create($saveData);
        }
        $userInfo = Admin::where('username', $data['username'])->get()->toArray();
        $uid = $userInfo[0]['id'];
        RoleUser::insert(['user_id' => $uid, 'role_id' => '']);

        return $this->success(['注册成功']);
    }

    public function checkLogin(Request $request)
    {
        $data = $request->only('username', 'password');
        $res = Auth::guard('admin')->attempt($data);
        if ($res) {
            // 登录成功进入首页
            $userInfo = Admin::where('username', $data['username'])->get()->toArray();
            //存储用户信息
            Session::put('user_name', $data['username']);
            Session::put('user_id', $userInfo[0]['id']);

            return $this->success(['登录成功']);
        } else {
            return $this->error(['登录失败']);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_name');
        $request->session()->forget('user_id');

        //Auth::guard('admin')->logout();
        // 跳转到登录页
        //return redirect('admin/login');

        return $this->success(['登出成功']);
    }

    /**
     * 获取角色
     *
     * @return array
     */
    public function getRole()
    {
        $re = Role::select('id', 'name')->get()->toArray();

        return $this->success([$re]);
    }


    /**
     * 获取权限
     *
     * @return array
     */
    public function getPermissions()
    {
        $re = Permission::select('id', 'name', 'display_name')->get()->toArray();

        return $this->success([$re]);
    }

    public function getUserList()
    {
        $user = Admin::get()->toArray();
        $user = array_column($user, null, 'id');
        $role = Role::get()->toArray();
        $role = array_column($role, null, 'id');
        $permission = Permission::get()->toArray();
        $permission = array_column($permission, null, 'id');
        $re = RoleUser::where('delete_flag', 1)->get()->toArray();

        $data = [];
        foreach ($re as $k => $v) {
            $data[$k]['uid'] = $v['user_id'];
            $data[$k]['username'] = $user[$v['user_id']]['username'];
            $data[$k]['role'] = isset($role[$v['role_id']]['name']) ? $role[$v['role_id']]['name'] : '';
            $data[$k]['permission'] = isset($permission[$v['permissions_id']]['display_name']) ? $permission[$v['permissions_id']]['display_name'] : '';

        }

        return $this->success([$data]);
    }

    /**
     * 添加人员角色
     *
     * @return array
     */
    public function addRoleUser(Request $request)
    {
        $param = $request->only(
            'role_id',
            'user_id'
        );
        $updateArr = [
            'delete_flag' => 2,
        ];
        RoleUser::where('user_id', $param['user_id'])->update($updateArr);

        $data = [
            'role_id' => $param['role_id'],
            'user_id' => $param['user_id']
        ];
        RoleUser::insert($data);

        return $this->success([]);
    }
}
