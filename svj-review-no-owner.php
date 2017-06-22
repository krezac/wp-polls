<?php
### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
    die('Access Denied');
}

### Poll Manager
$base_name = plugin_basename('wp-polls/polls-manager.php');
$base_page = 'admin.php?page='.$base_name; 

echo '<h1>Jednotky bez uživatele</h1>';

$units = $wpdb->get_results( $wpdb->prepare( "SELECT un.code, un.description FROM wp_svj_units un WHERE NOT EXISTS (SELECT * FROM wp_svj_unit_to_user uu WHERE uu.unit_id=un.id AND uu.active = 1)" ) );
if ($units) {
	echo '<h2>Jednotky bez uživatele</h2>';
	echo '<table class="wp-list-table fixed striped"><tr><th>Kod</th><th>Jednotka</th></tr>';
	foreach($units as $unit) {
		echo '<tr><td>';
		echo $unit->code;
		echo '</td><td>';
		echo $unit->description;
		echo '</td></tr>';
	}
	echo '</table>';
}
?>