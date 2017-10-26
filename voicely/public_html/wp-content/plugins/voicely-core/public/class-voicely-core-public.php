<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://voicely.org
 * @since      1.0.0
 *
 * @package    Voicely_Core
 * @subpackage Voicely_Core/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Voicely_Core
 * @subpackage Voicely_Core/public
 * @author     Your Name <email@example.com>
 */
class Voicely_Core_Public {

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
	 * @param      string    $voicely_core       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $voicely_core, $version ) {

		$this->voicely_core = $voicely_core;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->voicely_core, plugin_dir_url( __FILE__ ) . 'css/voicely-core-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		 wp_enqueue_script( $this->voicely_core, plugin_dir_url( __FILE__ ) . 'js/voicely-core-public.js', array( 'jquery' ), $this->version, false );
		 wp_enqueue_script( $this->voicely_core + '-2', plugin_dir_url( __FILE__ ) . 'js/login-logout-menu-entry.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Registers the 'letter' custom post type.
	 *
	 * @since    1.0.0
	 */
	function register_letter_post_type() {

		$labels = array(
			'name'                  => 'Letters',
			'singular_name'         => 'Letter',
			'menu_name'             => 'Letters',
			'name_admin_bar'        => 'Letter',
			'archives'              => 'Letter Archives',
			'attributes'            => 'Letter Attributes',
			'parent_item_colon'     => 'Letter Parent:',
			'all_items'             => 'All Letters',
			'add_new_item'          => 'Add New Letter',
			'add_new'               => 'Add New',
			'new_item'              => 'New Letter',
			'edit_item'             => 'Edit Letter',
			'update_item'           => 'Update Letter',
			'view_item'             => 'View Letter',
			'view_items'            => 'View Letters',
			'search_items'          => 'Search Letter',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into Letter',
			'uploaded_to_this_item' => 'Uploaded to this Letter',
			'items_list'            => 'Letters list',
			'items_list_navigation' => 'Letters list navigation',
			'filter_items_list'     => 'Filter letters list',
		);
		$args = array(
			'label'                 => 'Letter',
			'description'           => 'Open Letter',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields', ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'letter', $args );
	}



	/**
	 * Registers the addressees custom taxonomy.
	 *
	 *		Also adds the default addressee, "The World", if it doesn't already exist
	 *
	 * @since    1.0.0
	 */
	function register_addressee_taxonomy() {

		// Register the addressee taxonomy
		$labels = array(
			'name'                       => 'Addressees',
			'singular_name'              => 'Addressee',
			'menu_name'                  => 'Addressees',
			'all_items'                  => 'All addressees',
			'parent_item'                => 'Parent Addressee',
			'parent_item_colon'          => 'Parent Addressee:',
			'new_item_name'              => 'New Addressee',
			'add_new_item'               => 'Add New Addressee',
			'edit_item'                  => 'Edit Addressee',
			'update_item'                => 'Update Addressee',
			'view_item'                  => 'View Addressee',
			'separate_items_with_commas' => 'Separate addressees with commas',
			'add_or_remove_items'        => 'Add or remove addressees',
			'choose_from_most_used'      => 'Choose from the most written-to',
			'popular_items'              => 'Popular Addressees',
			'search_items'               => 'Search Addressees',
			'not_found'                  => 'Not Found',
			'no_terms'                   => 'No addressees',
			'items_list'                 => 'Addressees list',
			'items_list_navigation'      => 'Addressees list navigation',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_in_menu'				 => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'description'				 => 'A person to whom a letter may be addressed',
		);
		register_taxonomy( 'addressee', array( 'letter' ), $args );

		// Add the default addressee if not present
		if ( !( term_exists( 'The World', 'addressee' ) ) ) {
			$world_args = array(
				'description' => 'Open to all!',
			);
			$result = wp_insert_term( 'The World', 'addressee', $world_args );
			add_term_meta( $result['term_id'], 'pretty_name', 'The World', true );
		}
	}


	// Prevent users from accessing WP's login page
	function prevent_wp_login() {
		// WP tracks the current page - global the variable to access it
		global $pagenow;
		$action = (isset($_GET['action'])) ? $_GET['action'] : '';
		if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
			wp_redirect( get_bloginfo('url') );
			exit();
		}
	}

	// Handle/process Login/logout menu entry
	function loginout_setup_nav_menu_item( $item ) {
		// If this is the login/logout entry...
		if ( (basename($_SERVER['PHP_SELF']) != 'nav-menus.php') && isset( $item->url ) && ($item->url === '#loginout') ) {
			// Act on it appropriately
			$item->title = is_user_logged_in() ? 'Logout' : 'Login';
			$item->url = is_user_logged_in() ? wp_logout_url( get_permalink() ) : '';

			// Add an identifying DOM class for our JS script to identify this by in order to add the click event
			if ( !is_user_logged_in() )
				$item->classes[] = 'login-logout-in';
		}			
		return $item;
	}
}