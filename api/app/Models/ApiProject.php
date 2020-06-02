<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property boolean $is_del
 * @property int $addtime
 * @property boolean $sort
 * @property string $title
 * @property string $key
 * @property string $url
 * @property string $dingtalk_token
 * @property string $logo_url
 * @property string $last_update_time
 */
class ApiProject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_project';
    //取消时间戳
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['is_del', 'addtime', 'sort', 'title', 'key', 'url', 'dingtalk_token', 'logo_url', 'last_update_time'];

}
