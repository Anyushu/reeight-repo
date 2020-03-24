<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

use App\User;
use App\AddSites;
use App\Plans;
use App\Settlement;
use Auth;
use Google;
use DB;

use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_SegmentDimensionFilter;
use Google_Service_AnalyticsReporting_DimensionFilterClause;
use Google_Service_AnalyticsReporting_OrderBy;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_Webmasters_SearchAnalyticsQueryRequest;
use Google_Service_Webmasters_ApiDimensionFilter;
use Google_Service_Webmasters_ApiDimensionFilterGroup;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('webmaster');
        $this->middleware('analytics.reporting');
    }

    // SEOページキーワード
    public function get_seo_kyes(Request $request)
    {
        $limit = 1;
        $url = $request->url;
        $contetn_url_json = $request->content_url;
        $content_url = json_decode($contetn_url_json, true);
        $start = $request->start;
        $end = $request->end;
        $sc = $request->sc;
        $sc_re = $this->get_sc_data($sc, $url, $content_url, $start, $end, $limit);
        if (count($sc_re) == 0) {
            $kye = '';
        } else {
            $kye = $sc_re->rows[0]['keys'][0];
        }
        return Response::json($kye);
    }

    // SEOページ詳細
    public function get_seo_detail(Request $request)
    {
        $limit = 100;
        $url = $request->url;
        $content_url = $request->content_url;
        $start = $request->start;
        $end = $request->end;
        $sc = $request->sc;
        $sc_re = $this->get_sc_data($sc, $url, $content_url, $start, $end, $limit = 100);
        return Response::json($sc_re->rows);
    }

    // GAの全て
    public function get_ga_all(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $view_id = $request->view_id;
        $ga = $request->ga_report;
        $result = $this->get_ga_all_data($ga, $view_id, $start, $end);
        return Response::json($result);
    }

    // SCの全て
    public function get_sc_all(Request $request)
    {
        $url = $request->url;
        $start = $request->start;
        $end = $request->end;
        $sc = $request->sc;
        try {
            $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query->setStartDate($start);
            $query->setEndDate($end);
            $query->setSearchType('web');
            $resulets = $sc->searchanalytics->query($url, $query)->rows[0];
        } catch (\Exception $e) {
            $resulets = [];
        }
        return Response::json($resulets);
    }

    // GA全てのデータ
    public function get_ga_all_data($analytics, $view_id, $start, $end)
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:sessions');
        $pv = new Google_Service_AnalyticsReporting_Metric();
        $pv->setExpression('ga:pageviews');
        $ps = new Google_Service_AnalyticsReporting_Metric();
        $ps->setExpression('ga:pageviewsPerSession');
        $up = new Google_Service_AnalyticsReporting_Metric();
        $up->setExpression('ga:users');
        $time = new Google_Service_AnalyticsReporting_Metric();
        $time->setExpression('ga:avgTimeOnPage');
        $br = new Google_Service_AnalyticsReporting_Metric();
        $br->setExpression('ga:bounceRate');
        $cv = new Google_Service_AnalyticsReporting_Metric();
        $cv->setExpression('ga:goalCompletionsAll');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:sessions');
        $orderBy->setSortOrder('DESCENDING');
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($view_id);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($ss, $pv, $ps, $up, $time, $br, $cv));
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));
        $result = $analytics->reports->batchGet($body)->reports[0]->data->totals[0]->values;
        return $result;
    }

    // プライマリーSC
    public function get_sc_data($sc, $url, $content_url, $start, $end, $limit = 100)
    {
        try {
            $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query->setRowLimit($limit);
            $query->setStartDate($start);
            $query->setEndDate($end);
            $query->setDimensions(['query']);
            if ($content_url != '') {
                $filter = new Google_Service_Webmasters_ApiDimensionFilter();
                $filter->setDimension('page');
                $filter->setOperator('equals');
                $filter->setExpression($content_url);
                $filters = new Google_Service_Webmasters_ApiDimensionFilterGroup();
                $filters->setFilters([$filter]);
                $query->setDimensionFilterGroups([$filters]);
            }
            $resulets = $sc->searchanalytics->query($url, $query);
        } catch (\Exception $e) {
            $resulets = [];
        }
        return $resulets;
    }

    // アカウント情報変更
    public function edit_account(Request $request)
    {
        $user = Auth::user();
        $data = $request->only(['email', 'tel']);
        $validator = Validator::make($data, [
            'email' => 'required|unique:users,email,'.$user->id.'|max:255|email',
            'tel' => 'required|numeric|digits_between:8,11',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $user->company = $request->company;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->tel = $request->tel;
            $user->update();
        }
    }

    // PDF送付のフラグ
    public function set_send_flag(Request $request)
    {
        $site = AddSites::where('id', $request->id)->get()[0];
        $site->send_flag = $request->flag;
        $site->update();
    }

    // クレジット登録
    public function add_card(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'cn' => 'required',
            'ln' => 'required',
            'fn' => 'required',
            'ed_month' => 'required',
            'ed_year' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $cn = Crypt::encryptString($inputs['cn']);
            $settlement = new Settlement;
            $settlement->user_id = $user_id;
            $settlement->numbers = $cn;
            $settlement->holder = $inputs['ln'].' '.$inputs['fn'];
            $settlement->month = $inputs['ed_month'];
            $settlement->year = $inputs['ed_year'];
            $settlement->save();
        }
    }

    // クレジット編集
    public function edit_cards(Request $request)
    {
        foreach ($request->cards as $key => $val) {
            Settlement::find($val)->delete();
        }
    }
}
