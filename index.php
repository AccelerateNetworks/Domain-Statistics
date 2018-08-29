<?php
/*
	GNU Public License
	Version: GPL 3
*/

require_once "root.php";
require_once "resources/require.php";
require_once "resources/check_auth.php";

require "utils.php";

// add multi-lingual support
$language = new text;
$text = $language->get();

// additional includes
require_once "resources/header.php";
require_once "resources/paging.php";
//check permissions
if (permission_exists('domain_statistics')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<b>Domain Statistics</b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="tr_hover" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Domain</th>
						<th>Local Minutes Used</th>
						<th>Inbound Minutes Used</th>
						<th>Outbound Minutes</th>
						<th>Total Inbound/Outbound Minutes</th>
						<th>Total Cost</th>
					</tr>
				</thead>
			<?php
			$costpermin = 0.0069;
			$rowclass = "row_style0";
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
				echo "<td class=\"$rowclass\">".$domains['domain_name']."</td>";
				if(isset($domain_calltime['local'])){
					echo "<td class=\"$rowclass\">".$domain_calltime['local']."</td>";
				} else {
					echo "<td class=\"$rowclass\">0</td>";
				}
				if(isset($domain_calltime['inbound'])){
					echo "<td class=\"$rowclass\">".$domain_calltime['inbound']."</td>";
				} else {
					echo "<td class=\"$rowclass\">0</td>";
				}
				if(isset($domain_calltime['outbound'])){
					echo "<td class=\"$rowclass\">".$domain_calltime['outbound']."</td>";
				} else {
					echo "<td class=\"$rowclass\">0</td>";
				}
				echo "<td class=\"$rowclass\">".$totalinout."</td>";
				echo "<td class=\"$rowclass\">".$totalcost."</td>";
				echo "</tr>";
				if($rowclass == "row_style0") {
					$rowclass = "row_style1";
				} else {
					$rowclass = "row_style0";
				}
			}
			?>
			</table>
		</td>
	</tr>
</table>
<a href='export.php' class="btn" type="button">Export as CSV</a>
<?php require "footer.php";
