<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function success(array $data = [])
    {
        $code = 0;

        return compact('code', 'data');

    }


    protected function error(array $data = [])
    {
        $code = 1099;

        return compact('code', 'data');

    }
}
