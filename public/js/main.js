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
 *            YOUTUBE PLAYER                                *
 ***********************************************************/
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
 *            SPOTIFY PLAYER                                *
 ***********************************************************/
window.onSpotifyWebPlaybackSDKReady = () => {
  const token = 'BQD-PMzqLVPIFfzMl2qvDce1YEXDJzV2Qg6Gb7zxcvX07rjKvml6euC0vKS2bkpHnixLb7j2sirqtuZIaY-dmB0qWKsb6L3yflb13gvcuIbIEN3WVq9iW6awli8VDzovUgqvGmQl9xULUjsIbb319sofax65j9UaO62AWQ8WLzTfDQwCu_Y0Dw';
  const spotifyPlayer = new Spotify.Player({
    name: 'Web Playback SDK Quick Start Player',
    getOAuthToken: cb => { cb(token); }
  });

  // Error handling
  spotifyPlayer.addListener('initialization_error', ({ message }) => { console.error(message); });
  spotifyPlayer.addListener('authentication_error', ({ message }) => { console.error(message); });
  spotifyPlayer.addListener('account_error', ({ message }) => { console.error(message); });
  spotifyPlayer.addListener('playback_error', ({ message }) => { console.error(message); });

  // Playback status updates
  spotifyPlayer.addListener('player_state_changed', state => { console.log(state); });

  // Ready
  spotifyPlayer.addListener('ready', ({ device_id }) => {
    console.log('Ready with Device ID', device_id);
  });

  // Not Ready
  spotifyPlayer.addListener('not_ready', ({ device_id }) => {
    console.log('Device ID has gone offline', device_id);
  });

  // Connect to the player!
  spotifyPlayer.connect();

  const play = ({spotify_uri, playerInstance: {_options: {getOAuthToken, id}} }) => {
    getOAuthToken(access_token => {
      fetch(`https://api.spotify.com/v1/me/player/play?device_id=${id}`, {
        method: 'PUT',
        body: JSON.stringify({ uris: [spotify_uri] }),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${access_token}`
        },
      });
    });
  };

  $(".fa-spotify").click(function() {
    play({
      playerInstance: spotifyPlayer,
      spotify_uri: 'spotify:track:' + $(this).data('song-id'),
    });
  });

};