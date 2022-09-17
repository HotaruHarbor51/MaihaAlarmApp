$(function(){
  $('.btn-submit').click(function(){
    if(!window.confirm("入力した内容でよろしいでしょうか？")) return false;
    $('#MaihaAlarmForm').submit();
  });
})
