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


	}

}
