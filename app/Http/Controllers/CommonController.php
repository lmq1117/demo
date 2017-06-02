<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CommonController extends Controller
{
    //
    protected $session = Redis::connection('session');

    protected $config = [
        'appid'=>'wx858b762dc6af6249',
        'appSecret'=>'1ab68b48c760948ee041173508c3fcfc'
    ];
}
