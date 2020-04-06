@php
// 流入チャネル
if (isset($ga_result[0])) {
    $channel = $ga_result[0];
} else {
  $channel = [];
}
// sns
if (isset($ga_result[1])) {
    $sns = $ga_result[1];
} else {
  $sns = [];
}
// リファラル
if (isset($ga_result[2])) {
    $link = $ga_result[2];
} else {
  $link = [];
}
// 検索エンジン
if (isset($ga_result[3])) {
    $engine = $ga_result[3];
} else {
  $engine = [];
}
@endphp
@section('content')
<section class="reports">
<div class="container">
<div class="row mx-0 mb-3">

<div class="col-4 mb-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue-2">
<i class="fas fa-project-diagram opacity-color-blue-2"></i>
</span>
<h4 class="h6 text-dark mt-3 mb-4">流入チャネル</h4>
@foreach ($channel as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-blue-2" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-blue-2" style="width:{{ ($value[1]/$channel[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$channel[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
@endforeach
</div>
</div>
</div>

<div class="col-4 mb-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue">
<i class="fas fa-search opacity-color-blue"></i>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp }}%</span>
</p>
@endforeach
</div>
</div>
</div>

<div class="col-4 mb-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple-2">
<i class="fas fa-retweet opacity-color-purple-2"></i>
</span>
<h4 class="h6 text-dark mt-3 mb-4">SNSからの流入</h4>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
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
<span class="opacity-item opacity-bg-purple">
<i class="fas fa-link opacity-color-purple"></i>
</span>
<h4 class="h6 text-dark mt-3 mb-4">他サイトからの流入</h4>
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
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
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
<i class="fas fa-comment-dots opacity-color-orange"></i>
</span>
</div>
<div class="col-11">
<div class="d-flex justify-content-between pb-2">
<h3 class="font-weight-bold h5 mt-2">流入元分析の総評</h3>
<button id="comment_btn" type="button" name="button" class="btn btn-primary" onclick="saveTextarea('inflow',document.getElementById('comment_inflow'))">更新</button>
</div>
<textarea id="comment_inflow" class="border form-control text-secondary" name="name" rows="4" onfocus="textareaBtn()">
@if(isset($channel[0][0]))・{{ $channel[0][0] }}からの流入が多くを占めています。@endif
@if(isset($sns[0][0]))・{{ $sns[0][0] }}からの流入が多くを占めています。@endif
@if(isset($link[0][0]))・{{ $link[0][0] }}からの流入が多くを占めています。@endif
</textarea>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
