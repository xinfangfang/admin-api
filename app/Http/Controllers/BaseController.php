<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
class BaseController extends Controller
{

    public function login()
    {
        return view('index.login');
    }

}

