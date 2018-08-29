<?php
require_once "root.php";
require_once "resources/require.php";
require_once "resources/check_auth.php";

require "utils.php";
//check permissions
if (permission_exists('domain_statistics')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");

$output = fopen("php://output", "w");
$costpermin = 0.0069;
foreach(do_sql($db, "SELECT domain_uuid, domain_name FROM v_domains;") as $domains) {
  $domain_uuid = $domains['domain_uuid'];
  $domain_calltime = array();
  fputcsv($output, array("Domain", "Local Minutes Used", "Inbound Minutes Used", "Outbound Minutes", "Total Inbound/Outbound Minutes", "Total Cost")); // here you can change delimiter/enclosure
  foreach(do_sql($db, "SELECT v_xml_cdr.direction, SUM(v_xml_cdr.duration) FROM v_xml_cdr WHERE v_xml_cdr.domain_uuid = '$domain_uuid' AND v_xml_cdr.start_stamp > date_trunc('day', NOW() - interval '1 month') GROUP BY v_xml_cdr.direction;") as $domainrow) {
    $domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;
    $domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;
    $domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;

  }
  $totalinout = $domain_calltime['inbound'] + $domain_calltime['outbound'];
  $totalcost = $totalinout * $costpermin;
  fputcsv($output, array($domains['domain_name'], $domain_calltime['local'], $domain_calltime['inbound'], $domain_calltime['outbound'],$totalinout, $totalcost)); // here you can change delimiter/enclosure
}
fclose($output);
