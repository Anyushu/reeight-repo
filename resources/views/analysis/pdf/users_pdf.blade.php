@if(count($ga_result_user[0]) == 3 && count($ga_result_user[1]) == 3)
@php
// ユーザー数
$new_user_data = $ga_result_user[0][0]['New Visitor'][0];
$re_user_data = $ga_result_user[0][0]['Returning Visitor'][0];
$old_new_user_data = $ga_result_user[0][0]['New Visitor'][1];
$old_re_user_data = $ga_result_user[0][0]['Returning Visitor'][1];
$new_users = round($new_user_data / ($new_user_data + $re_user_data) * 100, 2);
$re_users = round($re_user_data / ($new_user_data + $re_user_data) * 100, 2);
$old_new_users = round($old_new_user_data / ($old_new_user_data + $old_re_user_data) * 100, 2);
$old_re_users = round($old_re_user_data / ($old_new_user_data + $old_re_user_data) * 100, 2);
$comp_new_users = round($new_users - $old_new_users, 2);
$comp_re_users = round($re_users - $old_re_users, 2);
// 男女比
if (!isset($ga_result_user[0][2]['male'])) {
  $female = $ga_result_user[0][2]['female'][0];
  $male = 0;
  $old_female = $ga_result_user[0][2]['female'][1];
  $old_male = 0;
  $female_str = round($female / ($female + $male) * 100, 2);
  $male_str = 0;
  $old_female_str = round($old_female / ($old_female + $old_male) * 100, 2);
  $old_male_str = 0;
  $comp_female = round($female_str - $old_female_str, 2);
  $comp_male = 0;
}elseif (!isset($ga_result_user[0][2]['female'])){
  $female = 0;
  $male = $ga_result_user[0][2]['male'][0];
  $old_female = 0;
  $old_male = $ga_result_user[0][2]['male'][1];
  $female_str = 0;
  $male_str = round($male / ($female + $male) * 100, 2);
  $old_female_str = 0;
  $old_male_str = round($old_male / ($old_female + $old_male) * 100, 2);
  $comp_female = 0;
  $comp_male = round($male_str - $old_male_str, 2);
}else{
  $female = $ga_result_user[0][2]['female'][0];
  $male = $ga_result_user[0][2]['male'][0];
  $old_female = $ga_result_user[0][2]['female'][1];
  $old_male = $ga_result_user[0][2]['male'][1];
  $female_str = round($female / ($female + $male) * 100, 2);
  $male_str = round($male / ($female + $male) * 100, 2);
  $old_female_str = round($old_female / ($old_female + $old_male) * 100, 2);
  $old_male_str = round($old_male / ($old_female + $old_male) * 100, 2);
  $comp_female = round($female_str - $old_female_str, 2);
  $comp_male = round($male_str - $old_male_str, 2);
}

// デバイス
$mobile = $ga_result_user[0][1]['mobile'][0];
$desktop = $ga_result_user[0][1]['desktop'][0];
$tablet = $ga_result_user[0][1]['tablet'][0];
$old_mobile = $ga_result_user[0][1]['mobile'][1];
$old_desktop = $ga_result_user[0][1]['desktop'][1];
$old_tablet = $ga_result_user[0][1]['tablet'][1];
$mobile_str = round($mobile / ($mobile + $desktop + $tablet) * 100, 2);
$desktop_str = round($desktop / ($mobile + $desktop + $tablet) * 100, 2);
$tablet_str = round($tablet / ($mobile + $desktop + $tablet) * 100, 2);
$old_mobile_str = round($old_mobile / ($old_mobile + $old_desktop + $old_tablet) * 100, 2);
$old_desktop_str = round($old_desktop / ($old_mobile + $old_desktop + $old_tablet) * 100, 2);
$old_tablet_str = round($old_tablet / ($old_mobile + $old_desktop + $old_tablet) * 100, 2);
$comp_mobile = round($mobile_str - $old_mobile_str, 2);
$comp_desktop = round($desktop_str - $old_desktop_str, 2);
$comp_tablet = round($tablet_str - $old_tablet_str, 2);
// 年齢
$age = $ga_result_user[1][2];
$age_max = $age[0][1];
$sort = [];
foreach ($age as $key => $value) {
    $sort[$key] = $value[0];
}
array_multisort($sort, SORT_ASC, $age);
// 国
$country = $ga_result_user[1][0];
// 地域
$area = $ga_result_user[1][1];
@endphp
@section('content_users')
<section class="reports">
<div class="container">
<div class="row mx-0 mb-3">
<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-red">
<img src="{{ asset('/img/fa-user-plus_red_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">新規ユーザー</h4>
<div class="text-center my-4">
<canvas class="m-auto" id="doughnut-chart-1" width="150" height="150"></canvas>
</div>
<div class="row mx-0">
<div class="col text-center">
<p class="opacity-color-red-2 m-0"><img src="{{ asset('/img/fa-user-plus_blue_24.png') }}"></p>
<p class="mb-1">新規ユーザー</p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $new_users }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_new_users >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_new_users }}%</span>
</p>
</div>
<div class="col text-center">
<p class="opacity-color-red m-0"><img src="{{ asset('/img/fa-user_purple_24.png') }}"></p>
<p class="mb-1">既存ユーザー</p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $re_users }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_re_users >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_re_users }}%</span>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-orange">
<img src="{{ asset('/img/fa-venus-mars_orange_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">性別</h4>
<div class="text-center my-4">
<canvas class="m-auto" id="doughnut-chart-2" width="150" height="150"></canvas>
</div>
<div class="row mx-0">
<div class="col text-center">
<p class="opacity-color-blue m-0"><img src="{{ asset('/img/fa-male_blue_24.png') }}"></p>
<p class="mb-1">男性</p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $male_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_male >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_male }}%</span>
</p>
</div>
<div class="col text-center">
<p class="opacity-color-red-2 m-0"><img src="{{ asset('/img/fa-female-red_24.png') }}"></p>
<p class="mb-1">女性</p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $female_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_female >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_female }}%</span>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue">
<img src="{{ asset('/img/fa-mobile-alt_blue_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">デバイス</h4>
<div class="text-center my-4">
<canvas class="m-auto" id="doughnut-chart-3" width="150" height="150"></canvas>
</div>
<div class="row mx-0">
<div class="col text-center">
<p class="m-0"><img src="{{ asset('/img/fa-mobile-alt_blue_24.png') }}"></p>
<p class="mb-1"><small>モバイル</small></p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $mobile_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_mobile >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span></span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_mobile }}%</span>
</p>
</div>
<div class="col text-center">
<p class="m-0"><img src="{{ asset('/img/fa-desktop_purple_24.png') }}"></p>
<p class="mb-1"><small>PC</small></p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $desktop_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_desktop >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_desktop }}%</span>
</p>
</div>
<div class="col text-center">
<p class="m-0"><img src="{{ asset('/img/fa-tablet-alt_red-2_24.png') }}"></p>
<p class="mb-1"><small>タブレット</small></p>
<p class="m-0 h5 font-weight-bold text-dark">{{ $tablet_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
@if($comp_tablet >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp_tablet }}%</span>
</p>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="row mx-0 mb-3">

<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue">
<img src="{{ asset('/img/fa-user_blue_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">年齢</h4>
@foreach ($age as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
<div class="progress-bar ka-bg-blue" style="width:{{ ($value[1]/$age_max*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$age_max*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<p class="mb-3 text-right">
@php
if ($age[$key][2] != 0) {
  $comp = round(($value[1] / (int)$age[$key][2] - 1) * 100, 2);
} else {
  $comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
@endforeach
</div>
</div>
</div>

<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple-2">
<img src="{{ asset('/img/fa-globe-asia_purple-2_16.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">国</h4>
@foreach ($country as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-purple-2" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-purple-2" style="width:{{ ($value[1]/$country[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$country[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="mb-3 text-right">
@php
if ($country[$key][2] != 0) {
  $comp = round(($value[1] / (int)$country[$key][2] - 1) * 100, 2);
} else {
  $comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
@endforeach
</div>
</div>
</div>

<div class="col-4">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple">
<img src="{{ asset('/img/fa-map-marker-alt.png') }}" alt="">
</span>
<h4 class="h6 text-dark mt-3">地域</h4>
@foreach ($area as $key => $value)
<p class="mb-0 overflow-hidden"><span class="float-left">{{ $value[0] }}</span><span class="float-right font-weight-bold h5 text-dark">{{ number_format($value[1]) }}</span></p>
<div class="progress">
@if ($key == 0)
<div class="progress-bar ka-bg-purple" style="width:100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
<div class="progress-bar ka-bg-purple" style="width:{{ ($value[1]/$area[0][1]*100) }}%" role="progressbar" aria-valuenow="{{ ($value[1]/$area[0][1]*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
<p class="mb-3 text-right">
@php
if ($area[$key][2] != 0) {
  $comp = round(($value[1] / (int)$area[$key][2] - 1) * 100, 2);
} else {
  $comp = 0;
}
@endphp
@if($comp >= 0)
<span class="opacity-color-green"><span class="mr-1">↑</span>
@else
<span class="opacity-color-red"><span class="mr-1">↓</span>
@endif
{{ $comp }}%</span>
</p>
@endforeach
</div>
</div>
</div>

</div>

<div class="col-12">
<div id="comment" class="card">
<div class="card-body">
<div class="row">
<div class="col-1 text-center">
<span class="opacity-item opacity-bg-orange">
<img src="{{ asset('/img/fa-comment-dots_orange_16.png') }}" alt="">
</span>
</div>
<div class="col-11">
<h3 class="font-weight-bold h5 mt-2">ユーザー属性の総評</h3>
<p id="comment_user">
・{{ $new_users }}% が初めてサイトに訪れているユーザーです。
<br>
・{{ $age[0][0] }}歳のユーザーが多く、@if($female_str > 50)女性@else男性@endifのユーザーが大半を占めています。
<br>
・{{ $country[0][0] }}、{{ $area[0][0] }}の地域からの流入が多くなっています。
</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<script>
let don = $('#doughnut-chart-1');
var userData = [{{ $new_user_data }}, {{ $re_user_data }}];
let myChart1 = new Chart(don, {
  type: 'doughnut',
  data: {
    labels: [
      '新規ユーザー', '既存ユーザー'
    ],
    datasets: [
      {
        data: userData,
        backgroundColor: ['#007AFF', '#9561e2']
      }
    ]
  },
  options: {
    legend: {
      display: false
    },
    responsive: false,
    cutoutPercentage: 85,
    animation: false
  }
});
let don2 = $('#doughnut-chart-2');
var userData = [{{ $male_str }}, {{ $female_str }}];
let myChart2 = new Chart(don2, {
  type: 'doughnut',
  data: {
    labels: [
      '男性', '女性'
    ],
    datasets: [
      {
        data: userData,
        backgroundColor: ['#007AFF', '#FF2D55']
      }
    ]
  },
  options: {
    legend: {
      display: false
    },
    responsive: false,
    cutoutPercentage: 85,
    animation: false
  }
});
let don3 = $('#doughnut-chart-3');
var userData = [{{ $mobile }}, {{ $desktop }}, {{ $tablet }}];
let myChart3 = new Chart(don3, {
  type: 'doughnut',
  data: {
    labels: [
      "モバイル", "PC", "タブレット"
    ],
    datasets: [
      {
        data: userData,
        backgroundColor: ['#007AFF', '#9561e2', '#FF2D55']
      }
    ]
  },
  options: {
    legend: {
      display: false
    },
    responsive: false,
    cutoutPercentage: 85,
    animation: false
  }
});
</script>
@endsection
@else
@section('content_users')
<section class="reports">
<div class="container">
<div class="col-12">
<div class="alert alert-warning">
<p class="m-0">ユーザーの属性情報が取得できませんでした。</p>
</div>
</div>
</div>
</section>
@endsection
@endif
