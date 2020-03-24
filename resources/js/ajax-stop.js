$(function() {
  $('.load').attr('disabled', 'disabled');
  $('.load .fa-spinner').css('display', 'inherit');
  $(document).ajaxStop(function() {
    $('.load .fa-spinner').css('display', 'none');
    $('.load').removeAttr('disabled');
  });
});