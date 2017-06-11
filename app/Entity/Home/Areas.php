<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    //
    protected $table = 'areas';



    public static function getAddressStr($path){
        $pathArr = explode(',',trim($path,','));
        $pathStrObj =  self::whereIn('area_id',$pathArr)->get();
        $pathstr = '';
        foreach($pathStrObj as $v){
            $pathstr .= $v->area_name;
        }
        return $pathstr;
    }
}
