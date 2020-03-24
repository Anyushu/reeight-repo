@section('title', '退会フォーム')

@extends('layouts.app')

@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@inject('uc', 'App\Http\Controllers\UserController')

@section('content')
<section class="wrap">
<div class="container">
<div class="form-signin">
{{ $uc->account_delete() }}
</div>
</div>
</section>
@endsection