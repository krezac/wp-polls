<?php
### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
    die('Access Denied');
}

### Poll Manager
$base_name = plugin_basename('wp-polls/polls-manager.php');
$base_page = 'admin.php?page='.$base_name; 

echo '<h1>Přehled vlastnictví jednotek</h1>';

$units = $wpdb->get_results( $wpdb->prepare( "SELECT un.code, un.description, us.user_login, us.user_email, un.ratio, uu.multiplier, uu.active FROM wp_svj_units un LEFT OUTER JOIN wp_svj_unit_to_user uu ON uu.unit_id=un.id LEFT OUTER JOIN wp_users us ON uu.user_id = us.id WHERE uu.active IS NULL OR uu.active = 1" ) );
if ($units) {
	echo '<h2>Jednotky a uživatelé</h2>';
	echo '<table class="wp-list-table fixed striped"><tr><th>Kod</th><th>Jednotka</th><th>Login</th><th>Email</th><th>Podil jednotky [%]</th><th>Spoluvlastnictvi</th><th>Aktivni</th></tr>';
	foreach($units as $unit) {
		echo '<tr><td>';
		echo $unit->code;
		echo '</td><td>';
		echo $unit->description;
		echo '</td><td>';
		echo $unit->user_login;
		echo '</td><td>';
		echo $unit->user_email;
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
$total = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(ratio) AS total_ratio FROM wp_svj_units" ) );
if ($total) {
	echo '<div>Celkem spoluvlastnickych podilu: <b>' . doubleval($total->total_ratio) . '%</b></div>';
}
$total_reg = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(uu.multiplier*un.ratio) AS registered_ratio FROM wp_svj_units un INNER JOIN wp_svj_unit_to_user uu ON uu.unit_id=un.id INNER JOIN wp_users us ON uu.user_id = us.id WHERE uu.active = 1" ) );
if ($total_reg) {
	echo '<div>Spoluvlastnickych podilu s uzivatelem: <b>' . doubleval($total_reg->registered_ratio) . '%</b></div>';
}

$usersnounit = $wpdb->get_results( $wpdb->prepare( "SELECT u.user_login, u.user_email FROM wp_users u WHERE u.id NOT IN (SELECT DISTINCT uu.user_id FROM wp_svj_unit_to_user uu WHERE u.id=uu.user_id AND active=1)" ) );
if ($usersnounit) {
	echo '<h2>Uživatelé bez jednotky</h2>';
	echo '<table class="wp-list-table fixed striped"><tr><th>Login</th><th>Email</th></tr>';
	foreach($usersnounit as $usernounit) {
		echo '<tr><td>';
		echo $usernounit->user_login;
		echo '</td><td>';
		echo $usernounit->user_email;
		echo '</td></tr>';
	}
	echo '</table>';
}

$unitsnouser = $wpdb->get_results( $wpdb->prepare( "SELECT code,description FROM wp_svj_units un WHERE un.id NOT IN (SELECT DISTINCT uu.unit_id FROM wp_svj_unit_to_user uu WHERE un.id=uu.unit_id AND uu.active=1)" ) );
if ($unitsnouser) {
	echo '<h2>Jednotky bez uživatele</h2>';
	echo '<table class="wp-list-table fixed striped"><tr><th>Kod</th><th>Jednotka</th></tr>';
	foreach($unitsnouser as $unitnouser) {
		echo '<tr><td>';
		echo $unitnouser->code;
		echo '</td><td>';
		echo $unitnouser->description;
		echo '</td></tr>';
	}
	echo '</table>';
}

?>