/************** Validate switch button ***************/
$("input[type='checkbox'][name='validated']").click(function() {
  $.ajax({
    url: '/admin/song/validated',
    data: {id: $(this).data('id'), validated: $(this).is(':checked') ? 1 : 0 },
    method: "post",
    dataType: "json"
  });
});

/******* inputs IDs Youtube / Spotify *******/
$(".youtube-id, .spotify-id").keyup(function(event) {
  if (event.keyCode === 13) {
    var type;
    if($(this).hasClass('youtube-id')) {
      type = 'youtubeId';
    } else if ($(this).hasClass('spotify-id')) {
      type = 'spotifyId';
    } else {
      return;
    }
    $.ajax({
      url: '/admin/song/' + $(this).data('song-id'),
      data: { [type]: $(this).val() },
      method: "patch",
      dataType: "json",
      success: function( data ) {
        alert('Video ID saved');
      },
      error: function() {
        alert('Oops, an error occured while saving video ID');
      }
    });
  }
});

/************** Songs styles ***************/
var styles = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: '/autocomplete/styles',
  ttl: 0,
  cache: false
});
styles.initialize();

elt = $('input.tags-styles');
elt.tagsinput({
  itemValue: 'id',
  itemText: 'name',
  typeaheadjs: {
    name: 'styles',
    displayKey: 'name',
    source: styles.ttAdapter()
  }
});

$('.tags-styles').each(function() {
  var elt = $(this);
  $(this).data('styles').forEach(function(element) {
    elt.tagsinput('add', element);
    elt.on('beforeItemRemove', function(event) {
		if (confirm('Delete the style "' + event.item.name + '"\nof the album \n"' + $(this).data('release-title') + '" ???')) {
      $.ajax({
        url: '/admin/release/'+$(this).data('release-id')+'/style/'+event.item.id,
        method: "post",
        dataType: "json"
      });

		}
    })
  });
});

