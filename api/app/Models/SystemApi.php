<?php
/**
 * @Desc:系统Api
 * @author: hbh
 * @Time: 2020/4/9   17:21
 */
namespace App\Models;
use App\Extension\Utils\CheckUtil;
use App\Extension\Utils\RequestUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemApi extends Model
{

    protected $table = 'system_api';

    /**根据请求地址获取对应的ID
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/14   16:51
     */
    public static function getIdByRequest(){
        $url=RequestUtil::get_url_path();
        $where=[
            ['url',$url],
            ['is_del',0],
        ];
        return SystemApi::query()->where($where)->value('id');
    }
    /**
     *  获取系统提供的API
     * @return array|mixed
     */
    public static function getApis()
    {
        $apis = Cache::get("system_api");
        if (is_string($apis)) $apis = json_decode($apis, true);

        if (empty($apis)) {
            $apis = SystemApi::query()->where("app_id", config('app.id'))->get()->toArray();
            if (is_array($apis) && count($apis)) {
                $cache_apis = json_encode($apis);

                if (CheckUtil::is_production()) {
                    Cache::put("system_api", $cache_apis, 1800); //缓存30分钟
                }

                $apis = json_decode($cache_apis, true); //保持输出结果一致
            }
        }

        return $apis;
    }
}
