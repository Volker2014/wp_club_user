<?php
function user_display_table($atts) { 
	$userData = get_user_data($atts);
	foreach ($userData as $user) {
		echo <<< EOM
<style>
table, th, td {
	border:0px black solid;
	margin-bottom:5px;
}
th, td {
    padding: 0px;
}
.avatar {
	width:100px;
}
.vorstand_table {
    margin-left:10px;
	margin-bottom:0px;
}
.vorstand_name {
    width:200px;
    font-size:16px;
    font-weight:bold;
}
.vorstand_position {
    font-size:16px;
    font-weight:bold;
}
.vorstand_mail {
    font-weight:bold;
}
</style>
<div>
  <table>
	  <tbody>
		  <tr>
			<td class="avatar"><img src='{$user['avatar']}' alt='{$user['first_name']} {$user['last_name']}'/></td>
			<td>
			  <table class="vorstand_table">
			  <tbody>
				<tr>
				  <td class="vorstand_name">{$user['first_name']} {$user['last_name']}</td>
				  <td class="vorstand_position">{$user['position']}</td>
				</tr>
				<tr>
				  <td>Anschrift:</td>
				  <td>{$user['street']}</td>
				</tr>
				<tr>
				  <td></td>
				  <td>{$user['postal']} {$user['city']}</td>
				</tr>
				<tr>
				  <td>Telefon:</td>
				  <td>{$user['phone']}</td>
				</tr>
				<tr>
				  <td>E - Mail:</td>
				  <td class="vorstand_mail">{$user['position_mail']}</td>
				</tr>
			  </tbody>
			  </table>
			</td>
		</tr>
	</tbody>
  </table>
</div>
EOM;
	}
}
?>