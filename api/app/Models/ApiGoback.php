<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $list_id
 * @property int $fid
 * @property string $title
 * @property string $title_desc
 * @property boolean $isrequired
 * @property boolean $istop
 * @property string $datatype
 * @property string $demo_desc
 * @property int $addtime
 * @property string $adduser
 * @property int $lasttime
 * @property string $lastuser
 * @property integer $sort
 * @property boolean $is_del
 * @property string $last_update_time
 */
class ApiGoback extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_goback';
    //取消时间戳
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['list_id', 'fid', 'title', 'title_desc', 'isrequired', 'istop', 'datatype', 'demo_desc', 'addtime', 'adduser', 'lasttime', 'lastuser', 'sort', 'is_del', 'last_update_time'];

    public static function getGobackListByListId($list_id)
    {
        $where = [
            ['list_id', $list_id],
            ['is_del', 0],
        ];
        $select = ['id', 'fid', 'title', 'title_desc', 'isrequired', 'istop', 'datatype', 'demo_desc', 'sort'];
        $list_info = ApiGoback::query()->where($where)->select($select)->get()->toArray();
        if (empty($list_info)) return [];

        $list=array_column($list_info,null,'id');
        return self::makeChildGroupByFid($list);

    }

    /**无限极分类
     * @param $list
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   18:43
     */
    public static function makeChildGroupByFid($list){

        $need_unset=[];
        foreach ($list as $item){
            if(isset($list[$item['fid']])){
                $list[$item['fid']]['child'][$item['id']]=$item;
                $need_unset[]=$item['id'];
                $temp=$list[$item['fid']];
                while (isset($list[$temp['fid']])){
                    $list[$temp['fid']]['child'][$temp['id']]=$temp;
                    $temp= $list[$temp['fid']];
                }
            }
        }
        foreach ($need_unset as $v) {
            unset($list[$v]);
        }

        return $list;
    }
}
