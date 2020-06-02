<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/4/14   16:35
 */

namespace App\Http\Controllers;

use App\Extension\Utils\DateUtil;
use App\Extension\Utils\RequestUtil;
use App\Models\ApiGoback;
use App\Models\ApiLits;
use App\Models\ApiParam;
use App\Models\ApiProject;
use App\Models\ApiProjectModule;
use App\Models\ApiProjectTestUser;
use App\Models\ApiTags;
use App\Models\SystemApi;
use App\Services\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiController extends BaseController
{

    /**获取接口列表
     * @param Request $request
     * @return callable|\Illuminate\Database\Eloquent\Builder|mixed
     * @author: hbh
     * @Time: 2020/4/26   16:24
     */
    public function getApiLists(Request $request)
    {
        $this->checkout_field = ['pid'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where = [];
        $this->addWhereExact($where, $request, ['pid', 'status', 'module_id', 'title', 'url', 'version']);
        $select = [];
        return $this->internalListAction($request, function () use ($select, $where) {
            $builder = ApiLits::query()->where($where);
            return $builder;
        }, function (&$result) {
            $tags_list = ApiTags::query()->where('is_del', 0)->get()->toArray();
            $tags_info = array_column($tags_list, 'title', 'id');

            $module_list = ApiProjectModule::query()->where('is_del', 0)->get()->toArray();
            $module_info = array_column($module_list, 'title', 'id');
            if (!empty($result['data'])) {
                foreach ($result['data'] as &$item) {
                    $temp = explode(',', $item['tags']);
                    foreach ($temp as $v) {
                        if (isset($tags_info[$v])) $item['tag_list'][] = $tags_info[$v];
                    }
                    if (isset($module_info[$item['module_id']])) $item['module_title'][] = $module_info[$item['module_id']];
                    $item['lasttime'] = DateUtil::callbackTime($item['lasttime']);
                }
            }
        });

    }

    /**获取接口详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   16:36
     */
    public function getApiInfo(Request $request)
    {
        $this->checkout_field = ['project_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $id = $request->input('id', 0);
        $where = [
            ['id', $id],
        ];
        $list = ApiLits::query()->where($where)->first();
        if (!isset($list)) {
            $list = new ApiLits();
        } else {
            $list->param_list = ApiParam::query()->where('list_id', $id)->where('is_del', 0)->get();
            $list->goback_list = ApiGoback::getGobackListByListId($id);
        }

        $project_info = ApiProject::query()->where('id', $request->input('project_id'))->first();
        $list->project_title = $project_info->title;
        $list->project_url = trim($project_info->url);
        $list->oldtime = time() - 604800;//最近一周
        $list->pid = $request->input('project_id');
        $list->tags_list = ApiTags::query()->where('is_del', 0)->select(['id', 'title'])->get();
        $list->module_list = ApiProjectModule::getModuleListByProjectId($request->input('project_id'));

        return $this->result->success($list);
    }

    /**编辑接口
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   17:25
     */
    public function editApi(Request $request)
    {
        $this->checkout_field = ['pid', 'title', 'module_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $edit_data = [
            'lastuser' => $user->username,
            'lasttime' => DateUtil::addtime(),
        ];
        $this->addUpdateData($edit_data, $request, ['pid', 'module_id', 'title', 'url', 'url_id', 'restype', 'ishtml', 'is_del', 'version', 'intro']);
        $tags = $request->input('tags[]', '');
        if (!empty($tags)) $edit_data['tags'] = implode(',', array_filter(explode(',', $tags)));
        $id = $request->input('id', 0);
        if ($id) {
            //修改接口
            $where = [
                ['id', $id],
                ['pid', $request->input('pid')],
            ];
            $info = ApiLits::query()->where($where)->first();
            if (!isset($info)) return $this->result->error('接口不存在');
            try {
                $info->update($edit_data);
                LoggerService::action('修改', '修改接口 ' . $request->input('title') . ' 的信息  成功');
                return $this->result->updateSuccess($info, $info);
            } catch (\Exception $e) {
                LoggerService::action('修改', '修改接口 ' . $request->input('title') . ' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //添加项目
            $edit_data['addtime'] = DateUtil::addtime();
            $edit_data['adduser'] = $user->username;
            try {
                $edit_data['id'] = ApiLits::query()->insertGetId($edit_data);
                LoggerService::action('新建', '新建接口 ' . $request->input('title') . ' 的信息  成功');
                return $this->result->addSuccess($edit_data, $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建', '新建接口 ' . $request->input('title') . ' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除接口
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   14:41
     */
    public function delApi(Request $request)
    {
        $this->checkout_field = ['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where = [
            ['id', $request->input('id')],
            ['is_del', 0],
        ];
        $info = ApiLits::query()->where($where)->first();
        if (!isset($info)) {
            return $this->result->error('接口不存在');
        }

        try {
            $info->is_del = 1;
            $info->save();
            LoggerService::action('删除', '删除接口 ' . $info->title . '  成功');
            return $this->result->updateSuccess([], $info);
        } catch (\Exception $e) {
            LoggerService::action('删除', '删除接口 ' . $info->title . '  失败');
            return $this->result->error($e->getMessage());
        }

    }

    /**获取参数详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   11:29
     */
    public function getParam(Request $request)
    {
        $this->checkout_field = ['list_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $id = $request->input('id', 0);
        $list_id = $request->input('list_id');
        $where = [
            ['id', $id],
            ['list_id', $list_id],
        ];
        $param = ApiParam::query()->where($where)->first();
        if (!isset($param)) {
            $param = new ApiParam();
        }
        $param->list_id = $list_id;
        return $this->result->success($param);
    }

    /**编辑参数
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   13:58
     */
    public function editParam(Request $request)
    {
        $this->checkout_field = ['title', 'list_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $project_info = ApiLits::query()->where('id', $request->input('list_id'))->first();
        if (!isset($project_info)) return $this->result->error('接口不存在');
        $edit_data = [
            'lastuser' => $user->username,
            'lasttime' => DateUtil::addtime(),
        ];
        $this->addUpdateData($edit_data, $request, ['title', 'list_id', 'datatype', 'demo_desc', 'title_desc', 'sort', 'isrequired', 'isvalue']);
        $id = $request->input('id', 0);
        if ($id) {
            //修改接口
            $where = [
                ['id', $id],
                ['list_id', $request->input('list_id')],
            ];
            $info = ApiParam::query()->where($where)->first();
            if (!isset($info)) return $this->result->error('参数不存在');
            try {
                $info->update($edit_data);
                LoggerService::action('修改', '修改接口 ' . $project_info->title . ' 参数' . $request->input('title') . ' 的信息  成功');
                return $this->result->updateSuccess([], $info);
            } catch (\Exception $e) {
                LoggerService::action('修改', '修改接口 ' . $project_info->title . ' 参数 ' . $request->input('title') . ' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //添加项目
            $edit_data['addtime'] = DateUtil::addtime();
            $edit_data['adduser'] = $user->username;
            try {
                $edit_data['id'] = ApiParam::query()->insertGetId($edit_data);
                LoggerService::action('新建', '新建接口 ' . $project_info->title . ' 参数 ' . $request->input('title') . ' 的信息  成功');
                return $this->result->addSuccess([], $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建', '新建接口 ' . $project_info->title . ' 参数 ' . $request->input('title') . ' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除参数
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   14:40
     */
    public function delParam(Request $request)
    {
        $this->checkout_field = ['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where = [
            ['id', $request->input('id')],
            ['is_del', 0],
        ];
        $info = ApiParam::query()->where($where)->first();
        if (!isset($info)) {
            return $this->result->error('接口参数不存在');
        }
        $project_info = ApiLits::query()->where('id', $info->list_id)->first();
        if (!isset($project_info)) return $this->result->error('接口不存在');
        try {
            $info->is_del = 1;
            $info->save();
            LoggerService::action('删除', '删除接口 ' . $project_info->title . ' 参数 ' . $info->title . '  成功');
            return $this->result->updateSuccess([], $info);
        } catch (\Exception $e) {
            LoggerService::action('删除', '删除接口 ' . $project_info->title . ' 参数 ' . $info->title . '  失败');
            return $this->result->error($e->getMessage());
        }

    }

    /**获取返回参数
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   15:14
     */
    public function getGoback(Request $request)
    {
        $this->checkout_field = ['list_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $id = $request->input('id', 0);
        $list_id = $request->input('list_id');
        $fid = $request->input('fid', 0);
        $where = [
            ['id', $id],
            ['list_id', $list_id],
            ['fid', $fid],
        ];
        $param = ApiGoback::query()->where($where)->first();
        if (!isset($param)) {
            $param = new ApiParam();
        }
        $param->list_id = $list_id;
        $param->fid = $fid;
        return $this->result->success($param);
    }

    /**编辑返回 参数
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   15:21
     */
    public function editGoback(Request $request)
    {
        $this->checkout_field = ['title', 'list_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $project_info = ApiLits::query()->where('id', $request->input('list_id'))->first();
        if (!isset($project_info)) return $this->result->error('接口不存在');
        $edit_data = [
            'lastuser' => $user->username,
            'lasttime' => DateUtil::addtime(),
        ];
        $this->addUpdateData($edit_data, $request, ['fid', 'title', 'list_id', 'datatype', 'demo_desc', 'title_desc', 'sort', 'isrequired']);
        $id = $request->input('id', 0);
        if ($id) {
            //修改接口
            $where = [
                ['id', $id],
                ['list_id', $request->input('list_id')],
            ];
            $info = ApiGoback::query()->where($where)->first();
            if (!isset($info)) return $this->result->error('返回参数不存在');
            try {
                $info->update($edit_data);
                LoggerService::action('修改', '修改接口 ' . $project_info->title . ' 返回参数' . $request->input('title') . ' 的信息  成功');
                return $this->result->updateSuccess([], $info);
            } catch (\Exception $e) {
                LoggerService::action('修改', '修改接口 ' . $project_info->title . ' 返回参数 ' . $request->input('title') . ' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //添加项目
            $edit_data['addtime'] = DateUtil::addtime();
            $edit_data['adduser'] = $user->username;
            try {
                $edit_data['id'] = ApiGoback::query()->insertGetId($edit_data);
                LoggerService::action('新建', '新建接口 ' . $project_info->title . ' 返回参数 ' . $request->input('title') . ' 的信息  成功');
                return $this->result->addSuccess([], $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建', '新建接口 ' . $project_info->title . ' 返回参数 ' . $request->input('title') . ' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除返回参数
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/27   15:21
     */
    public function delGoback(Request $request)
    {
        $this->checkout_field = ['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where = [
            ['id', $request->input('id')],
            ['is_del', 0],
        ];
        $info = ApiGoback::query()->where($where)->first();
        if (!isset($info)) {
            return $this->result->error('接口返回参数不存在');
        }
        $project_info = ApiLits::query()->where('id', $info->list_id)->first();
        if (!isset($project_info)) return $this->result->error('接口不存在');
        try {
            $info->is_del = 1;
            $info->save();
            LoggerService::action('删除', '删除接口 ' . $project_info->title . ' 返回参数 ' . $info->title . '  成功');
            return $this->result->updateSuccess([], $info);
        } catch (\Exception $e) {
            LoggerService::action('删除', '删除接口 ' . $project_info->title . ' 返回参数 ' . $info->title . '  失败');
            return $this->result->error($e->getMessage());
        }

    }

    /**测试页用的api详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/5/21   18:30
     */
    public function getApiInfoTest(Request $request)
    {
        $this->checkout_field = ['project_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $id = $request->input('id', 0);
        $where = [
            ['id', $id],
        ];
        $list = ApiLits::query()->where($where)->select()->first();
        if (!isset($list)) {
            $list = new ApiLits();
        } else {
            $list->param_list = ApiParam::query()->where('list_id', $id)->where('is_del', 0)->get();
            $list->testuser_list = ApiProjectTestUser::query()->where('pid', $list->pid)->where('is_del', 0)->select(['user_id','username'])->get();
        }

        $project_info = ApiProject::query()->where('id', $request->input('project_id'))->first();
        $list->project_title = $project_info->title;
        $list->project_url = trim($project_info->url);
        $list->pid = $request->input('project_id');


        return $this->result->success($list);
    }

    /**请求接口
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/5/21   16:55
     */
    public function runApi(Request $request)
    {
        $this->checkout_field = ['url'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $params=$request->input();
        $url=$params['url'];
        $testuser_id=$params['testuser_id'];
        if(!$testuser_id) return $this->result->error('请选择正确的帐号');
        $token_key='testuser_token_'.$testuser_id;
        $token=Cache::get($token_key);
        if(!$token){
           // $token = RequestUtil::curl_post(strstr($url, '/api/', true).'/api/token', ['id'=>$testuser_id]);
            $token = file_get_contents(strstr($url, '/api/', true).'/api/token?id='.$testuser_id);
            $params['token']=$token;
            if(!$token||stripos(substr($token, 0, 20),'<!doctype html>')>-1){
                //   return $this->result->error('获取帐号token异常,请检查选择的帐号或请求的地址');
                return $this->result->success(array('list' => '获取帐号token异常,请检查选择的帐号或请求的地址','status' => 200, 'data' => $params, 'url' => $url));
                // return $this->result->success($params,'获取帐号token异常,请检查选择的帐号或请求的地址\'');
            }
            Cache::put($token_key,$token,3600);
        }
        $params['token']=$token;

        array_walk($params,function (&$item,$k){
            if(in_array($k,['url','testuser_id'])) unset($item);
        });
        $res = RequestUtil::curl_post($url, $params);
        return $this->result->success(array('list' => $res, 'status' => 200, 'data' => $params, 'url' => $url));
    }
}
