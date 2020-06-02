<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $list_id
 * @property boolean $isrequired
 * @property boolean $isvalue
 * @property boolean $is_del
 * @property integer $sort
 * @property string $title
 * @property string $title_desc
 * @property string $datatype
 * @property string $demo_desc
 * @property int $addtime
 * @property int $lasttime
 * @property string $adduser
 * @property string $lastuser
 * @property string $last_update_time
 */
class ApiParam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_param';
    //取消时间戳
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['list_id', 'isrequired', 'isvalue', 'is_del', 'sort', 'title', 'title_desc', 'datatype', 'demo_desc', 'addtime', 'lasttime', 'adduser', 'lastuser', 'last_update_time'];

}
