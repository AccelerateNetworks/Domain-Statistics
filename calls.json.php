<?php
/*
	GNU Public License
	Version: GPL 3
*/
require_once "root.php";
require_once "resources/require.php";
require_once "resources/check_auth.php";
require_once "utils.php";

if(!isset($_REQUEST['key'])) {
  die(json_encode(array("success" => false, "reason" => "No API key specified")));
}

$args = array(
  ':limit' => 100,
  ':key' => $_REQUEST['key']
);

if(isset($_REQUEST['limit'])) {
  $args[':limit'] = $_REQUEST['limit'];
}

$results = do_sql($db, "SELECT v_xml_cdr.json FROM v_xml_cdr JOIN cdr_api_keys ON cdr_api_keys.domain_uuid = v_xml_cdr.domain_uuid WHERE cdr_api_keys.key = :key LIMIT :limit", $args);

$out = [];
foreach($results as $result) {
  $out[] = json_decode($result['json']);
}

echo(json_encode($out));
