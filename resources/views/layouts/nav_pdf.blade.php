<?php
$param = strstr($_SERVER["REQUEST_URI"], '?');
if (!$param) {
    $param = '';
}
?>

@section('nav_pdf')
<section class="mb-3 mt-n4">
<div class="container">
<div class="col-12">
<div class="card">
<div class="card-body pb-0">
<div class="row mx-0 mb-4">
<div class="col">
<h2 class="font-weight-bold h5 text-dark">
<span class="d-inline-block align-middle">{{ $site_name }}</span>
</h2>
<p class="m-0">{{ $site_url }}</p>
</div>
<div class="col text-right">
<p class="m-0">
<a class="text-decoration-none" href="#" data-toggle="modal" data-target="#period-form"><img class="mr-1 d-inline-block align-middle" src="{{ asset('/img/fa-calendar-alt_blue_14.png') }}" alt="fa-calendar">
<span class="opacity-color-blue d-inline-block align-middle mr-2">期間</span>
<span class="text-dark d-inline-block align-middle border-bottom">{{ $start }} ~ {{ $end }}</span>
</a>
</p>
<p class="m-0">
<a class="text-decoration-none" href="#" data-toggle="modal" data-target="#period-form"><img class="mr-1 d-inline-block align-middle" src="{{ asset('/img/fa-arrows-alt-h_red_14.png') }}" alt="fa-arrows">
<span class="opacity-color-red d-inline-block align-middle mr-2">比較</span>
<span class="text-dark d-inline-block align-middle border-bottom">{{ $com_start }} ~ {{ $com_end }}</span>
</a>
</p>
</div>
</div>

<ul class="nav nav-tabs report-nav">
<li class="nav-item">
<a class="nav-link ac_sumally" >サマリー</a>
</li>
<li class="nav-item">
<a class="nav-link ac_user" >ユーザー属性</a>
</li>
<li class="nav-item">
<a class="nav-link ac_inflow" >流入元分析</a>
</li>
<li class="nav-item">
<a class="nav-link ac_action" >ユーザー行動分析</a>
</li>
<li class="nav-item">
<a class="nav-link ac_conv" >コンバージョン分析</a>
</li>
<li class="nav-item">
<a class="nav-link ac_ad">広告分析</a>
</li>
@if($plan%2 == 0)
<li class="nav-item">
<a class="nav-link ac_query" >検索分析</a>
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
<div class="row mx-0">
<div class="col">
<p class="m-0">
<span class="opacity-color-blue d-inline-block align-middle mr-2">解析</span>
<div class="row">
<div class="col">
<input type="text" class="form-control datepicker" id="start" name="start" value="{{ $start }}">
</div>
<div class="col-1">
<span>~</span>
</div>
<div class="col">
<input type="text" class="form-control datepicker" id="end" name="end" value="{{ $end }}">
</div>
</div>
</p>
</div>
<div class="col">
<p class="m-0">
<span class="opacity-color-red d-inline-block align-middle mr-2">比較</span>
<div class="row">
<div class="col">
<input type="text" class="form-control datepicker" id="com_start" name="com_start" value="{{ $com_start }}">
</div>
<div class="col-1">
<span>~</span>
</div>
<div class="col">
<input type="text" class="form-control datepicker" id="com_end" name="com_end" value="{{ $com_end }}">
</div>
</div>
</p>
</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">閉じる</button>
<button type="submit" class="btn btn-sm btn-primary">期間を変更</button>
</div>
</form>
</div>
</div>
</div>
@endsection
