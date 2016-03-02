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

$args = array(
  ':key' => $_REQUEST['key']
);

$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
if (!$fp) {
  die(json_encode(array("success" => failse, "reason" => "Failed to connect to freeswitch")));
}

$cmd = "api sofia xmlstatus profile internal reg";
$xml_response = trim(event_socket_request($fp, $cmd));
//TODO if xml_response is Invaild Profile! set off all the alarms!!!!
$xml_response = str_replace("<profile-info>", "<profile_info>", $xml_response);
$xml_response = str_replace("</profile-info>", "</profile_info>", $xml_response);
$xml = new SimpleXMLElement($xml_response);
$out = [];
foreach ($xml->registrations->registration as $row) {				foreach ($xml->registrations->registration as $row) {
  $out[] = $row;
}

echo(json_encode($out));
