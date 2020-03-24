<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\URL;

use Auth;
use DB;
use Route;
use DateTime;

use App\User;
use App\AddSites;
use App\ReportSendDays;
use App\ReportSendMail;

use Mail;
use App\Mail\ReportSendMailCrone;
use App\Mail\CustomerSendmail;

class SendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = date("Y-m-d H:i:s");
        $now = new DateTime($now);
        $today = date('d');
        //月終わり
        $endDate  = new DateTime('last day of this month');
        if ($today == $endDate) {
            $today = 31;
        }
        $date_w = date('w');
        $reigstered_sites = AddSites::where('send_flag', 1)->get();
        foreach ($reigstered_sites as $key => $flagged_id) {
            // if today is Monday
            if ($date_w == 1) {
                $sites = ReportSendDays::where([['days', 7],['site_id',$flagged_id['id']]])->get();
                foreach ($sites as $key => $site) {
                    $site_id = $site->site_id;
                    $action_url = URL::signedRoute('report-pdf.week', ['AddSites' => $site_id]);
                    $_site = AddSites::where('id', $site_id)->first();
                    $site_name = $_site->site_name;
                    $site_url = $_site->url;
                    $user_id = $_site->user_id;
                    $user = User::where('id', $user_id)->first();
                    $user_email = $user->email;
                    $site_mail = ReportSendMail::where('site_id', $site_id)->first();
                    $_mail = $site_mail->mailaddress;
                    try {
                        \Mail::to($user_email)->send(new ReportSendMailCrone($site_url, $site_name, $action_url));
                        \Mail::to($_mail)->send(new CustomerSendmail($site_url, $site_name, $action_url));
                    } catch (\Exception $e) {
                    }
                }
            }
            // if today is not Monday
            $sites = ReportSendDays::where([['send_day', $today],['site_id',$flagged_id['id']]])->get();
            foreach ($sites as $key => $site) {
                $site_days = $site->days;
                $created_at = $site->created_at;
                $created_at = new DateTime($created_at);
                $diff = $now->diff($created_at);
                $diff = $diff->m;
                $site_id = $site->site_id;
                $site_days = $site->days;
                if ($diff % 12 && $site_days == 360) {
                    $action_url = URL::signedRoute('report-pdf.year', ['AddSites' => $site_id]);
                    $site_mail = ReportSendMail::where('site_id', $site_id)->first();
                    $_mail = $site_mail->mailaddress;
                    $_site = AddSites::where('id', $site_id)->first();
                    $user_id = $_site->user_id;
                    $user = User::where('id', $user_id)->first();
                    $user_email = $user->email;
                    $site_name = $_site->site_name;
                    $site_url = $_site->url;
                    try {
                        \Mail::to($user_email)->send(new ReportSendMailCrone($site_url, $site_name, $action_url));
                        \Mail::to($_mail)->send(new CustomerSendmail($site_url, $site_name, $action_url));
                    } catch (\Exception $e) {
                    }
                } elseif ($diff % 6 && $site_days == 180) {
                    $action_url = URL::signedRoute('report-pdf.six-month', ['AddSites' => $site_id]);
                    $site_mail = ReportSendMail::where('site_id', $site_id)->first();
                    $_mail = $site_mail->mailaddress;
                    $_site = AddSites::where('id', $site_id)->first();
                    $user_id = $_site->user_id;
                    $user = User::where('id', $user_id)->first();
                    $user_email = $user->email;
                    $site_name = $_site->site_name;
                    $site_url = $_site->url;
                    try {
                        \Mail::to($user_email)->send(new ReportSendMailCrone($site_url, $site_name, $action_url));
                        \Mail::to($_mail)->send(new CustomerSendmail($site_url, $site_name, $action_url));
                    } catch (\Exception $e) {
                    }
                } elseif ($diff % 3 && $site_days == 90) {
                    $action_url = URL::signedRoute('report-pdf.three-month', ['AddSites' => $site_id]);
                    $site_mail = ReportSendMail::where('site_id', $site_id)->first();
                    $_mail = $site_mail->mailaddress;
                    $_site = AddSites::where('id', $site_id)->first();
                    $user_id = $_site->user_id;
                    $user = User::where('id', $user_id)->first();
                    $user_email = $user->email;
                    $site_name = $_site->site_name;
                    $site_url = $_site->url;
                    try {
                        \Mail::to($user_email)->send(new ReportSendMailCrone($site_url, $site_name, $action_url));
                        \Mail::to($_mail)->send(new CustomerSendmail($site_url, $site_name, $action_url));
                    } catch (\Exception $e) {
                    }
                } elseif ($site_days == 30) {
                    $action_url = URL::signedRoute('report-pdf.one-month', ['AddSites' => $site_id]);
                    $site_mail = ReportSendMail::where('site_id', $site_id)->first();
                    $_mail = $site_mail->mailaddress;
                    $_site = AddSites::where('id', $site_id)->first();
                    $user_id = $_site->user_id;
                    $user = User::where('id', $user_id)->first();
                    $user_email = $user->email;
                    $site_name = $_site->site_name;
                    $site_url = $_site->url;
                    try {
                        \Mail::to($user_email)->send(new ReportSendMailCrone($site_url, $site_name, $action_url));
                        \Mail::to($_mail)->send(new CustomerSendmail($site_url, $site_name, $action_url));
                    } catch (\Exception $e) {
                    }
                } else {
                    return false;
                }
            }
        }
    }
}
