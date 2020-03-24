@section('title', '登録情報編集')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')
@section('content')
<section class="wrap">
<div class="container">
<div class="register-form form-signin mt-5">
<div class="print-error-msg" style="display:none">
<div class="alert alert-danger" role="alert">
<ul class="m-0"></ul>
</div>
</div>
<div class="print-success-msg" style="display:none">
<div class="alert alert-success" role="alert">
</div>
</div>
@csrf
<div class="form-group mb-4">
<label for="company">法人組織名</label>
<input id="company" type="text" class="form-control @error('name') is-invalid @enderror" name="company" value="{{ $user->company }}" required autofocus>
</div>
<div class="form-group mb-4">
<label for="name">{{ __('Name') }}</label>
<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required>
</div>
<div class="form-group mb-4">
<label for="tel">電話番号(半角・ハイフン無し)</label>
<input id="tel" type="tel" class="form-control @error('tel') is-invalid @enderror" name="tel" value="{{ $user->tel }}" required>
</div>
<div class="form-group mb-4">
<label for="email">{{ __('E-Mail Address') }}</label>
<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required>
</div>
<div class="form-group mb-0 text-center mt-5">
<a href="{{ route('account') }}" class="btn btn-outline-secondary mr-2">戻る</a>
<button id="btn-account-edit" type="button" class="btn btn-primary">変更する</button>
</div>

<input type="hidden" name="user_id" value="{{ $user->id }}">
</div>
</div>
</section>
@endsection
