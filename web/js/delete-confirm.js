$(document).ready(function(){
  $("input.deleteButton").click(function() {
    $(this).siblings(".cancelButton")[0].style.display = 'inline-block';
    $(this).siblings(".confirmButton")[0].style.display = 'inline-block';
    $(this)[0].style.display = 'none';
  });
});
$(document).ready(function(){
  $(".cancelButton").click(function() {
    $(this)[0].style.display = 'none';
    $(this).parent().children(".confirmButton")[0].style.display = 'none';
    $(this).parent().children(".deleteButton")[0].style.display = 'inline-block';
  });
});
