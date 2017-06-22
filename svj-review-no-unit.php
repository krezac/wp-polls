<?php
### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
    die('Access Denied');
}

### Poll Manager
$base_name = plugin_basename('wp-polls/polls-manager.php');
$base_page = 'admin.php?page='.$base_name; 

echo '<h1>Uživatelé bez jednotky</h1>';


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

?>