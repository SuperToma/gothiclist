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

/********** Auto upload cover **********/
$('.coverInputFile').change(function() {
  const coverId = $(this).attr("data-id");
  const formData = new FormData();

  formData.append('cover', $(this)[0].files[0]);
  formData.append('id', coverId);

  $.ajax({
    url: '/admin/upload_cover',
    type: 'post',
    data: formData,
    contentType: false,
    processData: false,
    success: function(resp){
      if(resp.file){
        const imgUrl = resp.file + "?" + Math.round(Math.random() * 1000);
        $(".cover_" + coverId).attr("src", imgUrl);
      }else{
        alert('Error while uploading file');
      }
    },
    error: function(resp){
      if("responseText" in resp) {
        alert("Error: " + resp.statusText)
      } else if("responseJSON" in resp) {
        alert("Error: " + resp.responseJSON.message);
      } else {
        alert("Error");
      }
    }
  });
});

/********** Auto upload mp3 **********/
$('.mp3InputFile').change(function() {
  const songId = $(this).attr("data-id");
  const formData = new FormData();

  formData.append('mp3', $(this)[0].files[0]);
  formData.append('id', songId);

  $.ajax({
    url: '/admin/upload_mp3',
    type: 'post',
    data: formData,
    contentType: false,
    processData: false,
    success: function(resp){
      if(resp.message === "Success"){
        alert("Upload success");
      }else{
        alert('Error while uploading file');
      }
    },
    error: function(resp){
      alert("error: " + resp.responseJSON.message);
    }
  });
});