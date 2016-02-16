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
						<th>Name</th>
						<th>Last Access</th>
						<th>Enabled</th>
						<th>Actions</th>
					</tr>
				</thead>
			<?php
			$rowclass = "row_style0";
			foreach(o_sql($db, "SELECT * FROM cdr_api_keys WHERE domain_uuid = :domain_uuid", array(':domain_uuid' => $domain_uuid)) as $row) {
				echo "<td class=\"$rowclass\">".row['name']."</td>";
				echo "<td class=\"$rowclass\">(not available)</td>";
				echo "<td class=\"$rowclass\">".$row['enabled']."</td>";
				echo "<td class=\"$rowclass\">[ <a href=\"#\">edit</a> ] [ <]</td>";
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
