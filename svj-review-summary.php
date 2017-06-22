<?php
### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
    die('Access Denied');
}

### Poll Manager
$base_name = plugin_basename('wp-polls/polls-manager.php');
$base_page = 'admin.php?page='.$base_name; 

echo '<h1>Přehled vlastnictví jednotek - shrnuti</h1>';

$total = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(ratio) AS total_ratio FROM wp_svj_units" ) );
if ($total) {
	echo '<div>Celkem spoluvlastnickych podilu: <b>' . doubleval($total->total_ratio) . '%</b></div>';
}
$total_reg = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(uu.multiplier*un.ratio) AS registered_ratio FROM wp_svj_units un INNER JOIN wp_svj_unit_to_user uu ON uu.unit_id=un.id INNER JOIN wp_users us ON uu.user_id = us.id WHERE uu.active = 1" ) );
if ($total_reg) {
	echo '<div>Spoluvlastnickych podilu s uzivatelem: <b>' . doubleval($total_reg->registered_ratio) . '%</b></div>';
}

?>