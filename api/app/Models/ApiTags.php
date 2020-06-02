<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $addtime
 * @property string $title
 * @property boolean $is_del
 * @property int $sort
 * @property string $last_update_time
 */
class ApiTags extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['addtime', 'title', 'is_del', 'sort', 'last_update_time'];

}
