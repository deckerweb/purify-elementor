<?php
/** Preserve website preferences and Elementor data; remove this host's update cache. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
$purify_elementor_updater_class = require __DIR__ . '/includes/deckerweb-github-release-updater-v2.php';
$purify_elementor_updater_class::clear_repository_cache( 'https://github.com/deckerweb/purify-elementor' );

require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v3( __DIR__ . '/purify-elementor.php' );
