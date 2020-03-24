<?php

namespace App\Http\Middleware;

use Closure;
use Google;
use Auth;
use App\AddSites;
use App\User;

class PdfWebmaster
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
        $pattern = '/[0-9.,０-９．，]+/u';
        $subject = $_SERVER["REQUEST_URI"];
        $result = preg_match($pattern, $subject, $matches);
        $site = AddSites::where('id', $matches[0])->first();
        $user = User::where('id', $site->user_id)->first();

        $google_client = Google::getClient();
        $user_access_token = $user->google_token;
        $user_refresh_token = $user->google_refresh_token;
        $user_time_created = (int)$user->time_created;

        $time = time();
        $time_diff = $time - $user_time_created;

        if ($time_diff < 3600) {
          $google_client->setAccessToken($user_access_token);
        } else {
          $refresh = $google_client->refreshToken($user_refresh_token);
          $user->google_token = $refresh['access_token'];
          $user->google_refresh_token = $refresh['refresh_token'];
          $user->update();
          $google_client->setAccessToken($refresh['access_token']);
        }

        $search_console = Google::make('Webmasters');
        $request->merge(['sc' => $search_console]);

        return $next($request);
    }
}
