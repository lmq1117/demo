<?php

namespace App\Entity\Traits;

// 二进制相关
trait BinaryTrait {
    
    
    public static function getArrByInt(int $num){
        $arr = [];
        $scene_str = (string)decbin($num);
        $count = strlen($scene_str) - 1;
        for($i = $count ; $i >= 0 ; $i--){
            if($scene_str[$i] == '1'){
                $arr[] = pow(2,($i) );
            }
        }
        return $arr;
    }
}
