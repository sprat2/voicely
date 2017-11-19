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

    /**
    * Hook in and add a metabox for addressees metadata
    * 
    * NOTE: Changing meta-key names will leave the old ones in the database
    *       Must manually clear them if desired.
    *
    * NOTE: It is expected that the addressee name is the user's twitter handle, complete with "@".'
    *
    */
    function addressee_register_taxonomy_metabox() {
        $prefix = 'addressee_term_';

        // Metabox to add fields to categories and tags
        $cmb_term = new_cmb2_box( array(
            'id'               => $prefix . 'edit',
            'title'            => esc_html__( 'Addressee Metabox', 'cmb2' ), // Doesn't output for term boxes
            'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
            'taxonomies'       => array( 'addressee', ), // Tells CMB2 which taxonomies should have these fields
            'new_term_section' => true, // Will display in the "Add New Addressee" section
        ) );

        // Template input field for the addition of a new addressee meta-field:
        // $cmb_term->add_field( array(
        //     'name' => 'field_title',
        //     'desc' => 'field_description',
        //     'type' => 'text',
        //     'id'   => 'meta_key_name' // meta-key name
        // ) );

        // Pretty Name:
        $cmb_term->add_field( array(
            'name' => 'Recognizable Name',
            'desc' => 'e.g., "Chevy Chase", rather than "Cornelius Crane Chase"',
            'type' => 'text',
            'id'   => 'pretty_name' // meta-key name
        ) );

        // Full name:
        $cmb_term->add_field( array(
            'name' => 'Full Name',
            'desc' => 'e.g., "Cornelius Crane Chase" rather than "Chevy Chase"',
            'type' => 'text',
            'id'   => 'full_name' // meta-key name
        ) );

        // Address:
        $cmb_term->add_field( array(
            'name' => 'Address',
            'desc' => 'e.g., 123 Drury Ln., Salt Lake City, UT 84101',
            'type' => 'text',
            'id'   => 'address_1' // meta-key name
        ) );

        // Phone number:
        $cmb_term->add_field( array(
            'name' => 'Phone Number',
            'desc' => 'e.g., 8015551234',
            'type' => 'text',
            'id'   => 'phone_num_1' // meta-key name
        ) );

        // Email address:
        $cmb_term->add_field( array(
            'name' => 'Email Address',
            'desc' => 'e.g., johnsmith@example.com',
            'type' => 'text_email',
            'id'   => 'email_1' // meta-key name
        ) );

        // Facebook user ID
        $cmb_term->add_field( array(
            'name' => 'Facebook User ID',
            'desc' => 'e.g. 100000123456789',
            'type' => 'text',
            'id'   => 'fb_id' // meta-key name
        ) );

        // Facebook username
        $cmb_term->add_field( array(
            'name' => 'Facebook Username',
            'desc' => 'e.g. JohnQSmith',
            'type' => 'text',
            'id'   => 'fb_username' // meta-key name
        ) );
        
        // Instagram handle
        $cmb_term->add_field( array(
            'name' => 'Instagram Handle',
            'desc' => 'e.g. johnsmith',
            'type' => 'text',
            'id'   => 'ig_handle' // meta-key name
        ) );
        
        // Instagram handle
        $cmb_term->add_field( array(
            'name' => 'Image URL',
            'desc' => 'e.g. https://example.com/johnsmith.png',
            'type' => 'text_url',
            // 'protocols' => array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet' ), // Array of allowed protocols
            'id'   => 'img_url' // meta-key name
        ) );

    }

}
