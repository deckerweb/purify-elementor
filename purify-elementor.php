<?php
/**
 * Plugin Name: Purify Elementor
 * Plugin URI: https://github.com/deckerweb/purify-elementor
 * Description: Targeted Elementor advertising cleanup with optional AI, news and locked widget controls.
 * Version: 1.1.1
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Author: David Decker – DECKERWEB
 * Author URI: https://github.com/deckerweb
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: purify-elementor
 * Domain Path: /languages/
 * Update URI: https://github.com/deckerweb/purify-elementor
 * GitHub Plugin URI: https://github.com/deckerweb/purify-elementor
 *
 * Copyright © 2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'DDW_PE_PLUGIN_FILE', __FILE__ );
/** Load host translations before component initialization. @return void */
function ddw_pe_load_translations( $locale = '' ) {
    load_plugin_textdomain( 'purify-elementor', false, dirname( plugin_basename( DDW_PE_PLUGIN_FILE ) ) . '/languages/' );
    $locale = $locale ?: determine_locale();
    $file = __DIR__ . '/languages/purify-elementor-' . $locale . '.mo';
    if ( in_array( $locale, array( 'de_DE', 'de_DE_formal' ), true ) && is_readable( $file ) ) {
        load_textdomain( 'purify-elementor', $file, $locale );
    }
}
add_action( 'init', 'ddw_pe_load_translations', 0 );
add_action( 'change_locale', 'ddw_pe_load_translations', 0 );
require_once __DIR__ . '/includes/host-translations.php';
require_once __DIR__ . '/includes/core.php';
require_once __DIR__ . '/includes/changelog.php';
require_once __DIR__ . '/includes/integrations.php';
require_once __DIR__ . '/includes/update-validation.php';
if ( ! isset( $GLOBALS['ddw_purify_elementor_instance'] ) ) {
    $GLOBALS['ddw_purify_elementor_instance'] = new DDW_Purify_Elementor( false );
}
