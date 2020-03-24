@section('title', '新規会員登録')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')
@section('content')
<section class="wrap">
<div class="container">
<form class="register-form form-signin mt-5" method="POST" action="{{ route('register') }}">
@csrf
<div class="form-group mb-4">
<label for="company">法人組織名</label>
<input id="company" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="株式会社〇〇" name="company" value="{{ old('company') }}" required autofocus>
@error('name')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<div class="form-group mb-4">
<label class="not-must" for="post">部署名</label>
<input id="post" type="text" class="form-control @error('post') is-invalid @enderror" placeholder="マーケティング部" name="post" value="{{ old('post') }}">
@error('name')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<div class="form-group mb-4">
<label for="name">{{ __('Name') }}</label>
<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="山田 太郎" required autocomplete="name">
@error('name')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<div class="form-group mb-4">
<label for="tel">電話番号(半角・ハイフン無し)</label>
<input id="tel" type="tel" class="form-control @error('tel') is-invalid @enderror" placeholder="0123456789" name="tel" value="{{ old('tel') }}" required>
@error('tel')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<div class="form-group mb-4">
<label for="email">{{ __('E-Mail Address') }}</label>
<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="info@example.com" required autocomplete="email">
@error('email')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<div class="form-group mb-4">
<label for="password">{{ __('Password') }}(8文字以上)</label>
<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
@error('password')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<div class="form-group mb-4">
<label for="password-confirm">{{ __('Confirm Password') }}</label>
<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
</div>
<div class="form-group mb-0 text-center mt-5">
<button type="submit" class="btn btn-primary" onclick="gtag('event', 'conversion', {'send_to': 'AW-672878991/Lv9ECK-S9MUBEI-j7cAC'});">{{ __('Register') }}</button>
</div>
</form>
</div>
</section>
@endsection
