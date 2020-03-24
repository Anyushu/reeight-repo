require('chart.js');

$(function() {
  // datepicker
  $('.datepicker, .input-daterange').datepicker({
    language: 'ja',
    autoclose: true,
    format: 'yyyy-mm-dd',
    endDate: '-1d',
  }).on('changeDate', function(e) {
    let target = document.getElementById("check_range");
    let change_btn = document.getElementById("data_change_btn");
    // if ($(this).attr('id') == 'start') {
    let com_end = $('#com_end').val();
    com_end = com_end.split('-');
    com_end = new Date(com_end[0], com_end[1] - 1, com_end[2]);
    let start = $('#start').val();
    start = start.split('-');
    start = new Date(start[0], start[1] - 1, start[2]);
    let end = $('#end').val();
    end = end.split('-');
    end = new Date(end[0], end[1] - 1, end[2]);
    let com_start = $('#com_start').val();
    com_start = com_start.split('-');
    com_start = new Date(com_start[0], com_start[1] - 1, com_start[2]);

    if (start <= com_end) {
      target.innerHTML = '解析期間の開始日を比較期間の終了日以降に設定してください';
      change_btn.disabled = 'disabled';
    } else if(start > end) {
      target.innerHTML = '解析期間の終了日を解析期間の開始日以降に設定してください';
      change_btn.disabled = 'disabled';
    } else if(com_start > com_end) {
      target.innerHTML = '比較期間の終了日を比較期間の開始日以降に設定してください';
      change_btn.disabled = 'disabled';
    } else if(com_start >= end) {
      target.innerHTML = '解析期間の終了日を比較期間の開始日以降に設定してください';
      change_btn.disabled = 'disabled';
    } else {
      target.innerHTML = '';
      change_btn.disabled = '';
    }
  });
});
