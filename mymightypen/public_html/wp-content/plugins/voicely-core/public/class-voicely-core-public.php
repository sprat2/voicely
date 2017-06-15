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
		// Include plugin dir path for JS reference
		// wp_localize_script($this->voicely_core, 'pluginPublicUrl', array( 'pluginPublicUrl' => plugin_dir_url(__FILE__) ) );
	}

	/**
	 * Add social media buttons to index.php's login dialog.
	 *
	 * @since    1.0.0
	 */
	public function add_wsl_widget() {
		do_action( 'wordpress_social_login' );
	}

	/**
	 * Display frontend letter-writing dialog.
	 *
	 * @since    1.0.0
	 */
	public function write_letter_dialog() {
		if ( is_user_logged_in() ) {
			ob_start();
			include plugin_dir_path( __FILE__ ) . 'partials/letter-dialog.php';
			$form = ob_get_clean();
			return $form;
		}
		else {
			// Printed, not returned - That's bad.  Should buffer.
			echo '<p>Please log in to write a letter.</p><br><p>Or...</p>';
			do_action( 'wordpress_social_login' );
		}
	}

	/**
	 * Adds an image to letters.
	 *
	 * @since    1.0.0
	 */
	public function give_letter_an_image( $param1 ) {
		if ( in_category( 'Letter' ) ) {
			$param1 = get_site_url() . '/wp-content/uploads/2017/04/stockphoto-used-for-APIs.jpeg';
		}
		//echo ( $param['src'] . '<br><br>' );
		//var_export( $param1 );
		return $param1;
	}

	public function info_grab( $param1 ) {
		global $wp_query;
		if ( ( !is_admin() ) && $wp_query->is_main_query() ) {
		echo 'Data:';
		echo '<pre>';
		var_export ( $param1 );
		echo '</pre>';
		}
	}

	public function start_session() {
		session_start();
	}

	// Displays the stylized category selection display, beginning with the given category
	//   parameter as the starting category.
	public function voicely_frontend_letter_writing_dialog( $params ) {

		// This helper function prints the session variable's contents, surrounded with PRE tags
		function print_session_pretty() {
			// echo '<pre>';
			// var_export( $_SESSION );
			// echo '</pre>';		
		}

		// Include category selection dialog code
		include plugin_dir_path( __FILE__ ) . 'partials/category-selection-display.php';

		// Reset this page's session variables if the page was queried without any GET params
		// Then dislpay the person selection dialog
		if ( empty( $_GET ) ) {
			if ( isset( $_SESSION['people_selected'] ) )
				unset( $_SESSION['people_selected'] );
			if ( isset( $_SESSION['topics_selected'] ) )
				unset( $_SESSION['topics_selected'] );
			if ( isset( $_SESSION['letter_title'] ) )
				unset( $_SESSION['letter_title'] );
			if ( isset( $_SESSION['letter_body'] ) )
				unset( $_SESSION['letter_body'] );
			if ( isset( $_SESSION['fb_user_prefix'] ) )
				unset( $_SESSION['fb_user_prefix'] );
			if ( isset( $_SESSION['twitter_friend_tags'] ) )
				unset( $_SESSION['twitter_friend_tags'] );
			if ( isset( $_SESSION['twitter_token'] ) )
				unset( $_SESSION['twitter_token'] );
			print_session_pretty();

			$html_for_display = generate_display_given_head( 'People', 'category', 'people' );
			echo do_shortcode( $html_for_display );
		}
		// If mid-selecting person, display categories again
		elseif ( ( isset( $_GET['selecting'] ) ) &&
				 ( $_GET['selecting'] === 'people' ) &&
				 ( isset( $_GET['selection_people'] ) ) ) {
			print_session_pretty();
			$html_for_display = generate_display_given_head( $_GET['selection_people'], 'category', 'people' );
			echo do_shortcode( $html_for_display );
		}
		// Record person selected and display letter writing dialog
		elseif ( ( isset( $_GET['selection_people'] ) ) &&
				 ( !isset( $_GET['selecting'] ) ) ) {
			print_session_pretty();
			// Save the person selected
			//echo 'Selected: ' . $_GET['selection_people'] . '<br>';
			$_SESSION['people_selected'] = $_GET['selection_people']; // person ID
			
			// Display letter writing dialog:
			include plugin_dir_path( __FILE__ ) . 'partials/letter-dialog-v2.php';
		}
		// Letter is written.  Save its values and allow user to select tags/categories/topics.
		elseif ( isset( $_GET['letter_recorded'] ) ) {
			print_session_pretty();

			if ( isset( $_POST['letter_title'] ) ) {
				$_SESSION['letter_title'] = sanitize_text_field( $_POST['title'] );
			}
			if ( isset( $_POST['letter_body'] ) ) {
				$_SESSION['letter_body'] = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['contents'] ) ) );
			}

			$html_for_display = generate_display_given_head( 'Topics', 'category', 'topics' );
			echo do_shortcode( $html_for_display );
		}
		// If mid-selecting category, display categories again
		elseif ( ( isset( $_GET['selecting'] ) ) &&
				 ( $_GET['selecting'] === 'topics' ) &&
				 ( isset( $_GET['selection_topics'] ) ) ) {
			print_session_pretty();
			$html_for_display = generate_display_given_head( $_GET['selection_topics'], 'category', 'topics' );
			echo do_shortcode( $html_for_display );
		}
		// Category is selected.  Prompt user to share.
		elseif ( ( isset( $_GET['selection_topics'] ) ) &&
				 ( !isset( $_GET['selecting'] ) ) ) {
			print_session_pretty();
			// Save the category selected
			//echo 'Selected: ' . $_GET['selection_topics'] . '<br>';
			$_SESSION['topics_selected'] = $_GET['selection_topics']; // topic name

			// Include modal frame functions
			include plugin_dir_path( __FILE__ ) . 'partials/social-modal.php';

			// Create the Facebook sharing modal using the just-imported code
			echo getSocialModal( getFacebookBody(), '?facebook_complete' );
		}
		elseif ( isset( $_GET['facebook_complete'] ) ) {
			print_session_pretty();
			// Save the user's Facebook sharing message, selected friends, and auth token
			if ( isset( $_GET['fb_user_prefix'] ) )
				$_SESSION['fb_user_prefix'] = $_GET['fb_user_prefix'];
			if ( isset( $_GET['facebook_friend_tags'] ) )
				$_SESSION['facebook_friend_tags'] = $_GET['facebook_friend_tags'];
			if ( isset( $_GET['facebook_token'] ) )
				$_SESSION['facebook_token'] = $_GET['facebook_token'];

			// Include modal frame functions
			include plugin_dir_path( __FILE__ ) . 'partials/social-modal.php';

			// Create the Twitter sharing modal using the just-imported code
			echo getSocialModal( getTwitterBody(), '?twitter_complete' );
		}
		elseif ( isset( $_GET['twitter_complete'] ) ) {
			print_session_pretty();
			// Save the user's Twitter sharing message, selected friends, and auth token
			if ( isset( $_GET['tw_user_prefix'] ) )
				$_SESSION['tw_user_prefix'] = $_GET['tw_user_prefix'];
			if ( isset( $_GET['twitter_friend_tags'] ) )
				$_SESSION['twitter_friend_tags'] = $_GET['twitter_friend_tags'];
			if ( isset( $_GET['twitter_token'] ) )
				$_SESSION['twitter_token'] = $_GET['twitter_token'];

			// Include modal frame functions
			include plugin_dir_path( __FILE__ ) . 'partials/social-modal.php';

			// Create the Email sharing modal using the just-imported code
			echo getSocialModal( getEmailBody(), '?email_complete' );
		}
		elseif ( isset( $_GET['email_complete'] ) ) {
			print_session_pretty();
			// Save the user's Email sharing message and auth token, if applicable

			// Include modal frame functions
			include plugin_dir_path( __FILE__ ) . 'partials/social-modal.php';

			// Create the email-mail sharing modal using the just-imported code
			echo getSocialModal( getMailBody(), '?share_dialogs_complete' );

			// After this, reroute them to login/registration
		}
		elseif ( isset( $_GET['share_dialogs_complete'] ) ) {
			print_session_pretty();
			
			// Login/Registration:
			// XXX

			echo '<a href=.?login_reg_complete>login/registration...</a>';
		}
		elseif ( isset( $_GET['login_reg_complete'] ) ) {
			print_session_pretty();
			
			// Post user's letter, share via sharing methods, and redirect to homepage:
			// XXX

			echo '<pre>';
			if ( isset( $_SESSION ) )
				var_export( $_SESSION );
			else
				echo 'No data set.  Session variable empty.';
			echo '</pre>';

			echo '<a href=/>publishing & sharing fulfillment...</a>';
		}
		// If unexpected params are received, say so
		elseif ( true ) {
			print_session_pretty();
			echo 'Control structure bypassed.';
		}
	}

	/**
	 * Registers the 'people' custom post type.
	 *
	 * @since    1.0.0
	 */
	public function register_person_post_type_and_meta() {
		$labels = array (
			'name' => 'People',
			'public' => true,
			'taxonomies' => array( 'category' ),
			'singular_name' => 'person',
			'add_new_item' => 'Add new person',
			'add_new' => 'Add new',
			'edit_item' => 'Edit person',
			'new_item' => 'New person',
			'view_item' => 'View person',
			'view_items' => 'View people',
			'search_items' => 'Search people',
			'not_found' => 'No people found',
			'not_found_in_trash' => 'No people found in trash',
			'all_items' => 'All people',
			'archives' => 'People archives',
			'attributes' => 'Person attributes',
		);
		$args = array (
			'label' => 'People',
			'labels' => $labels,
			'description' => 'People',
			'taxonomies' => array( 'category' ),
			'hierarchical' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,		
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'capability_type' => 'post',
		);
		register_post_type( 'people', $args );
	
		register_taxonomy_for_object_type( 'category', 'person' );
	}



	/**
	 * Registers the 'people' custom post type.
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
			'menu_name'                  => 'Addressee',
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

			wp_insert_term( 'The World', 'addressee', $world_args );
		}
	}
}