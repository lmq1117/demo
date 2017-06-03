<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class VisitorInfo extends Model
{
    protected $table = 'visitor_info';

    protected $primaryKey = 'id';
    
    public $timestamps = true;
    
    protected $fillable = ['name','phone','scene','note'];
    
    /**
      * 指定时间字符
      *
      * @param  DateTime|int  $value
      * @return string
      */
    public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }
    // visitor_info 建表sql
    private function sakya_create_table(){
        $sql = <<<EEE
DROP TABLE IF EXISTS `visitor_info`;
CREATE TABLE `visitor_info` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名or联系人',
  `phone` bigint(1) NOT NULL DEFAULT 0 COMMENT '手机号',
  `scene` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '接入场景',
  `note` varchar(500) NOT NULL DEFAULT '' COMMENT '需求说明',
  `created_at` int(11) NOT NULL DEFAULT 0 COMMENT '新增时间',
  `updated_at` int(11) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='访客登记表';
EEE;
        return $sql;
    }

}
