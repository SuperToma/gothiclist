/************** Validate switch button ***************/
$("input[type='checkbox'][name='validated']").click(function() {
  $.ajax({
    url: '/admin/song/validated',
    data: {id: $(this).data('id'), validated: $(this).is(':checked') ? 1 : 0 },
    method: "post",
    dataType: "json"
  });
});

/************** Styles ***************/

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
  $(this).data('values').forEach(function(element) {
    elt.tagsinput('add', element);
    elt.on('beforeItemRemove', function(event) {
      alert('yeah');
    })
  });
});

