<?php
/** Shared cleanup engine; embedded unchanged in the generated snippet. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! class_exists( 'DDW_Purify_Elementor', false ) ) {
final class DDW_Purify_Elementor {
    const VERSION = '1.0.1';
    const OPTION = 'ddw_purify_elementor';
    private $snippet;
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
        add_action( 'admin_head', array( $this, 'admin_styles' ) );
        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ), 999 );
        add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_scripts' ), 999 );
        add_filter( 'get_user_option_elementor_enable_ai', array( $this, 'ai_preference' ), 999, 3 );
        if ( ! $snippet ) {
            add_action( 'admin_init', array( $this, 'settings' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'settings_assets' ) );
        }
    }
    public static function defaults() {
        return array( 'cleanup' => true, 'hide_locked_widgets' => false, 'hide_ai' => false, 'hide_news' => false, 'show_home' => false, 'hide_dashboard_widget' => false, 'show_account' => true, 'show_mcp' => true );
    }
    public function options() {
        $defaults = self::defaults();
        if ( $this->snippet ) { $defaults['hide_dashboard_widget'] = true; }
        $stored = $this->snippet ? array() : get_option( self::OPTION, array() );
        $options = self::sanitize( is_array( $stored ) ? array_merge( $defaults, $stored ) : $defaults );
        // Snippet users can supply the same options via this public filter.
        $filtered = apply_filters( 'deckerweb/purify_elementor/options', $options );
        return is_array( $filtered ) ? self::sanitize( array_merge( $options, $filtered ) ) : $options;
    }
    public static function sanitize( $input ) {
        $out = array();
        foreach ( self::defaults() as $key => $default ) {
            $out[ $key ] = is_array( $input ) && isset( $input[ $key ] ) && in_array( $input[ $key ], array( true, 1, '1' ), true );
        }
        return $out;
    }
    private function active() { return did_action( 'elementor/loaded' ) || defined( 'ELEMENTOR_VERSION' ); }
    public static function text( $en, $de ) {
        return 0 === strpos( get_user_locale(), 'de' ) ? $de : $en;
    }
    /** Remove only explicitly audited class/method pairs; retain technical notices. */
    public function cleanup_callbacks() {
        if ( ! $this->active() || ! $this->options()['cleanup'] ) { return; }
        $rules = array(
            'admin_footer_text' => array( 'Elementor\\Core\\Admin\\Admin' => array( 'admin_footer_text' ) ),
            'current_screen' => array( 'Elementor\\Modules\\Promotions\\Conversion_Banner' => array( 'maybe_register_banner_hooks' ) ),
            'admin_print_footer_scripts-index.php' => array(
                'Elementor\\Modules\\Promotions\\Pointers\\Birthday' => array( 'enqueue_notice' ),
                'Elementor\\Modules\\Promotions\\Pointers\\Black_Friday' => array( 'enqueue_notice' ),
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
    public function plugin_links( $links ) {
        if ( $this->active() && $this->options()['cleanup'] ) { unset( $links['go_pro'] ); }
        return $links;
    }
    public function dashboard_links( $links ) {
        $o = $this->options();
        if ( $o['cleanup'] ) { unset( $links['go-pro'], $links['ai'] ); }
        return $links;
    }
    public function upgrade_available( $available ) {
        return $this->options()['cleanup'] ? false : $available;
    }
    /** Elementor uses both bare slugs and admin.php?page=... submenu URLs. */
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
    public function menus() {
        $this->clean_menus();
        $this->shortcuts();
        if ( ! $this->snippet ) {
            add_options_page( 'Purify Elementor', 'Purify Elementor', 'manage_options', 'purify-elementor', array( $this, 'page' ) );
        }
    }
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
    public static function pro_menu_slugs() {
        return array( 'elementor-app', 'elementor-theme-builder', 'e-form-submissions', 'popup_templates', 'elementor-custom-elements', 'elementor-editor-custom-elements', 'elementor_custom_fonts', 'elementor_custom_icons', 'elementor_custom_code' );
    }
    public function excluded_navigation( $slugs ) {
        $o = $this->options();
        if ( $o['cleanup'] && ! self::has_pro() ) { $slugs = array_merge( $slugs, self::pro_menu_slugs() ); }
        if ( ! $o['show_account'] ) { $slugs[] = 'elementor-connect-account'; }
        if ( ! $o['show_mcp'] ) { $slugs[] = 'elementor-mcp'; }
        return array_values( array_unique( $slugs ) );
    }
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
    public function dashboard_widget() {
        if ( $this->active() && $this->options()['hide_dashboard_widget'] ) {
            remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal' );
        }
    }
    public function settings_assets( $hook ) {
        if ( 'settings_page_purify-elementor' !== $hook ) { return; }
        wp_enqueue_style( 'ddw-pe-settings', plugins_url( 'assets/settings.css', DDW_PE_PLUGIN_FILE ), array(), self::VERSION );
        wp_enqueue_script( 'ddw-pe-settings', plugins_url( 'assets/settings.js', DDW_PE_PLUGIN_FILE ), array(), self::VERSION, true );
    }
    public function admin_styles() {
        if ( ! $this->active() ) { return; }
        $o = $this->options();
        $css = '';
        if ( $o['cleanup'] ) { $css .= '#e-conversion-banner{display:none!important}';
            if ( ! self::has_pro() ) { $css .= '#editor-one-top-bar [data-test=header-upgrade-button]{display:none!important}'; }
        }
        if ( $o['hide_news'] ) { $css .= '#e-dashboard-overview .e-overview__feed{display:none!important}'; }
        if ( $css ) { echo '<style id="ddw-purify-elementor-admin">' . $css . '</style>'; } // Static CSS only.
    }
    public function editor_styles() {
        $o = $this->options();
        $css = '';
        if ( $o['cleanup'] ) {
            $css .= '#elementor-panel-get-pro-elements,#elementor-panel-get-pro-elements-sticky,.elementor-panel-editor-sticky-promotion,.elementor-panel-heading-promotion{display:none!important}';
        }
        if ( $o['hide_locked_widgets'] ) { $css .= '.elementor-panel .elementor-element--promotion{display:none!important}'; }
        if ( ! self::has_pro() ) {
            if ( $o['cleanup'] ) { $css .= '#elementor-panel-elements-navigation [data-tab=global],#elementor-panel-global{display:none!important}'; }
            if ( $o['hide_locked_widgets'] ) { $css .= '#elementor-panel-category-pro-elements{display:none!important}'; }
        }
        if ( $css ) { wp_add_inline_style( 'elementor-editor', $css ); }
    }
    public static function has_pro() {
        return defined( 'ELEMENTOR_PRO_VERSION' ) || class_exists( '\ElementorPro\Plugin' );
    }
    /** Match a functional menu entry only in Free, including dynamically rendered menus. */
    public function editor_scripts() {
        if ( self::has_pro() || ! $this->options()['cleanup'] ) { return; }
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
    public function ai_preference( $value, $option = '', $user = null ) {
        return $this->active() && $this->options()['hide_ai'] ? '0' : $value;
    }
    public function settings() {
        register_setting( 'ddw_purify_elementor', self::OPTION, array( 'type' => 'array', 'sanitize_callback' => array( __CLASS__, 'sanitize' ), 'default' => self::defaults() ) );
    }
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
        echo '<div class="wrap ddw-pe-root"><header class="ddw-pe-header"><img class="ddw-pe-icon" src="' . esc_url( plugins_url( 'assets/artwork/icon-' . ( 0 === strpos( get_user_locale(), 'de' ) ? 'de' : 'en' ) . '.svg', DDW_PE_PLUGIN_FILE ) ) . '" alt="" width="64" height="64"><div><h1>Purify Elementor</h1><p>' . esc_html( self::text( 'A calmer Elementor workspace. Existing content and stored Elementor preferences remain intact.', 'Eine ruhigere Elementor-Oberfläche. Vorhandene Inhalte und gespeicherte Elementor-Einstellungen bleiben erhalten.' ) ) . '</p></div></header>';
        if ( ! $this->active() ) { echo '<p>' . esc_html( self::text( 'Elementor is currently inactive. Cleanup will start when it is active.', 'Elementor ist derzeit inaktiv. Die Bereinigung greift, sobald es aktiv ist.' ) ) . '</p>'; }
        echo '<form action="options.php" method="post">';
        settings_fields( 'ddw_purify_elementor' );
        foreach ( $labels as $key => $label ) {
            echo '<input type="hidden" name="' . esc_attr( self::OPTION . '[' . $key . ']' ) . '" value="0"><p><label><input type="checkbox" name="' . esc_attr( self::OPTION . '[' . $key . ']' ) . '" value="1" ' . checked( $o[ $key ], true, false ) . '> ' . esc_html( $label ) . '</label></p>';
        }
        echo '<p>' . esc_html( self::text( 'Hiding Account or MCP only removes the admin submenu. Connections and services stay intact.', 'Das Ausblenden von Konto oder MCP entfernt nur das Admin-Untermenü. Verbindungen und Dienste bleiben erhalten.' ) ) . '</p>';
        submit_button();
        echo '</form><p>' . esc_html( self::text( 'V4 promotional surfaces and third-party offers are not fully covered in this first version. No license or error notices are globally suppressed.', 'V4-Werbeflächen und Angebote von Drittanbietern werden in dieser ersten Version nicht vollständig abgedeckt. Lizenz- und Fehlermeldungen werden nicht pauschal unterdrückt.' ) ) . '</p>';
        echo '<footer class="ddw-pe-footer" aria-label="' . esc_attr( self::text( 'Plugin information', 'Plugininformationen' ) ) . '"><div><strong>Purify Elementor</strong> · ' . esc_html( self::text( 'Version', 'Version' ) ) . ' ' . esc_html( self::VERSION ) . ' · <button type="button" class="button-link" id="ddw-pe-history" aria-controls="ddw-pe-dialog" aria-haspopup="dialog">' . esc_html( self::text( 'Changelog', 'Änderungsverlauf' ) ) . '</button> · <a href="https://github.com/deckerweb/purify-elementor/blob/main/' . ( 0 === strpos( get_user_locale(), 'de' ) ? 'README.de.md' : 'README.md' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( self::text( 'Documentation', 'Dokumentation' ) ) . '</a><p>' . esc_html( self::text( 'A calmer Elementor workspace.', 'Eine ruhigere Elementor-Oberfläche.' ) ) . '</p></div><div><span>© 2026 <a href="https://github.com/deckerweb" target="_blank" rel="noopener noreferrer">David Decker – DECKERWEB</a></span><a href="https://github.com/deckerweb/purify-elementor" target="_blank" rel="noopener noreferrer">' . esc_html( self::text( 'Plugin website', 'Plugin-Website' ) ) . '</a></div></footer>';
        // hidden + a scoped rule protect the closed state against global admin styles.
        echo '<style>#ddw-pe-dialog[hidden],#ddw-pe-dialog:not([open]){display:none!important}</style>';
        echo '<dialog hidden id="ddw-pe-dialog" aria-labelledby="ddw-pe-dialog-title"><header class="ddw-pe-dialog-header"><h2 id="ddw-pe-dialog-title">' . esc_html( self::text( 'Changelog', 'Änderungsverlauf' ) ) . '</h2><button type="button" class="button" id="ddw-pe-dialog-close" autofocus>' . esc_html( self::text( 'Close', 'Schließen' ) ) . '</button></header>';
        if ( function_exists( 'ddw_pe_changelog_html' ) ) { echo ddw_pe_changelog_html(); }
        echo '</dialog></div>';

    }
}
}
