<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\ApiTmpController;
use App\User;

class UserController extends ApiTmpController
{
    //会员中心所需数据
    public function getUserInfo(Request $request){
        $req_data = $request->all();
        $username = $req_data['username'];
        $user = User::findForId($username);

    }
}
