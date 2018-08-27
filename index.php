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
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<b>CDR API</b>
					</td>
					<td width="30%" align="right" valign="top">
						<a href="api_key_edit.php" class="btn">New</a>
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
						<th>Type</th>
						<th>Total Seconds</th>
						<th>Total</th>
					</tr>
				</thead>
			<?php
			$rowclass = "row_style0";
			foreach(do_sql($db, "SELECT v_domains.domain_name, v_xml_cdr.direction, SUM(v_xml_cdr.duration) FROM v_xml_cdr, v_domains WHERE v_domains.domain_uuid = v_xml_cdr.domain_uuid AND v_xml_cdr.start_stamp > date_trunc('day', NOW() - interval '1 month') GROUP BY v_domains.domain_name, v_xml_cdr.direction;", array(':domain_uuid' => $domain_uuid)) as $row) {
				echo "<td class=\"$rowclass\">".$row['domain_name']."</td>";
				echo "<td class=\"$rowclass\">".$row['direction']."</td>";
				echo "<td class=\"$rowclass\">".$row['sum']."</td>";
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
<?php require "footer.php";
