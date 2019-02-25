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
    if(jqXHR.responseJSON.message) {
      alert(jqXHR.responseJSON.message);
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
 *            YOUTUBE & SPOTIFY PLAYER                      *
 ***********************************************************/
window.onSpotifyWebPlaybackSDKReady = function () {}; // Prevent player error on loading

$(document).ready(function(){
  $("body")
    .find('[data-toggle="modal"]')
    .click(function(event) {
      event.preventDefault();
      $('#modal iframe').attr('src', $(this).attr('href'));

      $('#modal').on('hidden.bs.modal', function () {
        $("#modal iframe").attr("src", '');
      });
  });
});

/************************************************************
 *                   Edit artist page                       *
 ***********************************************************/
$(".artist a.edit").click(function() {
  $('#artist_description').addClass('hidden');
  $('#artist_description_form').removeClass('hidden');
  CKEDITOR.replace('artist_description_text');
});

/************************************************************
 *                     Comment add                          *
 ***********************************************************/

$(".comment").submit(function(e) {
  e.preventDefault();

  const form = $(this);

  $.ajax({
    type: "POST",
    url: '/comment/add',
    data: form.serialize(),
    success: function(data) {
      alert(data);
      window.location.href = window.location.origin + window.location.pathname + '#last-comment';
      window.location.reload(true);
    },
    error: function(e) {
      if(typeof e.responseJSON === 'object' && typeof e.responseJSON.msg === 'string') {
        alert(e.responseJSON.msg);
      } else {
        alert('Sorry, an error occurred while posting your comment');
      }
    }
  });
});
