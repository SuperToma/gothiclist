$(".vote").click(function() {
  $button = $(this);

  $.ajax({
    url: '/vote/' + $button.data('type') + '/' + $button.data('id'),
    method: "post",
    dataType: "json"
  })
  .done(function (res) {
    if(res.action === 'add') {
      // Heart ADD styles
      if($button.hasClass('fa-heart')) {
        $button.removeClass('fa-heart-o').addClass('tomato');
        $button.next().html(parseInt($button.next().html()) + 1)
      }
    } else {
      // Heart DELETE styles
      if($button.hasClass('fa-heart')) {
        $button.removeClass('tomato').addClass('fa-heart-o');
        $button.next().html(parseInt($button.next().html()) - 1)
      }
    }
  })
  .fail(function (jqXHR) {
    if(jqXHR.responseText.message) {
      alert(jqXHR.responseText.message);
    } else {
      alert("Sorry, an error occurred");
    }
  })
});