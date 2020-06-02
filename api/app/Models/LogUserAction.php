<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $uid
 * @property int $addtime
 * @property string $act_type
 * @property string $ip
 * @property string $nickname
 * @property string $location
 * @property string $infos
 */
class LogUserAction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_user_action';

    /**
     * @var array
     */
    protected $fillable = ['uid', 'addtime', 'act_type', 'ip', 'nickname', 'location', 'infos'];

    //取消时间戳
    public $timestamps = false;
}
