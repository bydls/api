<?php
/**
 * @Desc:代码
 * @author: hbh
 * @Time: 2020/4/10   19:36
 */

namespace App\Extension\Utils;


class CodeUtil
{
    /**生成8位随机扰码
     * @return false|string
     * @author: hbh
     * @Time: 2020/4/10   19:49
     */
    public static function getSalt()
    {
        $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        return substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 9), 8);
    }

    /**对密码进行加密
     * @param $password
     * @param $salt
     * @return string
     * @author: hbh
     * @Time: 2020/4/9   19:14
     */
    public static function hashMixed($password)
    {
        $pwd_md5 = md5($password);
        $salt_md5 = md5(config('cipher.password_key')) ?: $pwd_md5;
        $mixed = [];
        for ($i = 0; $i < 32; $i++) $mixed[$i] = $pwd_md5[$i] . $salt_md5[$i];
        $strMixed = implode("", $mixed);
        return md5($strMixed);
    }
}
