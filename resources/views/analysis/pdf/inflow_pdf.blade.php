@php
// 流入チャネル
if (isset($ga_result_inflow[0])) {
    $channel = $ga_result_inflow[0];
} else {
    $channel = [];
}
// sns
if (isset($ga_result_inflow[1])) {
    $sns = $ga_result_inflow[1];
} else {
    $sns = [];
}
// リファラル
if (isset($ga_result_inflow[2])) {
    $link = $ga_result_inflow[2];
} else {
    $link = [];
}
// 検索エンジン
if (isset($ga_result_inflow[3])) {
    $engine = $ga_result_inflow[3];
} else {
    $engine = [];
}
@endphp
@section('content_inflow')
<section class="reports">
<div class="container">
<div class="row mx-0 mb-3">

<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue">
<img src="{{ asset('/img/fa-project-diagram_sky_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">流入チャネル</h4>
@foreach ($channel as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-blue" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-blue" style="width:{{ ($value[1]/$channel[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$channel[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="mb-3 text-right">
@php
if ($channel[$key][2] != 0) {
$comp = round(($value[1] / (int)$channel[$key][2] - 1) * 100, 2);
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
@endforeach
</div>
</div>
</div>

<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue">
<img src="{{ asset('/img/fa-search_blue_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3 mb-4">検索エンジン別の流入</h4>
@foreach ($engine as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-blue" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
@if($engine[0][1]*100 != 0)
<div class="progress-bar ka-bg-blue" style="width:{{ ($value[1]/$engine[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$engine[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
@endif
@endif
</div>
<p class="mb-3 text-right">
@php
if ($engine[$key][2] != 0) {
$comp = round(($value[1] / (int)$engine[$key][2] - 1) * 100, 2);
} else {
$comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green mr-1">↑</i>
@else
<span class="opacity-color-red mr-1">↓</i>
@endif
{{ $comp }}%</span>
</p>
@endforeach
</div>
</div>
</div>

<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple-2">
<img src="{{ asset('/img/fa-retweet_purple-2_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">SNSからの流入</h4>
@if(count($sns) === 0)
<p class="m-0">データがありません。</p>
@endif
@foreach ($sns as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-purple-2" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-purple-2" style="width:{{ ($value[1]/$sns[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$sns[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="mb-3 text-right">
@php
if ($sns[$key][2] != 0) {
$comp = round(($value[1] / (int)$sns[$key][2] - 1) * 100, 2);
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
@endforeach
</div>
</div>
</div>

<div class="col-4 mt-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple">
<img src="{{ asset('/img/fa-link_purple_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">他サイトからのリンク</h4>
@if(count($link) === 0)
<p class="m-0">データがありません。</p>
@endif
@foreach ($link as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-purple" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-purple" style="width:{{ ($value[1]/$link[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$link[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="mb-3 text-right">
@php
if ($link[$key][2] != 0) {
$comp = round(($value[1] / (int)$link[$key][2] - 1) * 100, 2);
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
@endforeach
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
<h3 class="font-weight-bold h5 mt-2">流入元分析の総評</h3>
<p id="comment_inflow">
@if(isset($channel[0][0]))・{{ $channel[0][0] }}からの流入が多くを占めています。<br>@endif
@if(isset($sns[0][0]))・{{ $sns[0][0] }}からの流入が多くを占めています。<br>@endif
@if(isset($link[0][0]))・{{ $link[0][0] }}からの流入が多くを占めています。@endif
</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<script>

</script>
@endsection
