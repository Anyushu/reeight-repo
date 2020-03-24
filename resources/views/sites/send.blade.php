@section('title', 'レポートメール受信設定 - '.$add_sites->site_name)

@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">
<form class="form form-signin register-form" method="post" action="{{ route('send-setting-update', $add_sites->id) }}">
@csrf
@if(Session::has('message'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
{{ session('message') }}
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
@endif
<div class="form-group mb-4">
<label for="send_flag" class="d-block">レポートをメールで自動受信</label>
<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="send_flag" id="send_flag_true" value="1" <?php if ($add_sites->send_flag == 1) echo 'checked'; ?>>
<label class="form-check-label not-must" for="send_flag_true">する</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="send_flag" id="send_flag_false" value="0" <?php if ($add_sites->send_flag == 0) echo 'checked'; ?>>
<label class="form-check-label not-must" for="send_flag_false">しない</label>
</div>
</div>

<div class="form-group mb-4">
<label for="to_email">受信メールアドレス</label>
<input type="email" name="to_email" value="{{ $mail['to'] }}" class="form-control @error('to_email') is-invalid @enderror" id="to_email" placeholder="info@example.com">
@error('to_email')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>

<div class="form-group mb-4">
<label class="not-must" for="cc_email">受信メールアドレス（CC）</label>
<input type="text" name="cc_email" value="{{ $mail['cc'] }}" class="form-control @error('cc_email') is-invalid @enderror" id="cc_email" placeholder="info@example.com,info@example.com,info@example.com">
@error('cc_email')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
<small id="emailHelp" class="form-text text-muted">※メールアドレスを半角カンマ(,)区切りで入力してください</small>
</div>

<div class="form-group mb-4">
<label for="" class="d-block">レポートを受信する頻度（複数選択可）</label>
<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="analyzing_period[]" id="week" value="7" <?php if(isset($days['7'])) echo 'checked'; ?>>
<label class="form-check-label not-must" for="week">毎週（月曜日）</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="analyzing_period[]" id="month" value="30" checked>
<label class="form-check-label not-must" for="month">毎月</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="analyzing_period[]" id="tree-month" value="90" <?php if(isset($days['90'])) echo 'checked'; ?>>
<label class="form-check-label not-must" for="tree-month">3ヶ月毎</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="analyzing_period[]" id="six-month" value="180" <?php if(isset($days['180'])) echo 'checked'; ?>>
<label class="form-check-label not-must" for="six-month">6ヶ月毎</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="analyzing_period[]" id="year" value="360" <?php if(isset($days['360'])) echo 'checked'; ?>>
<label class="form-check-label not-must" for="year">12ヶ月毎</label>
</div>
</div>

<div class="form-group mb-4">
<label for="" class="d-block">レポートを受信する日付</label>
<select class="custom-select custom-select-sm w-50" name="send_day">
@for ($i=1; $i<=31; $i++)
@if($send_day === $i)
<option value="{{ $i }}" selected>{{ $i }}日</option>
@else
<option value="{{ $i }}">{{ $i }}日</option>
@endif
@endfor
</select>
<small class="form-text text-muted">※31日が無い月は自動的に末日になります。
<br>※レポートを受信する頻度が「毎週」の場合は毎週”月曜日”になります。</small>
</div>

<div class="form-group">
<label for="send_flag" class="d-block">前の期間との比較</label>
<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="comparison_flag" id="comparison_flag_true" value="1" checked>
<label class="form-check-label not-must" for="comparison_flag_true">する</label>
</div>
<!-- <div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="comparison_flag" id="comparison_flag_false" value="0" <?php if($add_sites->comparison_flag == 0) echo 'checked' ?> disabled>
<label class="form-check-label not-must" for="comparison_flag_false">しない<small>※準備中</small></label>
</div> -->
</div>

<input type="hidden" name="comparison_flag" value="0">

<button type="submit" class="btn btn-primary mt-4">変更する</button>
<a class="btn btn-sm btn-outline-secondary mt-4 ml-3" href="{{ route('dashboard') }}">ダッシュボードへ戻る</a>
</form>
</div>
</section>
@endsection
