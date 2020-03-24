@php
try {
  $pic_url = "https://www.googleapis.com/pagespeedonline/v2/runPagespeed?screenshot=true&strategy=desktop&url=".$url;
  $json = file_get_contents($pic_url);
  $data = json_decode($json, true);
  $screenshot = $data['screenshot']['data'];
  $screenshot = str_replace('_', '/', $screenshot);
  $screenshot = str_replace('-', '+', $screenshot);
} catch(\Exception $e) {
  $screenshot = '';
}

@endphp

@section('content_top')
<section class="top_page">
<div class="container">
<div class="logo">
@if ($logo != '')
<img src="{{ $logo }}">
@else
<h2 class="font-weight-bold">{{ $site_name }}</h2>
@endif
</div>
<h1 class="text-black font-weight-bold">アクセス解析レポート</h1>
<h2 class="py-4 font-weight-bold">ANARYTICS REPORT</h2>
@if($screenshot != '')
<img src="data:image/png;base64,{{ $screenshot }}">
@endif
<table class="table table-borderless">
<tbody>
<tr>
<th scope="row">期間</th>
<td>@php echo $start; @endphp 〜 @php echo $end; @endphp</td>
</tr>
<tr>
<th scope="row">サイト名</th>
<td>@php echo $site_name; @endphp</td>
</tr>
<tr>
<th scope="row">URL</th>
<td>@php echo $url; @endphp</td>
</tr>
</table>
</div>
</section>
@endsection
