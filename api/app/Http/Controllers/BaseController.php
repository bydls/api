<?php
/**
 * @Desc:基础类
 * @author: hbh
 * @Time: 2020/4/9   18:08
 */

namespace App\Http\Controllers;


use App\Extension\Cipher\Cipher;
use App\Http\Validators\CommonValidator;
use App\Services\ResultService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\JWTGuard;
use Tymon\JWTAuth\Facades\JWTAuth;
class BaseController extends Controller
{
    /**
     * @var  JWTGuard 认证服务
     */
    protected $auth;

    /**
     * @var ResultService 返回结果处理服务
     */
    protected $result;

    //需要验证的参数
    protected $checkout_field;

    //异常信息
    protected $error_msg;

    public function __construct(Cipher $cipherService)
    {
      //  header("Access-Control-Allow-Origin: *");
       // header("access-control-expose-headers: Authorization, Content-Disposition");

        $this->auth = app('auth.driver');
        $this->result = new ResultService($cipherService, $this->auth);
    }


    /**获取用户 及必传参数
     * @param  $user
     * @param Request $request
     * @param string $object_name
     * @return bool|string
     * @author: hbh
     * @Time: 2020/4/9   18:40
     */
    protected function getCommonInfo(&$user, Request $request, $object_name = '')
    {
        $user = JWTAuth::parseToken()->touser();
        if (!$user) {
            $this->error_msg = '获取用户失败';
            return false;
        }
        $this->validator($request, $object_name);
        return true;
    }

    /**验证必传参数
     * @param Request $request
     * @param $object_name
     * @return bool
     * @author: hbh
     * @Time: 2020/4/10   13:44
     */
    protected function validator(Request $request, $object_name)
    {
        if (!empty($this->checkout_field)) {
            $params = $request->input();
            $check_result = CommonValidator::checkParams($params, $this->checkout_field, $object_name);
            if ($check_result) {
                $this->error_msg = $check_result;
                return false;
            }
        }
        return true;
    }

    /**公用分页
     * @param Request $request
     * @param callable $method
     * @param callable|null $handle
     * @return callable|Builder|mixed
     * @author: hbh
     * @Time: 2020/4/14   15:05
     */
    protected function internalListAction(Request $request, callable $method, callable $handle = null)
    {
        $page = intval($request->input('page', 0));                                  //记录起始位置
        $size = intval($request->input('size', 10));
        $start=($page-1)*$size;
        $builder=$method();
        //增加对无法构建Builder的处理
        /* @var Builder $builder */
        if ($builder instanceof Builder) {

            $total = $builder->count();
            //排序
            $sort_name = $request->input('sort_name');
            if (!empty($sort_name)) {
                $sort = $request->input('sort', 'desc');
                $sort = ($sort == 'asc') ? 'asc' : 'desc';
                $builder->orderBy($sort_name, $sort);
            } else {
                $builder->orderBy($builder->getModel()->getQualifiedKeyName(), "desc"); //默认按时间倒序排列
            }

            //分页
            if ($start > $total) $start = 0; //如果起始记录数大于记录总数，回到第一条
            $data = $builder->skip($start)->take($size)->get()->toArray();

            //输出结果
            $result = ['total' => $total, 'data' => $data,];

            if ($handle) $handle($result);

            return $this->result->success($result);
        } else {
            //异常情况直接返回
            return $builder;
        }
    }

    /**获取要查询的select字段
     * @param Request $request
     * @return array|string
     * @author: hbh
     * @Time: 2020/4/14   16:48
     */
    protected function getSelectField(Request $request){
        $select_field = intval($request->input('select_field', ''));
        if(!$select_field)  return '*';
        return explode(',',$select_field);
    }
    /**获取要查询的select字段
     * @param Request $request
     * @return array|string
     * @author: hbh
     * @Time: 2020/4/14   16:48
     */
    protected function getWhere(Request $request){
        $where=[
            ['status',1],
        ];
        $where_field = intval($request->input('where_field'));
        if(is_array($where_field)&&!empty($where_field)){
            foreach ($where_field as $k=> $v){
                array_push($where,[$k,$v]);
            }
        }
        return $where;
    }

    /**添加where条件
     * @param $where
     * @param Request $request
     * @param $params
     * @param int $default
     * @return mixed
     */
    public  function addWhereExact(&$where, Request $request, $params)
    {
        if (empty($params)) return $where;
        foreach ($params as $item) {
            $this_param=$request->input($item,'');
            if(!empty($this_param)){
                switch ($item){
                    case 'nickname':
                        array_push($where,['nickname','like',"%$this_param%"]);
                        break;
                    case 'title':
                        array_push($where,['title','like',"%$this_param%"]);
                        break;
                    case ' begintime':
                        array_push($where,['addtime','>=',strtotime($this_param)]);
                        break;
                    case 'endtime':
                        array_push($where,['addtime','<=',strtotime($this_param)]);
                        break;
                    default:
                        array_push($where,[$item,$this_param]);
                        break;
                }
            }
        }
    }

    /**添加update条件
     * @param $update
     * @param Request $request
     * @param $params
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/26   16:41
     */
    public  function addUpdateData(&$update,Request $request, $params)
    {
        if (empty($params)) return $update;
        foreach ($params as $item) {
            $this_param=$request->input($item,'');
            if(!empty($this_param)||strlen(trim($this_param))>0){
                $update[$item] = trim($this_param);
            }
        }
    }
}
