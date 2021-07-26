<?php
global $wpdb;
$table = $wpdb->prefix.'email_sms_records_tb';
if (isset($_GET['action']) && $_GET['action']== 'delete') {
	$deleted = $wpdb->delete( $table, array( 'record_id' => $record_id ) );
}    

?>