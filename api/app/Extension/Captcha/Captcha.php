<?php
/**
 * @Desc:验证码
 * @author: hbh
 * @Time: 2020/4/9   18:54
 */

namespace App\Extension\Captcha;


use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Captcha
{

    /**获取验证码
     * @return array
     * @author: hbh
     * @Time: 2020/4/10   9:59
     */
    public static function getcaptcha()
    {
        $obj_phrase = new PhraseBuilder(config('captcha.Captcha.captcha_length'));
        $builder = new CaptchaBuilder(null, $obj_phrase);
        $builder->build(config('captcha.Captcha.captcha_width'), config('captcha.Captcha.captcha_height'), config('captcha.Captcha.captcha_font'));

        $phrase = $builder->getPhrase();
        $identity = substr(md5(time() . microtime() . rand(0, 10000)), 0, 16);
        $captcha = [
            'image' => $builder->inline(),
            'identity' => $identity,
            'phrase' => Str::lower($phrase)
        ];
        Cache::put($identity, $captcha, config('captcha.Captcha.captcha_cache_expire'));
        array_pop($captcha);
        return $captcha;
    }

    /**检测验证码是否正确
     * @param $code
     * @param $identity
     * @return bool|string
     * @author: hbh
     * @Time: 2020/4/10   10:19
     */
    public static function checkCaptchaCode($code,$identity)
    {
        if (config('app')['debug'] != true) {
            //1.判断验证码是否正确
            if (!Cache::has($identity)) {
                return '验证码不正确';
            } else {
                $captcha = Cache::get($identity);
                if ($captcha['phrase'] != Str::lower($code)) {
                    return '验证码不正确';
                }
                Cache::forget($identity);
            }
        }

        return false;
    }
}
