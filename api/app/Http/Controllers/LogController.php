<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/4/14   18:37
 */

namespace App\Http\Controllers;


use App\Extension\Utils\DateUtil;
use App\Models\LogUserAction;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    /**获取用户行为日志
     * @param Request $request
     * @return callable|\Illuminate\Database\Eloquent\Builder|mixed
     * @author: hbh
     * @Time: 2020/4/14   15:06
     */
    public function getUserAction(Request $request){
        $this->getCommonInfo($user, $request, 'login');
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[];
        $this->addWhereExact($where, $request, ['nickname', 'act_type', 'begintime', 'endtime']);
        return $this->internalListAction($request, function () use ($where) {
            $builder=LogUserAction::query()->where($where);
            return $builder;
        },function (&$result){
            if(!empty($result['data'])){
                foreach ($result['data'] as &$item){
                    $item['addtime'] = DateUtil::callbackTime($item['addtime']);
                    $item['infos_sub'] = mb_strlen($item['infos']) > 30 ? mb_substr($item['infos'], 0, 27) . '...' : $item['infos'];
                }

            }
        });
    }
}
