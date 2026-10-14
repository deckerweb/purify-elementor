<?php
/**
 * Translate embedded updater messages through the existing Purify Elementor domain.
 * @param string $message English updater source message.
 * @return string Localized host message, or the original unknown message.
 */
defined( 'ABSPATH' ) || exit;
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
        default:
            return $message;
    }
};
