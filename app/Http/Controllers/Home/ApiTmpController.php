<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiTmpController extends Controller
{
    // 这货废弃，请继承 App\Http\Controllers\ApiController
    //protected $returnMsg;
    //protected $error_no = [
    //    0 => 'success',
    //    1 => '收藏商品失败！',
    //    2 => '关注商品失败！',
    //    3 => '同一商品不能重复关注！',
    //    4 => '同一商品不能重复收藏！'
    //];
    //
    //protected function setReturnMsg($code=0){
    //    $this->returnMsg['code'] = $code;
    //    $this->returnMsg['message'] = $this->error_no[$code];
    //}
}
