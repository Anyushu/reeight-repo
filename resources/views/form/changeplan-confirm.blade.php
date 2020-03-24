@section('title', 'プラン変更申請確認')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">

<form class="register-form form-signin" method="post" action="{{ route('change-plan.send') }}">
@csrf

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="name">{{ __('Name') }}</label>
<p class="m-0">{{ $inputs['name'] }}</p>
</div>

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="email">{{ __('E-Mail Address') }}</label>
<p class="m-0">{{ $inputs['email'] }}</p>
</div>

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="site">対象サイト</label>
<p class="m-0">{{ $inputs['site_name'] }}</p>
</div>

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="plan_name">変更したいプラン／解約</label>
<p class="m-0">{{ $inputs['plan_name'] }}</p>
</div>

<input type="hidden" name="name" value="{{ $inputs['name'] }}">
<input type="hidden" name="email" value="{{ $inputs['email'] }}">
<input type="hidden" name="site_name" value="{{ $inputs['site_name'] }}">
<input type="hidden" name="site_url" value="{{ $inputs['site_url'] }}">
<input type="hidden" name="plan_name" value="{{ $inputs['plan_name'] }}">
<input type="hidden" name="now" value="{{ $inputs['now'] }}">
<input type="hidden" name="site_id" value="{{ $inputs['site_id'] }}">

<div class="form-group mb-0 text-center mt-5">
<button type="submit" class="btn btn-primary">送信する</button>
</div>

</form>

</div>
</section>
@endsection