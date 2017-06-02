<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Home\ApiTmpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CommonController extends ApiTmpController
{
    //
    protected $session;

    protected $config = [
        'appid'=>'wx858b762dc6af6249',
        'appSecret'=>'1ab68b48c760948ee041173508c3fcfc'
    ];

    public function __construct()
    {
        //parent::__construction();
        $this->session = Redis::connection('session');

    }
}
