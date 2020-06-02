<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property integer $pid
 * @property int $module_id
 * @property string $title
 * @property string $url
 * @property string $url_id
 * @property boolean $restype
 * @property boolean $ishtml
 * @property boolean $is_del
 * @property string $version
 * @property string $lastuser
 * @property int $lasttime
 * @property string $adduser
 * @property int $addtime
 * @property boolean $status
 * @property string $tags
 * @property string $intro
 * @property string $last_update_time
 */
class ApiLits extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_lists';
    //取消时间戳
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['pid', 'module_id', 'title', 'url', 'url_id', 'restype', 'ishtml', 'is_del', 'version', 'lastuser', 'lasttime', 'adduser', 'addtime', 'status', 'tags', 'intro', 'last_update_time'];

}
