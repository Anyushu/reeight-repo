@push('scripts')
<script src="{{ mix('/js/report.js') }}"></script>
@endpush

@push('style')
<style media="screen">
body {
  background-color: #fafafa;
}
</style>
@endpush

{{-- 変数定義 --}}
@php
$route_name = Route::current()->getName();
$site_id = $add_site->id;
$site_url = $add_site->url;
$site_name = $add_site->site_name;
@endphp

@section('title', '解析レポート - '.$site_name)

@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')
@include('layouts.nav')

{{-- ルート名によってコンテンツを切り替え --}}
@if ($route_name == 'ga-report')
  @include('analysis.report.sumally')
@elseif ($route_name == 'ga-user')
  @include('analysis.report.users')
@elseif ($route_name == 'ga-inflow')
  @include('analysis.report.inflow')
@elseif ($route_name == 'ga-action')
  @include('analysis.report.action')
@elseif ($route_name == 'ga-conversion')
  @include('analysis.report.conversion')
@elseif ($route_name == 'ga-ad')
  @include('analysis.report.ad')
@elseif ($route_name == 'sc-query')
  @include('analysis.report.query')
@endif
