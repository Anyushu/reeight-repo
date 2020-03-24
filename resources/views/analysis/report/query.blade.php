@if($sc_result != [])
@section('content')
<section class="reports">
<div class="container">

<!-- グラフ -->
<div class="col-12">
<div class="card mb-3">
<div class="card-body">
<div class="row align-items-center">
<div class="col-3 text-center">
<span class="opacity-item opacity-bg-green">
<i class="fas fa-search opacity-color-green"></i>
</span>
<h3 class="text-dark font-weight-bold h6 mt-3">検索結果表示回数</h3>
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

<!-- 表 -->
<div class="col-12 mb-3">
<div class="card">
<div class="card-body">
<div class="table-responsive">
<table class="table table-striped table-borderless">
<thead>
<tr>
<th class="font-weight-bold align-center" colspan="2">クリック数上位10キーワード</th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-orange"><i class="fas fa-search opacity-color-orange"></i></span><small class="mt-2 d-block">クリック数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-green"><i class="fas fa-search opacity-color-green"></i></span><small class="mt-2 d-block">検索結果<br>表示回数</small></th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue-2"><i class="fas fa-mouse opacity-color-blue-2"></i></span><small class="mt-2 d-block">平均クリック率</th>
<th class="font-weight-normal text-center align-top"><span class="opacity-item opacity-bg-blue"><i class="fas fa-list-ol opacity-color-blue"></i></span><small class="mt-2 d-block">平均掲載順位</small></th>
</tr>
</thead>
<tbody>
@php
$max_imp = [];
$max_imp_tmp = 0;
@endphp
@foreach ($sc_result['original'] as $key => $val)
@php
$query = $val->keys[0];
$click = (int)$val->clicks;
$impressions = (int)$val->impressions;
$ctr = round($val->ctr*100, 2);
$position = round($val->position, 2);
if ($max_imp_tmp < $impressions) {
    $max_imp[0] = [$query, $impressions];
    $max_imp_tmp = $impressions;
}
@endphp
<tr>
<td class="table_number">{{ $key+1 }}</td>
<td><span class="text-dark">{{ $query }}</span></td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ $click }}</span>
<div class="progress">
@if($sc_result['max']['clicks'])
<div class="progress-bar ka-bg-orange" style="width:{{ $click/$sc_result['max']['clicks']*100 }}%" role="progressbar" aria-valuenow="{{ $click/$sc_result['original'][0]['clicks']*100 }}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
</div>
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ $impressions }}</span>
<div class="progress">
@if($sc_result['max']['impressions'])
<div class="progress-bar ka-bg-green" style="width:{{ $impressions/$sc_result['max']['impressions']*100 }}%" role="progressbar" aria-valuenow="{{ $impressions/$sc_result['max']['impressions']*100 }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
@endif
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ $ctr }}%</span>
<div class="progress">
@if($sc_result['max']['ctr'])
<div class="progress-bar ka-bg-blue-2" style="width:{{ ($ctr*100)/($sc_result['max']['ctr']*100) }}%" role="progressbar" aria-valuenow="{{ ($ctr*100)/($sc_result['max']['ctr']*100) }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
@endif
</td>
<td class="text-right">
<span class="text-dark font-weight-bold">{{ $position }}位</span>
<div class="progress">
@if($sc_result['max']['position'])
<div class="progress-bar ka-bg-blue" style="width:{{ $sc_result['max']['position']/$position*100 }}%" role="progressbar" aria-valuenow="{{ $sc_result['max']['position']/$position*100 }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>

<!-- コメント -->
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
<h3 class="font-weight-bold h5 mt-2">検索分析の総評</h3>
<button id="comment_btn" type="button" name="button" class="btn btn-primary" onclick="saveTextarea('query',document.getElementById('comment_query'))">更新</button>
</div>
<textarea id="comment_query" class="border form-control text-secondary" name="name" rows="4" onfocus="textareaBtn()">
@if($sc_result != [])
・「{{ $sc_result['original'][0]['keys'][0] }}」が期間内で一番多くクリックされています。
・「{{ $max_imp[0][0] }}」が期間内で一番多く検索結果に表示されています。
@if ($sc_result['sum']['original'] - $sc_result['sum']['comp'] > 0)・検索表示回数が上昇傾向にあります。@else・検索表示回数が下降傾向にあります。@endif
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
let clicks = @json($sc_result['impressions'], JSON_PRETTY_PRINT);
@if(isset($sc_result['comp']['impressions']))
let clicks_comp = @json($sc_result['comp']['impressions'], JSON_PRETTY_PRINT);
@else
let clicks_comp = 0;
@endif
let arrayLabel = @json($sc_result['date'], JSON_PRETTY_PRINT);
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
        data: clicks
      }, {
        label: '前期間',
        borderColor: '#FF2D55',
        borderWidth: 2,
        backgroundColor: 'rgba(0, 0, 0, 0)',
        pointBackgroundColor: '#FF2D55',
        pointRadius: 2,
        pointBorderWidth: 0,
        data: clicks_comp
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
                            console.log(value,index);

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
@else
@section('content')
<section class="reports">
<div class="container">
<div class="col-12">
<div class="alert alert-warning">
<p class="m-0">レポート作成に必要な情報が不足しているため出力できませんでした。</p>
</div>
</div>
</div>
</section>
@endsection
@endif
