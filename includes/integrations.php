<?php
/** Bundled deckerweb integrations; the snippet deliberately remains standalone. */
defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register_v2( DDW_PE_PLUGIN_FILE, array(), __DIR__ . '/deckerweb-plugin-library' );
/** Initialize this host's guarded public release updater. @return void */
add_action( 'init', static function () {
    // Public release repository; the matching Update URI prevents accidental mismatches.
    /**
     * Filter the public update repository, which must match the plugin Update URI.
     * @since 1.0.0
     * @param string $repository Repository URL; return an approved public deckerweb URL.
     */
    $repository = apply_filters( 'deckerweb/purify_elementor/update_repository', 'https://github.com/deckerweb/purify-elementor' );
    if ( ! is_string( $repository ) || ! preg_match( '~^https://github\.com/deckerweb/[A-Za-z0-9._-]+/?$~D', $repository ) ) { return; }
    $headers = get_file_data( DDW_PE_PLUGIN_FILE, array( 'update_uri' => 'Update URI' ) );
    if ( rtrim( $headers['update_uri'], '/' ) !== rtrim( $repository, '/' ) ) { return; }
    $updater_class = require __DIR__ . '/deckerweb-github-release-updater-v2.php';
    if ( ! is_string( $updater_class ) || ! class_exists( $updater_class, false ) || ! defined( $updater_class . '::SUPPORTS_HOST_TRANSLATIONS' ) || ! constant( $updater_class . '::SUPPORTS_HOST_TRANSLATIONS' ) ) { $GLOBALS['ddw_pe_updater_unavailable'] = true; return; }
    $language = 0 === strpos( DDW_Purify_Elementor::locale(), 'de' ) ? 'de' : 'en';
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
    /**
     * Filter localized update-dialog artwork URLs.
     * @since 0.2.0
     * @param array $artwork Icons and banners; return the modified maps.
     * @param string $language Selected artwork language, de or en.
     */
    $artwork = apply_filters( 'deckerweb/purify_elementor/update_artwork', $artwork, $language );
    $options = array( 'translate' => require __DIR__ . '/updater-translations.php' );
    if ( function_exists( 'deckerweb_library_updater_options_v1' ) && defined( $updater_class . '::SUPPORTS_CATALOG_PROVIDER' ) && constant( $updater_class . '::SUPPORTS_CATALOG_PROVIDER' ) ) {
        $options = array_merge( $options, deckerweb_library_updater_options_v1( DDW_PE_PLUGIN_FILE, $repository ) );
    }
    try {
        $updater = new $updater_class( DDW_PE_PLUGIN_FILE, $repository, 'Purify Elementor', DDW_Purify_Elementor::text( 'A calmer Elementor workspace.', 'Eine ruhigere Elementor-Oberfläche.' ), is_array( $artwork ) ? $artwork : array(), $options );
        $updater->register();
    } catch ( \InvalidArgumentException $error ) { return; }
} );
