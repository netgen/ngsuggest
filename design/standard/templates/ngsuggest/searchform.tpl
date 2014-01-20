<form action={'/content/search/'|ezurl} method="get" id="{$form_id}" name="{$form_id}">
{include uri='design:ngsuggest/searchfield.tpl' form_id=$form_id search_id=$search_id}
<input id="searchbutton" class="button" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
</form>
