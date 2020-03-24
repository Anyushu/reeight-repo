@section('content')
<section class="wrap">
<div class="container">

<div class="mb-3">
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text"><i class="fas fa-search"></i></div>
</div>
<input id="mysite-search" class="form-control search" type="text" placeholder="キーワードでサイトを絞り込み" width="500">
</div>
</div>

<ul class="list-group list-group-flush">
<li class="list-group-item">
<div class="row">
<div class="col-4"><span class="font-weight-bold text-dark">サイトの名前</span></div>
<div class="col-3"><span class="font-weight-bold text-dark">サイトのジャンル</span></div>
<div class="col-3"><span class="font-weight-bold text-dark">ご契約プラン</span></div>
</div>
</li>
@if (count($add_sites) != 0)
@foreach ($add_sites as $key => $site)
@php
if ($site->trial_at != null) {
    $trial_flag = true;
    $regi = date('Y-m-d', strtotime($site->trial_at));
    $remaining = intval(strtotime(date('Y-m-d', strtotime($regi.' +10 days'))) - strtotime(date('Y-m-d'))) / (60*60*24);
    if ($remaining > 0) {
        $remaining_flag = true;
    } else {
        $remaining_flag = false;
    }
} else {
    $trial_flag = false;
}
$plan_date = null;
@endphp
<li class="my-site list-group-item list-group-item-action" data-toggle="collapse" href="#collapse-{{ $key }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $key }}">
<div class="row align-items-center">
<div class="col-4 d-flex align-items-center">
<figure class="m-0 line-height-1">
<img class="d-inline-block align-middle" src="https://www.google.com/s2/favicons?domain={{ $site->url }}" width="16" height="16">
</figure>
<div class="ml-2">
<span class="d-inline-block text-dark">{{ $site->site_name }}</span>
<span class="d-block"><small>{{ $site->url }}</small></span>
</div>
</div>
<div class="col-3">{{ $categories[($site->category - 1)]->cat }}</div>
<div class="col-3">
@if ($trial_flag && ($site->plan == 7 || $site->plan == 8))
@if ($remaining_flag)
無料トライアル：残り{{ $remaining }}日
@else
再開はプランを選択
@endif
@else
{{ $plans[($site->plan - 1)]->name }}
@if($site->plan_created_at != null)
@php
$plan_date = strtotime($site->plan_created_at);
$site_plan = $site->plan;
if ($site_plan == 1 || $site_plan == 2) {
    $plus = '12';
} elseif ($site_plan == 3 || $site_plan == 4) {
    $plus = '6';
} elseif ($site_plan == 5 || $site_plan == 6) {
    $plus = '1';
}
@endphp
<span class="d-block"><small>更新日：{{ date('Y年n月1日', strtotime('+'.$plus.' month', $plan_date)) }}</small></span>
@endif
@endif
</div>
</div>
<div class="collapse" id="collapse-{{ $key }}">
<hr>
<div class="d-flex justify-content-between align-items-center">
<div class="">
@if ($remaining_flag || $plan_date != null)
@if ($site->plan != null)
<a href="{{ route('ga-report', $site->id) }}" class="btn btn-sm btn-primary mr-2">レポートを作成する</a>
@endif
@if ($site->plan % 2 == 0 && $site->plan != null)
<a href="{{ route('seo-report', $site->id) }}" class="btn btn-sm btn-outline-primary">SEO分析する</a>
@endif
@endif
</div>
<div class="">
<a href="{{ route('send-setting', $site->id) }}" class="btn btn-sm btn-outline-secondary mr-2">メール受信設定</a>
@if ($trial_flag && ($site->plan == 7 || $site->plan == 8))
<form class="d-inline-block mr-2" action="{{ route('plan') }}" method="post">
@csrf
<button class="btn btn-sm btn-outline-secondary" type="submit">プランを選択する</button>
<input type="hidden" name="site_id" value="{{ $site->id }}">
<input type="hidden" name="site_url" value="{{ $site->url }}">
<input type="hidden" name="site_name" value="{{ $site->site_name }}">
</form>
@else
<form class="d-inline-block mr-2" action="{{ route('change-plan.form') }}" method="post">
@csrf
<button class="btn btn-sm btn-outline-secondary" type="submit">プランの変更</button>
<input type="hidden" name="site_id" value="{{ $site->id }}">
<input type="hidden" name="site_url" value="{{ $site->url }}">
<input type="hidden" name="site_name" value="{{ $site->site_name }}">
<input type="hidden" name="site_plan" value="{{ $site->plan }}">
</form>
@endif
<a href="{{ route('sites-edit', $site->id) }}" class="btn btn-sm btn-outline-secondary">サイト情報変更</a>
</div>
</div>
</div>
</li>
@endforeach
@endif
</ul>
@if (count($add_sites) == 0)
<div class="mt-5 text-center">
<a href="{{ route('addsite') }}" class="btn btn-primary">サイトを追加する</a>
</div>
@else
<div class="mt-3">
<a href="{{ route('addsite') }}" class="text-primary"><i class="fas fa-plus mr-1"></i>サイトを追加する</a>
</div>
@endif
</div>
</section>
<script>
  if(localStorage != null){
    localStorage.clear()
  }
</script>
@endsection
