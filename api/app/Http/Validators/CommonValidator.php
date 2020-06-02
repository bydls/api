<?php
/**
 * @Desc:通用的验证必传字段，统一管理字段名称，格式
 * @author: hbh
 * @Time: 2020/4/9   18:42
 */

namespace App\Http\Validators;


use Illuminate\Support\Facades\Validator;

class CommonValidator
{
    public static function rules($rules_type)
    {
        $arr = [
            //默认常用字段
            'default' => [
                'year' => 'required|numeric',
                'month' => 'required|numeric',
                'memo' => 'required|max:200',
                'state' => 'required|numeric',
                'id' => 'required|numeric',
                'username'=>'required|max:100',
                'title'=>'required|max:50',
                'url'=>'required|url|max:100',
                'pid'=>'required|numeric',
                'project_id'=>'required|numeric',
                'module_id'=>'required|numeric',
                'list_id'=>'required|numeric',
                'user_id'=>'required|numeric',
            ],
            //登录用
            'login' => [
                'username'=>'required|max:100',
                'captcha'  => 'required|max:6',
                'password' => 'required|max:20',
                'role_id' => 'required|numeric',
            ],

        ];
        if ($rules_type && isset($arr[$rules_type])) {
            //默认常用的公用参数
            return $arr[$rules_type];
        }
        return $arr['default'];

    }

    public static function attributes($rules_type)
    {
        $arr = [
            //默认常用字段
            'default' => [
                'year' => '年份',
                'month' => '月份',
                'state' => '状态',
                'id' => 'id',
                'username' => '用户名',
                'title'=>'标题',
                'url'=>'地址',
                'url.url' => 'URL格式不正确，请输入有效的URL',
                'pid' => '参数异常',
                'project_id' => '项目',
                'module_id' => '模块',
                'list_id' => '接口',
                'user_id' => '用户ID',
            ],
            //登录用
            'login' => [
                'captcha'  => '验证码',
                'username' => '用户名',
                'password' => '密码',
                'role_id' => '角色',
            ],

        ];
        if ($rules_type && isset($arr[$rules_type])) {
            //默认常用的公用参数
            return $arr[$rules_type];
        }
        return $arr['default'];

    }


    /**验证毕传参数   返回false为验证通过 否则返回提示信息
     * @param $request_params
     * @param $check_params //要验证的必传参数
     * @param string $rules_type
     * @return bool|string
     */
    public static function checkParams($request_params, $check_params = [], $rules_type = '')
    {
        if (empty($request_params) || !is_array($check_params) || empty($check_params)) {
            return '无验证参数';
        }

        $all_rules = self::rules($rules_type);
        $rules = [];
        foreach ($check_params as $item) {
            if (!isset($all_rules[$item])) {
                return '参数' . $item . '未定义';
            } else {
                $rules[$item] = $all_rules[$item];
            }
        }

        $validator = Validator::make($request_params, $rules, self::attributes($rules_type));
        if ($validator->fails()) {//验证字段失败，失败信息自己封装处理
            //返回第一个错误消息，一般用这个就行了
            return $validator->errors()->first();
        }
        return false;
    }
}
