<?php
/** The same categorized entries as the bilingual README files. */
defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/deckerweb-changelog-v1.php';
function ddw_pe_changelog_html() {
    $language = 0 === strpos( get_user_locale(), 'de' ) ? 'de' : 'en';
    $path = dirname( __DIR__ ) . '/CHANGELOG.' . $language . '.txt';
    $text = is_readable( $path ) ? file_get_contents( $path ) : '';
    return Deckerweb_Changelog_Renderer_V1::render( is_string( $text ) ? $text : '' );
}
