<?php
/*
	GNU Public License
	Version: GPL 3
*/
require_once "root.php";
require_once "resources/require.php";
require_once "utils.php";

if(!isset($_REQUEST['key'])) {
  die(json_encode(array("success" => false, "reason" => "No API key specified")));
}
$domain_name = do_sql($db, "SELECT v_domains.domain_name FROM v_domains, cdr_api_keys WHERE cdr_api_keys.domain_uuid = v_domains.domain_uuid AND cdr_api_keys.key = :key",
  array(':key' => $_REQUEST['key']));

if(count(domain_name) != 1) {
  die(json_encode(array("success" => false, "reason" => "Invalid API Key")));
}

$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
if(!$fp) {
  die(json_encode(array("success" => failse, "reason" => "Failed to connect to freeswitch")));
}

$cmd = "api sofia xmlstatus profile internal reg";
$xml_response = trim(event_socket_request($fp, $cmd));
//TODO if xml_response is Invaild Profile! set off all the alarms!!!!

$xml_response = str_replace("<profile-info>", "<profile_info>", $xml_response);
$xml_response = str_replace("</profile-info>", "</profile_info>", $xml_response);
$xml = new SimpleXMLElement($xml_response);

$out = [];

foreach($xml->registrations->registration as $row) {
  if($row['sip-auth-realm'] == $domain_name[0]['domain_name']){
    $out[] = $row;
  }
}

echo(json_encode($out));
