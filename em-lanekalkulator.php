<?php
/*
Plugin Name: EM lånekalkulator
Description: Lånekalkulator
Version: 1.0.6
GitHub Plugin URI: zeah/EM-lanekalkulator
*/

defined('ABSPATH') or die('Blank Space');

// constant for plugin location
define('LANEKALKULATOR_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once 'inc/lk-shortcode.php';
require_once 'inc/lk-customizer.php';

function init_emlanekalkulator() {
	LK_shortcode::get_instance();
	LK_customizer::get_instance();
}

add_action('plugins_loaded', 'init_emlanekalkulator');