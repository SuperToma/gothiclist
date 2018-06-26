/******************************************
 *                VOTES                   *
 *****************************************/
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

/******************************************
 *               AVATARS                  *
 *****************************************/
defaultImgUrl = '/img/1x1.png';

jQuery(document).ready(function() {
  $('img.avatar').each(function() {

    var imgUrl = '/img/1x1.png';

    switch($(this).data("provider-name")) {
      case 'Facebook':
        imgUrl = "https://graph.facebook.com/"+$(this).data("provider-id")+"/picture";
        imgUrl += "?width="+$(this).data("size")+"&height="+$(this).data("size")+"&type=square";
        break;
      case 'Twitter':
        imgUrl = "https://avatars.io/twitter/"+$(this).data("provider-nickname")+"/small";
        break;
      case 'Vkontakte':
        imgUrl = $(this).data("avatar-url");
        if(!imgUrl) {
          imgUrl = defaultImgUrl;
        }
        break;
    }

    $(this).attr("src", imgUrl);
  });
});

/************************************************************
 *            YOUTUBE VIDEOS                                *
 * cf. https://gist.github.com/koistya/7eac879f674ae9f5e26c *
 ***********************************************************/
(function () {
  var videoDialogPlayer;

  $(function () {
    $('.youtube-dialog').on('shown.bs.modal', function () {
      var videoDialogPlayer = new YT.Player('videoDialogPlayer', {
        height: '390',
        width: '640',
        videoId: '4HG6Ek_SyJs'
      });
      videoDialogPlayer.playVideo();
    }).on('hide.bs.modal', function () {
      videoDialogPlayer.stopVideo();
    });
  });

}());