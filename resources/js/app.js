// fortawesomeの設定
FontAwesomeConfig = {
  searchPseudoElements: true
};

require('@fortawesome/fontawesome-free/js/all.js');
require('./bootstrap');
require('bootstrap-datepicker');
require('bootstrap-datepicker/dist/locales/bootstrap-datepicker.ja.min.js');
require('tablesorter');
require('xlsx');
require('file-saverjs');
require('tableexport');
require('jquery.quicksearch');

$(function() {
  // Tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // サイト検索フォーム
  let $searchInput = $('#ga-search');
  $searchInput.quicksearch('#ga-accounts>.accounts');
  $searchInput.on('input', function() {
    $('.collapse').collapse('hide');
  });
  let $search = $('#mysite-search');
  $search.quicksearch('.my-site');

  // モーダル
  $('.addsite-modal').on('click', function() {
    let account_name = $(this).attr('data-name');
    let property_name = $(this).attr('data-property');
    let view_id = $(this).attr('data-id');
    let data_url = $(this).attr('data-url');
    $('#addsite-form-label').text(account_name);
    $('#site-name').val(property_name);
    $('#view-id').val(view_id);
    $('#site-url').val(data_url);
  });

  // テーブルソート
  let table_sort = $('#seo-report-detail table').tablesorter();

  // テーブルエクスポート
  let table_export = $('#seo-report-detail .table-responsive .table').tableExport({
    formats: ['xlsx', 'csv', 'txt'],
    bootstrap: true,
    filename: 'kagari_seo_report',
    exportButtons: false
  });
  let table_export_seo = $('#seo-detail-table').tableExport({
    formats: ['xlsx', 'csv', 'txt'],
    bootstrap: true,
    filename: 'kagari_seo_report_detail',
    exportButtons: false
  });

  // テーブルスクロール
  $('.horizontal-scroll li').on('click', function() {
    const wrap = $('#seo-report-detail .table-responsive');
    const wrap_w = wrap.outerWidth();

    $('.horizontal-scroll li').removeClass('active');
    $('.row-head th').removeClass('active');

    $(this).addClass('active');
    if ($(this).hasClass('seo')) {
      wrap.animate({
        scrollLeft: -wrap_w
      });
      $('.row-head th.seo').addClass('active');
    } else {
      wrap.animate({
        scrollLeft: wrap_w
      });
      $('.row-head th.site').addClass('active');
    }
  });

  $(document).ajaxStop(function() {
    // 時間取得
    let today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth() + 1;
    let day = today.getDate();
    let hours = today.getHours();
    let minutes = today.getMinutes();
    let seconds = today.getSeconds();
    let time = year + '_' + month + '_' + day + '_' + hours + '_' + minutes + '_' + seconds;

    // テーブルソート
    table_sort.trigger('update', true);
    // テーブルエクスポート
    table_export.update({
      formats: ['xlsx', 'csv', 'txt'],
      bootstrap: true,
      filename: time,
      exportButtons: false,
    });
    table_export_seo.update({
      formats: ['xlsx', 'csv', 'txt'],
      bootstrap: true,
      filename: 'seo_' + time,
      exportButtons: false,
    });
  });

  // アカウント情報更新
  $('#btn-account-edit').on('click', function() {
    console.log('click');
    const csrf = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrf
      }
    });
    if (window.confirm('変更してもよろしいですか？')) {
      $.ajax({
        url: '/dashboard/account/edit-data',
        type: 'post',
        datatype: 'json',
        data: {
          company: $('input[name="company"]').val(),
          name: $('input[name="name"]').val(),
          tel: $('input[name="tel"]').val(),
          email: $('input[name="email"]').val(),
          password: $('input[name="password"]').val(),
          password_confirmation: $('input[name="password_confirmation"]').val(),
        }
      }).done(function(data) {
        if ($.isEmptyObject(data.error)) {
          $('.print-success-msg').children('.alert').html('');
          $('.print-success-msg').css('display', 'block');
          $('.print-success-msg').children('.alert').text('正しく更新されました。');
        } else {
          print_error_msg(data.error);
        }
      });
    } else {
      return false;
    }
  });

  function print_error_msg(msg) {
    $('.print-error-msg').children('.alert').find('ul').html('');
    $('.print-error-msg').css('display', 'block');
    $.each(msg, function(key, value) {
      $('.print-error-msg').children('.alert').find('ul').append('<li>' + value + '</li>');
    });
  }

    // seo datepicker
    $('.datepicker').datepicker({
      language: 'ja',
      autoclose: true,
      format: 'yyyy-mm-dd',
      endDate: '-3d',
    }).on('changeDate', function(e) {
      let target = document.getElementById("check_range");
      let change_btn = document.getElementById("data_change_btn");
      let start = $('#start').val();
      start = start.split('-');
      start = new Date(start[0], start[1] - 1, start[2]);
      let end = $('#end').val();
      end = end.split('-');
      end = new Date(end[0], end[1] - 1, end[2]);

      if (start > end) {
          target.innerHTML = '解析期間の終了日を解析期間の開始日以降に設定してください';
          change_btn.disabled = 'disabled';
      } else {
        target.innerHTML = '';
        change_btn.disabled = '';
      }
    });
});
