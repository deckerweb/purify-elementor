#!/usr/bin/env python3
"""Generate one standalone PHP snippet from the shared plugin engine."""
from pathlib import Path
root = Path(__file__).resolve().parents[1]
core = (root / 'includes/core.php').read_text()
assert core.startswith('<?php\n')
header = '''<?php
/** Purify Elementor 1.0.1 – generated standalone snippet.
 * Author: David Decker – DECKERWEB. License: GPL-2.0-or-later.
 * Generated from includes/core.php; do not maintain a separate cleanup engine.
 * Run everywhere, not admin-only (Elementor editor/AJAX need the same preferences).
 * Use either this snippet OR the plugin. In snippet managers omit the opening PHP tag.
 * Optional values below. Defaults match the plugin. No settings page or stored options.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
'''
configuration = '''
if ( ! isset( $GLOBALS['ddw_purify_elementor_instance'] ) ) {
    add_filter( 'deckerweb/purify_elementor/options', static function ( $options ) {
        return array_merge( $options, array(
            'cleanup' => true,
            'hide_locked_widgets' => false,
            'hide_ai' => false,
            'hide_news' => false,
            'show_home' => false,
            'hide_dashboard_widget' => true,
            'show_account' => true,
            'show_mcp' => true,
        ) );
    } );
    $GLOBALS['ddw_purify_elementor_instance'] = new DDW_Purify_Elementor( true );
}
'''
(root / 'purify-elementor-snippet.php.txt').write_text(header + core[6:] + configuration)
