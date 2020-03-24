@section('title', 'ログイン')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')
@section('content')
<section class="wrap">
<div class="container">
<form class="mt-5 form-signin" method="POST" action="{{ route('login') }}">
@csrf
<!-- メール -->
<div class="form-group">
<label for="email">{{ __('E-Mail Address') }}</label>
<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="info@example.com" autofocus>
@error('email')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<!-- パスワード -->
<div class="form-group">
<label for="password">{{ __('Password') }}</label>
<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
@error('password')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div>
<!-- ログイン記憶 -->
<div class="form-group">
<div class="form-check">
<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
<label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
</div>
</div>
<!-- ログイン・忘れた -->
<div class="form-group mt-5 mb-0">
<p class="text-center mb-4"><button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button></p>
{{-- <p class="m-0 text-center"><a href="{{ route('register') }}">新規会員登録</a></p> --}}
@if (Route::has('password.request'))
<p class="m-0 text-center">
<a class="btn btn-link" href="{{ route('password.request') }}">
<small class="text-body">{{ __('Forgot Your Password?') }}</small>
</a>
</p>
@endif
</div>
</form>
</div>
</section>
@endsection
