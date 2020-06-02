<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property boolean $is_del
 * @property string $name
 * @property int $addtime
 * @property string $last_update_time
 */
class ApiRole extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'api_role';

    /**
     * @var array
     */
    protected $fillable = ['is_del', 'name', 'addtime', 'last_update_time'];

}
