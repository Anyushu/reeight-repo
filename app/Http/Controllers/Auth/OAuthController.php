<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;

class OAuthController extends Controller
{
    /**
     * 各SNSのOAuth認証画面にリダイレクトして認証
     * @param string $provider サービス名
     * @return mixed
     */
    public function socialOAuth(string $provider)
    {
        if ($provider == 'google') {
            $parameters = [
              'access_type' => 'offline',
              'approval_prompt' => 'force'
            ];
            return Socialite::driver($provider)
            ->scopes(['https://www.googleapis.com/auth/analytics', 'https://www.googleapis.com/auth/webmasters.readonly'])
            ->with($parameters)
            ->redirect();
        } else {
            return Socialite::driver($provider)->redirect();
        }
    }

    /**
     * 各サイトからのコールバック
     * @param string $provider サービス名
     * @return mixed
     */
    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        if ($provider == 'google') {
            $login_user_id = Auth::user()->id;
            $token = $socialUser->token;
            $refresh_token = $socialUser->refreshToken;
            $time_created = time();
            DB::table('users')
            ->where('id', $login_user_id)
            ->update([
              'google_token' => $token,
              'google_refresh_token' => $refresh_token,
              'time_created' => $time_created,
            ]);
            return redirect('/dashboard');
        }
    }
}
