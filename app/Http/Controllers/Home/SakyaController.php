<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SakyaController extends Controller
{
    //
    protected $message;
    protected $code = [
        0 => 'fail!',
        1 => 'success！'
    ];

    protected function setReturnMsg($code=0){
        $this->returnMsg['code'] = $code;
        $this->returnMsg['message'] = $this->error_no[$code];
    }
    
    /**
     * @param int    $re   状态标记
     * @param string $msg  状态描述
     *
     * @return bool
     */
    protected function returnMsg($code = 1, $msg = false){
        $message = $msg ? $msg : ( $this->code[$code] ?? 'fail!');
        return json_encode( array('code'=>$code, 'message'=>$message));
    }
    /**
     * echo json
     * @param string $re
     *
     * @return bool
     */
    protected function returnData($re=''){
        if ($re !== false && $re !== null && $re !== 0 && $re !== -1) {
            return json_encode(array('code' => 1, 'data' => $re));
        } return $this->returnMsg(0);
    }
}
