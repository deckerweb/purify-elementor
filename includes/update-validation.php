<?php
/** Validate this host's candidate identity and offered version before installation. */
defined( 'ABSPATH' ) || exit;
/**
 * Reject mismatched host packages before the installed files are replaced.
 * @param string|WP_Error $source Extracted source directory or prior failure.
 * @param string $remote_source Temporary extraction directory.
 * @param object $upgrader WordPress upgrader instance.
 * @param array $context Plugin update context, including bulk per-package contexts.
 * @return string|WP_Error Valid source or a localized rejection.
 */
function ddw_pe_validate_update_source( $source, $remote_source, $upgrader, $context ) {
    if ( is_wp_error( $source ) || ! is_array( $context ) || ( $context['plugin'] ?? '' ) !== plugin_basename( DDW_PE_PLUGIN_FILE ) ) { return $source; }
    if ( isset( $context['type'] ) && 'plugin' !== $context['type'] ) { return $source; }
    if ( isset( $context['action'] ) && 'update' !== $context['action'] ) { return $source; }
    $single = 'plugin' === ( $context['type'] ?? '' ) && 'update' === ( $context['action'] ?? '' );
    $bulk = $upgrader instanceof Plugin_Upgrader && true === $upgrader->bulk;
    if ( ! $single && ! $bulk ) { return $source; }
    global $wp_filesystem;
    $file = trailingslashit( $source ) . 'purify-elementor.php';
    $error = new WP_Error( 'ddw_pe_invalid_update', DDW_Purify_Elementor::text( 'The update package does not match this plugin or its requirements.', 'Das Update-Paket passt nicht zu diesem Plugin oder seinen Voraussetzungen.' ) );
    if ( ! is_object( $wp_filesystem ) || ! $wp_filesystem->is_file( $file ) ) { return $error; }
    $size = $wp_filesystem->size( $file );
    if ( ! is_numeric( $size ) || $size < 1 || $size > 65536 ) { return $error; }
    $contents = $wp_filesystem->get_contents( $file );
    if ( ! is_string( $contents ) ) { return $error; }
    $headers = array();
    foreach ( array( 'Plugin Name', 'Version', 'Update URI', 'Requires PHP', 'Requires at least' ) as $header ) {
        $headers[ $header ] = preg_match( '/^[ \t\/*#@]*' . preg_quote( $header, '/' ) . ':(.*)$/mi', substr( $contents, 0, 8192 ), $match ) ? trim( $match[1] ) : '';
    }
    $updates = get_site_transient( 'update_plugins' );
    $offer = is_object( $updates ) ? ( $updates->response[ plugin_basename( DDW_PE_PLUGIN_FILE ) ] ?? null ) : null;
    $offered = is_object( $offer ) ? ( $offer->new_version ?? '' ) : '';
    if ( 'Purify Elementor' !== $headers['Plugin Name'] || 'https://github.com/deckerweb/purify-elementor' !== $headers['Update URI'] || ! preg_match( '/^\d+\.\d+\.\d+$/D', $headers['Version'] ) || $offered !== $headers['Version'] || ! version_compare( $headers['Version'], DDW_Purify_Elementor::VERSION, '>' ) ) { return $error; }
    foreach ( array( 'Requires PHP', 'Requires at least' ) as $key ) { if ( ! preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $headers[$key] ) ) { return $error; } }
    return is_php_version_compatible( $headers['Requires PHP'] ) && is_wp_version_compatible( $headers['Requires at least'] ) ? $source : $error;
}
add_filter( 'upgrader_source_selection', 'ddw_pe_validate_update_source', 30, 4 );
