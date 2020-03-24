@php
$param = strstr($_SERVER["REQUEST_URI"], '?');
if (!$param) {
    $param = '';
}
@endphp

@section('nav')
<section class="mb-3 mt-n4">
<div class="container">
<div class="col-12">
<div class="card">
<div class="card-body pb-0">
<div class="row mx-0 mb-4">
<div class="col">
<h2 class="font-weight-bold h5 text-dark">
<img class="mr-1 d-inline-block align-middle" src="https://www.google.com/s2/favicons?domain={{ $site_url }}" width="16" height="16">
<span class="d-inline-block align-middle">{{ $site_name }}</span>
</h2>
<p class="m-0">{{ $site_url }}</p>
</div>
<div class="col text-right">
<p class="m-0">
<a class="text-decoration-none" href="#" data-toggle="modal" data-target="#period-form"><i class="far fa-calendar-alt mr-1 opacity-color-blue d-inline-block align-middle"></i>
<span class="opacity-color-blue d-inline-block align-middle mr-2">指定期間</span>
<span class="text-dark d-inline-block align-middle border rounded-lg px-2">{{ $start }} ~ {{ $end }}</span>
</a>
</p>
<p class="m-0">
<a class="text-decoration-none" href="#" data-toggle="modal" data-target="#period-form"><i class="fas fa-arrows-alt-h mr-1 opacity-color-red d-inline-block align-middle"></i>
<span class="opacity-color-red d-inline-block align-middle mr-2">比較期間</span>
<span class="text-dark d-inline-block align-middle border rounded-lg px-2">{{ $com_start }} ~ {{ $com_end }}</span>
</a>
</p>
</div>
</div>

<ul class="nav nav-tabs report-nav">
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'ga-report') echo 'active'; @endphp" href="{{ route('ga-report', $site_id) }}{{ $param }}"><i class="far fa-calendar-alt mr-1"></i>サマリー</a>
</li>
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'ga-user') echo 'active'; @endphp" href="{{ route('ga-user', $site_id) }}{{ $param }}"><i class="fas fa-users mr-1"></i>ユーザー属性</a>
</li>
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'ga-inflow') echo 'active'; @endphp" href="{{ route('ga-inflow', $site_id) }}{{ $param }}"><i class="fas fa-project-diagram mr-1"></i>流入元分析</a>
</li>
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'ga-action') echo 'active'; @endphp" href="{{ route('ga-action', $site_id) }}{{ $param }}"><i class="fas fa-pager mr-1"></i>ユーザー行動分析</a>
</li>
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'ga-conversion') echo 'active'; @endphp" href="{{ route('ga-conversion', $site_id) }}{{ $param }}"><i class="fas fa-flag mr-1"></i>コンバージョン分析</a>
</li>
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'ga-ad') echo 'active'; @endphp" href="{{ route('ga-ad', $site_id) }}{{ $param }}"><i class="fas fa-ad mr-1"></i>広告分析</a>
</li>
@if($plan%2 == 0)
<li class="nav-item">
<a class="nav-link @php if(Route::current()->getName() == 'sc-query') echo 'active'; @endphp" href="{{ route('sc-query', $site_id) }}{{ $param }}"><i class="fab fa-searchengin mr-1"></i>検索分析</a>
</li>
@endif
</ul>

</div>
</div>
</div>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="period-form" tabindex="-1" role="dialog" aria-labelledby="period-form-label" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title font-weight-bold" id="period-form-label">解析期間を変更する</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<form class="" method="get">
<div class="modal-body">
<div class="mb-4">
<p class="m-0">
<span class="opacity-color-blue d-inline-block align-middle mr-2">解析期間</span>
<div class="row align-items-center">
<div class="col-3">
<input type="text" class="form-control datepicker check_date" id="start" name="start" value="{{ $start }}">
</div>
<div class="col-1 p-0 text-center">〜</div>
<div class="col-3">
<input type="text" class="form-control datepicker" id="end" name="end" value="{{ $end }}">
</div>
</div>
</p>
</div>
<div class="">
<p class="m-0">
<span class="opacity-color-red d-inline-block align-middle mr-2">比較期間</span>
<div class="row align-items-center">
<div class="col-3">
<input type="text" class="form-control datepicker" id="com_start" name="com_start" value="{{ $com_start }}">
</div>
<div class="col-1 p-0 text-center">〜</div>
<div class="col-3">
<input type="text" class="form-control datepicker check_date" id="com_end" name="com_end" value="{{ $com_end }}">
</div>
</div>
</p>
</div>
<p id="check_range" class="opacity-color-red font-weight-bold"></p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">閉じる</button>
<button id="data_change_btn" type="submit" class="btn btn-sm btn-primary">期間を変更</button>
</div>
</form>
</div>
</div>
</div>
@endsection
