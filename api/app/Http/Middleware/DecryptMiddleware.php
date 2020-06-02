<?php

namespace App\Http\Middleware;

use App\Exceptions\ParamsErrorException;
use App\Extension\Cipher\Cipher;
use App\Services\LoggerService;
use Closure;

class DecryptMiddleware
{

    protected $cipherService;

    public function __construct(Cipher $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ParamsErrorException
     */
    public function handle($request, Closure $next)
    {
        if ($request->get('data')) {
            LoggerService::exception($request->get('data'));
            $query_arr = $request->get('data');
            if (!empty($query_arr)) {
                $params = $this->cipherService->decrypt($query_arr);
                if (is_array($params)) {
                    $request->merge($params);
                    $request->offsetUnset('data');
                }
            }


        }
        return $next($request);
    }
}
