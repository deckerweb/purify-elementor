<?php
/** The same categorized entries as the bilingual README files. */
defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/deckerweb-changelog-v1.php';
/**
 * Render escaped local release entries in the current host language.
 * @return string Structured accessible changelog HTML.
 */
function ddw_pe_changelog_html() {
    $language = 0 === strpos( DDW_Purify_Elementor::locale(), 'de' ) ? 'de' : 'en';
    $path = dirname( __DIR__ ) . '/CHANGELOG.' . $language . '.txt';
    $text = is_readable( $path ) ? file_get_contents( $path ) : '';
    return Deckerweb_Changelog_Renderer_V1::render( is_string( $text ) ? $text : '' );
}
