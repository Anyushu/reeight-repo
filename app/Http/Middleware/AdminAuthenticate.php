<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ベーシック認証で入力されたIDとPASS
        $user = $request->getUser();
        $pass = $request->getPassword();

        // IDとPASSが一致していればコントローラのアクションへ
        if ($user == 'laraweb' && $pass = 'laraweb') {
            return $next($request);
        }

        // 間違っていたら401
        return response('認証エラー', 401)->header('WWW-Authenticate', 'Basic');
    }
}
