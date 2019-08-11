<?php

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GS_WOO_HACK_MSG );
/**
 * Function triggered on activation for create table on db
 * 
 * @author Francesco Licandro
 */
if( ! function_exists( 'function_activation' ) ) {
	function function_activation() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'gs_woo_meta';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		meta_id bigint(20) NOT NULL AUTO_INCREMENT,
		wc_attribute_tax_id bigint(20) NOT NULL,
		meta_key varchar(255) DEFAULT '',
		meta_value longtext DEFAULT '',
		PRIMARY KEY (meta_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}