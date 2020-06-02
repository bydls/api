<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/4/22   13:37
 */

namespace App\Http\Controllers;


use App\Extension\Cipher\Cipher;
use App\Extension\Utils\CheckUtil;
use App\Extension\Utils\CodeUtil;
use App\Extension\Utils\DateUtil;
use App\Models\ApiRole;
use App\models\ApiUser;
use App\Services\LoggerService;
use Illuminate\Http\Request;
use Mockery\Exception;
use PHPUnit\Framework\MockObject\Api;

class UserController extends BaseController
{
    protected $user;
    /**获取用户列表
     * @param Request $request
     * @return callable|\Illuminate\Database\Eloquent\Builder|mixed
     * @author: hbh
     * @Time: 2020/4/23   17:12
     */
    public function getUserList(Request $request){
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[];
        $this->addWhereExact($where, $request, ['nickname','is_del']);
        $select=['username','nickname','role_id','addtime','is_del','last_update_time','id'];
        return $this->internalListAction($request, function () use ($select,$where)  {
            $builder=ApiUser::query()->where($where)->select($select);
            return $builder;
        },function (&$result){
            if(!empty($result['data'])){
                $role=ApiRole::query()->where('is_del',0)->pluck('name','id');
                array_walk($result['data'],function (&$item) use ($role){
                    $item['addtime']=DateUtil::callbackTime($item['addtime']);
                    if(isset($role[$item['role_id']]))  $item['role_name']=$role[$item['role_id']];
                    unset($item['role_id']);
                });


            }
        });

    }

    /**获取一个用户信息
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @author: hbh
     * @Time: 2020/4/23   17:19
     */
    public function getOneUserInfo(Request $request){
        $this->checkout_field=['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $user=ApiUser::query()->where($where)->first();
        if(!isset($user)){
            $user=new ApiUser();
        }
        $user->role_list=ApiRole::query()->where('is_del',0)->select('id','name')->get();
       return  $this->result->success($user);
    }

    /**编辑用户（新建或者编辑）
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/10   12:42
     */
    public function editUser(Request $request)
    {
        $this->checkout_field = ['username', 'role_id'];
        $this->getCommonInfo($user, $request, 'login');
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $id = $request->input('id', 0);
        $username = $request->input('username');
        $role_id = $request->input('role_id');
        $edit_data = [
            'username' => $username,
            'role_id' => $role_id,
        ];
        $nickname = $request->input('nickname', '');
        if ($nickname) {
            if (!checkUtil::checkNickname($nickname)) return $this->result->errorCode(101008);
            $edit_data['nickname'] = $nickname;
        }
        if ($id) {
            //修改用户
            $where = [
                ['id', $id],
                ['username', $username],
            ];
            $user = ApiUser::query()->where($where)->first();
            if (!isset($user)) return $this->result->errorCode(101005);
            try {
                $user->update($edit_data);
                LoggerService::action('修改','修改用户 '.$username.' 的信息  成功');
                return $this->result->updateSuccess([], $user);
            } catch (\Exception $e) {
                LoggerService::action('修改','修改用户 '.$username.' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        } else {
            //新建用户
            //验证用户名
            if (!CheckUtil::checkUserName($username)) return $this->result->errorCode(101006);
            //验证密码
            $password = $request->input('password', '');
            if (!CheckUtil::checkPassword($password)) return $this->result->errorCode(101007);
            $password = CodeUtil::hashMixed($password);
            $edit_data['password'] = $password;
            $edit_data['addtime'] = DateUtil::addtime();
            $edit_data['salt'] = CodeUtil::getSalt();
            try {
                $edit_data['id']=ApiUser::query()->insertGetId($edit_data);
                LoggerService::action('新建','新建用户 '.$username.' 的信息  成功');
                return $this->result->addSuccess([], $edit_data);
            } catch (\Exception $e) {
                LoggerService::action('新建','新建用户 '.$username.' 的信息  失败');
                return $this->result->error($e->getMessage());
            }
        }
    }

    /**删除用户
     * @param Request $request
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/24   14:23
     */
    public function delUser(Request $request){
        $this->checkout_field=['id'];
        $this->getCommonInfo($user, $request);
        if ($this->error_msg) return $this->result->error($this->error_msg);
        $where=[
            ['id',$request->input('id')],
            ['is_del',0],
        ];
        $user=ApiUser::query()->where($where)->first();
        if(!isset($user)){
            return  $this->result->error(101002);
        }

        try {
            $user->is_del=1;
            $user->save();
            LoggerService::action('删除','删除用户 '.$user->username.'  成功');
            return $this->result->updateSuccess([], $user);
        }
        catch (\Exception $e){
            LoggerService::action('删除','删除用户 '.$user->username.'  失败');
            return $this->result->error($e->getMessage());
        }

    }
}
