<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\AddSites;
use App\Plans;
use Auth;
use Google;
use DB;
use Route;

use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_DimensionFilter;
use Google_Service_AnalyticsReporting_DimensionFilterClause;
use Google_Service_AnalyticsReporting_OrderBy;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_Webmasters_SearchAnalyticsQueryRequest;
use Google_Service_Webmasters_ApiDimensionFilter;
use Google_Service_Webmasters_ApiDimensionFilterGroup;

class PdfController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $route_name = Route::current() -> getName();
        if ($route_name === 'ga-pdf') {
            $this->middleware('auth');
        }
    }

    public function index(Request $request, $sites)
    {
        // 諸々
        $add_site = AddSites::where('id', $sites)->first();
        $site_name = $add_site->site_name;
        $view_id =(string)$add_site->VIEW_ID;
        $plan = $add_site->plan;
        $url = $add_site->url;
        if ($add_site->logo_path != '') {
            $logo = '/storage/logos/'.$add_site->logo_path;
        } else {
            $logo = '';
        }

        // AnalyticsReporting API インスタンス
        $gsa = $request->ga_report;
        $sc = $request->sc;

        // レポート送付用
        $route_name = Route::current()->getName();
        if ($route_name !== 'ga-pdf') {
            switch ($route_name) {
              case 'report-pdf.week':
                $days = 7;
                $lastSunday = date('Y-m-d', strtotime('last Sunday'));
                $start = date('Y-m-d', strtotime("$lastSunday -6 days"));
                $end = date('Y-m-d', strtotime('+6 day', strtotime($start)));
                $com_end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $com_start = date('Y-m-d', strtotime('-6 days', strtotime($com_end)));
                break;
              case 'report-pdf.one-month':
                $days = 30;
                $start = date('Y-m-d', strtotime('first day of previous month'));
                $end = date('Y-m-t', strtotime($start));
                $com_start = date('Y-m-01', strtotime(date('Y-m-d').' -2 month'));
                $com_end = date('Y-m-t', strtotime($com_start));
                break;
              case 'report-pdf.three-month':
                $days = 90;
                $last_month = date('Y-m-d', strtotime('first day of previous month'));
                $start = date('Y-m-d', strtotime('-2 month', strtotime($last_month)));
                $end = date('Y-m-d', strtotime('last day of previous month'));
                $com_end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $diff = abs(strtotime($end) - strtotime($start))/(60 * 60* 24);
                $com_start = date('Y-m-d', strtotime("$com_end -$diff days"));
                break;
              case 'report-pdf.six-month':
                $days = 180;
                $last_month = date('Y-m-d', strtotime('first day of previous month'));
                $start = date('Y-m-d', strtotime('-5 month', strtotime($last_month)));
                $end = date('Y-m-d', strtotime('last day of previous month'));
                $com_end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $diff = abs(strtotime($end) - strtotime($start))/(60 * 60* 24);
                $com_start = date('Y-m-d', strtotime("$com_end -$diff days"));
                break;
              case 'report-pdf.year':
                $days = 360;
                $last_month = date('Y-m-d', strtotime('first day of previous month'));
                $start = date('Y-m-d', strtotime('-11 month', strtotime($last_month)));
                $end = date('Y-m-d', strtotime('last day of previous month'));
                $com_end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $diff = abs(strtotime($end) - strtotime($start))/(60 * 60* 24);
                $com_start = date('Y-m-d', strtotime("$com_end -$diff days"));
                break;
              default:
                $days = 7;
                $days = 7;
                $lastSunday = date('Y-m-d', strtotime('last Sunday'));
                $start = date('Y-m-d', strtotime("$lastSunday -6 days"));
                $end = date('Y-m-d', strtotime('+6 day', strtotime($start)));
                $com_end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $com_start = date('Y-m-d', strtotime('-6 days', strtotime($com_end)));
                break;
            }
            $send_info = DB::table('report_send_days')->where([
                'site_id' => $sites,
                'days' => $days,
            ])->first();
        } else { // レポート送付用ここまで
            if (isset($request->start)) {
                $end = $request->end;
                $start = $request->start;
                $com_end = $request->com_end;
                $com_start = $request->com_start;
            } else {
                $end = date('Y-m-d', strtotime('-1 day', time()));
                $start = date('Y-m-d', strtotime('-30 days', time()));
                $com_end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $com_start = date('Y-m-d', strtotime('-29 days', strtotime($com_end)));
            }
        }

        // ルートごとの返り値変更
        $ga_result_data = $this->get_ga_data($gsa, $view_id, $start, $end, $com_start, $com_end);
        $ga_result_user = $this->get_ga_user($gsa, $view_id, $start, $end, $com_start, $com_end);
        $ga_result_inflow = $this->get_ga_inflow($gsa, $view_id, $start, $end, $com_start, $com_end);
        $ga_result_action = $this->get_ga_action($gsa, $view_id, $start, $end, $com_start, $com_end);
        $ga_result_conversion = $this->get_ga_conversion($gsa, $view_id, $start, $end, $com_start, $com_end);
        $ga_result_ad = $this->get_ga_ad($gsa, $view_id, $start, $end, $com_start, $com_end);
        if ($plan%2 == 0) {
            $sc_result = $this->get_sc_query($sc, $url, 10, $start, $end, $com_start, $com_end);
        } else {
            $sc_result = null;
        }

        return view('analysis.pdf.index_pdf')->with([
          'ga_result_data'=>$ga_result_data,
          'ga_result_user'=>$ga_result_user,
          'ga_result_inflow'=>$ga_result_inflow,
          'ga_result_action'=>$ga_result_action,
          'ga_result_conversion'=>$ga_result_conversion,
          'ga_result_ad'=>$ga_result_ad,
          'sc_result'=>$sc_result,
          'add_site' => $add_site,
          'end' => $end,
          'start' => $start,
          'com_end' => $com_end,
          'com_start' => $com_start,
          'url' => $url,
          'logo' => $logo,
          'site_name' => $site_name,
          'plan' => $plan
        ]);
    }

    // サマリー
    public function get_ga_data($analytics, $VIEW_ID, $start, $end, $comStart, $comEnd)
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $dateRangeTwo = new Google_Service_AnalyticsReporting_DateRange();
        $dateRangeTwo->setStartDate($comStart);
        $dateRangeTwo->setEndDate($comEnd);
        $start_months = date('Y-m-01', strtotime(date('Y-m-d').' -3 month'));
        $end_months = date('Y-m-t', strtotime(date('Y-m-d').' -1 month'));
        $three_months = new Google_Service_AnalyticsReporting_DateRange();
        $three_months->setStartDate($start_months);
        $three_months->setEndDate($end_months);
        $up = new Google_Service_AnalyticsReporting_Metric();
        $up->setExpression('ga:users');
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:sessions');
        $pv = new Google_Service_AnalyticsReporting_Metric();
        $pv->setExpression('ga:pageviews');
        $ps = new Google_Service_AnalyticsReporting_Metric();
        $ps->setExpression('ga:pageviewsPerSession');
        $aveSs = new Google_Service_AnalyticsReporting_Metric();
        $aveSs->setExpression('ga:avgSessionDuration');
        $time = new Google_Service_AnalyticsReporting_Metric();
        $time->setExpression('ga:avgTimeOnPage');
        $exit = new Google_Service_AnalyticsReporting_Metric();
        $exit->setExpression('ga:exitRate');
        $br = new Google_Service_AnalyticsReporting_Metric();
        $br->setExpression('ga:bounceRate');
        $date = new Google_Service_AnalyticsReporting_Dimension();
        $date->setName('ga:date');
        $months = new Google_Service_AnalyticsReporting_Dimension();
        $months->setName('ga:yearMonth');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:sessions');
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges(array($dateRange,$dateRangeTwo));
        $request->setMetrics(array($ss, $ps, $up, $time, $br, $aveSs, $pv, $exit));
        $requestUser = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestUser->setViewId($VIEW_ID);
        $requestUser->setDateRanges(array($dateRange, $dateRangeTwo));
        $requestUser->setMetrics($up);
        $requestUser->setDimensions($date);
        $label = ['セッション', 'ユーザー数', 'ページビュー', 'ページ/セッション', '平均滞在時間(秒)', '離脱率(%)', '直帰率(%)'];
        $transition = new Google_Service_AnalyticsReporting_ReportRequest();
        $transition->setViewId($VIEW_ID);
        $transition->setDateRanges($three_months);
        $transition->setMetrics([$ss, $up, $pv, $ps, $time, $br, $exit]);
        $transition->setDimensions($months);
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request, $requestUser));
        $body_2 = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body_2->setReportRequests($transition);
        $result = $analytics->reports->batchGet($body)->reports[0]->data->totals;
        $resultUsers = $analytics->reports->batchGet($body)->reports[1]->data->rows;
        $transition_data = $analytics->reports->batchGet($body_2)->reports[0]->data->rows;
        $arrayUser = [];
        $array = [];
        $comp_array = [];
        $arrayUser['original'] = [];
        $arrayUser['compare'] = [];
        $origindaydiff = abs(strtotime($end) - strtotime($start))/(60 * 60* 24) +1;
        $comparedaydiff = abs(strtotime($comEnd)  - strtotime($comStart))/(60 * 60* 24) +1;
        foreach ($resultUsers as $i => $resultUser) {
            $day = date('Y-m-d', strtotime($resultUser->dimensions[0]));
            if ($resultUser->metrics[1]->values[0] == 0) {
                $user = $resultUser->metrics[0]->values[0];
                $arrayUser['original'][(string)$day] = (int)$user;
            } else {
                $user = $resultUser->metrics[1]->values[0];
                $arrayUser['compare'][(string)$day] = (int)$user;
            }
        }
        for ($i=1; $i < $origindaydiff; $i++) {
            $c = date("Y-m-d", strtotime("$start +$i day"));
            if (!isset($arrayUser['original'][$c])) {
                $arrayUser['original'][$c] = 0;
            }
        }
        ksort($arrayUser['original']);
        for ($i=1; $i < $comparedaydiff; $i++) {
            $d = date("Y-m-d", strtotime("$comStart +$i day"));
            if (!isset($arrayUser['compare'][$d])) {
                $arrayUser['compare'][$d] = 0;
            }
        }
        ksort($arrayUser['compare']);
        foreach ($result as $key => $value) {
            $value = $value->values;
            array_push($array, $value);
        }
        foreach ($array[0] as $key => $val) {
            if ((float)$array[1][$key] != 0) {
                $comp_array[] = round(((float)$val / (float)$array[1][$key] - 1) * 100, 2);
            } else {
                $comp_array[] = 0;
            }
        }
        $transition_data_arr = [];
        foreach ($transition_data as $key => $val) {
            $metr = $val->metrics[0]->values;
            foreach ($metr as $key => $vals) {
                $vals += 0;
                if (is_int($vals)) {
                    $transition_data_arr[$val->dimensions[0]][$label[$key]] = number_format($vals);
                } else {
                    $transition_data_arr[$val->dimensions[0]][$label[$key]] = round($vals, 2);
                }
            }
        }
        return [
          'transition' => $arrayUser,
          'sumally' => $array,
          'transitions' => $transition_data_arr,
          'labels' => $label,
          'comp' => $comp_array
        ];
    }

    // ユーザーサマリー
    public function get_ga_user($analytics, $VIEW_ID, $start, $end, $comStart, $comEnd)
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $dateRangeTwo = new Google_Service_AnalyticsReporting_DateRange();
        $dateRangeTwo->setStartDate($comStart);
        $dateRangeTwo->setEndDate($comEnd);
        $city = new Google_Service_AnalyticsReporting_Dimension();
        $city->setName('ga:city');
        $country = new Google_Service_AnalyticsReporting_Dimension();
        $country->setName('ga:country');
        $gender = new Google_Service_AnalyticsReporting_Dimension();
        $gender->setName('ga:userGender');
        $age = new Google_Service_AnalyticsReporting_Dimension();
        $age->setName('ga:userAgeBracket');
        $device = new Google_Service_AnalyticsReporting_Dimension();
        $device->setName('ga:deviceCategory');
        $userType = new Google_Service_AnalyticsReporting_Dimension();
        $userType->setName('ga:userType');
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:users');
        $ss->setAlias('uu');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:users');
        $orderBy->setSortOrder('DESCENDING');
        $requestCity = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestCity->setViewId($VIEW_ID);
        $requestCity->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestCity->setMetrics($ss);
        $requestCity->setDimensions($city);
        $requestCity->setOrderBys($orderBy);
        $requestCity->setPageSize('5');
        $requestCountry = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestCountry->setViewId($VIEW_ID);
        $requestCountry->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestCountry->setMetrics($ss);
        $requestCountry->setDimensions($country);
        $requestCountry->setOrderBys($orderBy);
        $requestCountry->setPageSize('5');
        $requestGender = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestGender->setViewId($VIEW_ID);
        $requestGender->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestGender->setMetrics($ss);
        $requestGender->setDimensions($gender);
        $requestGender->setOrderBys($orderBy);
        $requestAge = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestAge->setViewId($VIEW_ID);
        $requestAge->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestAge->setMetrics($ss);
        $requestAge->setDimensions($age);
        $requestAge->setOrderBys($orderBy);
        $requestAge->setPageSize('5');
        $requestDevice = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestDevice->setViewId($VIEW_ID);
        $requestDevice->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestDevice->setMetrics($ss);
        $requestDevice->setDimensions($device);
        $requestDevice->setOrderBys($orderBy);
        $requestUserType = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestUserType->setViewId($VIEW_ID);
        $requestUserType->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestUserType->setMetrics($ss);
        $requestUserType->setDimensions($userType);
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($requestCountry, $requestCity, $requestAge));
        $bodyTwo = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $bodyTwo->setReportRequests(array($requestUserType, $requestDevice, $requestGender));
        $reports = $analytics->reports->batchGet($body)->reports;
        $reportsTwo = $analytics->reports->batchGet($bodyTwo)->reports;
        $number = [];
        $numberUser = [];
        foreach ($reportsTwo as $i => $value) {
            $rows = $value->data->rows;
            foreach ($rows as $key => $val) {
                $dimension = $val->dimensions[0];
                $number[$i][$dimension] = [$val->metrics[0]->values[0],$val->metrics[1]->values[0]];
            }
        }
        foreach ($reports as $i => $value) {
            $rows = $value->data->rows;
            foreach ($rows as $key => $val) {
                $numberUser[$i][] = [$val->dimensions[0], $val->metrics[0]->values[0], $val->metrics[1]->values[0]];
            }
        }
        return [$number, $numberUser];
    }

    public function get_ga_inflow($analytics, $VIEW_ID, $start, $end, $comStart, $comEnd)
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $dateRangeTwo = new Google_Service_AnalyticsReporting_DateRange();
        $dateRangeTwo->setStartDate($comStart);
        $dateRangeTwo->setEndDate($comEnd);
        $medium = new Google_Service_AnalyticsReporting_Dimension();
        $medium->setName('ga:channelGrouping');
        $social = new Google_Service_AnalyticsReporting_Dimension();
        $social->setName('ga:socialNetwork');
        $referral = new Google_Service_AnalyticsReporting_Dimension();
        $referral->setName('ga:fullReferrer');
        $engine = new Google_Service_AnalyticsReporting_Dimension();
        $engine->setName('ga:source');
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:sessions');
        $ss->setAlias('ss');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:sessions');
        $orderBy->setSortOrder('DESCENDING');

        $requestMedium = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestMedium->setViewId($VIEW_ID);
        $requestMedium->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestMedium->setMetrics($ss);
        $requestMedium->setPageSize('5');
        $requestMedium->setDimensions($medium);
        $requestMedium->setOrderBys($orderBy);

        $filter_social = new \Google_Service_AnalyticsReporting_DimensionFilter();
        $filter_social->setDimensionName('ga:socialNetwork');
        $filter_social->setNot(true);
        $filter_social->setExpressions(["(not set)"]);

        $filters_social = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
        $filters_social->setFilters([$filter_social]);

        $requestSocial = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestSocial->setViewId($VIEW_ID);
        $requestSocial->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestSocial->setMetrics($ss);
        $requestSocial->setPageSize('5');
        $requestSocial->setDimensions($social);
        $requestSocial->setDimensionFilterClauses($filters_social);
        $requestSocial->setOrderBys($orderBy);

        $filter = new Google_Service_AnalyticsReporting_DimensionFilter();
        $filter->setDimensionName('ga:sourceMedium');
        $filter->setExpressions(['referral']);
        $filters = new Google_Service_AnalyticsReporting_DimensionFilterClause();
        $filters->setFilters($filter);
        $requestReferral = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestReferral->setViewId($VIEW_ID);
        $requestReferral->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestReferral->setMetrics($ss);
        $requestReferral->setDimensions($referral);
        $requestReferral->setDimensionFilterClauses($filters);
        $requestReferral->setOrderBys($orderBy);
        $requestReferral->setPageSize('5');

        $engine_filter = new Google_Service_AnalyticsReporting_DimensionFilter();
        $engine_filter->setDimensionName('ga:medium');
        $engine_filter->setExpressions(['organic']);
        $engine_filters = new Google_Service_AnalyticsReporting_DimensionFilterClause();
        $engine_filters->setFilters($engine_filter);
        $search_engine = new Google_Service_AnalyticsReporting_ReportRequest();
        $search_engine->setViewId($VIEW_ID);
        $search_engine->setDateRanges([$dateRange, $dateRangeTwo]);
        $search_engine->setMetrics($ss);
        $search_engine->setDimensions($engine);
        $search_engine->setOrderBys($orderBy);
        $search_engine->setDimensionFilterClauses($engine_filters);
        $search_engine->setPageSize('5');

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($requestMedium,$requestSocial,$requestReferral, $search_engine));
        $reports = $analytics->reports->batchGet($body)->reports;
        $number = [];
        $result = [];
        foreach ($reports as $i => $value) {
            $rows = $value->data->rows;
            foreach ($rows as $key => $val) {
                $number[$i][] = [
                  $val->dimensions[0],
                  $val->metrics[0]->values[0],
                  $val->metrics[1]->values[0]
                ];
            }
        }
        return $number;
    }

    // 行動分析
    public function get_ga_action($analytics, $VIEW_ID, $start, $end, $comStart, $comEnd)
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $dateRangeTwo = new Google_Service_AnalyticsReporting_DateRange();
        $dateRangeTwo->setStartDate($comStart);
        $dateRangeTwo->setEndDate($comEnd);
        $action = new Google_Service_AnalyticsReporting_Dimension();
        $action->setName('ga:pageTitle');
        $path = new Google_Service_AnalyticsReporting_Dimension();
        $path->setName('ga:pagePath');
        $up = new Google_Service_AnalyticsReporting_Metric();
        $up->setExpression('ga:users');
        $br = new Google_Service_AnalyticsReporting_Metric();
        $br->setExpression('ga:bounceRate');
        $ps = new Google_Service_AnalyticsReporting_Metric();
        $ps->setExpression('ga:pageviewsPerSession');
        $time = new Google_Service_AnalyticsReporting_Metric();
        $time->setExpression('ga:avgTimeOnPage');
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:sessions');
        $pv = new Google_Service_AnalyticsReporting_Metric();
        $pv->setExpression('ga:pageviews');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:sessions');
        $orderBy->setSortOrder('DESCENDING');
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges(array($dateRange, $dateRangeTwo));
        $request->setDimensions([$action, $path]);
        $request->setMetrics(array($ss,$pv,$ps,$up,$time,$br));
        $request->setOrderBys($orderBy);
        $request->setPageSize('10');
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));
        $reports = $analytics->reports->batchGet($body);
        $reports = $reports[0]->data->rows;
        $number = [];
        foreach ($reports as $key => $report) {
            $number[$key][0][] = [$report->dimensions,$report->metrics[0]->values[0],$report->metrics[0]->values[1],$report->metrics[0]->values[2],$report->metrics[0]->values[3],$report->metrics[0]->values[4],$report->metrics[0]->values[5]];
            $number[$key][1][]= [$report->dimensions,$report->metrics[1]->values[0],$report->metrics[1]->values[1],$report->metrics[1]->values[2],$report->metrics[1]->values[3],$report->metrics[1]->values[4],$report->metrics[1]->values[5]];
        }
        return $number;
    }

    // CV解析
    public function get_ga_conversion($analytics, $VIEW_ID, $start, $end, $comStart, $comEnd)
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $dateRangeTwo = new Google_Service_AnalyticsReporting_DateRange();
        $dateRangeTwo->setStartDate($comStart);
        $dateRangeTwo->setEndDate($comEnd);
        $action = new Google_Service_AnalyticsReporting_Dimension();
        $action->setName('ga:sourceMedium');
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:sessions');
        $up = new Google_Service_AnalyticsReporting_Metric();
        $up->setExpression('ga:users');
        $br = new Google_Service_AnalyticsReporting_Metric();
        $br->setExpression('ga:bounceRate');
        $ps = new Google_Service_AnalyticsReporting_Metric();
        $ps->setExpression('ga:pageviewsPerSession');
        $time = new Google_Service_AnalyticsReporting_Metric();
        $time->setExpression('ga:avgTimeOnPage');
        $cv = new Google_Service_AnalyticsReporting_Metric();
        $cv->setExpression('ga:goalCompletionsAll');
        $cvr = new Google_Service_AnalyticsReporting_Metric();
        $cvr->setExpression('ga:goalConversionRateAll');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:users');
        $orderBy->setSortOrder('DESCENDING');
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges(array($dateRange, $dateRangeTwo));
        $request->setDimensions($action);
        $request->setMetrics(array($cv,$cvr,$up,$br,$ps,$time));
        $request->setOrderBys($orderBy);
        $request->setPageSize('10');
        $requestTwo = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestTwo->setViewId($VIEW_ID);
        $requestTwo->setDateRanges(array($dateRange,$dateRangeTwo));
        $requestTwo->setMetrics(array($ss, $cv, $cvr, $br));
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request, $requestTwo));
        $reports = $analytics->reports->batchGet($body);
        $reportsOne = $reports[0]->data->rows;
        $reportsTwo = $reports[1]->data->totals;
        $number = [];
        foreach ($reportsOne as $key => $report) {
            $number[$key][0][] = [$report->dimensions,$report->metrics[0]->values[0],$report->metrics[0]->values[1],$report->metrics[0]->values[2],$report->metrics[0]->values[3],$report->metrics[0]->values[4],$report->metrics[0]->values[5]];
            $number[$key][1][]= [$report->dimensions,$report->metrics[1]->values[0],$report->metrics[1]->values[1],$report->metrics[1]->values[2],$report->metrics[1]->values[3],$report->metrics[1]->values[4],$report->metrics[1]->values[5]];
        }
        $array = [];
        foreach ($reportsTwo as $value) {
            $value = $value->values;
            array_push($array, $value);
        }
        return [$number,$array];
    }

    // 広告
    public function get_ga_ad($analytics, $VIEW_ID, $start, $end, $comStart, $comEnd)
    {
        $filter = new Google_Service_AnalyticsReporting_DimensionFilter();
        $filter->setDimensionName('ga:medium');
        $filter->setExpressions(['cpc']);
        $filters = new Google_Service_AnalyticsReporting_DimensionFilterClause();
        $filters->setFilters($filter);

        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start);
        $dateRange->setEndDate($end);
        $dateRangeTwo = new Google_Service_AnalyticsReporting_DateRange();
        $dateRangeTwo->setStartDate($comStart);
        $dateRangeTwo->setEndDate($comEnd);
        $query = new Google_Service_AnalyticsReporting_Dimension();
        $query->setName('ga:adMatchedQuery');
        $ss = new Google_Service_AnalyticsReporting_Metric();
        $ss->setExpression('ga:sessions');
        $cv = new Google_Service_AnalyticsReporting_Metric();
        $cv->setExpression('ga:goalCompletionsAll');
        $cvr = new Google_Service_AnalyticsReporting_Metric();
        $cvr->setExpression('ga:goalConversionRateAll');
        $adCost = new Google_Service_AnalyticsReporting_Metric();
        $adCost->setExpression('ga:adCost');
        $adClicks = new Google_Service_AnalyticsReporting_Metric();
        $adClicks->setExpression('ga:adClicks');
        $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName('ga:adClicks');
        $orderBy->setSortOrder('DESCENDING');
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDimensionFilterClauses($filters);
        $request->setDateRanges(array($dateRange,$dateRangeTwo));
        $request->setMetrics(array($adCost, $adClicks, $cv));
        $requestTwo = new Google_Service_AnalyticsReporting_ReportRequest();
        $requestTwo->setViewId($VIEW_ID);
        $requestTwo->setDateRanges(array($dateRange, $dateRangeTwo));
        $requestTwo->setDimensions($query);
        $requestTwo->setMetrics(array($adClicks,$adCost,$ss,$cv,$cvr));
        $requestTwo->setOrderBys($orderBy);
        $requestTwo->setPageSize('10');
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request, $requestTwo));
        $result = $analytics->reports->batchGet($body);
        $result = $analytics->reports->batchGet($body)->reports[0]->data->totals;
        $resultTwo = $analytics->reports->batchGet($body)->reports[1]->data->rows;
        $array = [];
        $number = [];
        foreach ($result as $value) {
            $value = $value->values;
            array_push($array, $value);
        }
        foreach ($resultTwo as $key => $report) {
            $number[$key][0][] = [$report->dimensions,$report->metrics[0]->values[0],$report->metrics[0]->values[1],$report->metrics[0]->values[2],$report->metrics[0]->values[3],$report->metrics[0]->values[4]];
            $number[$key][1][]= [$report->dimensions,$report->metrics[1]->values[0],$report->metrics[1]->values[1],$report->metrics[1]->values[2],$report->metrics[1]->values[3],$report->metrics[1]->values[4]];
        }
        return [$array, $number];
    }

    public function get_sc_query($sc, $url, $limit = 10, $start, $end, $com_start, $com_end)
    {
        $resulets = [];
        $impressions_sum = 0;
        $impressions_sum_comp = 0;
        try {
            $query_date = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query_date->setDimensions(['date']);
            $query_date->setStartDate($start);
            $query_date->setEndDate($end);
            $date_query = $sc->searchanalytics->query($url, $query_date)->rows;
            foreach ($date_query as $key => $val) {
                $resulets['date'][] = $val->keys[0];
                $resulets['clicks'][] = $val->clicks;
                $resulets['impressions'][] = $val->impressions;
                $impressions_sum += $val->impressions;
            }

            $query_date_comp = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query_date_comp->setDimensions(['date']);
            $query_date_comp->setStartDate($com_start);
            $query_date_comp->setEndDate($com_end);
            $date_query = $sc->searchanalytics->query($url, $query_date_comp)->rows;
            foreach ($date_query as $key => $val) {
                $resulets['comp']['date'][] = $val->keys[0];
                $resulets['comp']['clicks'][] = $val->clicks;
                $resulets['comp']['impressions'][] = $val->impressions;
                $impressions_sum_comp += $val->impressions;
            }

            $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query->setRowLimit($limit);
            $query->setDimensions(['query']);
            $query->setStartDate($start);
            $query->setEndDate($end);
            $resulets['original'] = $sc->searchanalytics->query($url, $query)->rows;

            $query_comp = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query_comp->setRowLimit($limit);
            $query_comp->setDimensions(['query']);
            $query_comp->setStartDate($com_start);
            $query_comp->setEndDate($com_end);
            $resulets['comparison'] = $sc->searchanalytics->query($url, $query_comp)->rows;

            // 最大値取得
            $max_clicks = [];
            $max_impressions = [];
            $max_ctr = [];
            $max_position = [];
            foreach ($resulets['original'] as $key => $val) {
                $max_clicks[] = $val->clicks;
                $max_impressions[] = $val->impressions;
                $max_ctr[] = $val->ctr;
                $max_position[] = $val->position;
            }
            $resulets['max'] = [
                'clicks' => max($max_clicks),
                'impressions' => max($max_impressions),
                'ctr' => max($max_ctr),
                'position' => min($max_position)
            ];
            $resulets['sum'] = [
                'original' => $impressions_sum,
                'comp' => $impressions_sum_comp
            ];
        } catch (\Exception $e) {
            return [];
        }

        return $resulets;
    }
}
