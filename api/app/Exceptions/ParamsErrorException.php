<?php
/**
 * @Desc:参数异常描述
 * @author: hbh
 * @Time: 2020/4/9   10:34
 */
namespace App\Exceptions;


use Exception;
use Throwable;

class ParamsErrorException extends Exception
{
    const SIGN_ERROR = 1001;    // 签名验证失败
    const MISSING_PARAMS = 1002;    // 缺少参数
    const PARAM_ERROR = 1003;    // 参数格式错误
    const TRANS = [
        1001 => '签名验证失败',
        1002 => '缺少参数',
        1003 => '参数格式错误'
    ];


    public function __construct($code = 0, $message = null, Throwable $previous = null)
    {
        if (!$message) {
            if (array_key_exists($code,self::TRANS)) {
                $message = self::TRANS[$code];
            } else {
                $message = '参数异常';
            }
        }
        parent::__construct($message, $code, $previous);
    }
}
