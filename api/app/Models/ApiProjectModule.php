<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $title
 * @property boolean $is_del
 * @property int $addtime
 * @property string $last_update_time
 */
class ApiProjectModule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_project_module';
    //取消时间戳
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['pid', 'title', 'is_del', 'addtime', 'last_update_time'];

    public static function getModuleListByProjectId($project_id){
        $where=[
            ['pid',$project_id],
            ['is_del',0],
        ];
        $select=['id','title'];
        return ApiProjectModule::query()->where($where)->select($select)->get();
    }
}
