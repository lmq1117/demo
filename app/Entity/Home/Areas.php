<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    //
    protected $table = 'areas';



    public static function getAddressStr($path){
        $pathArr = explode(',',trim($path,','));
        $pathStrObj =  self::whereIn('id',$pathArr)->get();
        return $pathStrObj;
    }
}
