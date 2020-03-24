@section('title', '退会フォーム送信完了')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">
<h2 class="text-center font-weight-bold mb-4">退会申請を承りました。</h2>
<p class="text-center mb-0">退会手続き完了し次第、改めて担当よりご連絡致します。
<br><small>※退会の完了には2営業日いただいております。<br>営業日はこちら（https://kagari.ai/faq/others/2308/）からご確認ください。</small></p>
</div>
</section>
@endsection