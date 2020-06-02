<?php
/**
 * @Desc:加密处理
 * @author: hbh
 * @Time: 2020/4/9   14:34
 */

namespace App\Extension\Cipher;


class Cipher
{
    /**
     * 签名和加密参数
     * @token
     * @encrypt_key
     * @sign_key
     * @iv
     */
    private $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
    }

    /**自定义加密算法
     * @param $plain
     * @return false|string
     * @author: hbh
     * @Time: 2020/4/10   9:10
     */
    public function encrypt($plain)
    {
     //   return json_encode($plain);
        return $plain;
    }

    /**自定义解密算法
     * @param $cipherStr
     * @return mixed
     * @author: hbh
     * @Time: 2020/4/10   9:10
     */
    public function decrypt($cipherStr)
    {
        //return json_decode($cipherStr, true);
        return $cipherStr;
    }
}
