@section('title', '登録サイト編集')

@extends('layouts.app')
@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@section('content')

@php
$site = $add_sites;
if ($add_sites->logo_path != '') {
    $logo = '/storage/logos/'.$add_sites->logo_path;
} else {
    $logo = '';
}
@endphp
<section class="wrap">
<div class="container">
<form class="form form-signin" method="post" action="{{ route('sites-update', $site->id) }}" enctype="multipart/form-data">
@csrf
<div class="form-group">
<label for="site_name">サイトの名前</label>
<input type="text" name="site_name" class="form-control" id="site_name" value="{{ $site->site_name }}">
</div>
<div class="form-group">
<label for="genre">サイトのジャンル</label>
<select id="genre" class="form-control" name="genre" required>
@foreach ($categories as $key_c => $category)
@if($site->category == $key_c+1)
<option value="{{ ($key_c+1) }}" selected>{{ $category->cat }}</option>
@else
<option value="{{ ($key_c+1) }}">{{ $category->cat }}</option>
@endif
@endforeach
</select>
</div>
<div class="form-group">
<label for="industries">サイトの業種</label>
<select id="industries" class="form-control" name="industries" required>
@foreach ($industries as $key_i => $industry)
@if($site->industry == $key_i+1)
<option value="{{ ($key_i+1) }}" selected>{{ $industry->name }}</option>
@else
<option value="{{ ($key_i+1) }}">{{ $industry->name }}</option>
@endif
@endforeach
</select>
</div>
<!-- <div class="form-group">
<label for="exampleFormControlSelect2">ご契約プラン</label>
</div> -->
<div class="form-group">
<label for="image_file">サイトロゴ</label>
<input type="file" name="image_file" class="form-control-file" id="image_file">
</div>
@if($logo != '')
<img src="{{ $logo }}" class="my-1 logo_now">
<br>
<small class="my-1">現在登録中のロゴ</small>
<br>
@else
<small class="my-1">サイトロゴは現在登録されていません</small>
<br>
@endif
<a class="btn btn-sm btn-outline-secondary mt-4 mr-3" href="{{ route('dashboard') }}">キャンセル</a>
<button type="submit" class="btn btn-primary mt-4">変更する</button>
</form>
</div>
</section>
@endsection
