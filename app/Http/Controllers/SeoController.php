<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\AddSites;
use App\Plans;
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

class SeoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('analytics.reporting');
        $this->middleware('webmaster');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $sites)
    {
        $site = AddSites::where('id', $sites)->get();
        $view_id = (String)$site[0]->VIEW_ID;
        $url = $site[0]->url;
        $plan = $site[0]->plan;
        $name = $site[0]->site_name;
        $sc_result = [];
        if (!isset($request->start)) {
            $start = date('Y-m-d', strtotime('-32 day'));
        } else {
            $start = $request->start;
        }
        if (!isset($request->end)) {
            $end = date('Y-m-d', strtotime('-3 day'));
        } else {
            $end = $request->end;
        }
        $today = date('Y-m-d');

        if (!isset($request->page)) {
            $page = '0';
            $this_page = 1;
        } else {
            if ($request->page == 1) {
                $page = '0';
                $this_page = 1;
            } else {
                $page = $request->page*100-99;
                $this_page = $request->page;
            }
        }

        // Google APIのインスタンス取得
        $sc = $request->sc;
        $ga = $request->ga_report;

        // GAのレポート結果
        $ga_result = $this->get_ga_data($ga, $view_id, $start, $end, (String)$page);
        // dd($ga_result);
        if($ga_result['counts'][0] != null){
          if ($ga_result['counts'][0] == 'error') {
              $counts = 0;
              $all_pages = 0;
              $ga_results = [];
              $ga_message = '終了日が開始日よりも以前になっています。開始日以降を設定してください';
          } else {
              $ga_results = $ga_result['rows'];
              $counts = $ga_result['counts'][0];
              $all_pages = ceil($counts / 100);
              $ga_message = '';

              // SCのレポート結果
              $sc_results = $this->get_sc_data($sc, $url, 9999, $start, $end);
              foreach ($sc_results as $key => $val) {
                  $sc_result[$val['keys'][0]] = [
                      'clicks' => $val['clicks'],
                      'ctr' => round(($val['ctr']*100), 1),
                      'impressions' => $val['impressions'],
                      'position' => round($val['position'], 1),
                  ];
              }
          }
        }else{
          $ga_results = [];
          $sc_result = [];
          $all_pages = 0;
          $ga_message = '';
        }

          return view('analysis.seo.index')->with([
              'ga' => $ga_results,
              'sc' => $sc_result,
              'url' => $url,
              'name' => $name,
              'plan' => $plan,
              'start' => $start,
              'end' => $end,
              'today' => $today,
              'all_pages' => $all_pages,
              'this_page' => $this_page,
              'view_id' => $view_id,
              'site_id' => $sites,
              'ga_message' => $ga_message,
          ]);

    }

    // GAの値取得
    public function get_ga_data($analytics, $view_id, $start, $end, $page_token = '0')
    {
        try {
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
            $val = new Google_Service_AnalyticsReporting_Metric();
            $val->setExpression('ga:pageValue');
            $cv = new Google_Service_AnalyticsReporting_Metric();
            $cv->setExpression('ga:goalCompletionsAll');
            $ps = new Google_Service_AnalyticsReporting_Metric();
            $ps->setExpression('ga:avgPageLoadTime');
            $title = new Google_Service_AnalyticsReporting_Dimension();
            $title->setName('ga:pageTitle');
            $path = new Google_Service_AnalyticsReporting_Dimension();
            $path->setName('ga:pagePath');
            $orderBy = new Google_Service_AnalyticsReporting_OrderBy();
            $orderBy->setFieldName('ga:sessions');
            $orderBy->setSortOrder('DESCENDING');
            $request = new Google_Service_AnalyticsReporting_ReportRequest();
            $request->setViewId($view_id);
            $request->setDateRanges($dateRange);
            $request->setMetrics(array($ss, $pv, $ps, $up, $time, $br, $val, $cv, $ps));
            $request->setDimensions(array($title, $path));
            $request->setOrderBys($orderBy);
            $request->setPageToken($page_token);
            $request->setPageSize('100');
            $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
            $body->setReportRequests(array($request));
            $reports = $analytics->reports->batchGet($body);
            $rowCount = $reports[0]->data->rowCount;
            $result = [];
            $result['counts'][] = $rowCount;
            for ($reportIndex = 0; $reportIndex < count($reports); ++$reportIndex) {
                $report = $reports[ $reportIndex ];
                $header = $report->getColumnHeader();
                $dimensionHeaders = $header->getDimensions();
                $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
                $rows = $report->getData()->getRows();
                for ($rowIndex = 0; $rowIndex < count($rows); ++$rowIndex) {
                    $row = $rows[$rowIndex];
                    $dimensions = $row->getDimensions();
                    $metrics = $row->getMetrics();
                    $result['rows'][$rowIndex] = [];
                    for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); ++$i) {
                        array_push($result['rows'][$rowIndex], $dimensions[$i]);
                    }
                    for ($j = 0; $j < count($metricHeaders) && $j < count($metrics); ++$j) {
                        $entry = $metricHeaders[$j];
                        $values = $metrics[$j];
                        for ($valueIndex = 0; $valueIndex < count($values->getValues()); ++$valueIndex) {
                            $value = $values->getValues()[$valueIndex];
                            array_push($result['rows'][$rowIndex], $value);
                        }
                    }
                }
            }
            return $result;
        } catch (\Exception $e) {
            $result = [];
            $result['counts'][0] = 'error';
            return $result;
        }
    }

    // SCのページ単位取得
    public function get_sc_data($sc, $url, $limit = 100, $start, $end)
    {
        try {
            $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $query->setRowLimit($limit);
            $query->setStartDate($start);
            $query->setEndDate($end);
            $query->setDimensions(['page']);
            return $resulets = $sc->searchanalytics->query($url, $query)->rows;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
