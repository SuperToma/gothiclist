$(".vote").click(function() {
  console.log($(this).data('id'));
  $.ajax({
    url: '/vote/' + $(this).data('type') + '/' + $(this).data('id'),
    method: "post",
    dataType: "json"
  })
  .done(function (res) {

  })
  .fail(function (jqXHR) {
    if(jqXHR.responseText.message) {
      alert(jqXHR.responseText.message);
    } else {
      alert("Sorry, an error occurred");
    }
  })
});