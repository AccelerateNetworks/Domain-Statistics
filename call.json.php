<?php
/*
	GNU Public License
	Version: GPL 3
*/
require_once "root.php";
require_once "resources/require.php";
require_once "utils.php";

header("Content-Type: application/json");

if(!isset($_REQUEST['key'])) {
  die(json_encode(array("success" => false, "reason" => "No API key specified")));
}
if(!isset($_REQUEST['call'])) {
	die(json_encode(array("success" => false, "reason" => "No call specified")));
}

$args = array(
  ':call' => $_REQUEST['call'],
  ':key' => $_REQUEST['key']
);

if(isset($_REQUEST['limit'])) {
  $args[':limit'] = $_REQUEST['limit'];
}

$results = do_sql($db, "SELECT v_xml_cdr.json FROM v_xml_cdr JOIN cdr_api_keys ON cdr_api_keys.domain_uuid = v_xml_cdr.domain_uuid WHERE cdr_api_keys.key = :key AND v_xml_cdr.uuid = :call LIMIT :limit", $args);

return sanatize_cdr($result[0]['json']);
}

echo(json_encode($out));
