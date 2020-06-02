<?php
/**
 * @Desc:用户操作日志
 * @author: hbh
 * @Time: 2020/4/9   17:05
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LogUserOperate extends Model
{
    protected $table = 'log_user_operate';
    //取消时间戳
    public $timestamps = false;
}
