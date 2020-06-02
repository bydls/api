<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $username
 * @property string $password
 * @property boolean $is_del
 * @property int $addtime
 * @property string $last_update_time
 */
class ApiProjectTestUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_project_test_user';
    //取消时间戳
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['pid', 'username', 'password', 'is_del', 'addtime', 'last_update_time'];

}
