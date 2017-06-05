<?php
function get_total_user_ratio($user_id) {
	global $wpdb;
	$query = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(uu.multiplier*u.ratio) as user_ratio from wp_svj_unit_to_user uu INNER JOIN wp_svj_units u ON uu.unit_id=u.id WHERE uu.user_id=%d AND uu.active=1", $user_id ) );
	if ($query) {
		return doubleval($query->user_ratio);
	}
	return 0.0;
}


function get_total_voters_ratio($poll_id) {
	global $wpdb;
	$query = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(uu.multiplier*u.ratio) as attended_ratio from wp_pollsip pip INNER JOIN wp_svj_unit_to_user uu on pip.pollip_userid=uu.user_id AND uu.active=1 INNER JOIN wp_svj_units u ON uu.unit_id=u.id WHERE pollip_qid=%d", $poll_id ) );
	if ($query) {
		return doubleval($query->attended_ratio);
	}
	return 0.0;
}

function get_answer_svj_percentage($poll_id, $answer_id) {
	global $wpdb;
	$query = $wpdb->get_row( $wpdb->prepare( "SELECT SUM(uu.multiplier*u.ratio) as attended_ratio from wp_pollsip pip INNER JOIN wp_svj_unit_to_user uu on pip.pollip_userid=uu.user_id AND uu.active=1 INNER JOIN wp_svj_units u ON uu.unit_id=u.id WHERE pollip_qid=%d AND pollip_aid=%d", $poll_id, $answer_id ) );
	if ($query) {
		return doubleval($query->attended_ratio);
	}
	return 0.0;
}

?>