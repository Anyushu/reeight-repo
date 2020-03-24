@section('title', 'アカウント情報')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<!-- $add_sites -->
<!-- $user -->
<section class="wrap">
<div class="container">
<div class="form-signin">
<h5 class="font-weight-bold mb-3">
<span>アカウント概要</span>
<a href="{{ route('edit') }}" class="float-right font-weight-normal h6 text-primary"><i class="fas fa-user-edit mr-1"></i>編集する</a>
</h5>
<table class="table">
<tbody>
<tr>
<th>法人・組織名</th>
<td class="text-right">{{ $user->company }}</td>
</tr>
<tr>
<th>お名前</th>
<td class="text-right">{{ $user->name }}</td>
</tr>
<tr>
<th>メールアドレス</th>
<td class="text-right">{{ $user->email }}</td>
</tr>
<tr>
<th>電話番号</th>
<td class="text-right">{{ $user->tel }}</td>
</tr>
<tr>
<th>パスワード</th>
<td class="text-right"><a href="{{ route('password.request') }}"><i class="fas fa-key mr-1"></i>再設定する</a></td>
</tr>
<tr>
<th>登録済みカード</th>
<td class="text-right"><a href="{{ route('edit.cards') }}"><i class="far fa-edit mr-1"></i>編集する</a></td>
</tr>
<tr>
<th>登録サイト数</th>
<td class="text-right">{{ $add_sites }} サイト</td>
</tr>
</tbody>
</table>
<div class="text-right">
<a class="btn btn-sm btn-block btn-outline-secondary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt mr-1"></i>{{ __('Logout') }}</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</div>
{{-- <div class="text-right mt-2">
<a class="text-secondary" href="{{ route('unsubscribe.form') }}">
<small><i class="fas fa-door-open mr-1"></i>退会する</small>
</a>
</div> --}}
</div>
</div>
</section>
@endsection