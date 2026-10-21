<?php
/** Translate the pinned updater through this host domain. */
defined( 'ABSPATH' ) || exit;
/**
 * Resolve an updater source string in the host textdomain.
 * @param string $message English updater message.
 * @return string Translated host message, or original source text.
 */
return static function ( string $message ): string {
    // Literal calls let the host's normal translation extractor collect every source string.
    switch ( $message ) {
        case 'Private mode must be boolean.':
            return __( 'Private mode must be boolean.', 'purify-elementor' );
        case 'Invalid authentication provider.':
            return __( 'Invalid authentication provider.', 'purify-elementor' );
        case 'The plugin must be installed in a stable slug directory.':
            return __( 'The plugin must be installed in a stable slug directory.', 'purify-elementor' );
        case 'Invalid GitHub repository URL.':
            return __( 'Invalid GitHub repository URL.', 'purify-elementor' );
        case 'The private update could not be authorized. Check the repository credentials and refresh updates.':
            return __( 'The private update could not be authorized. Check the repository credentials and refresh updates.', 'purify-elementor' );
        case 'Could not create the update download file.':
            return __( 'Could not create the update download file.', 'purify-elementor' );
        case 'The private update download failed. Check credentials and try again.':
            return __( 'The private update download failed. Check credentials and try again.', 'purify-elementor' );
        case 'Could not access the update filesystem.':
            return __( 'Could not access the update filesystem.', 'purify-elementor' );
        case 'GitHub release does not contain the plugin main file.':
            return __( 'GitHub release does not contain the plugin main file.', 'purify-elementor' );
        case 'Could not prepare the GitHub release package.':
            return __( 'Could not prepare the GitHub release package.', 'purify-elementor' );
        case 'See the release on GitHub.':
            return __( 'See the release on GitHub.', 'purify-elementor' );
        case 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.':
            return __( 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.', 'purify-elementor' );
        case 'Manage installed plugins':
            return __( 'Manage installed plugins', 'purify-elementor' );
        case 'Dismiss this notice':
            return __( 'Dismiss this notice', 'purify-elementor' );
        case 'Permission denied.':
            return __( 'Permission denied.', 'purify-elementor' );
        case 'Public catalog callbacks require both providers and cannot be used in private mode.':
            return __( 'Public catalog callbacks require both providers and cannot be used in private mode.', 'purify-elementor' );
        case 'The approved release could not be verified.':
            return __( 'The approved release could not be verified.', 'purify-elementor' );
        case 'The approved release changed. Refresh updates first.':
            return __( 'The approved release changed. Refresh updates first.', 'purify-elementor' );
        default:
            return $message;
    }
};
