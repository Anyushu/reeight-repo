@section('title', '支払い設定')

@extends('layouts.app')

@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<form class="payment-form" action="{{ route('payment-done') }}" method="post">
@csrf
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">カード番号</legend>
<div class="col-7 mr-auto">
<input type="text" class="form-control" id="cardnum" name="cn" placeholder="0000111122223333">
<small class="form-text text-muted">※半角英数字(空白なし)でご入力ください</small>
<div class="mt-2">
<img src="{{ asset('/img/credit-card.gif') }}" alt="クレジットカード" width="244">
</div>
</div>
</div>
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">有効期限(月/年)</legend>
<div class="col-7 mr-auto">
<div class="row">
<div class="col-3 m-0">
<input id="ed_month" class="form-control" type="text" name="ed_month" value="" placeholder="01">
</div>
<div class="col-3 m-0">
<input id="ed_year" class="form-control" type="text" name="ed_year" value="" placeholder="20">
</div>
</div>
</div>
</div>
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">カード名義</legend>
<div class="col-7 mr-auto">
<div class="row">
<div class="col">
<input id="ln" class="form-control" type="text" name="ln" value="" placeholder="性（例：KAGARI）">
</div>
<div class="col">
<input id="fn" class="form-control" type="text" name="fn" value="" placeholder="名（例：TAROU）">
</div>
</div>
</div>
</div>
</form>
@endsection