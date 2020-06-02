<?php
/**
 * @Desc:加密服务
 * @author: hbh
 * @Time: 2020/4/10   9:05
 */

namespace App\Providers;


use App\Extension\Cipher\Cipher;
use Illuminate\Support\ServiceProvider;

class CipherServiceProvider extends  ServiceProvider
{

    public function register()
    {
        $this->app->singleton(Cipher::class, function () {
            return new Cipher(config('cipher.Cipher'));
        });
    }
}
