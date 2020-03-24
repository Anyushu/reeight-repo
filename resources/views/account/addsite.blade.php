@section('title', 'サイト追加')

@extends('layouts.app')

@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')
<section class="wrap">
<div class="container">

<h2 class="text-muted h5 font-weight-bold mb-4">GoogleAnalytics登録済みのサイトを選択</h2>

<p class="m-0">
<small>お探しのサイトが無い場合はGoogleAnalyticsにて追加してください。</small>
<br><a href="https://seo.kagari.ai/blog/google-analytics/" target="_blank">
<small><i class="fas fa-link mr-1"></i>GoogleAnalyticsの設定方法</small>
</a>
</p>

<div class="mt-3">
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text"><i class="fas fa-search"></i></div>
</div>
<input id="ga-search" class="form-control search" type="text" placeholder="キーワードで絞り込み" width="500">
</div>
</div>

<div class="ga-properties mt-4" data-spy="scroll" data-offset="0">
<div id="ga-accounts" class="sites-list list-group list-group-flush list">
@foreach ($properties as $key => $property)
<a class="accounts list-group-item list-group-item-action text-body account-name" href="#collapse-{{ $key }}" data-toggle="collapse" aria-expanded="false" aria-controls="collapse-{{ $key }}">
{{ $property['account_name'] }}
</a>
<div id="collapse-{{ $key }}" class="collapse list-group list-group-flush pl-4 property-name">
@foreach ($property['data'] as $key_id => $prop)
@if($key_id != null)
<a href="#"
class="list-group-item list-group-item-action text-body addsite-modal"
data-toggle="modal"
data-target="#addsite-form"
data-name="{{ $property['account_name'] }}"
data-id="{{ $key_id }}"
data-url="{{ $prop['url'] }}"
data-property="{{ $prop['name'] }}"><i class="fas fa-globe-asia mr-3"></i>{{ $prop['name'] }}</a>
@else
<del class="list-group-item text-body addsite-modal">{{ $prop['name'] }}<small>※このサイトは追加できません。</small></del>
@endif
@endforeach
</div>
@endforeach
</div>
</div>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="addsite-form" tabindex="-1" role="dialog" aria-labelledby="addsite-form-label" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title font-weight-bold" id="addsite-form-label">Modal title</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form id="addsite-form" class="form register-form" method="post" action="{{ route('trial') }}" enctype="multipart/form-data">
@csrf
<div class="form-group mb-4">
<label for="site-name">サイト名</label>
<input id="site-name" type="text" class="form-control" name="site-name" value="{{ old('site-name') }}" required autofocus>
</div>
<div class="form-group mb-4">
<label for="industries">サイトの業種を選択</label>
<select id="industries" class="form-control" name="industries" required>
@foreach ($industries as $key_i => $industry)
<option value="{{ ($key_i+1) }}">{{ $industry->name }}</option>
@endforeach
</select>
</div>
<div class="form-group mb-4">
<label for="genre">サイトのカテゴリーを選択</label>
<select id="genre" class="form-control" name="genre" required>
@foreach ($categories as $key_c => $category)
<option value="{{ ($key_c+1) }}">{{ $category->cat }}</option>
@endforeach
</select>
</div>
<div id="images-input" class="form-group mb-4">
<label class="not-must" for="images">レポートに表示するロゴ<small class="ml-2">※3MBまで、.jpg,.pngのみ</small></label>
<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
<input type="file" class="form-control-file" accept="image/*" name="image_file" id="images">
</div>
<input id="view-id" type="hidden" name="view-id" value="">
<input id="site-url" type="hidden" name="site-url" value="">
<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
<button id="formsubmit" type="submit" class="btn btn-primary">このサイトを追加する</button>
</form>
</div>
<div class="modal-footer">
</div>
</div>
</div>
</div>
<script>
$('input#images').on('change', function() {
    $('#images-input').children('#images-error').remove();
    $('#formsubmit').removeAttr('disabled');
    let file = $(this).prop('files')[0];
    let size = file.size;
    if (size > 3145728) {
      $('#images-input').append('<p id="images-error" class="m-0"><small class="text-danger">ファイルサイズが大きすぎます</small></p>');
      $('#formsubmit').attr('disabled', 'disabled');
    }
});
</script>
@endsection
