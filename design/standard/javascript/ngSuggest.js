//////
jQuery(document).ready(function() {
//////

function jsonSuggestSelect_callback(item) {
  //alert(JSON.stringify(item));
  return false;
}

jQuery('.ngsuggestfield').jsonSuggest(
  function(text, searchId, wildCard, caseSensitive, notCharacter) {
    var JSONData = '';
    /*var success = false;*/
    function processJSONData(data) {
      for (var f in data.facet_counts.facet_fields) {
        for (var i in data.facet_counts.facet_fields[f]) {
          if (i%2 == 0) {
            if (i != 0) JSONData += ',';
            JSONData += '{"text":"' + data.facet_counts.facet_fields[f][i] + '"}';
          }
          success = true;
        }
      }
    }
    jQuery.ajax({
      async: false,
      dataType: 'json',
      data: 'keyword=' + text,
      url: '/ngsuggest/searchsolr?id=' + searchId,
      success: function (data, textStatus) {processJSONData(data);},
      error: function (XMLHttpRequest, textStatus, errorThrown) {
      	//alert(textStatus);
      }
    });

    return JSON.parse('[' + JSONData + ']');
  }, {ajaxResults:true, maxResults:9, minCharacters:1, onSelect:jsonSuggestSelect_callback}
);

//////
});
//////
