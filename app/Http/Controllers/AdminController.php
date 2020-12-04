<?php
/**
 * Created by PhpStorm.
 * User: xinfangfang
 * Date: 2020/11/27
 * Time: 1:24 AM
 */

namespace App\Http\Controllers;


use App\models\Role;
use App\models\Permission;
use App\User;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class AdminController
{
    use EntrustUserTrait;

    /**
     * 添加角色
     */
    public function addRole()
    {
        $owner = new Role();
        $owner->name = 'owner';
        $owner->display_name = 'Project Owner'; // optional
        $owner->description = 'User is the owner of a given project'; // optional
        $owner->save();

        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description = 'User is allowed to manage and edit other users'; // optional
        $admin->save();

        return "添加成功";

    }

    /**
     * 添加用户
     */
    public function addUser()
    {
        $user = User::where('name', '=', 'michele')->first();
        $user->attachRole(1);

        return "添加用户成功";
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

        return "添加权限成功";
    }
}
