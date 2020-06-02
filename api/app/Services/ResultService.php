<?php
/**
 * @Desc:返回格式
 * @author: hbh
 * @Time: 2020/4/9   14:22
 */

namespace App\Services;


use App\Extension\Cipher\Cipher;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\JWTGuard;

class ResultService
{
    private $cipherService;
    private $auth;

    public function __construct(Cipher $cipherService,JWTGuard $auth)
    {
        $this->cipherService = $cipherService;
        $this->auth = $auth;
    }

    public function build($code, $msg, $data = [], $encrypt = true, $use_user_key = 1)
    {
        $user = $this->auth->user() ? $this->auth->user() : Request::instance()->get('platform_guest');
        if ($user && $use_user_key == 1) {
            $option = [
                'encrypt_key' => $user['encrypt_key'],
                'sign_key' => $user['sign_key'],
                'iv' => $user['iv'],
            ];
            $this->cipherService->setOptions($option);
        }

        $code = (int)$code;
        $msg = ($msg ? $msg : $this->getCodeDescription($code));
        $data = isset($data) ? $data : [];

        // 考虑到前台使用字符 '0' 判定，将数字转换成字符串
        $code = trim((string)$code);
        $return_data['code'] = $code;
        $return_data['msg'] = $msg;
        $return_data['data'] = $encrypt ? $this->cipherService->encrypt($data) : $data;
        $return_data['cipher'] = $encrypt;
        return $return_data;
    }

    /**成功返回
     * @param bool $data
     * @param string $msg
     * @param string $code
     * @param bool $encrypt
     * @param int $use_user_key
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   14:43
     */
    public function success($data = true, $msg = '', $code = '0', $encrypt = true, $use_user_key = 1)
    {
        // $msg 及 $code 的初始化，由函数 build 完成
        return $this->build($code, $msg, $data, $encrypt, $use_user_key);
    }

    /**失败返回
     * @param bool $data
     * @param string $msg
     * @param int $code
     * @param bool $encrypt
     * @param int $use_user_key
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   14:44
     */
    public function failed($data = false, $msg = '失败', $code = 1, $encrypt = true, $use_user_key = 1)
    {
        return $this->build($code, $msg, $data, $encrypt, $use_user_key);
    }


    /**保存成功返回并保存日志
     * @param array $data
     * @param null $object
     * @param bool $log
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   17:58
     */
    public function addSuccess($data=[], $object=null,$log = true)
    {
        if ($log&&isset($object)) {
            LoggerService::operateAdd($object);
        }
        return $this->build(0, "添加成功", $data, true, 1);
    }

    /**更新成功并保存日志
     * @param array $data
     * @param null $object
     * @param bool $log
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   17:59
     */
    public function updateSuccess($data=[], $object=null,$log = true)
    {
        if ($log&&isset($object)) {
            LoggerService::operateUpdate($object);
        }
        return $this->build(0, "更新成功", $data, true, 1);
    }

    /**删除成功并保存日志
     * @param array $data
     * @param null $object
     * @param bool $log
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   18:00
     */
    public function delSuccess($data=[], $object=null,$log = true)
    {
        if ($log&&isset($object)) {
            LoggerService::operateDel($object);
        }
        return $this->build(0, "删除成功", $data, true, 1);
    }

    /**修改失败返回
     * @param array $data
     * @param int $code
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   14:51
     */
    public function saveFailed($data=[], $code = -1)
    {
        return $this->build($code, "保存失败", $data, true, 1);
    }

    /**自定义异常返回
     * @param $msg
     * @param int $code
     * @param array $data
     * @param bool $encrypt
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   14:48
     */
    public function error($msg, $data = [],$code = -2)
    {
        return $this->success($data, $msg, $code, true);
    }

    /**异常返回
     * @param int $code
     * @param array $data
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   19:35
     */
    public function errorCode($code = -2,$data=[])
    {
        return $this->build($code, $this->getCodeDescription($code), $data, true, 1);
    }

    /**获取用户信息异常
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/9   14:52
     */
    public function errorUser()
    {
        return $this->build(401, "获取用户失败");
    }


    /**从配置的错误代码中读取相应的描述信息
     * @param $code
     * @return mixed|string
     * @author: hbh
     * @Time: 2020/4/9   14:54
     */
    public function getCodeDescription($code)
    {
        $desc = config('errorcode.' . $code);
        return isset($desc) ? $desc : '未知的执行状态代码';
    }
}
