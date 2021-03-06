<?php
/**
 * @Desc:验证
 * @author: hbh
 * @Time: 2020/4/10   11:21
 */

namespace App\Extension\Utils;


class CheckUtil
{
    /**验证用户名是否合法
     * @param $username
     * @return bool
     * @author: hbh
     * @Time: 2020/4/10   11:12
     */

    public static function checkUserName($username)
    {
        return !!preg_match('/^[A-Za-z]{1}[A-Za-z0-9_-]{5,17}$/', $username);
    }
    /**验证密码是否合法
     * @param $password
     * @return bool
     * @author: hbh
     * @Time: 2020/4/10   11:19
     */
    public static function checkPassword($password)
    {
        return !!preg_match('/(?=^.{6,16}$)(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/', $password);
    }

    public static function checkNickname($nickname){
        return mb_strlen($nickname)>20?false:true;
    }
    /**检查是否为本地环境
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:25
     */
    public static function is_local()
    {
        return config('app.env') == 'local';
    }

    /**检查是否为生产环境
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:25
     */
    public static function is_production()
    {
        return config('app.env') == 'production';
    }

    /**检查手机号格式是否正确
     * @param $string
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:26
     */
    public static function is_mobile($string)
    {
        return !!preg_match('/^1[23456789]\d{9}$/', $string);
    }

    /**检测是否是身份证号
     * @param $string
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:26
     */
    public static function is_idNumber($string)
    {
        return !!preg_match('/(^\d{15}$)|(^\d{17}([0-9]|X)$)/', $string);
    }

    /**检测是否是姓名
     * @param $string
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:27
     */
    public static function is_name($string)
    {
        return !!preg_match('/^[\x{4e00}-\x{9fa5}]+[·•]?[\x{4e00}-\x{9fa5}]+$|^[a-zA-Z\s]*[a-zA-Z\s]{2,20}$/isu', $string);
    }

    /**检测是否是金额
     * @param $money
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:27
     */
    public static function is_money($money)
    {
        if (bccomp(floatval($money), 0, 2) < 1) return false;
        return !!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money);
    }

    /**
     *  判断是否是有效的银行卡
     * @param $card
     * @return bool
     */
    public static function is_bankCard($card)
    {
        // step1 判断是否16到19位
        $pattern = '/^\d{11,24}$/';
        if (!preg_match($pattern, $card)) {
            return false;
        }

        //如果是标准银联卡，检测卡号是否正确
        if (self::startsWith($card, ['62', '638888', '685800'])) {
            // step2 LUHN 算法校验
            $len = strlen($card);

            $sum = 0;
            for ($i = 0; $i < $len; $i++) {
                if (($i + $len) & 1) { // 奇数
                    $sum += ord($card[$i]) - ord('0');
                } else { // 偶数
                    $tmp = (ord($card[$i]) - ord('0')) * 2;
                    $sum += floor($tmp / 10) + $tmp % 10;
                }
            }
            return $sum % 10 === 0;
        }

        return true;
    }

    /**以批量字符串开头
     * @param $haystack
     * @param $needles
     * @return bool
     * @author: hbh
     * @Time: 2020/4/9   16:31
     */
    public static function startsWith($haystack, $needles)
    {
        $flag = false;
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
                $flag = true;
            }
        }
        return $flag;
    }
}
