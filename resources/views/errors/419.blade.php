@section('title', 'このページは有効期限切れです')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container pt-5 text-center">
<a class="btn btn-primary" href="{{ route('dashboard') }}">サイト一覧に戻る</a>
</div>
</section>
@endsection