@section('title', '退会申請フォーム')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">

<form class="register-form form-signin" method="post" action="{{ route('unsubscribe.confirm') }}">
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
<small class="form-text text-muted">※こちらのメールアドレスに退会確認のメールが送信されます。</small>
</div>

<div class="form-group mb-4">
<label class="font-weight-bold" for="cause">KAGARIは常にサービス向上に努めております。<br>よろしければ退会理由をお聞かせください（複数回答可）</label>
@error('cause')
<div class="alert alert-danger alert-dismissible fade show" role="alert">
こちらの内容は必須項目です。
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
@enderror
<div class="form-check">
<input class="form-check-input" type="checkbox" value="他社のツールに乗り換えるから" id="check1" name="cause[]">
<label class="form-check-label not-must" for="check1">他社のツールに乗り換えるから</label>
</div>
<div class="form-check">
<input class="form-check-input" type="checkbox" value="技術的な問題があった" id="check2" name="cause[]">
<label class="form-check-label not-must" for="check2">技術的な問題があった</label>
</div>
<div class="form-check">
<input class="form-check-input" type="checkbox" value="必要とする機能がなかった" id="check3" name="cause[]">
<label class="form-check-label not-must" for="check3">必要とする機能がなかった</label>
</div>
<div class="form-check">
<input class="form-check-input" type="checkbox" value="利用する機会がなくなった" id="check4" name="cause[]">
<label class="form-check-label not-must" for="check4">利用する機会がなくなった</label>
</div>
<div class="form-check">
<input class="form-check-input" type="checkbox" value="料金やプランに満足できなかった" id="check5" name="cause[]">
<label class="form-check-label not-must" for="check5">料金やプランに満足できなかった</label>
</div>
<div class="form-check mb-3">
<input class="form-check-input" type="checkbox" value="その他" id="check6" name="cause[]">
<label class="form-check-label not-must" for="check6">その他</label>
</div>
<textarea class="form-control" id="enquete" rows="5" name="enquete"></textarea>
</div>

<div class="form-group mb-0 text-center mt-5">
<button type="button" class="btn btn-sm btn-outline-secondary mr-2" onclick="history.back()">キャンセル</button>
<button type="submit" class="btn btn-primary">確認する</button>
</div>

</form>

</div>
</section>
@endsection
