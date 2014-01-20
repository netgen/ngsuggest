<?php

$Module = array( 'name' => 'ngSuggest',
                 'variable_params' => true );

$ViewList = array();
$ViewList['searchsolr'] = array(
    'script' => 'solrresult.php',
    'params' => array(),
    'post_actions' => array( ),
    'unordered_params' => array( ) );

?>
