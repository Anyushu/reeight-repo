<?php

namespace App\Http\Middleware;

use Socialite;
use Closure;
use Google;
use Auth;

class AnalyticsProperties
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
        $google_client = Google::getClient();

        $user = Auth::user();
        $user_access_token = $user->google_token;
        $user_refresh_token = $user->google_refresh_token;
        $user_time_created = (int)$user->time_created;

        $time = time();
        $time_diff = $time - $user_time_created;

        if ($time_diff < 3600) {
            $google_client->setAccessToken($user_access_token);
        } else {
            $refresh = $google_client->refreshToken($user_refresh_token);
            if (isset($refresh['error'])) {
                $parameters = [
                  'access_type' => 'offline',
                  'approval_prompt' => 'force'
                ];
                return Socialite::driver('google')
                ->scopes(['https://www.googleapis.com/auth/analytics', 'https://www.googleapis.com/auth/webmasters.readonly'])
                ->with($parameters)
                ->redirect();
            } else {
                $user->google_token = $refresh['access_token'];
                $user->google_refresh_token = $refresh['refresh_token'];
                $user->update();
                $google_client->setAccessToken($refresh['access_token']);
            }
        }

        $google_analytics = Google::make('Analytics');
        $request->merge(['ga' => $google_analytics]);

        return $next($request);
    }
}
