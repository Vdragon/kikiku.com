<div id="cse" style="width: 100%;">{GOOGLE_SEARCH_LOADING_TEXT}</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript"> 
  google.load('search', '1', {language : '{GOOGLE_SEARCH_LANG}', style : google.loader.themes.{GOOGLE_SEARCH_THEME}});
  google.setOnLoadCallback(function() {
    var customSearchOptions = {};  var customSearchControl = new google.search.CustomSearchControl(
      '{GOOGLE_SEARCH_ID}', customSearchOptions);
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    customSearchControl.draw('cse');
    customSearchControl.execute('{GOOGLE_SEARCH_TEXT}');
  }, true);
</script>