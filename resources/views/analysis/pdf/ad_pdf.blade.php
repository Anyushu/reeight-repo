@php
// 広告費用
$cost = $ga_result_ad[0][0][0];
if ($cost == 0) {
    $bool = false;
} else {
    $bool = true;
}
if ($bool) {
    $click = $ga_result_ad[0][0][1];
    $cv = $ga_result_ad[0][0][2];
    $price = round($cost/$click, 1);
    $old_cost = $ga_result_ad[0][1][0];
    $old_click = $ga_result_ad[0][1][1];
    $old_cv = $ga_result_ad[0][1][2];
    if ($old_cv != 0 && $cv != 0) {
        $cvcost = round($cost / $cv, 0);
        $old_cvcost = round($old_cost / $old_cv, 0);
        if (($old_cvcost - 1) > 0) {
            $comp_all_cvcost = round(($cvcost / $old_cvcost - 1) * 100, 2);
        } else {
            $comp_all_cvcost = 0;
        }
    } else {
        $cvcost = 0;
        $old_cvcost = 0;
        $comp_all_cvcost = 0;
    }
    if ($old_cost != 0) {
        $comp_all_cost = round(($cost / $old_cost - 1) * 100, 2);
    } else {
        $comp_all_cost = 0;
    }
    if ($old_click != 0) {
        $old_price = round($old_cost / $old_click, 1);
    } else {
        $old_price = 0;
    }
    if ($old_price != 0) {
        $comp_price = round(($price / $old_price - 1) * 100, 2);
    } else {
        $comp_price = 0;
    }
    if ($old_click != 0) {
        $comp_click = round(($click / $old_click - 1) * 100, 2);
    } else {
        $comp_click = 0;
    }
    if ($old_cost != 0) {
        $comp_cost = round(($cost / $old_cost - 1) * 100, 2);
    } else {
        $comp_cost = 0;
    }
    if ($old_cv != 0) {
        $comp_cv = round(($cv / $old_cv - 1) * 100, 2);
    } else {
        $comp_cv = 0;
    }
    $arr_click = [];
    $arr_cost = [];
    $arr_price = [];
    $arr_cv = [];
    $arr_cv_r = [];
    foreach ($ga_result_ad[1] as $key => $val) {
        $val[0][0][1] == 0 ? $arr_click[] = 1 : $arr_click[] = $val[0][0][1];
        $val[0][0][2] == 0 ? $arr_cost[] = 1 : $arr_cost[] = $val[0][0][2];
        $val[0][0][3] == 0 ? $arr_price[] = 1 : $arr_price[] = $val[0][0][3];
        $val[0][0][4] == 0 ? $arr_cv[] = 1 : $arr_cv[] = $val[0][0][4];
        $val[0][0][5] == 0 ? $arr_cv_r[] = 1 : $arr_cv_r[] = $val[0][0][5];
    }
    rsort($arr_click);
    rsort($arr_cost);
    rsort($arr_price);
    rsort($arr_cv);
    rsort($arr_cv_r);
}
$general = 0;
@endphp
@section('content_ad')
<section class="reports">
<div class="container">
@if($bool)
<div class="row mx-0 mb-3">
<div class="col">
<div class="card">
<div class="card-body">
<div class="row mx-0 align-items-center">
<div class="col-6 text-center">
<span class="opacity-item opacity-bg-blue">
<img src="{{ asset('/img/fa-yen-sign_blue_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">広告費用</h4>
<p class="h4 font-weight-bold text-dark"><span class="mr-1">¥</span>{{ number_format(round($cost, 0)) }}</p>
<p class="m-0"><small class="text-secondary"><img class="mr-1" src="{{ asset('/img/fa-arrows-alt-h_text-secondary_14.png') }}">¥{{ number_format(round($old_cost, 0)) }}</small></p>
<span class="d-block">
@if($comp_all_cost <= 0)
<span class="opacity-color-green"><span class="mr-1">↓</span>
@else
<span class="opacity-color-red"><span class="mr-1">↑</span>
@endif
{{ $comp_all_cost }}%</span>
</span>
</span>
</div>
<div class="col-1">
<p class="m-0"><img src="{{ asset('/img/fa-equals_blue_16.png') }}"></p>
</div>
<div class="col-5">
<p class="overflow-hidden opacity-bg-blue m-0 px-3 py-2 rounded-lg text-center text-dark">
<span>クリック単価</span>
<span class="d-block h5 mb-1 font-weight-bold text-dark"><span class="mr-1">¥</span>{{ $price }}</span>
<span class="d-block">
@if($comp_price <= 0)
<span class="opacity-color-green"><span class="mr-1">↓</span>
@else
<span class="opacity-color-red"><span class="mr-1">↑</span>
@endif
{{ $comp_price }}%</span>
</span>
</span>
</p>
<p class="my-1 opacity-color-blue text-center"><img src="{{ asset('/img/fa-times_blue_16.png') }}"></p>
<p class="overflow-hidden opacity-bg-blue m-0 px-3 py-2 rounded-lg text-center text-dark">
<span>クリック数</span>
<span class="d-block h5 mb-1 font-weight-bold text-dark">{{ number_format($click) }}</span>
<span class="d-block">
@if($comp_click >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_click }}%</span>
</span>
</span>
</p>
</div>
</div>
<hr>
<small class="d-block text-left text-secondary">期間内で消化された広告費⽤。</small>
</div>
</div>
</div>

<div class="col">
<div class="card">
<div class="card-body">
<div class="row mx-0 align-items-center">
<div class="col-6 text-center">
<span class="opacity-item opacity-bg-purple">
<img src="{{ asset('/img/fa-yen-sign_purple_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">コンバージョン単価</h4>
<p class="h4 font-weight-bold text-dark"><span class="mr-1">¥</span>{{ number_format($cvcost) }}</p>
<p class="m-0"><small class="text-secondary"><img class="mr-1" src="{{ asset('/img/fa-arrows-alt-h_text-secondary_14.png') }}">{{ number_format($old_cvcost) }}</small></p>
<span class="d-block">
@if($comp_all_cvcost <= 0)
<span class="opacity-color-green"><span class="mr-1">↓</span>
@else
<span class="opacity-color-red"><span class="mr-1">↑</span>
@endif
{{ $comp_all_cvcost }}%</span>
</span>
</span>
</div>
<div class="col-1">
<p class="m-0"><img src="{{ asset('/img/fa-equals_purple_16.png') }}"></p>
</div>
<div class="col-5">
<p class="overflow-hidden opacity-bg-purple m-0 px-3 py-2 rounded-lg text-center text-dark">
<span>広告費用</span>
<span class="d-block h5 mb-1 font-weight-bold text-dark"><span class="mr-1">¥</span>{{ number_format($cost) }}</span>
<span class="d-block">
@if($comp_cost <= 0)
<span class="opacity-color-green"><span class="mr-1">↓</span>
@else
<span class="opacity-color-red"><span class="mr-1">↑</span>
@endif
{{ $comp_cost }}%</span>
</span>
</span>
</p>
<p class="my-1 opacity-color-purple text-center"><img src="{{ asset('/img/fa-divide_purple_16.png') }}"></p>
<p class="overflow-hidden opacity-bg-purple m-0 px-3 py-2 rounded-lg text-center text-dark">
<span>コンバージョン数</span>
<span class="d-block h5 mb-1 font-weight-bold text-dark">{{ $cv }}</span>
<span class="d-block">
@if($comp_cv >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_cv }}%</span>
</span>
</span>
</p>
</div>
</div>
<hr>
<small class="d-block text-left text-secondary">コンバージョン数1に対する広告費⽤。</small>
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
<th class="font-weight-normal align-top"></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue"><img src="{{ asset('/img/fa-mouse-pointer_blue_16.png') }}"></span><small class="mt-2 d-block">クリック数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-purple-2"><img src="{{ asset('/img/fa-yen-sign_purple_16.png') }}"></span><small class="mt-2 d-block">費用</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue-2"><img src="{{ asset('/img/fa-yen-sign_blue-2_16.png') }}"></span><small class="mt-2 d-block">クリック単価</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-orange"><img src="{{ asset('/img/fa-flag_orange_16.png') }}"></span><small class="mt-2 d-block">コンバージョン数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-purple"><img src="{{ asset('/img/fa-flag_purple_16.png') }}"></span><small class="mt-2 d-block">コンバージョン率</small></th>
</tr>
</thead>
<tbody>
@foreach ($ga_result_ad[1] as $key => $val)
<tr>
<td><span class="text-dark">{{ $val[0][0][0][0] }}</span></td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][1]) }}</span>
<div class="progress">
@if($arr_click[0] != 0)
<div class="progress-bar ka-bg-blue" style="width:{{ ($val[0][0][1]/$arr_click[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][1]/$arr_click[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="text-dark font-weight-bold"><i class="fas fa-yen-sign mr-1"></i>{{ number_format(round($val[0][0][2], 2)) }}</span>
<div class="progress">
@if($arr_cost[0] != 0)
<div class="progress-bar ka-bg-purple-2" style="width:{{ ($val[0][0][2]/$arr_cost[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][2]/$arr_cost[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
@if($comp <= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold"><i class="fas fa-yen-sign mr-1"></i>{{ number_format($val[0][0][3]) }}</span>
<div class="progress">
@if($arr_price[0] != 0)
<div class="progress-bar ka-bg-blue-2" style="width:{{ ($val[0][0][3]/$arr_price[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][3]/$arr_price[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
@if($comp <= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ number_format($val[0][0][4]) }}</span>
<div class="progress">
@if($arr_cv[0] != 0)
<div class="progress-bar ka-bg-orange" style="width:{{ ($val[0][0][4]/$arr_cv[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][4]/$arr_cv[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="text-right mb-0">
@php
if ($val[1][0][4] != 0) {
$comp = round(($val[0][0][4] / $val[1][0][4] - 1) * 100, 2);
} else {
$comp = 0;
}
$general += $comp;
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
<span class="text-dark font-weight-bold">{{ round($val[0][0][5], 2) }}%</span>
<div class="progress">
@if($arr_cv_r[0] != 0)
<div class="progress-bar ka-bg-purple" style="width:{{ ($val[0][0][5]/$arr_cv_r[0]*100) }}%" role="progressbar" aria-valuenow="{{ ($val[0][0][5]/$arr_cv_r[0]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<h3 class="font-weight-bold h5 mt-2">広告分析の総評</h3>
<p id="comment_ad">
・広告費用が比較期間よりも@if($comp_cost > 0)高くなっており、@else低くなっており、@endifクリック単価が@if($comp_price > 0)高くなっています。@else低くなっています。@endif
<br>
・各キーワードにおけるコンバージョン獲得率は@if($general > 0)増加傾向にあり、@else減少傾向にあり、@endifクリック数が一番多いキーワードは「{{ $ga_result_ad[1][0][0][0][0][0] }}」となっています。
</p>
</div>
</div>
</div>
</div>
</div>
@else
<div class="col-12">
<div class="alert alert-warning">
<p class="m-0">Google広告と連携されていないか、広告設定が正しくありません。</p>
</div>
</div>
@endif
</div>
</section>
@endsection
