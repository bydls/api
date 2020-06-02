<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/4/14   15:00
 */

namespace App\Http\Controllers;


use App\Extension\Utils\DateUtil;
use App\Models\ApiLits;
use App\Models\ApiProject;
use App\Models\ApiProjectModule;
use App\models\ApiUser;
use App\Models\LogUserAction;
use App\Models\LogUserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends BaseController
{

    public function getSystemConfig(Request $request)
    {
        $version=DB::select("select VERSION() as num");
        $data = [
            'adminLogsCount' => LogUserAction::query()->count(),
            'Mysqlverion' => $version[0]->num,// mysql 版本
            'Phpverion' => PHP_VERSION,// php 版本
            'Server' => PHP_OS . '(' . $_SERVER['SERVER_ADDR'] . ')',// 服务器环境
            'Software' => $_SERVER['SERVER_SOFTWARE'],// 开发环境
            'Times' => date('Y-m-d H:i'),// 当前时间
            'list_count' => ApiLits::query()->where('is_del',0)->count(),
            'user_count' => ApiUser::query()->where('is_del',0)->count(),
            'today_count' => LogUserAction::query()->where('addtime','>',strtotime(date('Ymd')))->count(),
            'project_count' => ApiProject::query()->where('is_del',0)->select('id','title')->count(),];
       return $this->result->success($data);
    }

    /**获取项目列表
     * @return callable|\Illuminate\Database\Eloquent\Builder|mixed
     * @author: hbh
     * @Time: 2020/4/24   15:39
     */
    public function getCount(Request $request){
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['is_del',0],
        ];
        $count['list_info']=ApiLits::query()->where($where)->selectRaw('count(1) as num,pid')->groupBy('pid')->pluck('num','pid')->toArray();
        $count['list_all']=array_sum($count['list_info']);
        $count['action_all']=LogUserAction::query()->count();
        $data['count']=$count;
        $data['project_info']=ApiProject::query()->where($where)->select('id','title')->get()->toArray();
        return $this->result->success($data);
    }
}
