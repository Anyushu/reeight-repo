@php
$ss = $ga_result_conversion[1][0][0];
@endphp
@if($ss)
@php
// cv数
$cv = $ga_result_conversion[1][0][1];
$old_cv = $ga_result_conversion[1][1][1];
if($old_cv != 0) {
  $comp_cv = round(($cv/$old_cv-1)*100, 2);
} else {
  $comp_cv = 0;
}
// cv率
$cv_r = round($ga_result_conversion[1][0][2], 2);
$old_cv_r = round($ga_result_conversion[1][1][2], 2);
if($old_cv_r != 0) {
  $comp_cv_r = $old_cv_r - $cv_r;
} else {
  $comp_cv_r = 0;
}
// セッション
$ss = $ga_result_conversion[1][0][0];
// 直帰
$ex = $ga_result_conversion[1][0][3];
$arr_cv = [];
$arr_cv_r = [];
$arr_uu = [];
$arr_ex = [];
$arr_ps = [];
$arr_time = [];
foreach ($ga_result_conversion[0] as $key => $val) {
    $arr_cv[] = (float)$val[0][0][1];
    $arr_cv_r[] = (float)$val[0][0][2];
    $arr_uu[] = (float)$val[0][0][3];
    $arr_ex[] = (float)$val[0][0][4];
    $arr_ps[] = (float)$val[0][0][5];
    $arr_time[] = (float)$val[0][0][6];
}
rsort($arr_cv);
rsort($arr_cv_r);
rsort($arr_uu);
rsort($arr_ex);
rsort($arr_ps);
rsort($arr_time);
@endphp
@section('content_conversion')
<section class="reports">
<div class="container">
<div class="row mx-0 mb-3">
<div class="col-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-orange">
<img src="{{ asset('/img/fa-flag_orange_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">コンバージョン数</h4>
<p class="h4 font-weight-bold text-dark">{{ $cv }}</p>
<p class="m-0">
<span class="text-secondary"><img class="mr-1" src="{{ asset('/img/fa-arrows-alt-h_text-secondary_14.png') }}">{{ $old_cv }}</span>
</p>
<p class="m-0">
@if($comp_cv >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_cv }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">Googleアナリティクスに設定された「⽬標」の達成数。</small>
</div>
</div>
</div>
<div class="col-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple">
<img src="{{ asset('/img/fa-flag_purple_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">コンバージョン率</h4>
<p class="h4 font-weight-bold text-dark">{{ $cv_r }}<small class="ml-1">%</small></p>
<p class="m-0">
<span class="text-secondary"><img class="mr-1" src="{{ asset('/img/fa-arrows-alt-h_text-secondary_14.png') }}">{{ $old_cv_r }}</span>
</p>
<p class="m-0">
@if($comp_cv_r >= 0)
<span class="opacity-color-red"><span class="mr-1">↑</span>
@else
<span class="opacity-color-green"><span class="mr-1">↓</span>
@endif
{{ $comp_cv_r }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">コンバージョンに達成したセッション数の割合。</small>
</div>
</div>
</div>
<div class="col-6">
<div class="card">
<div class="card-body">
<p class="overflow-hidden opacity-bg-blue opacity-color-blue m-0 p-3 rounded-lg">
<span class="float-left line-height">セッション数</span>
<span class="float-right font-weight-bold h5 line-height m-0">{{ number_format($ss) }}</span>
</p>
<p class="text-right my-2 text-dark"><span class="mr-2">↓</span>{{ round(100 - $ex, 2) }}%</p>
<p class="overflow-hidden opacity-bg-blue opacity-color-blue m-0 p-3 rounded-lg">
<span class="float-left line-height">サイト内閲覧数</span>
<span class="float-right font-weight-bold h5 line-height m-0">{{ number_format(round($ss * (100 - $ex) / 100, 0)) }}</span>
</p>
<p class="text-right my-2 text-dark"><span class="mr-2">↓</span>{{ round($cv / round($ss * (100 - $ex) / 100, 0) * 100, 2) }}%</p>
<p class="overflow-hidden opacity-bg-blue opacity-color-blue m-0 p-3 rounded-lg">
<span class="float-left line-height">コンバージョン数</span>
<span class="float-right font-weight-bold h5 line-height m-0">{{ $cv }}</span>
</p>
</div>
</div>
</div>
</div>

<div class="col-12 mb-3">
<div class="card">
<div class="card-body">
<div class="table-responsive">
<table class="table table-striped table-borderless table-sm">
<thead>
<tr>
<th class="font-weight-bold align-center" colspan="2">上位10件の流入元</th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-orange"><img src="{{ asset('/img/fa-flag_orange_16.png') }}"></span><small class="mt-2 d-block">コンバージョン<br>数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-purple"><img src="{{ asset('/img/fa-flag_purple_16.png') }}"></span><small class="mt-2 d-block">コンバージョン<br>率</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue"><img src="{{ asset('/img/fa-user_blue_16.png') }}"></span><small class="mt-2 d-block">ユーザー数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-purple-2"><img src="{{ asset('/img/fa-arrow-alt-circle-left_purple-2_16.png') }}"></span><small class="mt-2 d-block">直帰率</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue-2"><img src="{{ asset('/img/fa-pager_blue-2_16.png') }}"></span><small class="mt-2 d-block">ページ/<br>セッション</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-red"><img src="{{ asset('/img/fa-clock_red-2_16.png') }}"></span><small class="mt-2 d-block">平均<br>セッション時間</small></th>
</tr>
</thead>
<tbody>
@foreach ($ga_result_conversion[0] as $key => $val)
<tr>
<td class="table_number">{{ $key+1 }}</td>
<td><span class="text-dark">{{ $val[0][0][0][0] }}</span></td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][1]) }}</span>
<div class="progress">
@if($arr_cv[0] == 0)
<div class="progress-bar ka-bg-orange" style="width:0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-orange" style="width:{{ ($val[0][0][1]/$arr_cv[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][1]/$arr_cv[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][1] != 0) {
$comp = round(($val[0][0][1] / $val[1][0][1] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][2], 2) }}%</span>
<div class="progress">
@if($arr_cv_r[0] == 0)
<div class="progress-bar ka-bg-purple" style="width:0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-purple" style="width:{{ ($val[0][0][2]/$arr_cv_r[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][2]/$arr_cv_r[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][2] != 0) {
$comp = round(($val[0][0][2] / $val[1][0][2] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][3]) }}</span>
<div class="progress">
@if($arr_uu[0] !=0)
<div class="progress-bar ka-bg-blue" style="width:{{ ($val[0][0][3]/$arr_uu[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][3]/$arr_uu[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][3] != 0) {
$comp = round(($val[0][0][3] / $val[1][0][3] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][4], 2) }}%</span>
<div class="progress">
@if($arr_ex[0] !=0)
<div class="progress-bar ka-bg-purple-2" style="width:{{ ($val[0][0][4]/$arr_ex[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][4]/$arr_ex[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][4] != 0) {
$comp = round(($val[0][0][4] / $val[1][0][4] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][5], 2) }}</span>
<div class="progress">
@if($arr_ps[0] != 0)
<div class="progress-bar ka-bg-blue-2" style="width:{{ ($val[0][0][5]/$arr_ps[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][5]/$arr_ps[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][5] != 0) {
$comp = round(($val[0][0][5] / $val[1][0][5] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][6], 1) }}秒</span>
<div class="progress">
@if($arr_time[0] != 0)
<div class="progress-bar ka-bg-red" style="width:{{ ($val[0][0][6]/$arr_time[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][6]/$arr_time[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][6] != 0) {
$comp = round(($val[0][0][6] / $val[1][0][6] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>
<div class="col-12">
<div id="comment" class="card">
<div class="card-body">
<div class="row">
<div class="col-1 text-center">
<span class="opacity-item opacity-bg-orange">
<img src="{{ asset('/img/fa-comment-dots_orange_16.png') }}" alt="">
</span>
</div>
<div class="col-11">
<h3 class="font-weight-bold h5 mt-2">コンバージョン分析の総評</h3>
<p id="comment_conversion">
@if($comp_cv > 0)
・コンバージョン獲得数が増加しています。
<br>
@else
・コンバージョン獲得数が減少しています。
<br>
@endif
@if($cv != 0)
その中でも「{{ $ga_result_conversion[0][0][0][0][0][0] }}」からのコンバージョン獲得が多くなっています。
@endif
</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@else
@section('content_conversion')
<section class="reports">
<div class="container">
<div class="col-12">
<div class="alert alert-warning">
<p class="m-0">レポート作成に必要な情報が不足しているため出力できませんでした。</p>
</div>
</div>
</div>
</section>
@endsection
@endif
