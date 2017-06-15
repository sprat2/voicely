<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://voicely.org
 * @since      1.0.0
 *
 * @package    Voicely_Core
 * @subpackage Voicely_Core/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Voicely_Core
 * @subpackage Voicely_Core/admin
 * @author     Your Name <email@voicely.org>
 */
class Voicely_Core_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $voicely_core    The ID of this plugin.
	 */
	private $voicely_core;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $voicely_core       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $voicely_core, $version ) {

		$this->voicely_core = $voicely_core;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Voicely_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Voicely_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->voicely_core, plugin_dir_url( __FILE__ ) . 'css/voicely-core-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Voicely_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Voicely_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->voicely_core, plugin_dir_url( __FILE__ ) . 'js/voicely-core-admin.js', array( 'jquery' ), $this->version, false );

	}

}
