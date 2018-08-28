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

function outputCSV($data) {
  $output = fopen("php://output", "w");
  foreach ($data as $row)
    fputcsv($output, $row); // here you can change delimiter/enclosure
  fclose($output);
}

$out = array();
  foreach(do_sql($db, "SELECT v_domains.domain_name, v_xml_cdr.direction, SUM(v_xml_cdr.duration) FROM v_xml_cdr, v_domains WHERE v_domains.domain_uuid = v_xml_cdr.domain_uuid AND v_xml_cdr.start_stamp > date_trunc('day', NOW() - interval '1 month') GROUP BY v_domains.domain_name, v_xml_cdr.direction;")  as $row) {
    $out[] = array($row['domain_name'], $row['direction'], $row['sum']);
  }
outputCSV($out);
