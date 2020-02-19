<?php
 /*
	Domain Statistics for FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX
	The Initial Developer of the Original Code is
	Dan Ryan <dan@acceleratenetworks.com>
	Portions created by the Initial Developer are Copyright (C) 2020
	the Initial Developer. All Rights Reserved.
	Contributor(s):
	None (yet)
*/

require_once "root.php";
require_once "resources/require.php";
require_once "resources/check_auth.php";

// Check if we should export a CSV
if ($_POST['submit'] == "Export as CSV") {
	require_once "export.php";
	exit;
}

require_once "utils.php";

// add multi-lingual support (un-implemented)
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
//get http post variables and set them to php variables
$costpermin = 0.0069;
if (!empty($_POST["costpermin"])) {
	$costpermin = $_POST["costpermin"];
}
$start_stamp_begin = date("Y-m-d", strtotime("-1 months"));
if (!empty($_POST["start_stamp_begin"])) {
	$start_stamp_begin = $_POST["start_stamp_begin"];
}
$start_stamp_end = date("Y-m-d");
if (!empty($_POST["start_stamp_end"])) {
	$start_stamp_end = $_POST["start_stamp_end"];
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<form id='frm_export' method='post' action='index.php'>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<tr>
								<b>Domain Statistics<?php echo $_POST['submit'];?></b>
								<input class="btn" name="submit" value="Export as CSV" type="submit" style="float: right;">
								<table border="0" cellspacing="0" cellpadding="0" width="100%">
									<tr>
										<td>
											<table border="0" cellspacing="0" cellpadding="0" width="100%">
												<tr>
													<td class='vncell' valign='top' nowrap='nowrap'>Date Range</td>
													<td class='vtable' align='right' style='position: relative; min-width: 250px;'>
														<input type='text' class='formfld datepicker' style='min-width: 115px; width: 115px;' name='start_stamp_begin' placeholder="<?php echo $text['label-from']?>" value="<?php echo escape($start_stamp_begin);?>">
														<input type='text' class='formfld datepicker' style='min-width: 115px; width: 115px;' name='start_stamp_end' placeholder="<?php echo $text['label-to']?>" value="<?php echo escape($start_stamp_end);?>">
													</td>
												</tr>
											</table>
										</td>
										<td>
											<table border="0" cellspacing="0" cellpadding="0" width="100%">
												<tr>
													<td class='vncell' valign='top' nowrap='nowrap'>Cost per minute</td>
													<td class='vtable' align='right' style='position: relative; min-width: 125px;'>
														<input class='formfld' type='text' name='costpermin' maxlength='255' value="<?php echo escape($costpermin);?>" required='required'>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</tr>
							<td style="padding-top: 8px;" align="right" nowrap="">
								<input class="btn" value="Reset" onclick="document.location.href='index.php';" type="button">
								<input class="btn" name="submit" value="Search" type="submit">
							</td>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<table class="tr_hover" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Domain</th>
						<th>Local Minutes</th>
						<th>Inbound Minutes</th>
						<th>Outbound Minutes</th>
						<th>Total External Minutes</th>
						<th>Total Cost</th>
					</tr>
				</thead>
			<?php

			$rowclass = "row_style0";
			foreach(do_sql($db, "SELECT domain_uuid, domain_name FROM v_domains;") as $domains) {
				$domain_uuid = $domains['domain_uuid'];
				$domain_calltime = array();
				foreach(do_sql($db, "SELECT v_xml_cdr.direction, SUM(v_xml_cdr.duration) FROM v_xml_cdr WHERE v_xml_cdr.domain_uuid = :domain_uuid AND v_xml_cdr.start_stamp > date(:start_stamp_begin) AND v_xml_cdr.start_stamp < date(:start_stamp_end) GROUP BY v_xml_cdr.direction;", array(":domain_uuid" => $domain_uuid, ":start_stamp_end" => $start_stamp_end, ":start_stamp_begin" => $start_stamp_begin)) as $domainrow) {
					$domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;
					$domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;
					$domain_calltime[$domainrow['direction']] = $domainrow['sum']/60;

				}
				$totalinout = $domain_calltime['inbound'] + $domain_calltime['outbound'];
				$totalcost = $totalinout * $costpermin;
				echo "<td class=\"$rowclass\">".$domains['domain_name']."</td>";
				if(isset($domain_calltime['local'])){
					echo "<td class=\"$rowclass\">".round($domain_calltime['local'], 2)."</td>";
				} else {
					echo "<td class=\"$rowclass\">0</td>";
				}
				if(isset($domain_calltime['inbound'])){
					echo "<td class=\"$rowclass\">".round($domain_calltime['inbound'], 2)."</td>";
				} else {
					echo "<td class=\"$rowclass\">0</td>";
				}
				if(isset($domain_calltime['outbound'])){
					echo "<td class=\"$rowclass\">".round($domain_calltime['outbound'], 2)."</td>";
				} else {
					echo "<td class=\"$rowclass\">0</td>";
				}
				echo "<td class=\"$rowclass\">".round($totalinout, 2)."</td>";
				echo "<td class=\"$rowclass\">".round($totalcost, 2)."</td>";
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
