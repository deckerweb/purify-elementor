<?php
/** Remove only this plugin's per-site settings. Elementor data remains untouched. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
// Network-wide iteration is intentionally avoided; documented for multisite.
delete_option( 'ddw_purify_elementor' );
