<?php
/**
 * @Desc:项目
 * @author: hbh
 * @Time: 2020/4/21   10:51
 */

namespace App\Http\Controllers;
use App\Extension\Utils\CheckUtil;
use App\Extension\Utils\CodeUtil;
use App\Models\ApiProject;
use App\Models\ApiProjectModule;
use App\Extension\Utils\DateUtil;
use App\Models\ApiProjectTestUser;
use App\models\ApiUser;
use App\Services\LoggerService;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{

    /**获取项目列表
     * @return callable|\Illuminate\Database\Eloquent\Builder|mixed
     * @author: hbh
     * @Time: 2020/4/24   15:39
     */
    public function getProjectList(Request $request){
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['is_del',0],
        ];
        $this->addWhereExact($where, $request, ['title']);

        return $this->internalListAction($request, function () use ($where)  {
            $builder=ApiProject::query()->where($where);
            return $builder;
        },function (&$result){
            if(!empty($result['data'])){
                $prject_ids=array_column($result['data'],'id');
                $where=[
                    ['is_del',0],
                ];
                $select=['id','title','pid'];
                $prject_module_info=ApiProjectModule::query()->where($where)->whereIn('pid',$prject_ids)->select($select)->get()->toArray();
                $prject_module=[];
                foreach ($prject_module_info as $item){
                    $prject_module[$item['pid']][]=$item;
                }
                array_walk($result['data'],function (&$item) use ($prject_module){
                    $item['addtime']=DateUtil::callbackTime($item['addtime']);
                    $item['module_list']=[];
                    if(isset($prject_module[$item['id']]))  $item['module_list']=$prject_module[$item['id']];
                });


            }
        });
    }

    /**获取一个项目详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/24   18:03
     */
    public function getOneProject(Request $request){
        $this->checkout_field=['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        if($request->input('id')<1){
            return  $this->result->success([]);
        }
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $user=ApiProject::query()->where($where)->first();
        return  $this->result->success($user);
    }

    /**编辑项目
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/24   17:39
     */
    public function editProject(Request $request){
        $this->checkout_field=['title','url'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $edit_data = [
            'sort' => $request->input('sort',1),
            'title' => $request->input('title'),
            'key' => $request->input('key',''),
            'url' => $request->input('url'),
            'dingtalk_token' => $request->input('dingtalk_token',''),
        ];
        $id = $request->input('id', 0);
        if ($id) {
            //修改项目
            $where = [
                ['id', $id],
                ['is_del',0],
            ];
            $project = ApiProject::query()->where($where)->first();
            if (!isset($project)) return $this->result->error('项目不存在');
            try {
                $project->update($edit_data);
                LoggerService::action('修改','修改项目 '.$request->input('title').' 的信息  成功');
                return $this->result->updateSuccess([], $project);
            } catch (\Exception $e) {
                LoggerService::action('修改','修改项目 '.$request->input('title').' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //添加项目
            $edit_data['addtime'] = DateUtil::addtime();
            try {
                $edit_data['id']=ApiProject::query()->insertGetId($edit_data);
                LoggerService::action('新建','新建项目 '.$request->input('title').' 的信息  成功');
                return $this->result->addSuccess([], $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建','新建项目 '.$request->input('title').' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除一个项目
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   11:18
     */
    public function delProject(Request $request){
        $this->checkout_field=['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $project=ApiProject::query()->where($where)->first();
        if(!isset($project)){
            return  $this->result->error('项目不存在');
        }

        try {
            $project->is_del=1;
            $project->save();
            LoggerService::action('删除','删除项目 '.$project->title.'  成功');
            return $this->result->updateSuccess([], $project);
        }
        catch (\Exception $e){
            LoggerService::action('删除','删除项目 '.$project->title.'  失败');
            return $this->result->error($e->getMessage());
        }

    }
    /**获取一个模块详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   10:17
     */
    public function getOneProjectModule(Request $request){
        $this->checkout_field=['pid','id'];
        $this->getCommonInfo($user, $request);
        $pid=$request->input('pid');
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $module=ApiProjectModule::query()->where($where)->first();
        if(!isset($module)){
            $module=new ApiProjectModule();
        }
        $module->project_title=ApiProject::query()->where('id',$pid)->value('title');
        $module->pid=$pid;
        return  $this->result->success($module);
    }

    /**编辑一个模块
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   10:26
     */
    public function editProjectModule(Request $request){
        $this->checkout_field=['pid','title'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $edit_data = [
            'pid' => $request->input('pid'),
            'title' => $request->input('title'),
        ];
        $id = $request->input('id', 0);
        if ($id) {
            //修改模块
            $where = [
                ['id', $id],
                ['is_del',0],
            ];
            $module = ApiProjectModule::query()->where($where)->first();
            if (!isset($module)) return $this->result->error('模块不存在');
            try {
                $module->update($edit_data);
                LoggerService::action('修改','修改模块 '.$request->input('title').' 的信息  成功');
                return $this->result->updateSuccess([], $module);
            } catch (\Exception $e) {
                LoggerService::action('修改','修改模块 '.$request->input('title').' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //添加项目
            $edit_data['addtime'] = DateUtil::addtime();
            try {
                $edit_data['id']=ApiProjectModule::query()->insertGetId($edit_data);
                LoggerService::action('新建','新建模块 '.$request->input('title').' 的信息  成功');
                return $this->result->addSuccess([], $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建','新建模块 '.$request->input('title').' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除一个模块
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   11:20
     */
    public function delProjectModule(Request $request){
        $this->checkout_field=['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $module=ApiProjectModule::query()->where($where)->first();
        if(!isset($module)){
            return  $this->result->error('模块不存在');
        }

        try {
            $module->is_del=1;
            $module->save();
            LoggerService::action('删除','删除模块 '.$module->title.'  成功');
            return $this->result->updateSuccess([], $module);
        }
        catch (\Exception $e){
            LoggerService::action('删除','删除模块 '.$module->title.'  失败');
            return $this->result->error($e->getMessage());
        }

    }

    /**获取模块列表
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   12:05
     */
    public function getOneProjectModuleList(Request $request){
        $this->checkout_field=['project_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        if($request->input('project_id')<1){
            return  $this->result->success([]);
        }
        $result=[
            'data'=>ApiProjectModule::getModuleListByProjectId($request->input('project_id')),
        ];
        return  $this->result->success($result);
    }

    /**测试帐号关联列表
     * @param Request $request
     * @return callable|\Illuminate\Database\Eloquent\Builder|mixed
     * @author: hbh
     * @Time: 2020/5/21   18:02
     */
    public function getProjectTestuserList(Request $request){
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['is_del',0],
        ];
        $this->addWhereExact($where, $request, ['title']);

        return $this->internalListAction($request, function () use ($where)  {
            $builder=ApiProject::query()->where($where);
            return $builder;
        },function (&$result){
            if(!empty($result['data'])){
                $prject_ids=array_column($result['data'],'id');
                $where=[
                    ['is_del',0],
                ];
                $select=['id','username','user_id','pid'];
                $prject_testuser_info=ApiProjectTestUser::query()->where($where)->whereIn('pid',$prject_ids)->select($select)->get()->toArray();
                $prject_testuser=[];
                foreach ($prject_testuser_info as $item){
                    $prject_testuser[$item['pid']][]=$item;
                }
                array_walk($result['data'],function (&$item) use ($prject_testuser){
                    $item['addtime']=DateUtil::callbackTime($item['addtime']);
                    $item['testuser_list']=[];
                    if(isset($prject_testuser[$item['id']]))  $item['testuser_list']=$prject_testuser[$item['id']];
                });


            }
        });
    }

    /**获取一条测试帐号详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/5/21   18:05
     */
    public function getOneProjectTestuser(Request $request){
        $this->checkout_field=['pid','id'];
        $this->getCommonInfo($user, $request);
        $pid=$request->input('pid');
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $testuser=ApiProjectTestUser::query()->where($where)->first();
        if(!isset($testuser)){
            $testuser=new ApiProjectTestUser();
        }
        $testuser->project_title=ApiProject::query()->where('id',$pid)->value('title');
        $testuser->pid=$pid;
        return  $this->result->success($testuser);
    }

    /**编辑测试帐号
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/5/21   18:12
     */
    public function editProjectTestuser(Request $request){
        $this->checkout_field=['pid','username','user_id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $edit_data = [
            'pid' => $request->input('pid'),
            'username' => $request->input('username'),
            'user_id' => $request->input('user_id'),
        ];
        $id = $request->input('id', 0);
        if ($id) {
            //修改模块
            $where = [
                ['id', $id],
                ['is_del',0],
            ];
            $testuser = ApiProjectTestUser::query()->where($where)->first();
            if (!isset($testuser)) return $this->result->error('模块不存在');
            try {
                $testuser->update($edit_data);
                LoggerService::action('修改','修改测试帐号 '.$request->input('username').' 的信息  成功');
                return $this->result->updateSuccess([], $testuser);
            } catch (\Exception $e) {
                LoggerService::action('修改','修改测试帐号 '.$request->input('username').' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //添加项目
            $edit_data['addtime'] = DateUtil::addtime();
            try {
                $edit_data['id']=ApiProjectTestUser::query()->insertGetId($edit_data);
                LoggerService::action('新建','新建测试帐号 '.$request->input('username').' 的信息  成功');
                return $this->result->addSuccess([], $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建','新建测试帐号 '.$request->input('username').' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除一条测试帐号详情
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/5/21   18:07
     */
    public function delProjectTestuser(Request $request){
        $this->checkout_field=['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $testuser=ApiProjectTestUser::query()->where($where)->first();
        if(!isset($testuser)){
            return  $this->result->error('测试帐号不存在');
        }

        try {
            $testuser->is_del=1;
            $testuser->save();
            LoggerService::action('删除','删除测试帐号 '.$testuser->username.'  成功');
            return $this->result->updateSuccess([], $testuser);
        }
        catch (\Exception $e){
            LoggerService::action('删除','删除测试帐号 '.$testuser->username.'  失败');
            return $this->result->error($e->getMessage());
        }

    }
}
