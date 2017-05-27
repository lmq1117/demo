<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiTmpController extends Controller
{
    //
    protected $returnMsg;

    protected function setReturnMsg($msg="",$code=0){
        $this->returnMsg['code'] = $code;
        $this->returnMsg['message'] = $msg;
    }
}
