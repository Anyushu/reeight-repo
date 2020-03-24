@push('scripts')
<script src="{{ mix('/js/seo.js') }}"></script>
@endpush

@push('style')
<style media="screen">
body {
  background-color: #fafafa;
}
</style>
@endpush

@section('title', 'SEO解析 - '.$name)

@extends('layouts.app')

@include('layouts.head')
@include('layouts.header')
@include('layouts.footer')

@include('layouts.analysis')