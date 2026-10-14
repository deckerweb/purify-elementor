<?php
/** Shared cleanup engine; embedded unchanged in the generated snippet. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! class_exists( 'DDW_Purify_Elementor', false ) ) {
final /** Coordinate targeted Elementor cleanup and per-site preferences. */
class DDW_Purify_Elementor {
    const VERSION = '1.1.0';
    /** @var string[] Free versions with audited cleanup integration points. */
    const AUDITED_ELEMENTOR_VERSIONS = array( '4.3.3', '4.3.4', '4.3.5' );
    const OPTION = 'ddw_purify_elementor';
    /** @var bool Whether this instance is the standalone snippet. */
    private $snippet;
    /**
     * Register cleanup hooks and optional settings-page hooks.
     * @param bool $snippet Whether to omit plugin-only administration.
     * @return void No return value.
     */
    public function __construct( $snippet = false ) {
        $this->snippet = $snippet;
        add_action( 'elementor/init', array( $this, 'cleanup_callbacks' ), 999 );
        add_action( 'admin_init', array( $this, 'cleanup_callbacks' ), 999 );
        add_action( 'admin_menu', array( $this, 'menus' ), PHP_INT_MAX );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_navigation_scripts' ), 999 );
        add_filter( 'elementor/editor-one/menu/excluded_level4_slugs', array( $this, 'excluded_navigation' ), 999 );
        add_filter( 'elementor/editor-one/menu/excluded_level3_slugs', array( $this, 'excluded_navigation' ), 999 );
        add_action( 'admin_head', array( $this, 'clean_menus' ), PHP_INT_MAX );
        add_filter( 'elementor_one/upgrade_available', array( $this, 'upgrade_available' ), PHP_INT_MAX );
        add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget' ), PHP_INT_MAX );
        add_filter( 'plugin_action_links_elementor/elementor.php', array( $this, 'plugin_links' ), 999 );
        add_filter( 'elementor/admin/dashboard_overview_widget/footer_actions', array( $this, 'dashboard_links' ), 999 );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ), 999 );
        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ), 999 );
        add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_scripts' ), 999 );
        add_filter( 'elementor/editor/localize_settings', array( $this, 'editor_settings' ), 999 );
        add_action( 'admin_print_footer_scripts', array( $this, 'mcp_promotion' ), 0 );
        add_filter( 'get_user_option_elementor_enable_ai', array( $this, 'ai_preference' ), 999, 3 );
        if ( ! $snippet ) {
            add_action( 'admin_init', array( $this, 'settings' ) );
            if ( defined( 'DDW_PE_PLUGIN_FILE' ) ) { add_filter( 'plugin_action_links_' . plugin_basename( DDW_PE_PLUGIN_FILE ), array( $this, 'settings_link' ) ); }
            add_action( 'admin_enqueue_scripts', array( $this, 'settings_assets' ) );
            add_action( 'admin_notices', array( $this, 'admin_notice' ) );
        }
    }
    /**
     * Return the eight default website preferences.
     * @return array Result of this operation.
     */
    public static function defaults() {
        return array( 'cleanup' => true, 'hide_locked_widgets' => false, 'hide_ai' => false, 'hide_news' => false, 'show_home' => false, 'hide_dashboard_widget' => false, 'show_account' => true, 'show_mcp' => true );
    }
    /**
     * Merge stored website preferences and the options filter.
     * @return array Result of this operation.
     */
    public function options() {
        $defaults = self::defaults();
        if ( $this->snippet ) { $defaults['hide_dashboard_widget'] = true; }
        $stored = $this->snippet ? array() : get_option( self::OPTION, array() );
        $options = self::sanitize( is_array( $stored ) ? array_merge( $defaults, $stored ) : $defaults );
        // Snippet users can supply the same options via this public filter.
        /**
         * Filter the normalized cleanup preferences.
         * @since 0.1.0
         * @param array $options Boolean preferences; return the modified array.
         */
        $filtered = apply_filters( 'deckerweb/purify_elementor/options', $options );
        return is_array( $filtered ) ? self::sanitize( array_merge( $options, $filtered ) ) : $options;
    }
    /**
     * Normalize known preference keys to strict booleans.
     * @param mixed $input Submitted preference values.
     * @return array Result of this operation.
     */
    public static function sanitize( $input ) {
        $out = array();
        foreach ( self::defaults() as $key => $default ) {
            $out[ $key ] = is_array( $input ) && isset( $input[ $key ] ) && in_array( $input[ $key ], array( true, 1, '1' ), true );
        }
        return $out;
    }
    /**
     * Determine whether Elementor is available for cleanup.
     * @return bool Result of this operation.
     */
    private function active() { return defined( 'ELEMENTOR_VERSION' ) && in_array( ELEMENTOR_VERSION, self::AUDITED_ELEMENTOR_VERSIONS, true ); }
    /**
     * Translate an English host message using its textdomain.
     * @param string $en English source message.
     * @param string $de German fallback for standalone snippets.
     * @return string Result of this operation.
     */
    public static function text( $en, $de ) {
        if ( function_exists( '__' ) && ! ( isset( $GLOBALS['ddw_purify_elementor_instance'] ) && $GLOBALS['ddw_purify_elementor_instance']->snippet ) ) { return __( $en, 'purify-elementor' ); }
        return 0 === strpos( get_user_locale(), 'de' ) ? $de : $en;
    }
    /**
     * Remove only audited promotional callbacks; preserve technical notices.
     * @return void No return value.
     */
    public function cleanup_callbacks() {
        if ( ! $this->active() || ! $this->options()['cleanup'] ) { return; }
        $rules = array(
            'admin_footer_text' => array( 'Elementor\\Core\\Admin\\Admin' => array( 'admin_footer_text' ) ),
            'current_screen' => array( 'Elementor\\Modules\\Promotions\\Conversion_Banner' => array( 'maybe_register_banner_hooks' ) ),
            'admin_print_footer_scripts-index.php' => array(
                'Elementor\\Modules\\Promotions\\Pointers\\Birthday' => array( 'enqueue_notice' ),
                'Elementor\\Modules\\Promotions\\Pointers\\Black_Friday' => array( 'enqueue_notice' ),
                'Elementor\\Modules\\Promotions\\Pointers\\Promotional_Pointer' => array( 'enqueue_notice' ),
            ),
        );
        global $wp_filter;
        foreach ( $rules as $tag => $classes ) {
            if ( empty( $wp_filter[ $tag ] ) || ! isset( $wp_filter[ $tag ]->callbacks ) ) { continue; }
            foreach ( $wp_filter[ $tag ]->callbacks as $priority => $callbacks ) {
                foreach ( $callbacks as $entry ) {
                    $fn = $entry['function'];
                    if ( ! is_array( $fn ) || ! is_object( $fn[0] ) ) { continue; }
                    $class = get_class( $fn[0] );
                    if ( isset( $classes[ $class ] ) && in_array( $fn[1], $classes[ $class ], true ) ) {
                        remove_filter( $tag, $fn, $priority );
                    }
                }
            }
        }
    }
    /**
     * Remove the named Elementor upgrade link when cleanup is enabled.
     * @param array $links Existing links to preserve.
     * @return array Result of this operation.
     */
    public function plugin_links( $links ) {
        if ( $this->active() && $this->options()['cleanup'] ) { unset( $links['go_pro'] ); }
        return $links;
    }
    /**
     * Remove named promotional dashboard actions.
     * @param array $links Existing links to preserve.
     * @return array Result of this operation.
     */
    public function dashboard_links( $links ) {
        if ( ! $this->active() ) { return $links; }
        $o = $this->options();
        if ( $o['cleanup'] ) { unset( $links['go-pro'], $links['ai'] ); }
        return $links;
    }
    /**
     * Control Elementor One upgrade-menu registration.
     * @param bool $available Existing upgrade availability.
     * @return bool Result of this operation.
     */
    public function upgrade_available( $available ) {
        return $this->active() && $this->options()['cleanup'] ? false : $available;
    }
    /**
     * Normalize bare page slugs and escaped admin-page URLs.
     * @param mixed $value Existing value to normalize or filter.
     * @return string Result of this operation.
     */
    public static function menu_slug( $value ) {
        if ( ! is_string( $value ) ) { return ''; }
        $url = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' );
        $query = parse_url( $url, PHP_URL_QUERY );
        if ( is_string( $query ) ) {
            parse_str( $query, $args );
            if ( isset( $args['page'] ) && is_string( $args['page'] ) ) { return $args['page']; }
        }
        return $value;
    }
    /**
     * Clean navigation and register the website settings page.
     * @return void No return value.
     */
    public function menus() {
        $this->clean_menus();
        $this->shortcuts();
        if ( ! $this->snippet ) {
            add_options_page( 'Purify Elementor', 'Purify Elementor', 'manage_options', 'purify-elementor', array( $this, 'page' ) );
        }
    }
    /**
     * Remove explicitly audited submenu entries using current preferences.
     * @return void No return value.
     */
    public function clean_menus() {
        if ( $this->active() ) {
            $o = $this->options();
            global $submenu;
            foreach ( (array) $submenu as $parent => $items ) {
                foreach ( $items as $item ) {
                    if ( ! isset( $item[2] ) ) { continue; }
                    $slug = self::menu_slug( $item[2] );
                    $upgrade = $o['cleanup'] && in_array( $slug, array( 'go_elementor_pro', 'elementor-one-upgrade', 'admin_menu_promo' ), true );
                    $home = ! $o['show_home'] && 'elementor-home' === $slug;
                    $account = ! $o['show_account'] && in_array( $slug, array( 'elementor-connect-account', 'elementor-connect' ), true );
                    $mcp = ! $o['show_mcp'] && 'elementor-mcp' === $slug;
                    $pro_only = $o['cleanup'] && ! self::has_pro() && in_array( $slug, self::pro_menu_slugs(), true );
                    if ( $upgrade || $home || $account || $mcp || $pro_only ) {
                        remove_submenu_page( $parent, $item[2] );
                    }
                }
            }
        }
    }
    /**
     * Return audited Pro-only navigation identifiers.
     * @return array Result of this operation.
     */
    public static function pro_menu_slugs() {
        return array( 'elementor-app', 'elementor-theme-builder', 'e-form-submissions', 'popup_templates', 'elementor-custom-elements', 'elementor-editor-custom-elements', 'elementor_custom_fonts', 'elementor_custom_icons', 'elementor_custom_code' );
    }
    /**
     * Extend Elementor sidebar exclusions while preserving existing exclusions.
     * @param array $slugs Existing excluded navigation slugs.
     * @return array Result of this operation.
     */
    public function excluded_navigation( $slugs ) {
        if ( ! $this->active() ) { return $slugs; }
        $o = $this->options();
        if ( $o['cleanup'] && ! self::has_pro() ) { $slugs = array_merge( $slugs, self::pro_menu_slugs() ); }
        if ( ! $o['show_account'] ) { $slugs[] = 'elementor-connect-account'; }
        if ( ! $o['show_mcp'] ) { $slugs[] = 'elementor-mcp'; }
        return array_values( array_unique( $slugs ) );
    }
    /**
     * Add capability-scoped links to existing Elementor pages.
     * @return void No return value.
     */
    public function shortcuts() {
        if ( ! $this->active() ) { return; }
        global $submenu;
        $parent = isset( $submenu['elementor-home'] ) ? 'elementor-home' : 'elementor';
        $links = array(
            array( 'Templates', 'Templates', 'edit_posts', 'edit.php?post_type=elementor_library' ),
            array( 'Add New Template', 'Neues Template hinzufügen', 'edit_posts', 'edit.php?post_type=elementor_library#add_new' ),
            array( 'Settings', 'Einstellungen', 'manage_options', 'admin.php?page=elementor-settings' ),
            array( 'Element Manager', 'Element-Manager', 'manage_options', 'admin.php?page=elementor-element-manager' ),
        );
        foreach ( $links as $link ) {
            $existing = array_column( $submenu[ $parent ] ?? array(), 2 );
            if ( ! in_array( $link[3], $existing, true ) ) {
                add_submenu_page( $parent, self::text( $link[0], $link[1] ), self::text( $link[0], $link[1] ), $link[2], $link[3] );
            }
        }
    }
    /**
     * Clean localized Elementor navigation and dynamic upgrade controls.
     * @return void No return value.
     */
    public function admin_navigation_scripts() {
        if ( ! $this->active() ) { return; }
        $o = $this->options();
        $blocked = $this->excluded_navigation( array() );
        $script = 'const ddwPeBlockedAdminSlugs=' . wp_json_encode( $blocked ) . ';';
        $script .= <<<'JS'
(() => {
    const config = window.editorOneSidebarConfig;
    if (!config) return;
    const blocked = new Set(ddwPeBlockedAdminSlugs);
    const keep = item => !blocked.has(item.slug);
    if (Array.isArray(config.menuItems)) config.menuItems = config.menuItems.filter(keep);
    if (config.level4Groups && typeof config.level4Groups === 'object') {
        Object.entries(config.level4Groups).forEach(([key, group]) => {
            if (blocked.has(key)) { delete config.level4Groups[key]; return; }
            if (group && Array.isArray(group.items)) group.items = group.items.filter(keep);
        });
    }
})();
JS;
        wp_add_inline_script( 'editor-one-sidebar-navigation', $script, 'before' );
        if ( ! $o['cleanup'] || self::has_pro() ) { return; }
        $top = <<<'JS'
(() => {
    const clean = () => {
        const root = document.getElementById('editor-one-top-bar');
        if (!root) return;
        root.querySelectorAll('[data-test="header-upgrade-button"]').forEach(item => item.style.setProperty('display','none','important'));
        root.querySelectorAll('button, a, [role="button"]').forEach(item => {
            const label = (item.textContent || item.getAttribute('aria-label') || '').trim();
            if (/^(Upgrade|Upgrade Now|Jetzt upgraden|Upgrade Sale Now)$/i.test(label)) item.style.setProperty('display','none','important');
        });
    };
    const start = () => {
        const root = document.getElementById('editor-one-top-bar');
        if (!root) return;
        clean();
        new MutationObserver(clean).observe(root,{childList:true,subtree:true});
    };
    if (document.readyState==='loading') document.addEventListener('DOMContentLoaded',start,{once:true});
    else start();
})();
JS;
        wp_add_inline_script( 'editor-one-top-bar', $top, 'after' );
    }
    /**
     * Remove only the Elementor dashboard widget when requested.
     * @return void No return value.
     */
    public function dashboard_widget() {
        if ( $this->active() && $this->options()['hide_dashboard_widget'] ) {
            remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal' );
        }
    }
    /**
     * Enqueue local settings assets and scoped host-owned footer dialog protection.
     * @param string $hook Current admin page hook suffix.
     * @return void No return value.
     */
    public function settings_assets( $hook ) {
        if ( in_array( $hook, array( 'settings_page_purify-elementor', 'settings_page_deckerweb-library', 'settings_page_deckerweb-library-network' ), true ) ) {
            wp_enqueue_style( 'ddw-pe-footer-dialogs', plugins_url( 'assets/footer-dialogs.css', DDW_PE_PLUGIN_FILE ), array(), self::VERSION );
            wp_enqueue_script( 'ddw-pe-footer-dialogs', plugins_url( 'assets/footer-dialogs.js', DDW_PE_PLUGIN_FILE ), array(), self::VERSION, true );
        }
        if ( 'settings_page_purify-elementor' !== $hook ) { return; }
        wp_enqueue_style( 'ddw-pe-settings', plugins_url( 'assets/settings.css', DDW_PE_PLUGIN_FILE ), array(), self::VERSION );
        wp_enqueue_script( 'ddw-pe-settings', plugins_url( 'assets/settings.js', DDW_PE_PLUGIN_FILE ), array(), self::VERSION, true );
    }
    /**
     * Enqueue targeted Elementor admin cleanup styles.
     * @return void No return value.
     */
    public function admin_styles() {
        if ( ! $this->active() ) { return; }
        $o = $this->options();
        $css = '';
        if ( $o['cleanup'] ) { $css .= '#e-conversion-banner{display:none!important}';
            if ( ! self::has_pro() ) { $css .= '#editor-one-top-bar [data-test=header-upgrade-button],#editor-one-sidebar-navigation nav>div:last-child:has(>button){display:none!important}'; }
        }
        if ( $o['hide_news'] ) { $css .= '#e-dashboard-overview .e-overview__feed{display:none!important}'; }
        if ( $css ) {
            wp_register_style( 'ddw-pe-admin-cleanup', false, array(), self::VERSION );
            wp_enqueue_style( 'ddw-pe-admin-cleanup' );
            wp_add_inline_style( 'ddw-pe-admin-cleanup', $css );
        }
    }
    /**
     * Add targeted Free-editor promotion styles without changing widget registration.
     * @return void No return value.
     */
    public function editor_styles() {
        if ( ! $this->active() ) { return; }
        $o = $this->options();
        $css = '';
        if ( $o['cleanup'] ) {
            $css .= '#elementor-panel-get-pro-elements,#elementor-panel-get-pro-elements-sticky,.elementor-panel-editor-sticky-promotion,.elementor-panel-heading-promotion{display:none!important}';
            $css .= '#elementor-navigator__footer__promotion{display:none!important}#elementor-navigator__elements.elementor-navigator-list__promotion{margin-bottom:0!important}';
            if ( ! self::angie_active() ) {
                $css .= '#elementor-panel .elementor-panel-custom-widgets__cta,#elementor-panel .elementor-panel-category-custom-widgets-empty,#elementor-panel-elements-widget-creation-area,[data-testid="e-angie-guide-card"]{display:none!important}';
                // Preserve a category as soon as it contains a real widget.
                $css .= '#elementor-panel-category-custom-widgets:not(:has(.elementor-element)){display:none!important}';
                $css .= '#elementor-editor-wrapper button[aria-label="Angie"],#elementor-editor-wrapper-v2 button[aria-label="Angie"]{display:none!important}';
            }
        }
        if ( $o['hide_locked_widgets'] ) { $css .= '.elementor-panel .elementor-element--promotion{display:none!important}'; }
        if ( ! self::has_pro() ) {
            if ( $o['cleanup'] ) { $css .= '#elementor-panel-elements-navigation [data-tab=global],#elementor-panel-global{display:none!important}'; }
            if ( $o['hide_locked_widgets'] ) { $css .= '#elementor-panel-category-pro-elements{display:none!important}'; }
        }
        if ( $css ) { wp_add_inline_style( 'elementor-editor', $css ); }
    }
    /**
     * Detect the active Angie integration through the audited Elementor helper.
     *
     * @return bool Whether Angie tools must remain accessible.
     */
    private static function angie_active() {
        return is_callable( array( '\Elementor\Core\Utils\Hints', 'is_plugin_active' ) ) && \Elementor\Core\Utils\Hints::is_plugin_active( 'angie' );
    }
    /**
     * Disable only the automatic Angie installation promotion at request time.
     *
     * @param array $settings Elementor's localized editor configuration.
     * @return array Settings preserving other Angie data and all functional editor configuration.
     */
    public function editor_settings( $settings ) {
        if ( ! $this->active() || ! $this->options()['cleanup'] || self::angie_active() || ! is_array( $settings ) ) { return $settings; }
        if ( isset( $settings['angie'] ) && is_array( $settings['angie'] ) ) { $settings['angie']['autoShow'] = false; }
        return $settings;
    }
    /**
     * Dequeue only the audited MCP advertising registrar, after the page enqueues it.
     *
     * The MCP application, analytics, credentials, abilities and saved settings are untouched.
     *
     * @return void No return value.
     */
    public function mcp_promotion() {
        if ( ! $this->active() || ! $this->options()['cleanup'] ) { return; }
        wp_dequeue_script( 'elementor-mcp-promotion-registrar' );
    }
    /**
     * Determine whether Elementor Pro is loaded.
     * @return bool Result of this operation.
     */
    public static function has_pro() {
        return defined( 'ELEMENTOR_PRO_VERSION' ) || class_exists( '\ElementorPro\Plugin' );
    }
    /**
     * Hide audited dynamic Free-editor promotion entry points.
     * @return void No return value.
     */
    public function editor_scripts() {
        if ( ! $this->active() || self::has_pro() || ! $this->options()['cleanup'] ) { return; }
        $label = __( 'Theme Builder', 'elementor' );
        $script = 'const ddwPeThemeBuilderLabel = ' . wp_json_encode( $label ) . ';';
        $script .= <<<'JS'
(() => {
    const normalize = text => text.trim().replace(/[\s–—-]+/g, ' ').toLowerCase();
    const names = new Set([ddwPeThemeBuilderLabel, 'Theme Builder', 'Theme-Builder'].map(normalize));
    let pending = false;
    const clean = () => {
        pending = false;
        document.querySelectorAll('[role="menuitem"]').forEach(item => {
            if (names.has(normalize(item.textContent || ''))) item.style.setProperty('display', 'none', 'important');
        });
        document.querySelectorAll('#elementor-panel [data-view="site-editor"], #elementor-panel [data-name="site-editor"]').forEach(item => {
            item.style.setProperty('display', 'none', 'important');
        });
    };
    const start = () => {
        clean();
        new MutationObserver(() => {
            if (!pending) { pending = true; requestAnimationFrame(clean); }
        }).observe(document.body, {childList:true, subtree:true});
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, {once:true});
    else start();
})();
JS;
        wp_add_inline_script( 'elementor-editor', $script, 'after' );
    }
    /**
     * Override the AI preference at runtime without changing stored user data.
     * @param mixed $value Existing value to normalize or filter.
     * @param string $option WordPress user-option identifier.
     * @param mixed $user User supplied by the preference filter.
     * @return mixed Result of this operation.
     */
    public function ai_preference( $value, $option = '', $user = null ) {
        return $this->active() && $this->options()['hide_ai'] ? '0' : $value;
    }
    /**
     * Prepend the host settings link for authorized website administrators.
     * @param array $links Existing plugin action links.
     * @return array Links including Settings when manage_options is granted.
     */
    /**
     * Provide the settings link host operation.
     * @param array $links Existing links to preserve.
     * @return void No return value.
     */
    public function settings_link( $links ) {
        if ( current_user_can( 'manage_options' ) ) {
            $links = array_merge( array( 'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=purify-elementor' ) ) . '">' . esc_html( self::text( 'Settings', 'Einstellungen' ) ) . '</a>' ), $links );
        }
        return $links;
    }
    /**
     * Register the website option with strict sanitization.
     * @return void No return value.
     */
    public function settings() {
        register_setting( 'ddw_purify_elementor', self::OPTION, array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize' ), 'default' => self::defaults() ) );
    }
    /**
     * Show the shared-updater warning once on this host's settings screen.
     *
     * WordPress places this notice after the complete header via wp-header-end.
     * No output is emitted from menu, asset or admin-head hooks.
     *
     * @return void No return value.
     */
    public function admin_notice() {
        $screen = get_current_screen();
        if ( empty( $GLOBALS['ddw_pe_updater_unavailable'] ) || ! current_user_can( 'manage_options' ) || ! $screen || 'settings_page_purify-elementor' !== $screen->id ) { return; }
        echo '<div class="notice notice-warning"><p>' . esc_html( self::text( 'The loaded shared updater does not support host translations. Purify updates are paused.', 'Der geladene gemeinsame Updater unterstützt keine Host-Übersetzungen. Purify-Updates pausieren.' ) ) . '</p></div>';
    }
    /**
     * Render a compact, escaped compatibility summary without changing preferences.
     *
     * @return void No return value.
     */
    public function compatibility_status() {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        $missing = ! defined( 'ELEMENTOR_VERSION' );
        $elementor = $missing ? self::text( 'Not active', 'Nicht aktiv' ) : ELEMENTOR_VERSION;
        $pro = defined( 'ELEMENTOR_PRO_VERSION' ) ? ELEMENTOR_PRO_VERSION : ( self::has_pro() ? self::text( 'Detected; version unavailable', 'Erkannt; Version nicht verfügbar' ) : self::text( 'Not active', 'Nicht aktiv' ) );
        if ( ! $this->active() ) {
            $state = $missing ? self::text( 'Paused: Elementor is not active.', 'Pausiert: Elementor ist nicht aktiv.' ) : self::text( 'Paused: this Elementor version has not been checked.', 'Pausiert: Diese Elementor-Version wurde nicht geprüft.' );
        } elseif ( ! $this->options()['cleanup'] ) {
            $state = self::text( 'Advertising cleanup is off. Optional controls follow their own settings.', 'Werbebereinigung ist ausgeschaltet. Optionale Funktionen folgen ihren eigenen Einstellungen.' );
        } else {
            $state = self::text( 'Active', 'Aktiv' );
        }
        echo '<section class="ddw-pe-compatibility" aria-labelledby="ddw-pe-compatibility-title"><h2 id="ddw-pe-compatibility-title">' . esc_html( self::text( 'Compatibility', 'Kompatibilität' ) ) . '</h2><dl>';
        foreach ( array( 'Elementor' => $elementor, 'Elementor Pro' => $pro, self::text( 'Cleanup', 'Bereinigung' ) => $state ) as $label => $value ) {
            echo '<div><dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $value ) . '</dd></div>';
        }
        echo '</dl><p class="description">' . esc_html( self::text( 'Checked Elementor versions:', 'Geprüfte Elementor-Versionen:' ) ) . ' ' . esc_html( implode( ', ', self::AUDITED_ELEMENTOR_VERSIONS ) ) . '. ' . esc_html( self::text( 'Pro 4.3.1/4.3.2: source review and protection tests only.', 'Pro 4.3.1/4.3.2: ausschließlich Quellprüfung und Tests der Schutzlogik.' ) ) . '</p></section>';
    }
    /**
     * Render the authorized settings page, footer and accessible local changelog.
     * @return void No return value.
     */
    public function page() {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        $o = $this->options();
        $labels = array(
            'cleanup' => self::text( 'Hide audited advertising and upgrade links', 'Geprüfte Werbung und Upgrade-Links ausblenden' ),
            'hide_locked_widgets' => self::text( 'Hide locked promotional widgets in the V3 panel', 'Gesperrte Werbe-Widgets im V3-Panel ausblenden' ),
            'hide_ai' => self::text( 'Disable Elementor AI through its user preference at runtime', 'Elementor AI über seine Nutzereinstellung zur Laufzeit deaktivieren' ),
            'hide_news' => self::text( 'Hide dashboard news', 'Dashboard-News ausblenden' ),
            'show_home' => self::text( 'Show the Elementor Home submenu', 'Elementor-Untermenü Home anzeigen' ),
            'hide_dashboard_widget' => self::text( 'Hide the entire Elementor dashboard widget', 'Gesamtes Elementor-Dashboard-Widget ausblenden' ),
            'show_account' => self::text( 'Show the Elementor account submenu (also useful with Free)', 'Elementor-Konto-Untermenü anzeigen (auch mit Free nützlich)' ),
            'show_mcp' => self::text( 'Show the Elementor MCP submenu (free basic capabilities)', 'Elementor-MCP-Untermenü anzeigen (kostenlose Grundfunktionen)' ),
        );
        echo '<div class="wrap ddw-pe-root"><header class="ddw-pe-header"><img class="ddw-pe-icon" src="' . esc_url( plugins_url( 'assets/artwork/icon-' . ( 0 === strpos( get_user_locale(), 'de' ) ? 'de' : 'en' ) . '.svg', DDW_PE_PLUGIN_FILE ) ) . '" alt="" width="64" height="64"><div><h1>Purify Elementor</h1><p>' . esc_html( self::text( 'A calmer Elementor workspace. Existing content and stored Elementor preferences remain intact.', 'Eine ruhigere Elementor-Oberfläche. Vorhandene Inhalte und gespeicherte Elementor-Einstellungen bleiben erhalten.' ) ) . '</p></div></header><hr class="wp-header-end">';
        $this->compatibility_status();
        echo '<form id="ddw-pe-settings-form" action="options.php" method="post" data-ddw-pe-tracking-workaround="' . ( $this->active() ? '1' : '0' ) . '">';
        settings_fields( 'ddw_purify_elementor' );
        foreach ( $labels as $key => $label ) {
            echo '<input type="hidden" name="' . esc_attr( self::OPTION . '[' . $key . ']' ) . '" value="0"><p><label><input type="checkbox" name="' . esc_attr( self::OPTION . '[' . $key . ']' ) . '" value="1" ' . checked( $o[ $key ], true, false ) . '> ' . esc_html( $label ) . '</label></p>';
        }
        echo '<p>' . esc_html( self::text( 'Hiding Account or MCP only removes the admin submenu. Connections and services stay intact.', 'Das Ausblenden von Konto oder MCP entfernt nur das Admin-Untermenü. Verbindungen und Dienste bleiben erhalten.' ) ) . '</p>';
        submit_button();
        echo '</form><p>' . esc_html( self::text( 'Atomic/V4 promotions and third-party offers are only partially covered. Technical, license and error notices remain intact.', 'Atomic-/V4-Werbeflächen und Drittanbieterangebote werden nur teilweise abgedeckt. Technische, Lizenz- und Fehlermeldungen bleiben erhalten.' ) ) . '</p>';
        echo '<footer class="ddw-pe-footer" aria-label="' . esc_attr( self::text( 'Plugin information', 'Plugininformationen' ) ) . '"><div><strong>Purify Elementor</strong> · ' . esc_html( self::text( 'Version', 'Version' ) ) . ' ' . esc_html( self::VERSION ) . ' · <a href="https://github.com/deckerweb/purify-elementor/blob/main/CHANGELOG.' . ( 0 === strpos( get_user_locale(), 'de' ) ? 'de' : 'en' ) . '.txt" class="button-link" id="ddw-pe-history" aria-controls="ddw-pe-dialog" aria-haspopup="dialog">' . esc_html( self::text( 'Changelog', 'Änderungsverlauf' ) ) . '</a> · <a href="https://github.com/deckerweb/purify-elementor/blob/main/' . ( 0 === strpos( get_user_locale(), 'de' ) ? 'README.de.md' : 'README.md' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( self::text( 'Documentation', 'Dokumentation' ) ) . '</a><p>' . esc_html( self::text( 'A calmer Elementor workspace.', 'Eine ruhigere Elementor-Oberfläche.' ) ) . '</p></div><div><span>© 2026 <a href="https://github.com/deckerweb" target="_blank" rel="noopener noreferrer">David Decker – DECKERWEB</a></span><a href="https://github.com/deckerweb/purify-elementor" target="_blank" rel="noopener noreferrer">' . esc_html( self::text( 'Plugin website', 'Plugin-Website' ) ) . '</a></div></footer>';
        // hidden + a scoped rule protect the closed state against global admin styles.
        echo '<dialog hidden id="ddw-pe-dialog" aria-labelledby="ddw-pe-dialog-title"><header class="ddw-pe-dialog-header"><h2 id="ddw-pe-dialog-title">' . esc_html( self::text( 'Changelog', 'Änderungsverlauf' ) ) . '</h2><form method="dialog"><button type="submit" class="button" id="ddw-pe-dialog-close" autofocus>' . esc_html( self::text( 'Close', 'Schließen' ) ) . '</button></form></header>';
        if ( function_exists( 'ddw_pe_changelog_html' ) ) { echo ddw_pe_changelog_html(); }
        echo '</dialog></div>';

    }
}
}
