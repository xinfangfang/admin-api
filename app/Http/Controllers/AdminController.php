<?php
/**
 * Created by PhpStorm.
 * User: xinfangfang
 * Date: 2020/11/27
 * Time: 1:24 AM
 */

namespace App\Http\Controllers;


use App\models\Role;

class AdminController
{
    /**
     * 添加用户
     */
    public function addUser()
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
}