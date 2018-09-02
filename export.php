<?php
/*
 * Statistics on phone calls and the cost of each customer domain.
 * Copyright (c) 2018 Dan Ryan
 *
 * This file is part of Domain Statistics.
 *
 * Domain Statistics is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Domain Statistics is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with Domain Statistics. If not, see <https://www.gnu.org/licenses/>.
 */
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
// Set spreadsheet header
fputcsv($output, array("Domain", "Local Minutes Used", "Inbound Minutes Used", "Outbound Minutes", "Total Inbound/Outbound Minutes", "Total Cost")); // here you can change delimiter/enclosure
foreach(do_sql($db, "SELECT domain_uuid, domain_name FROM v_domains;") as $domains) {
  $domain_uuid = $domains['domain_uuid'];
  $domain_calltime = array();
  foreach(do_sql($db, "SELECT v_xml_cdr.direction, SUM(v_xml_cdr.duration) FROM v_xml_cdr WHERE v_xml_cdr.domain_uuid = '$domain_uuid' AND v_xml_cdr.start_stamp > date_trunc('day', NOW() - interval '1 month') GROUP BY v_xml_cdr.direction;") as $domainrow) {
    $domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;
    $domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;
    $domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;

  }
  $totalinout = $domain_calltime['inbound'] + $domain_calltime['outbound'];
  $totalcost = $totalinout * $costpermin;
  fputcsv($output, array($domains['domain_name'],
                    round($domain_calltime['local'], 2),
                    round($domain_calltime['inbound'], 2),
                    round($domain_calltime['outbound'], 2),
                    round($totalinout, 2),
                    round($totalcost, 2))); // here you can change delimiter/enclosure
}
fclose($output);
