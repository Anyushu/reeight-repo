@section('title', 'プラン変更申請')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')
@section('content')
<section class="wrap">
<div class="container">

<form class="register-form form-signin" method="post" action="{{ route('change-plan.confirm') }}">
@csrf

<div class="form-group mb-4">
<label class="font-weight-bold" for="name">{{ __('Name') }}</label>
<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" placeholder="山田 太郎" required autocomplete="name">
@error('name')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>

<div class="form-group mb-4">
<label class="font-weight-bold" for="email">{{ __('E-Mail Address') }}</label>
<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" placeholder="info@example.com" required autocomplete="email">
@error('email')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
<small class="form-text text-muted">※こちらのメールアドレスにプラン変更確認のメールが送信されます。</small>
</div>

<div class="form-group mb-4">
<label class="font-weight-bold" for="site">対象サイト</label>
<input id="site" type="text" class="form-control @error('site') is-invalid @enderror" name="site_name" value="{{ $site_name }}" required autocomplete="site">
@error('site')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>

<div class="form-group mb-4">
<label class="font-weight-bold" for="plan">変更したいプラン／解約</label>
<select class="form-control" id="plan" name="plan_name">
@foreach ($plan as $key => $val)
@php
if ($key == 6 || $key == 7) {
    continue;
}
@endphp
@if ($plan[($site_plan - 1)]->name != $val->name)
<option value="{{ $val->name }}">{{ $val->name }}</option>
@endif
@endforeach
<option value="解約">解約</option>
</select>
<small class="form-text text-muted">現在ご契約のプラン：{{ $plan[($site_plan - 1)]->name }}</small>
</div>

<div class="card">
<div class="card-body">
<p>下記の注意事項に同意いただける方のみチェックを入れ次へお進みください。
<br>
<br><b>【プラン変更】</b>
<br>・プラン変更の完了には2営業日いただいております。
<br>
<br><b>【解約/退会に関して】</b>
<br>・クレジットカード決済の方
<br>　ご登録サイトの解約/KAGARIの退会には2営業日いただいております。
<br>　月をまたぐ解約/退会にはご注意ください。
<br>
<br>　解約/退会完了が翌月を越えた場合は自動的に課金されますので、予めご了承ください。
<br>　例：申請 1月31(金）〜 完了 2月3日（月）の場合は自動課金
<br>（※土日祝 ・その他休業日は対応不可）
<br>
<br>　営業日はこちらから（<a href="https://kagari.ai/faq/others/2308/" target="_blank">https://kagari.ai/faq/others/2308/</a>）
<br>
<br>・請求書払いの方
<br>　解約/退会いただいた月の翌月の5日迄にご登録メールアドレスへ請求書をお送りいたします。</p>
<div class="form-group form-check m-0">
<input type="checkbox" class="form-check-input" value="1" name="agreement" id="agreement" required>
<label class="form-check-label" for="agreement">上記の注意事項に同意する</label>
</div>
</div>
</div>

<input type="hidden" name="site_id" value="{{ $site_id }}">
<input type="hidden" name="site_url" value="{{ $site_url }}">
<input type="hidden" name="now" value="{{ $plan[($site_plan - 1)]->name }}">

<div class="form-group mb-0 text-center mt-5">
<button type="submit" class="btn btn-primary">確認する</button>
</div>

</form>

</div>
</section>
@endsection