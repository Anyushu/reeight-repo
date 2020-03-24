@section('title', '退会フォーム確認')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">

<form class="register-form form-signin" method="post" action="{{ route('unsubscribe.send') }}">
@csrf

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="name">{{ __('Name') }}</label>
<p class="m-0">{{ $inputs['name'] }}</p>
<input type="hidden" name="name" value="{{ $inputs['name'] }}">
</div>

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="email">{{ __('E-Mail Address') }}</label>
<p class="m-0">{{ $inputs['email'] }}</p>
<input type="hidden" name="email" value="{{ $inputs['email'] }}">
</div>

<div class="form-group mb-4">
<label class="not-must font-weight-bold" for="cause">退会理由</label>
<p class="">
@php
$cause_input = '';
$cause = $inputs['cause'];
@endphp
@foreach ($cause as $key => $val)
@php
$cause_input .= $val.'、';
@endphp
・{{ $val }}<br>
@endforeach
@php
$cause_input = rtrim($cause_input, '、')
@endphp
</p>
<p class="m-0">{{ $inputs['enquete'] }}</p>
<input type="hidden" name="enquete" value="{{ $inputs['enquete'] }}">
<input type="hidden" name="cause" value="{{ $cause_input }}">
</div>

<div class="form-group mb-0 text-center mt-5">
<button type="button" class="btn btn-sm btn-outline-secondary mr-2" onclick="history.back()">戻る</button>
<button type="submit" class="btn btn-primary">送信する</button>
</div>

</form>

</div>
</section>
@endsection
