@php
$user_flag = true;
$ad_flag = true;
if(count($ga_result_user[0]) != 3 || count($ga_result_user[1]) != 3){
  $user_flag = false;
}
if($ga_result_ad[0][0][0] == 0){
  $ad_flag = false;
}
@endphp
<!DOCTYPE html>
<html lang="ja">
<head>
@yield('head')
<meta name="viewport" content="width=1140">
@stack('scripts')
<style media="screen">
  body {
    margin: 0 auto;
    width: 1140px !important;
    background-color: #fafafa;
  }
  a{
    pointer-events: none;
  }
  .container {
    width: 1080px !important;
    max-width: 1080px !important;
    min-width: 1080px !important;
  }
  #target>div {
    max-height: 1612px;
    min-height: 1612px;
    height: 1612px;
    background-color: #fff;
    margin-bottom: 60px;
  }
  #target1 .container {
    position: relative;
  }
  #target1 .logo {
    position: absolute;
    top: 40px;
    left: 0;
    max-height: 100px;
  }
  #target1 .logo img {
    max-width: 300px;
    max-height: 100px;
  }
  .spinner {
    z-index: 999;
    position: fixed;
    top: 0;
    left:0 ;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
  }
  #target2 .nav-item .ac_sumally{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }
  #target3 .nav-item .ac_user{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }
  #target4 .nav-item .ac_inflow{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }
  #target5 .nav-item .ac_action{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }
  #target6 .nav-item .ac_conv{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }
  #target7 .nav-item .ac_ad{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }
  #target8 .nav-item .ac_query{
    color: #007aff;
    border-bottom: solid 3px #007aff;
  }

</style>
</head>
<body class="drawer drawer--right">
<input type="hidden" id="site_name" value="{{ $site_name }}">
<input type="hidden" id="plan_flag" value="{{ $plan }}">
<!-- <div class="my-5 text-center">
<a href="#" class="btn btn-sm btn-outline-secondary" onclick="downloadImage()"><i class="fas fa-file-pdf mr-2"></i>もう一度PDFを生成する</a>
</div> -->
<div class="spinner">
<div class="spinner-grow text-primary" role="status">
<span class="sr-only">Loading...</span>
</div>
</div>
<!-- <a onclick="downloadImage()">画面キャプチャPDFのダウンロード</a> -->
<main id="target" class="py-5">
<div id="target1">
@yield('content_top')
</div>
<div id="target2">
@yield('nav_pdf')
@yield('content_sumally')
</div>
<div id="target3">
@yield('nav_pdf')
@yield('content_users')
</div>
<div id="target4" >
@yield('nav_pdf')
@yield('content_inflow')
</div>
<div id="target5" >
@yield('nav_pdf')
@yield('content_action')
</div>
<div id="target6" >
@yield('nav_pdf')
@yield('content_conversion')
</div>
<div id="target7" >
@yield('nav_pdf')
@yield('content_ad')
</div>
@if($plan%2 == 0)
<div id="target8">
@yield('nav_pdf')
@yield('content_query')
</div>
@endif
</main>
<script src="{{ asset('/js/html2canvas.min.js') }}"></script>
<script src="{{ asset('/js/jspdf.min.js') }}"></script>
<script>
$(window).on('load', function() {
  $.when(
    getTextarea(),
    downloadImage()
  ).done(function() {
    $('.spinner').css('display', 'none');
  });
});

function getTextarea(){
  if (localStorage.getItem('sumally') != null) {
    let val_sumally = localStorage.getItem('sumally');
    let target_sumally = document.getElementById("comment_sumally");
    target_sumally.innerHTML = val_sumally.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('sumally');
  }
  if (localStorage.getItem('user') != null) {
    let val_user = localStorage.getItem('user');
    let target_user = document.getElementById("comment_user");
    target_user.innerHTML = val_user.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('user');
  }
  if (localStorage.getItem('inflow') != null) {
    let val_inflow = localStorage.getItem('inflow');
    let target_inflow = document.getElementById("comment_inflow");
    target_inflow.innerHTML = val_inflow.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('inflow');
  }
  if (localStorage.getItem('action') != null) {
    let val_action = localStorage.getItem('action');
    let target_action = document.getElementById("comment_action");
    target_action.innerHTML = val_action.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('action');
  }
  if (localStorage.getItem('conversion') != null) {
    let val_conversion = localStorage.getItem('conversion');
    let target_conversion = document.getElementById("comment_conversion");
    target_conversion.innerHTML = val_conversion.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('conversion');
  }
  if (localStorage.getItem('ad') != null) {
    let val_ad = localStorage.getItem('ad');
    let target_ad = document.getElementById("comment_ad");
    target_ad.innerHTML = val_ad.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('ad');
  }
  if (localStorage.getItem('query') != null) {
    let val_query = localStorage.getItem('query');
    let target_query = document.getElementById("comment_query");
    target_query.innerHTML = val_query.replace(/\r?\n/g, "<br>");
    localStorage.removeItem('query');
  }
}
let plan_flag = document.getElementById('plan_flag').value;
function downloadImage() {
  if(plan_flag%2 == 0){
    var pdf = new jsPDF('p', 'pt', 'letter');
    for (var j = 1; j <= 8; j++) {
      var getId = "target" + j;
      toCanvas(pdf, getId, j);
    }
  } else {
    var pdf = new jsPDF('p', 'pt', 'letter');
    for (var j = 1; j <= 7; j++) {
      var getId = "target" + j;
      toCanvas(pdf, getId, j);
    }
  }
}
function toCanvas(pdf, getId, j) {
  html2canvas(document.getElementById(getId))
  .then(function(canvas) {
    if (j > 1) {
      pdf.addPage();
    }
    pdf.setPage(j);
    var imgData = canvas.toDataURL();
    var width = pdf.internal.pageSize.width;
    pdf.addImage(canvas, 'JPEG', 0, 20, width, 0);
    var today = new Date();
    var year = today.getFullYear();
    var month = today.getMonth()+1;
    var day = today.getDate();
    var pdf_name = year+'-'+month+'-'+day;
    var site_name = document.getElementById('site_name').value;
    if (plan_flag%2 == 0) {
      if (j == 8) {
        pdf.save(pdf_name+'_'+site_name);
      }
    } else {
      if (j == 7) {
        pdf.save(pdf_name);
      }
    }
  });
}
</script>
</body>
</html>