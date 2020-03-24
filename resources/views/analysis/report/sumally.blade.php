@php
// 現在のデータ
$users = $ga_result['sumally'][0][2];
$session = $ga_result['sumally'][0][0];
$pv = $ga_result['sumally'][0][6];
$ps = $ga_result['sumally'][0][1];
$session_time = $ga_result['sumally'][0][5];
$page_time = $ga_result['sumally'][0][3];
$exit_rate = $ga_result['sumally'][0][4];
$bounce_rate = $ga_result['sumally'][0][7];
$users_str = number_format($users);
$session_str = number_format($session);
$pv_str = number_format($pv);
$ps_str = round($ps, 2);
$session_time_str = round($session_time, 2);
$page_time_str = round($page_time, 2);
$exit_rate_str = round($exit_rate, 2);
$bounce_rate_str = round($bounce_rate, 2);
// 過去のデータ
$old_users = $ga_result['sumally'][1][2];
$old_session = $ga_result['sumally'][1][0];
$old_pv = $ga_result['sumally'][1][6];
$old_ps = $ga_result['sumally'][1][1];
$old_session_time = $ga_result['sumally'][ 1][5];
$old_page_time = $ga_result['sumally'][1][3];
$old_exit_rate = $ga_result['sumally'][1][4];
$old_bounce_rate = $ga_result['sumally'][1][7];
$old_users_str = number_format($old_users);
$old_session_str = number_format($old_session);
$old_pv_str = number_format($old_pv);
$old_ps_str = round($old_ps, 2);
$old_session_time_str = round($old_session_time, 2);
$old_page_time_str = round($old_page_time, 2);
$old_exit_rate_str = round($old_exit_rate, 2);
$old_bounce_rate_str = round($old_bounce_rate, 2);
// 比較のデータ
$comp_users = $ga_result['comp'][2];
$comp_session = $ga_result['comp'][0];
$comp_pv = $ga_result['comp'][6];
$comp_ps = $ga_result['comp'][1];
$comp_session_time = $ga_result['comp'][5];
$comp_page_time = $ga_result['comp'][3];
$comp_exit_rate = $ga_result['comp'][4];
$comp_bounce_rate = $ga_result['comp'][7];
// $ga_result['transitions']
@endphp
@section('content')
<section class="reports">
<div class="container">
<div class="col-12">
<div class="card mb-3">
<div class="card-body">
<div class="row align-items-center">
<div class="col-3 text-center">
<span class="opacity-item opacity-bg-blue">
<i class="fas fa-user opacity-color-blue"></i>
</span>
<h3 class="text-dark font-weight-bold h6 mt-3">ユーザー</h3>
<p class="m-0">
<small><i class="far fa-calendar-alt opacity-color-blue mr-1"></i>{{ $start }} ~ {{ $end }}</small>
</p>
<p class="m-0">
<small><i class="fas fa-arrows-alt-h opacity-color-red mr-1"></i>{{ $com_start }} ~ {{ $com_end }}</small>
</p>
</div>
<div class="col-9">
<canvas id="user-chart" class="line-chart" width="900" height="200"></canvas>
</div>
</div>
</div>
</div>
</div>

<div class="row mx-0 mb-3">
<div class="col">
<div class="card">
<div class="card-body">
<h3 class="font-weight-bold h5">過去3ヶ月の推移</h3>
<table class="table w-100">
<thead>
<tr>
<th>対象月</th>
@foreach ($ga_result['labels'] as $label)
<th>{{ $label }}</th>
@endforeach
</tr>
</thead>
<tbody>
@foreach ($ga_result['transitions'] as $key => $val)
<tr>
<td>{{ date('Y年m月', strtotime($key.'01')) }}</td>
@foreach ($val as $key => $vals)
<td>{{ $vals }}</td>
@endforeach
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>

<div class="row mx-0 mb-3">
<div class="col-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue">
<i class="fas fa-user opacity-color-blue"></i>
</span>
<h4 class="h6 text-dark mt-3">ユーザー数</h4>
<p class="h4 font-weight-bold text-dark">{{ $users_str }}</p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_users_str }}</span>
</p>
<p class="m-0">
@if($comp_users >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_users }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">期間中に１回以上のセッションを開始したユーザー数。</small>
</div>
</div>
</div>
<div class="col-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-orange">
<i class="fas fa-bolt opacity-color-orange"></i>
</span>
<h4 class="h6 text-dark mt-3">セッション数</h4>
<p class="h4 font-weight-bold text-dark">{{ $session_str }}</p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_session_str }}</span>
</p>
<p class="m-0">
@if($comp_session >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_session }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">ユーザーがサイトを訪問した回数。</small>
</div>
</div>
</div>
<div class="col-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-green">
<i class="fas fa-eye opacity-color-green"></i>
</span>
<h4 class="h6 text-dark mt-3">ページビュー数</h4>
<p class="h4 font-weight-bold text-dark">{{ $pv_str }}</p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_pv_str }}</span>
</p>
<p class="m-0">
@if($comp_pv >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_pv }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">ウェブサイト内の特定のページが開かれた回数。</small>
</div>
</div>
</div>
<div class="col-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-blue-2">
<i class="fas fa-pager opacity-color-blue-2"></i>
</span>
<h4 class="h6 text-dark mt-3">ページ/セッション</h4>
<p class="h4 font-weight-bold text-dark">{{ $ps_str }}</p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_ps_str }}</span>
</p>
<p class="m-0">
@if($comp_ps >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_ps }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">1セッション中に閲覧されたページビュー数の平均。</small>
</div>
</div>
</div>
<div class="col-3 mt-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-red-2">
<i class="fa fa-clock opacity-color-red-2"></i>
</span>
<h4 class="h6 text-dark mt-3">平均セッション時間</h4>
<p class="h4 font-weight-bold text-dark">{{ $session_time_str }}<small class="ml-1 h6">秒</small></p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_session_time_str }}</span>
</p>
<p class="m-0">
@if($comp_session_time >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_session_time }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">ユーザーの1回のサイト訪問における滞在時間の平均。</small>
</div>
</div>
</div>
<div class="col-3 mt-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-red">
<i class="far fa-clock opacity-color-red"></i>
</span>
<h4 class="h6 text-dark mt-3">平均ページ滞在時間</h4>
<p class="h4 font-weight-bold text-dark">{{ $page_time_str }}<small class="ml-1 h6">秒</small></p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_page_time_str }}</span>
</p>
<p class="m-0">
@if($comp_page_time >= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_page_time }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">1ページあたりのユーザーの滞在時間。</small>
</div>
</div>
</div>
<div class="col-3 mt-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple-2">
<i class="fas fa-arrow-alt-circle-left opacity-color-purple-2"></i>
</span>
<h4 class="h6 text-dark mt-3">直帰率</h4>
<p class="h4 font-weight-bold text-dark">{{ $exit_rate_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_exit_rate_str }}</span>
</p>
<p class="m-0">
@if($comp_exit_rate <= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_exit_rate }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">他のページに移動することなくサイトを離脱したセッションの割合。</small>
</div>
</div>
</div>
<div class="col-3 mt-3">
<div class="card">
<div class="card-body text-center">
<span class="opacity-item opacity-bg-purple">
<i class="fas fa-times-circle opacity-color-purple"></i>
</span>
<h4 class="h6 text-dark mt-3">離脱率</h4>
<p class="h4 font-weight-bold text-dark">{{ $bounce_rate_str }}<small class="ml-1 h6">%</small></p>
<p class="m-0">
<span class="text-secondary"><i class="fas fa-arrows-alt-h mr-1"></i>{{ $old_bounce_rate_str }}</span>
</p>
<p class="m-0">
@if($comp_bounce_rate <= 0)
<span class="opacity-color-green"><i class="fas fa-caret-up mr-1"></i>
@else
<span class="opacity-color-red"><i class="fas fa-caret-down mr-1"></i>
@endif
{{ $comp_bounce_rate }}%</span>
</p>
<hr>
<small class="d-block text-left text-secondary">すべてのページで、そのページが最後の閲覧ページになった割合。</small>
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
<i class="fas fa-comment-dots opacity-color-orange"></i>
</span>
</div>
<div class="col-11">
<div class="d-flex justify-content-between pb-2">
<h3 class="font-weight-bold h5 mt-2">サマリーの総評</h3>
<button id="comment_btn" type="button" name="button" class="btn btn-primary" onclick="saveTextarea('sumally',document.getElementById('comment_sumally'))">更新</button>
</div>
<textarea id="comment_sumally" class="border form-control text-secondary" name="name" rows="4" onfocus="textareaBtn()">
@if (($comp_users+$comp_session+$comp_pv) > 0)
・アクセス状況は上昇傾向にあります。
@else
・アクセス状況は下降傾向にあります。
@endif
@if ($comp_session_time+$comp_page_time < 0)
・ユーザーの滞在時間が下降傾向にあるのでユーザーが求めると配信コンテンツがマッチしていない可能性があります。
@endif
@if ($comp_exit_rate+$comp_bounce_rate < 0)
・ユーザーの直帰率・離脱率が下降傾向にありユーザーがコンテンツに満足し始めいていると言えます。
@else
・ユーザーの直帰率・離脱率が上昇傾向にありユーザーがコンテンツに満足していない可能性があります。
@endif
</textarea>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<script>
let ctx = $('#user-chart');
let originUser = @json($ga_result['transition']['original'], JSON_PRETTY_PRINT);
let compareUser = @json($ga_result['transition']['compare'], JSON_PRETTY_PRINT);
let arrayLabel = [];
let arrayLabel2 = [];
let arrayDataOne = [];
let arrayDataTwo = [];
for (var key in originUser) {
  arrayLabel.push(key);
  arrayDataOne.push(originUser[key]);
}
for (var key in compareUser) {
  arrayLabel2.push(key);
  arrayDataTwo.push(compareUser[key]);
}
let myChart = new Chart(ctx, {
  type: 'line',
  animation: true,
  data: {
    labels: arrayLabel,
    datasets: [
      {
        label: '今期間',
        borderColor: '#007AFF',
        borderWidth: 2,
        backgroundColor: 'rgba(0,122,255,.05)',
        pointBackgroundColor: '#007AFF',
        pointRadius: 2,
        pointBorderWidth: 0,
        data: arrayDataOne
      }, {
        label: '前期間',
        borderColor: '#FF2D55',
        borderWidth: 2,
        backgroundColor: 'rgba(0, 0, 0, 0)',
        pointBackgroundColor: '#FF2D55',
        pointRadius: 2,
        pointBorderWidth: 0,
        data: arrayDataTwo
      }
    ]
  },
  options: {
    tooltips: {
      enabled: false
    },
    legend: {
      display: false
    },
    scales: {
      xAxes: [
        {
          scaleLabel: {
            fontColor: '#EDEDED'
          },
          gridLines: {
            display: false
          },
          ticks: {
            callback: function(value,index,values){
              value = value.slice(5).replace('-','/');
              if(values.length > 10){
                var devide_num = Math.round(values.length/10);
                if(index % devide_num === 0){
                  return value;
                }else{
                  return '';
                }
              }else{
                return value;
              }

            },
            autoSkip: false,
            maxRotation: 0,
            minRotation: 0,
          }
        }
      ],
      yAxes: [
        {
          scaleLabel: {
            fontColor: '#EDEDED'
          },
          gridLines: {},
          ticks: {
            suggestedMin: 0,
            autoSkip: true
          }
        }
      ]
    }
  }
});
</script>
@endsection
