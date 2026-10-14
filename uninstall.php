<?php
/** Preserve website preferences and Elementor data; remove this host's update cache. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
delete_site_transient( 'ddw_ghru_' . substr( md5( 'https://github.com/deckerweb/purify-elementor' ), 0, 24 ) );

require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v3( __DIR__ . '/purify-elementor.php' );
