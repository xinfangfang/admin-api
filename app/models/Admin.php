<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
class Admin extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    protected $table = 'users';
    protected $guarded = [];
    use Authenticatable;
}
