<?php

/**
 * Fired during plugin activation
 *
 * @link       http://voicely.org
 * @since      1.0.0
 *
 * @package    Voicely_Core
 * @subpackage Voicely_Core/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Voicely_Core
 * @subpackage Voicely_Core/includes
 * @author     Your Name <email@voicely.org>
 */
class Voicely_Core_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

	 	//Create the basic categories required by the plugin
		// Note: These are not removed on deactivation.
		//       This is a choice for stability over clean removal.
		//       Categories may be removed manully if desired.
		// Note: Disabled while working on other use case (unnecessary for now)
		// wp_create_category( 'Letter' );
		// wp_create_category( 'Article' );
		// wp_create_category( 'People' );
		// wp_create_category( 'Topics' );


		// Create the Facebook friends association table if it doesn't already exist
		//   (or modified any such existing table to fit the following form)
		global $wpdb;
		$table_name = $wpdb->prefix . "VoicelyRecordedFacebookFriends";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			lesserFBID bigint(15) UNSIGNED,
			largerFBID bigint(15) UNSIGNED,
			PRIMARY KEY  (lesserFBID, largerFBID)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // importing dbDelta
		dbDelta( $sql );
	}

}
