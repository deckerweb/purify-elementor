<?php
/** Bundled deckerweb integrations; the snippet deliberately remains standalone. */
defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register( DDW_PE_PLUGIN_FILE, array(), __DIR__ . '/deckerweb-plugin-library' );
add_action( 'init', static function () {
    // Public release repository; the matching Update URI prevents accidental mismatches.
    $repository = apply_filters( 'deckerweb/purify_elementor/update_repository', 'https://github.com/deckerweb/purify-elementor' );
    if ( ! is_string( $repository ) || ! preg_match( '~^https://github\.com/deckerweb/[A-Za-z0-9._-]+/?$~D', $repository ) ) { return; }
    $headers = get_file_data( DDW_PE_PLUGIN_FILE, array( 'update_uri' => 'Update URI' ) );
    if ( rtrim( $headers['update_uri'], '/' ) !== rtrim( $repository, '/' ) ) { return; }
    if ( ! class_exists( '\Deckerweb\GitHubReleaseUpdater\V2\Updater' ) ) {
        require_once __DIR__ . '/deckerweb-github-release-updater-v2.php';
    }
    $language = 0 === strpos( get_user_locale(), 'de' ) ? 'de' : 'en';
    // Selected artwork: concept 02, Focus; localized bundled PNG/SVG assets.
    $base = 'assets/artwork/';
    $artwork = array(
        'icons' => array(
            'svg' => plugins_url( $base . 'icon-' . $language . '.svg', DDW_PE_PLUGIN_FILE ),
            '1x' => plugins_url( $base . 'icon-' . $language . '-128x128.png', DDW_PE_PLUGIN_FILE ),
            '2x' => plugins_url( $base . 'icon-' . $language . '.png', DDW_PE_PLUGIN_FILE ),
        ),
        'banners' => array(
            'low' => plugins_url( $base . 'banner-' . $language . '-772x250.png', DDW_PE_PLUGIN_FILE ),
            'high' => plugins_url( $base . 'banner-' . $language . '.png', DDW_PE_PLUGIN_FILE ),
        ),
    );
    $artwork = apply_filters( 'deckerweb/purify_elementor/update_artwork', $artwork, $language );
    try {
        $updater = new \Deckerweb\GitHubReleaseUpdater\V2\Updater( DDW_PE_PLUGIN_FILE, $repository, 'Purify Elementor', DDW_Purify_Elementor::text( 'A calmer Elementor workspace.', 'Eine ruhigere Elementor-Oberfläche.' ), is_array( $artwork ) ? $artwork : array() );
        $updater->register();
    } catch ( \InvalidArgumentException $error ) { return; }
} );
