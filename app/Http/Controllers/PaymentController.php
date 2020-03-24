<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\AddSites;
use App\Plans;
use App\Settlement;
use App\Mail\PaymentDonemail;
use Auth;
use DB;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 常に発火する場合は下記のように
        // $this->middleware('analytics.properties');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $site_id = $request['site-id'];
        $site = AddSites::where('id', $site_id)->get();
        $site_name = $site[0]->site_name;
        $site_url = $site[0]->url;
        $plan = Plans::where('id', $request['plan'])->get();
        $plan_name = $plan[0]->name;
        $plan_price = number_format((int)$plan[0]->price);
        $plan_period = $plan[0]->contract_period;

        $card = Settlement::where('user_id', $user->id)->get();

        return view('payment.payment')->with([
            'user' => $user,
            'site_id' => $site_id,
            'site_name' => $site_name,
            'site_url' => $site_url,
            'plan_id' => $request['plan'],
            'plan_name' => $plan_name,
            'plan_price' => $plan_price,
            'plan_period' => $plan_period,
            'card' => $card,
        ]);
    }

    public function done(Request $request)
    {
        if ($request->payment_methods != 2) { // クレジット決済
            $url = 'https://credit.j-payment.co.jp/gateway/gateway_token.aspx';
            $data = [
                'aid' => $request->aid,
                'rt' => 0,
                'tkn' => $request->tkn,
                'pn' => $request->pn,
                'em' => $request->em,
                'iid' => $request->iid
            ];
            $data = http_build_query($data, "", "&");
            $header = [
                "Content-Type: application/x-www-form-urlencoded",
                "Content-Length: ".strlen($data)
            ];
            $options = [
                'http' => [
                    'method' => 'GET',
                    'content' => $data,
                    'header' => implode("\r\n", $header),
                ]
            ];
            $options = stream_context_create($options);
            $contents = str_replace("\r", "", file_get_contents($url, false, $options));
            $error = true;

            if ($contents == 'OK') {
                AddSites::where('id', (int)$request['site_id'])->update([
                    'plan' => (int)$request['plan_id'],
                    'payment_methods' => (int)$request['payment_methods'],
                    'plan_created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if (isset($request->cn)) {
                    DB::table('billing_sheet')
                  ->insert([
                      'name' => $request['pn'],
                      'company' => $request['cn'],
                      'site_id' => $request['site_id'],
                      'created_at' => date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')
                  ]);
                }

                $user = Auth::user();
                $plan = Plans::where('id', ((int)$request['plan_id']))->first();
                $plan_name = $plan->name;
                $plan_price = number_format((int)$plan->price);
                $plan_period = $plan->contract_period;
                $site = AddSites::where('id', (int)$request['site_id'])->first();
                $site_name = $site->site_name;
                $updated_date = date('Y年n月1日', strtotime('+'.$plan_period.' month'));
                $inputs = [
                    'plan_name' => $plan_name,
                    'plan_price' => $plan_price,
                    'plan_period' => $plan_period,
                    'site_name' => $site_name,
                    'updated_date' => $updated_date
                ];
                // メール送信
                try {
                    \Mail::to($user->email)->send(new PaymentDonemail($inputs, $user));
                } catch (\Exception $e) {
                }
            } else {
                $error = false;
            }
        } else { // 請求書決済
            $contents = 'OK';
            $error = true;
            AddSites::where('id', (int)$request['site_id'])->update([
                'plan' => (int)$request['plan_id'],
                'payment_methods' => (int)$request['payment_methods'],
                'plan_created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if (isset($request['com_name']) && isset($request['per_name'])) {
                DB::table('billing_sheet')->insert([
                  'company' => $request['com_name'],
                  'name' => $request['per_name'],
                  'site_id' => $request['site_id'],
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s')
              ]);
            }

            $user = Auth::user();
            $plan = Plans::where('id', ((int)$request['plan_id']))->first();
            $plan_name = $plan->name;
            $plan_price = number_format((int)$plan->price);
            $plan_period = $plan->contract_period;
            $site = AddSites::where('id', (int)$request['site_id'])->first();
            $site_name = $site->site_name;
            $updated_date = date('Y年n月1日', strtotime('+'.$plan_period.' month'));
            $inputs = [
                'plan_name' => $plan_name,
                'plan_price' => $plan_price,
                'plan_period' => $plan_period,
                'site_name' => $site_name,
                'updated_date' => $updated_date
            ];
            // メール送信
            try {
                \Mail::to($user->email)->send(new PaymentDonemail($inputs, $user));
            } catch (\Exception $e) {
            }
        }

        // 2重送信対策
        $request->session()->regenerateToken();

        return view('payment.done')->with([
            'error_str' => $contents,
            'error' => $error,
            'site_id' => $request['site_id'],
            'site_name' => $request['site_name'],
            'site_url' => $request['site_url'],
            'plan_name' => $request['plan_name'],
            'plan_price' => $request['plan_price'],
            'plan_period' => $request['plan_period'],
        ]);
    }
}
