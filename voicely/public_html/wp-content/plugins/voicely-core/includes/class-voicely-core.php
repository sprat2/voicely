<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://voicely.org
 * @since      1.0.0
 *
 * @package    Voicely_Core
 * @subpackage Voicely_Core/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Voicely_Core
 * @subpackage Voicely_Core/includes
 * @author     Your Name <email@voicely.org>
 */
class Voicely_Core {

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $voicely_core    The string used to uniquely identify this plugin.
     */
    protected $voicely_core;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->voicely_core = 'voicely-core';
        $this->version = '1.0.0';

        $this->load_dependencies();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Voicely_Core_Loader. Orchestrates the hooks of the plugin.
     * - Voicely_Core_i18n. Defines internationalization functionality.
     * - Voicely_Core_Admin. Defines all hooks for the admin area.
     * - Voicely_Core_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-voicely-core-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        if ( is_admin() ) {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-voicely-core-admin.php';
        }
        
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-voicely-core-public.php';

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Voicely_Core_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Voicely_Core_i18n();

        add_action( 'plugins_loaded', array($plugin_i18n, 'load_plugin_textdomain') );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Voicely_Core_Admin( $this->get_voicely_core(), $this->get_version() );

        add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_styles') );
        add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts') );

        // Register meta-field editing box for addressees
        //   Note: dependent on CMB2's hook
        add_action( 'cmb2_admin_init', array($plugin_admin, 'addressee_register_taxonomy_metabox') );


        // // Display plugin activation errors
        // function tl_save_error() {
        //     update_option( 'plugin_error',  ob_get_contents() );
        // }
        // add_action( 'activated_plugin', 'tl_save_error' );
        // echo get_option( 'plugin_error' );
        // file_put_contents( 'C:\errors' , ob_get_contents() ); // or any suspected variable
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Voicely_Core_Public( $this->get_voicely_core(), $this->get_version() );

        // Register custom types
        // Note: Not yet supported by Avada's blog displays
        // Letter post type
        add_action( 'init', array($plugin_public, 'register_letter_post_type') );
        // addressees taxonomy
        add_action( 'init', array($plugin_public, 'register_addressee_taxonomy') );

        // Enqueue default template & script files
        add_action( 'wp_enqueue_scripts', array($plugin_public, 'enqueue_styles') );
        add_action( 'wp_enqueue_scripts', array($plugin_public, 'enqueue_scripts') );

        add_shortcode( 'voicely_frontend_letter_dialog', array($plugin_public, 'write_letter_dialog') );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->set_locale();
        if ( is_admin() ) {
            $this->define_admin_hooks();
        }
        $this->define_public_hooks();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_voicely_core() {
        return $this->voicely_core;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
