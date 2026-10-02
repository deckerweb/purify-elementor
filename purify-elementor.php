<?php
/**
 * Plugin Name: Purify Elementor
 * Plugin URI: https://github.com/deckerweb/purify-elementor
 * Update URI: https://github.com/deckerweb/purify-elementor
 * Description: Targeted Elementor advertising cleanup with optional AI, news and locked widget controls.
 * Version: 1.0.1
 * Author: David Decker – DECKERWEB
 * Author URI: https://deckerweb.de/
 * License: GPL-2.0-or-later
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * Text Domain: purify-elementor
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'DDW_PE_PLUGIN_FILE', __FILE__ );
require_once __DIR__ . '/includes/core.php';
require_once __DIR__ . '/includes/changelog.php';
require_once __DIR__ . '/includes/integrations.php';
if ( ! isset( $GLOBALS['ddw_purify_elementor_instance'] ) ) {
    $GLOBALS['ddw_purify_elementor_instance'] = new DDW_Purify_Elementor( false );
}
