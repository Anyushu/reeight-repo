@section('title', '支払い設定')

@extends('layouts.app')

@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')

@php
if ($plan_id == 1 || $plan_id == 2) {
  $str = '年';
} elseif ($plan_id == 3 || $plan_id == 4) {
  $str = '半年';
} else {
  $str = '月';
}
@endphp

<section class="wrap">
<div class="container">

<h2 class="text-center text-muted h5 font-weight-bold mb-5">お支払い方法を選択してください</h2>
<form id="mainform" class="payment-form" action="{{ route('payment-done') }}" method="post" name="payment_kagari">
@csrf
<div class="form-group row align-items-start">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">支払い方法</legend>
<div class="col-7 mr-auto">
<div class="form-check">
<input class="form-check-input" type="radio" name="payment_methods" id="inlineRadio1" value="1" onclick="changeForm();" checked>
<label id="credit_card" class="form-check-label" for="inlineRadio1">クレジットカード</label>
</div>
<div class="form-check">
<input class="form-check-input" type="radio" name="payment_methods" id="inlineRadio2" value="2" onclick="changeForm();">
<label id="billing_sheet" class="form-check-label" for="inlineRadio2">請求書支払い（法人のみ）</label>
</div>
</div>
</div>
<div id="card_info">
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">ご登録クレジットカード</legend>
<div class="col-7 mr-auto row m-0">
<div class="col-8 p-0">
<select id="cn-select" class="form-control" name="cards">
<option value="1">---</option>
@foreach ($card as $key => $val)
@php
$nums = 'カード番号下4桁（'.substr(\Crypt::decryptString($val->numbers), -4).'）';
$holder = explode(' ', $val->holder);
@endphp
<option
value="{{ $val->id }}"
data-cn="{{ \Crypt::decryptString($val->numbers) }}"
data-ln="{{ $holder[0] }}"
data-fn="{{ $holder[1] }}"
data-month="{{ $val->month }}"
data-year="{{ $val->year }}"
>{{ $nums }}</option>
@endforeach
</select>
</div>
<div class="col-4">
<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#payment-form">カード追加</button>
</div>
</div>
</div>
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">セキュリティコード/CVV</legend>
<div class="col-7 mr-auto">
<div class="col-3 m-0 p-0">
<input id="cvv" class="form-control" type="text" name="cvv" value="" placeholder="123">
</div>
<small class="form-text text-muted">※カードの裏面にある数字の下3桁を半角数字で入力ください</small>
</div>
</div>
</div>
<div id="bill_info" style="display:none;">
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">会社名</legend>
<div class="col-7 mr-auto">
<input type="text" class="form-control" id="com_name" name="com_name" placeholder="株式会社KAGARI">
</div>
</div>
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">宛名</legend>
<div class="col-7 mr-auto">
<input type="text" class="form-control" id="per_name" name="per_name" placeholder="">
</div>
</div>
</div>
<div class="mt-5 col-11 mx-auto">
<table class="table">
<tbody>
<tr>
<th>ご登録サイト</th>
<td>{{ $site_name }}（<a href="{{ $site_url }}" target="_blank">{{ $site_url }}</a>）</td>
</tr>
<tr>
<th>ご契約プラン</th>
<td>{{ $plan_name }}</td>
</tr>
<tr>
<th>お支払い金額</th>
<td>{{ $plan_price }} 円（税込）/ {{ $str }}</td>
</tr>
</tbody>
</table>
</div>

<div class="card">
<div class="card-body">
<p>下記の注意事項に同意いただける方のみチェックを入れ次へお進みください。
<br>
<br><b>【プラン変更に関して】</b>
<br>・プラン変更の完了には2営業日いただいております。
<br>
<br><b>【解約/退会に関して】</b>
<br>・クレジットカード決済の方
<br>　ご登録サイトの解約/KAGARIの退会には2営業日いただいております。
<br>　希望日の2営業日前に申請をお願いします。
<br>　解約/退会完了が翌月を越えた場合は自動的に課金されますので、予めご了承ください。
<br>
<br>・請求書払いの方
<br>　解約/退会いただいた月の翌月の5日迄にご登録メールアドレスへ請求書をお送りいたします。</p>
<div class="form-group form-check m-0">
<input type="checkbox" class="form-check-input" value="1" name="agreement" id="agreement" required>
<label class="form-check-label" for="agreement">上記の注意事項に同意する</label>
</div>
</div>
</div>

<div class="mt-5 text-center">
<button id="payment-submit" type="button" class="btn btn-primary">上記の内容で登録する</button>
</div>

<input type="hidden" name="site_id" value="{{ $site_id }}">
<input type="hidden" name="site_name" value="{{ $site_name }}">
<input type="hidden" name="site_url" value="{{ $site_url }}">
<input type="hidden" name="plan_id" value="{{ $plan_id }}">
<input type="hidden" name="plan_name" value="{{ $plan_name }}">
<input type="hidden" name="plan_price" value="{{ $plan_price }}">
<input type="hidden" name="plan_period" value="{{ $plan_period }}">

<input id="tkn" type="hidden" name="tkn" value="">
<input id="pn" type="hidden" name="pn" value="{{ $user->tel }}">
<input id="em" type="hidden" name="em" value="{{ $user->email }}">
<input id="aid" type="hidden" name="aid" value="117213">
<input id="iid" type="hidden" name="iid" value="kagari_report_0{{ $plan_id }}">
<input id="main_cn" type="hidden" name="cn" value="">
<input id="main_ed_month" type="hidden" name="ed_month" value="">
<input id="main_ed_year" type="hidden" name="ed_year" value="">
<input id="main_ln" type="hidden" name="ln" value="">
<input id="main_fn" type="hidden" name="fn" value="">

<input type="submit" style="display:none" name="submitBtn">

</form>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="payment-form" tabindex="-1" role="dialog" aria-labelledby="credit-card-regi" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title font-weight-bold" id="credit-card-regi">クレジットカード追加</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div id="card-form" class="modal-body">
<div class="form-group row align-items-start mt-4">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">カード番号</legend>
<div class="col-7 mr-auto">
<input type="text" class="form-control @error('cn') is-invalid @enderror" id="cardnum" name="cn" placeholder="0000111122223333" required>
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
<input id="ed_month" class="form-control @error('ed_month') is-invalid @enderror" type="text" name="ed_month" value="" placeholder="01" required>
</div>
<div class="col-3 m-0">
<input id="ed_year" class="form-control @error('ed_year') is-invalid @enderror" type="text" name="ed_year" value="" placeholder="20" required>
</div>
</div>
</div>
</div>
<div class="form-group row align-items-start mt-5">
<legend class="ml-auto col-form-label col-3 pt-0 font-weight-bold">カード名義</legend>
<div class="col-7 mr-auto">
<div class="row">
<div class="col">
<input id="ln" class="form-control @error('ln') is-invalid @enderror" type="text" name="ln" value="" placeholder="性（例：KAGARI）" required>
</div>
<div class="col">
<input id="fn" class="form-control @error('fn') is-invalid @enderror" type="text" name="fn" value="" placeholder="名（例：TAROU）" required>
</div>
</div>
</div>
</div>
</div>
<div class="modal-footer">
<button id="addcard" type="submit" class="btn btn-primary">保存する</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
</div>
</div>
</div>
</div>

<script src="//credit.j-payment.co.jp/gateway/js/CPToken.js"></script>
<script>
// 支払い方法切り替え
function changeForm() {
  let radio = document.getElementsByName('payment_methods');
  if(radio[0].checked){
    document.getElementById('card_info').style.display = "";
    document.getElementById('bill_info').style.display = "none";
  } else if(radio[1].checked){
    document.getElementById('card_info').style.display = "none";
    document.getElementById('bill_info').style.display = "";
  }
}

$('#addcard').on('click', function() {
  const csrf = $('meta[name="csrf-token"]').attr('content');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrf
    }
  });
  $.ajax({
    url: "{{ route('add.card') }}",
    type: 'post',
    datatype: 'json',
    data: {
      cn: $('#cardnum').val(),
      ed_month: $('#ed_month').val(),
      ed_year: $('#ed_year').val(),
      ln: $('#ln').val(),
      fn: $('#fn').val()
    }
  }).done(function(data) {
    location.reload();
  });
});

$('#cn-select').on('change', function() {
  let data_cn = $('#cn-select option:selected').attr('data-cn');
  let data_ln = $('#cn-select option:selected').attr('data-ln');
  let data_fn = $('#cn-select option:selected').attr('data-fn');
  let data_month = $('#cn-select option:selected').attr('data-month');
  let data_year = $('#cn-select option:selected').attr('data-year');

  $('#main_cn').val(data_cn);
  $('#main_ln').val(data_ln);
  $('#main_fn').val(data_fn);
  $('#main_ed_month').val(data_month);
  $('#main_ed_year').val(data_year);
});

$('#payment-submit').on('click', function() {
  let radio = document.getElementsByName('payment_methods');
  if (radio[1].checked) {
    $('#mainform').submit();
  } else {
    doPurchase();
  }
});

// クレジット処理
function doPurchase() {
  CPToken.TokenCreate({
    aid: '117213',
    cn: $('#main_cn').val(),
    ed: String($('#main_ed_year').val()) + String($('#main_ed_month').val()),
    fn: $('#main_fn').val(),
    ln: $('#main_ln').val(),
    cvv: $('#cvv').val(),
    md: '10'
  }, execPurchase);
}

// コールバック関数
function execPurchase(resultCode, errMsg) {
  if (resultCode != "Success") {
    window.alert(errMsg);
  } else {
    // カード情報を消去
    $('#main_cn').val("");
    $('#main_ed_year').val("");
    $('#main_ed_month').val("");
    $('#main_fn').val("");
    $('#main_ln').val("");
    $('#cvv').val("");
    $('#cn-select').val(1);
    // スクリプトからフォームをsubmit
    $('input[name="submitBtn"]').click();
  }
}
</script>
@endsection
