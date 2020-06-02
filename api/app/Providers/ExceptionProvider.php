<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/4/9   10:41
 */

namespace App\Providers;


//use App\Exceptions\ParamsErrorException;
//use Carbon\Laravel\ServiceProvider;
//use Dingo\Api\Exception\Handler;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Tymon\JWTAuth\Exceptions\InvalidClaimException;
//use Tymon\JWTAuth\Exceptions\JWTException;
//use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use App\Exceptions\ParamsErrorException;
use Dingo\Api\Exception\Handler;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\InvalidClaimException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ExceptionProvider extends ServiceProvider
{
    public function register()
    {
        //参数错误
        app(Handler::class)->register(function (ParamsErrorException $e) {
            $returnData['code'] = $e->getCode();
            $returnData['msg'] = $e->getMessage() ? $e->getMessage() : "";
            $returnData['s'] = '';
            $returnData['c'] = true;
            return response()->json($returnData);
        });

        //认证失败
        app(Handler::class)->register(function (JWTException $e) {
            if ($e instanceof TokenInvalidException) {//instanceof 变量是否属于某一类 class 的实例
                $returnData['msg'] = '身份认证失败-非法token';
            } else if ($e instanceof InvalidClaimException) {
                $returnData['msg'] = '身份认证失败-token已过期';
            } else {
                $returnData['msg'] = '身份认证失败';
            }
            $returnData['code'] = '401';
            return response()->json($returnData, 401);
        });

        //404非法请求
        app(Handler::class)->register(function (NotFoundHttpException $e) {
            $returnData['code'] = '404';
            $returnData['msg'] = $e->getMessage() ? $e->getMessage() : "Not Found";
            $returnData['name'] = config('app.name');
            return response()->json($returnData, 404);
        });

    }
}
