<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       crysto.netronit.com
 * @since      1.0.0
 *
 * @package    Music_chart
 * @subpackage Music_chart/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Music_chart
 * @subpackage Music_chart/admin
 * @author     Kunle Adekoya <crystoline@gmail.com>
 */
class Music_chart_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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
		 * defined in Music_chart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Music_chart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/music_chart-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'datatables','https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css' );
		wp_enqueue_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
		//wp_enqueue_style('prefix_bootstrap');
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
		 * defined in Music_chart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Music_chart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui');
        wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array( 'jquery' ));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/music_chart-admin.js', array(), $this->version, false );
		wp_enqueue_script('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_media();
		wp_localize_script( $this->plugin_name, 'wpApiSettings', array(
				'root' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' )
		) );
	}

	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		*/

		add_menu_page( 'My Music Chart', 'My Music Chart', 'manage_options', $this->plugin_name, array($this, 'display_music_chart_page'),'dashicons-playlist-audio'/* plugin_dir_url(__FILE__).'/img/icon_eministry.png' */, 5);
		add_submenu_page($this->plugin_name,'Songs', 'Songs', 'manage_options', $this->plugin_name.'-songs', array($this, 'display_songs_page'));
		add_submenu_page($this->plugin_name,'Albums', 'Albums', 'manage_options', $this->plugin_name.'-albums', array($this, 'display_albums_page'));
		add_submenu_page($this->plugin_name,'Artists', 'Artists', 'manage_options', $this->plugin_name.'-artists', array($this, 'display_artists_page'));
		add_submenu_page($this->plugin_name,'Settings', 'Settings', 'manage_options', $this->plugin_name.'-settings', array($this, 'display_plugin_setup_page'));
	}

	public function display_music_chart_page() {
		include_once( 'partials/music_chart-display.php' );

	}
	public function display_songs_page(){
		include_once( 'partials/music_chart-songs-display.php' );
	}
	public function display_albums_page(){
		include_once( 'partials/music_chart-albums-display.php' );
	}
	public function display_artists_page(){
		include_once( 'partials/music_chart-artists-display.php' );
	}

	public function display_plugin_setup_page(){
		include_once( 'partials/music_chart-admin-display.php' );
	}

}
