<?php
### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
    die('Access Denied');
}

### Poll Manager
$base_name = plugin_basename('wp-polls/polls-manager.php');
$base_page = 'admin.php?page='.$base_name; 

echo '<h1>Přehled vlastnictví jednotek (podle uživatele)</h1>';

$units = $wpdb->get_results( $wpdb->prepare( "SELECT us.user_login, us.user_email, un.code, un.description, un.ratio, uu.multiplier, uu.active FROM wp_svj_units un LEFT OUTER JOIN wp_svj_unit_to_user uu ON uu.unit_id=un.id LEFT OUTER JOIN wp_users us ON uu.user_id = us.id WHERE uu.active = 1 ORDER BY us.user_login, un.code" ) );
if ($units) {
	echo '<h2>Jednotky a uživatelé</h2>';
	echo '<table class="wp-list-table fixed striped"><tr><th>Kod</th><th>Jednotka</th><th>Login</th><th>Email</th><th>Podil jednotky [%]</th><th>Spoluvlastnictvi</th><th>Aktivni</th></tr>';
	foreach($units as $unit) {
		echo '<tr><td>';
		echo $unit->user_login;
		echo '</td><td>';
		echo $unit->user_email;
		echo '</td><td>';
		echo $unit->code;
		echo '</td><td>';
		echo $unit->description;
		echo '</td><td>';
		echo $unit->ratio;
		echo '</td><td>';
		echo $unit->multiplier;
		echo '</td><td>';
		echo $unit->active;
		echo '</td></tr>';
	}
	echo '</table>';
}

?>