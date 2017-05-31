<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiTmpController extends Controller
{
    //
    protected $returnMsg;
    protected $error_no = [
        0 => 'success',
        1 => '收藏失败！',
        2 => '关注成功！',
    ];

    protected function setReturnMsg($code=0){
        $this->returnMsg['code'] = $code;
        $this->returnMsg['message'] = $this->error_no[$code];
    }
}
