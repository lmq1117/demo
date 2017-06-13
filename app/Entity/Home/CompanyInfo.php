<?php

namespace App\Entity\Home;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use \App\Entity\Traits\BinaryTrait;
    
    protected $table = 'company_info';

    protected $primaryKey = 'id';
    
    public $timestamps = true;
    
    protected $fillable = ['name','phone','scene','note','value','company'];
    
    // visitor_info 建表sql
    private function sakya_create_table(){
        $sql = <<<EEE
DROP TABLE IF EXISTS `company_info`;
CREATE TABLE `company_info` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名or联系人',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `scene` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '接入场景',
  `value` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '预估金额',
  `note` varchar(500) DEFAULT '' COMMENT '需求说明',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '新增时间',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='公司登记表';
EEE;
        return $sql;
    }
    
    // 分页查询列表
    public static function getList( $current_page , $page_num , $is_del = 0 ){
        $a = ( (int)$current_page -1  )* (int)$page_num ; 
        return self::where('is_del',$is_del)->take($page_num)->skip($a)->get()->toArray();
    }
    
    // 查询当前条件下的数据总数
    public static function getCount( $is_del = 0){
        return self::where( 'is_del',$is_del )->count();
    }
    
    // 查询单个
    public static function getOne($id, $is_del = 0){
        $data = self::where('is_del',$is_del)->where('id',$id)->first()->toArray();
        if(empty($data)) return [];
        $data['scene'] = self::getArrByInt($data['scene']);
        return $data;
    }
    // 更新单个
    public static function updateById(array $data){
        return self::where( 'id',$data['id'] )->update($data);
    } 
    
    // 删除单个
    public static function delById($id){
        return self::where( 'id',$id )->update(['is_del' => 1]);
    }

}
