@php
$ss = [];
$pv = [];
$ps = [];
$uu = [];
$time = [];
$br = [];
foreach ($ga_result as $key => $val) {
    $ss[] = (float)$val[0][0][1];
    $pv[] = (float)$val[0][0][2];
    $ps[] = (float)$val[0][0][3];
    $uu[] = (float)$val[0][0][4];
    $time[] = (float)$val[0][0][5];
    $br[] = (float)$val[0][0][6];
}
rsort($ss);
rsort($pv);
rsort($ps);
rsort($uu);
rsort($time);
rsort($br);
$general_comment = 0;
@endphp
@if((isset($ss[0]) && $ss[0] > 10) || count($ss) != 0)
@section('content')
<section class="reports">
<div class="container">
<div class="col-12 mb-3">
<div class="card">
<div class="card-body">
<div class="table-responsive">
<table class="table table-striped table-borderless">
<thead>
<tr>
<th class="font-weight-bold align-center" colspan="2">アクセス上位10ページ</th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-orange"><i class="fas fa-bolt opacity-color-orange"></i></span><small class="mt-2 d-block">セッション数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-green"><i class="fas fa-eye opacity-color-green"></i></span><small class="mt-2 d-block">PV数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue-2"><i class="fas fa-pager opacity-color-blue-2"></i></span><small class="mt-2 d-block">ページ/<br>セッション</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue"><i class="fas fa-user opacity-color-blue"></i></span><small class="mt-2 d-block">ユーザー数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-red"><i class="far fa-clock opacity-color-red"></i></span><small class="mt-2 d-block">平均ページ<br>滞在時間</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-purple-2"><i class="fas fa-arrow-alt-circle-left opacity-color-purple-2"></i></span><small class="mt-2 d-block">直帰率</small></th>
</tr>
</thead>
<tbody>
@foreach ($ga_result as $key => $val)
<tr>
<td class="table_number">{{ $key+1 }}</td>
<td><span class="text-dark">@php mb_strlen($val[0][0][0][0]) > 40 ? print mb_substr($val[0][0][0][0], 0, 40).'...' : print $val[0][0][0][0]; @endphp</span>
<br><small class="text-secondary">{{ $val[0][0][0][1] }}</small></td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][1]) }}</span>
<div class="progress">
<div class="progress-bar ka-bg-orange" style="width:{{ ($val[0][0][1]/$ss[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][1]/$ss[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][1] != 0) {
$comp = round(($val[0][0][1] / $val[1][0][1] - 1) * 100, 2);
} else {
$comp = 0;
}
$general_comment += $comp;
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][2]) }}</span>
<div class="progress">
<div class="progress-bar ka-bg-green" style="width:{{ ($val[0][0][2]/$pv[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][2]/$pv[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][3], 2) }}</span>
<div class="progress">
<div class="progress-bar ka-bg-blue-2" style="width:{{ ($val[0][0][3]/$ps[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][3]/$ps[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][4]) }}</span>
<div class="progress">
<div class="progress-bar ka-bg-blue" style="width:{{ ($val[0][0][4]/$uu[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][4]/$uu[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][5], 1) }}秒</span>
<div class="progress">
<div class="progress-bar ka-bg-red" style="width:{{ ($val[0][0][5]/$time[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][5]/$time[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ round($val[0][0][6], 1) }}%</span>
<div class="progress">
<div class="progress-bar ka-bg-purple-2" style="width:{{ ($val[0][0][6]/$br[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][6]/$br[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
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
<i class="fas fa-comment-dots opacity-color-orange"></i>
</span>
</div>
<div class="col-11">
<div class="d-flex justify-content-between pb-2">
<h3 class="font-weight-bold h5 mt-2">ユーザー行動分析の総評</h3>
<button id="comment_btn" type="button" name="button" class="btn btn-primary" onclick="saveTextarea('action',document.getElementById('comment_action'))">更新</button>
</div>
<textarea id="comment_action" class="border form-control text-secondary" name="name" rows="4" onfocus="textareaBtn()">
・「{{ $ga_result[0][0][0][0][0] }}」ページが指定期間で一番多くのユーザーが訪れています。
・@if($general_comment > 0)
各ページ全体ではユーザーの流入が増加しており、上昇傾向にあります。
@else
各ページ全体ではユーザーの流入が減少しており、下降傾向にあります。
@endif
</textarea>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@else
@section('content')
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
