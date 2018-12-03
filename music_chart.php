<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              crysto.netronit.com
 * @since             1.0.0
 * @package           Music_chart
 *
 * @wordpress-plugin
 * Plugin Name:       My Music Chart
 * Plugin URI:        http://crysto.netronit.com/wp/plugins/my_music_chart
 * Description:       A plugin that helps you manage a music chart and archives.
 * Version:           1.0.0
 * Author:            crystoline
 * Author URI:        crysto.netronit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       music_chart
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * all classes in the include folder will be loaded automatically on called
 * @see spl_autoload_register
 */
$auto_load_reg = spl_autoload_register(function($class_name){
	$em_plugin_dir =  plugin_dir_path( __FILE__  );
	$class_name = strtolower(str_replace('_', '-', $class_name));
	if(is_file($em_plugin_dir.'includes/class-'.$class_name.'.php')){
		require_once $em_plugin_dir.'includes/class-'.$class_name.'.php';
	}
		
}, false);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-music_chart-activator.php
 */
function activate_music_chart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-music_chart-activator.php';
	Music_chart_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-music_chart-deactivator.php
 */
function deactivate_music_chart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-music_chart-deactivator.php';
	Music_chart_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_music_chart' );
register_deactivation_hook( __FILE__, 'deactivate_music_chart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-music_chart.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
add_shortcode( 'my-music-chart', '_music_chart_process_shortcode' );
include_once 'includes/class-music_chart-shortcode.php';
function _music_chart_process_shortcode ($atts){
	$id	= $atts['id']; //id="1" top="10"
	$top = $atts['top'];
    $type = $atts['type'];
    switch($type){
        case 2: return Music_chart_Shortcode::music_chart($id, $top);
        default: return Music_chart_Shortcode::music_chart2($id, $top);
    }

};
function run_music_chart() {

	$plugin = new Music_chart();
	$plugin->run();

}
run_music_chart();
