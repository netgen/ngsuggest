<?php

$db = eZDB::instance();

$installation_id_cond = "";
$resultSet = $db->arrayQuery( 'SELECT value FROM ezsite_data WHERE name=\'ezfind_site_id\'' );
if ( count( $resultSet ) >= 1 ) {
    $installation_id_cond = "+AND+meta_installation_id_s:".$resultSet[0]['value'];
}

$siteini = eZINI::instance( 'site.ini' );
$language_code_cond = "+AND+meta_language_code_s:".$siteini->variable( 'RegionalSettings', 'Locale' );


$search_id = $_GET['id'];

$ini = eZINI::instance( 'ngsuggest.ini' );
$limit = $ini->variable( 'default', 'Limit' );
$root_node = $ini->variable( 'default', 'RootNode' );
$classes = $ini->variable( 'default', 'Classes' );
$section = $ini->variable( 'default', 'Section' );
$field = $ini->variable( 'default', 'FacetField' );

if(strlen($search_id))
{
    if (strlen(trim($ini->variable( $search_id, 'Limit' )))) $limit = $ini->variable( $search_id, 'Limit' );
    if (strlen(trim($ini->variable( $search_id, 'RootNode' )))) $root_node = $ini->variable( $search_id, 'RootNode' );
    if (is_array($ini->variable( $search_id, 'Classes' )) && count(trim($ini->variable( $search_id, 'Classes' )))) $classes = $ini->variable( $search_id, 'Classes' );
    if (is_array($ini->variable( $search_id, 'Section' )) && count(trim($ini->variable( $search_id, 'Section' )))) $section = $ini->variable( $search_id, 'Section' );
    if (strlen(trim($ini->variable( $search_id, 'FacetField' )))) $field = $ini->variable( $search_id, 'FacetField' );
}
$rootnode_cond = "+AND+meta_path_si:".$root_node;

$classes_cond = "";
foreach( $classes as $class ) {
    if ($classes_cond == "") {
        $classes_cond="meta_contentclass_id_si:".$class;
    } else {
        $classes_cond.="+OR+meta_contentclass_id_si:".$class;
    }
}
if ($classes_cond != "") {
        $classes_cond="+AND+(+".$classes_cond."+)";
}

$section_cond = "";
foreach( $section as $sec ) {
    if ($section_cond == "") {
        $section_cond="meta_section_id_si:".$sec;
    } else {
        $section_cond.="+OR+meta_section_id_si:".$sec;
    }
}
if ($section_cond != "") {
        $section_cond="+AND+(+".$section_cond."+)";
}

$keywords = explode(' ',$_GET['keyword']);

$query='';
if (count($keywords)==1 && strlen($keywords[0])>0) {
    $query = "rows=0&facet=on&facet.field=".$field."&facet.prefix=". strtolower($keywords[0]) . "&facet.limit=".$limit."&facet.mincount=1&q=*:*&fq=meta_is_hidden_b:false+AND+meta_is_invisible_b:false".$language_code_cond.$classes_cond.$section_cond.$rootnode_cond.$installation_id_cond."&wt=json";

} else if (count($keywords)>1 && strlen($keywords[count($keywords)-1])>0) {
    $facet = $keywords[count($keywords)-1];
    array_pop($keywords);
    $search = implode("+", $keywords);
    $query = "rows=0&facet=on&facet.field=".$field."&facet.prefix=". strtolower($facet) . "&facet.limit=".$limit."&facet.mincount=1&q=".$search."&fq=meta_is_hidden_b:false+AND+meta_is_invisible_b:false".$language_code_cond.$classes_cond.$section_cond.$rootnode_cond.$installation_id_cond."&wt=json";

}
if (strlen($query)>0) {
    $ini = eZINI::instance( 'solr.ini' );
    $uri = $ini->variable( 'SolrBase', 'SearchServerURI' );

    $handle = fopen( $uri. "/select?". $query, "rb");
    $contents = stream_get_contents($handle);
    fclose($handle);
}
echo( $contents );

// Stop execution at this point, if we do not we'll have the
// pagelayout.tpl inside another pagelayout.tpl.
eZExecution::cleanExit();
?>
