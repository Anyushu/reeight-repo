@section('title', 'カード情報')
@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">
<table class="table">
<thead>
<tr>
<th scope="col"></th>
<th scope="col"></th>
<th scope="col">カード番号(下4桁)</th>
<th scope="col">カード名義</th>
<th scope="col">有効期限</th>
</tr>
</thead>
<tbody>
@foreach ($card as $key => $val)
@php
$nums = '****-****-****-'.substr(\Crypt::decryptString($val->numbers), -4);
$holder = $val->month.'/'.$val->year;
@endphp
<tr>
<th>
<div class="form-check">
<input class="form-check-input" name="cards" type="checkbox" value="{{ $val->id }}">
<label class="form-check-label"></label>
</div>
</th>
<th scope="row">{{ $key+1 }}</th>
<td>{{ $nums }}</td>
<td>{{ $val->holder }}</td>
<td>{{ $holder }}</td>
</tr>
@endforeach
</tbody>
</table>
<div class="mt-3">
<a href="#payment-form" class="text-primary float-left" data-toggle="modal" data-target="#payment-form"><i class="fas fa-plus mr-1"></i>カードを追加する</a>
<button style="display:none" id="cards-delete" type="button" class="btn btn-link text-danger float-right p-0"><i class="fas fa-minus mr-1"></i>消去する</button>
</div>
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

<script>
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

$('input[name=cards]').change(function() {
  if ($('input[name=cards]').is(':checked')) {
    $('#cards-delete').css('display', 'block');
  } else {
    $('#cards-delete').css('display', 'none');
  }
});

$('#cards-delete').on('click', function() {
  const csrf = $('meta[name="csrf-token"]').attr('content');
  let vals = $('input[name=cards]:checked').map(function() {
    return $(this).val();
  }).get();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrf
    }
  });
  $.ajax({
    url: "{{ route('edit.cards-ajax') }}",
    type: 'post',
    datatype: 'json',
    data: {
      cards: vals
    }
  }).done(function(data) {
    location.reload();
  });
});
</script>
@endsection