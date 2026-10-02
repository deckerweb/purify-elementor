# Purify Elementor

[Deutsch](README.de.md) · Version 1.0.0 · October 2, 2026

A small deckerweb plugin for a calmer Elementor workspace. This first version targets audited advertising surfaces without changing Elementor content, widget registration or stored Elementor preferences.

[Download the latest plugin ZIP](https://github.com/deckerweb/purify-elementor/releases/latest) · [Releases](https://github.com/deckerweb/purify-elementor/releases)

## Plugin installation

Upload the ZIP via **Plugins → Add New → Upload Plugin**, activate, then open **Settings → Purify Elementor**. Eight switches are available. Cleanup is inactive when Elementor is inactive; settings remain accessible. Requires WordPress 6.7+ and PHP 7.4+. Rules were audited against the published Elementor 4.3.3 package. Earlier and later versions have not been tested as supported.

## Coverage

| Rule | Default | Implementation and limits |
| --- | --- | --- |
| Plugin-list Go Pro link | On | Remove the named `go_pro` entry only. |
| Upgrade menus | On | Only `go_elementor_pro`, `elementor-one-upgrade` and `admin_menu_promo`; functional menus remain. Direct URLs remain accessible. |
| Dashboard upgrade and AI advertising links | On | Remove named footer actions; retain help and recently edited pages. |
| Admin-footer rating request | On | Remove only Elementor's known callback. |
| Conversion banner, Birthday/Black Friday pointers | On | Remove audited class/method callbacks; static banner selector as fallback. |
| V3 panel upgrade boxes, sticky offers and promotional headings | On | Targeted CSS selectors; offer data may still load. |
| Locked promotional widgets in the V3 panel | Off | Hide only `.elementor-element--promotion`; preserve widget registration. |
| Elementor AI | Off | Override `get_user_option_elementor_enable_ai` to `0` at runtime. Stored user preference remains intact. This disables AI functionality that respects the preference, rather than merely hiding buttons. |
| Account submenu | Visible | `show_account` is on: the supplied Template Library uses a free account even with Free. Hiding does not disconnect it. |
| Elementor MCP | Visible | `show_mcp` is on: free basic capabilities exist. Hiding does not disable MCP access. |
| Home submenu | Hidden | `show_home` is off; restore it in settings. The direct Home page and top-level menu remain available. |
| Entire dashboard widget | Plugin: visible; snippet: hidden | `hide_dashboard_widget` removes the metabox after registration. |
| Dashboard news | Off | Hide the Elementor widget feed only; this does not stop feed requests. |

No global notice suppression: technical, error, update and license notices are untouched. No changes to license status, page content or Pro permissions.

## Snippet installation

`purify-elementor-snippet.php.txt` is generated from the plugin engine. The same standalone PHP file is provided alongside the plugin ZIP. Paste it into a PHP snippet manager, omit the opening `<?php` if required, and select **run everywhere**. At the end, set `cleanup`, `hide_locked_widgets`, `hide_ai`, `hide_news`, `show_home`, `hide_dashboard_widget`, `show_account`, `show_mcp` to `true` or `false` as needed.

| Plugin | Snippet |
| --- | --- |
| Settings form and changelog | Code configuration; no settings page |
| Own WordPress settings option | No own stored options |
| Separate files, regular plugin management | Standalone; no additional files required |
| Regular plugin load timing | Load timing depends on snippet manager |
| Shared cleanup engine | Identical embedded engine |

**Activate only one variant.** An instance guard prevents duplicate registration but does not replace choosing one installation method. Late-loading or admin-only managers may miss early hooks or AI requests outside the admin. Regenerate the included snippet from the shared engine with `python3 tools/build-snippet.py`.

## deckerweb Library and updater

The plugin now bundles **deckerweb Library 0.3.0**. It adds the approved deckerweb catalog under Plugins → Add New; multiple embedding plugins elect one shared runtime. Library is a separate feature with its own settings and is not disabled by the Purify cleanup switch. The bundled catalog operates locally; online catalog refresh is optional. The snippet does not include the Library.

The bundled **deckerweb GitHub Release Updater V2** uses the public [deckerweb/purify-elementor repository](https://github.com/deckerweb/purify-elementor) and its stable releases. It checks GitHub for release metadata and downloads the plugin ZIP when you choose to update. Automatic updates are never enabled by the plugin. The repository and artwork can be filtered through `deckerweb/purify_elementor/update_repository` and `deckerweb/purify_elementor/update_artwork`; the repository must match the `Update URI` header. The snippet has no updater. An end-to-end update to a later version has not yet been tested.

Artwork concept 2 **Focus** is selected as the default and bundled in `assets/artwork/`. The prepared updater uses the matching German/English files for release information. The other concepts remain separate previews. Every banner and icon has German/English PNG/SVG versions; banners also have 772×250 and 1544×500 sizes, icons 128×128 and 256×256.

## Limits and validation

V4/Atomic promotional surfaces, React offers, locked Pro feature menus, service recommendations and third-party offers are not fully covered. V1 does not remove Atomic schemas or React assets that other surfaces may need. Existing locked widgets remain visible on the editing canvas; only panel offers can be hidden. Active Pro widgets should remain available; live Pro testing is pending.

PHP syntax checks, 45 isolated PHP checks using the real WordPress hook system and two JavaScript navigation checks passed. The isolated settings UI was checked in German and English, including mobile, Close, Escape and focus return. The owner confirmed the desired behavior on their installation before the 1.0.0 release. This is user verification, not an independent full integration test. Pro, multisite, other Elementor versions and alternate snippet managers remain unverified. No guaranteed performance gain or full shutdown of background services.

Multisite settings are per site; no network settings page. Network activation and network uninstall are untested. Uninstall removes the option only from the current site context; other sites may retain their own Purify options.

## Account and MCP

A free account is useful with Elementor Free for supplied [Template Library](https://elementor.com/help/connect-library/) templates. Own saved templates remain usable without a connection according to the [FAQ](https://elementor.com/help/build-with-elementor-ai-faq/). [Elementor MCP](https://elementor.com/mcp/) provides free connection and basic capabilities; Pro features require Pro and external AI tools may have their own costs. The [MCP help page](https://elementor.com/help/how-to-connect-elementor-to-an-ai-tool-using-mcp/) lists Core and Pro 4.3.0+, while the product page and Core code expose free capabilities. Do not assume a universal Pro requirement; live verification of the specific Free setup is pending. Retain both menus by default. Switches cover WordPress admin submenus and, from 0.3.0, the internal Elementor sidebar. Account/MCP services are not disabled.


In Free, general cleanup now hides the Theme Builder menu item and only the Globals widget tab. Global colors, fonts and Site Settings remain available. Hiding promotional widgets also hides the entire Pro category. These additional rules do not run when Elementor Pro is loaded. Theme Builder uses dynamic DOM matching by translated name; the owner has confirmed the desired behavior on their installation.

## Navigation from 0.3.0

Under Elementor in WordPress admin: **Templates** opens saved templates; **Add New Template** opens the native template chooser through the template list; **Settings** opens existing Elementor settings; **Element Manager** opens existing element management. The first two links require `edit_posts`, the last two `manage_options`; Elementor also enforces target permissions. Shortcuts are enabled independently of the general cleanup switch.

With cleanup enabled and Pro not loaded, Theme Builder, Submissions, Custom Elements including Fonts/Icons/Code, and Popups are removed from navigation. The internal sidebar is cleaned through public exclusion filters and localized configuration data; the top Upgrade button through an explicit DOM selector. No license or content data is changed. Internal Elementor pages remain directly accessible. Account/MCP switches now cover this sidebar too; hiding them does not disable connections.

These rules target Elementor 4.3.3 structures. Other versions may differ. The owner confirmed the desired navigation on their installation.

## FAQ

**Does deactivation change Elementor data?** No. It ends runtime rules. The plugin option remains until uninstall.

**Will Elementor Pro still work?** V1 does not remove Pro classes, widgets or license data. Test the specific Pro features you use on staging before deployment.

**Can I keep AI and news?** Yes. Both optional controls are off by default. General cleanup removes the advertising AI dashboard link independently.

**Is all advertising removed?** No. The table defines audited coverage. New surfaces require new audits.

**Can code control options?** Yes, use `deckerweb/purify_elementor/options` with the same eight boolean keys.

## Changelog

### 1.0.0 – October 2, 2026

- **New:** First public stable release on GitHub with installable plugin ZIP and generated standalone snippet.
- **Improved:** Activate the bundled GitHub Release Updater V2 for deckerweb/purify-elementor; include selected Focus artwork in German and English.
- **Misc:** Finalize bilingual documentation and release packaging. The owner confirmed the desired behavior on their installation; Pro and multisite remain unverified.


### 0.3.0 – October 2, 2026

- **New:** Four direct Elementor submenus: Templates, Add New Template, Settings and Element Manager with appropriate WordPress capabilities.
- **Improved:** Remove Pro entry points in Free from WordPress admin and the internal Elementor sidebar: Theme Builder, Submissions, Custom Elements and children, and Popups.
- **Fixed:** Hide the top Upgrade button using its explicit Elementor selector.
- **Improved:** Apply Account and MCP switches to the internal Elementor sidebar too.
- **Misc:** Regenerated shared-core snippet; additional navigation and shortcut regression checks.

### 0.2.2 – October 2, 2026

- **Fixed:** Hide the empty Pro widget category in Free when promotional widgets are hidden.
- **New:** Hide the Theme Builder menu item and Globals widget tab in Free when cleanup is enabled; preserve global colors and fonts.
- **Misc:** Keep these entry points when Elementor Pro is loaded. Dynamic Theme Builder menus are matched using their translated name.

### 0.2.1 – October 2, 2026

- **Fixed:** Recognize Home and other submenus using admin.php?page=… URLs.
- **Fixed:** Suppress Elementor One Upgrade through its own filter and remove late entries again before menu output.
- **Misc:** Added regression checks for the actual Home URL and maximum-priority Upgrade registration.

### 0.2.0 – October 2, 2026

- **Improved:** Bundled artwork concept 2 Focus as the default and prepared localized updater icons and banners.

- **New:** Account and MCP submenus remain visible for their free capabilities; separate settings can hide them.

- **New:** Home submenu hidden by default; can be restored in settings.
- **New:** Dashboard widget switch; the standalone snippet hides the entire Elementor widget by default.
- **New:** Bundled deckerweb Library 0.3.0; GitHub Updater V2 prepared for a future repository and currently inactive.
- **New:** Three artwork concepts with German and English icons and banners, supplied separately as PNG and SVG.
- **Fixed:** Removed the additional red admin_menu_promo Upgrade menu item.
- **Fixed:** Explicitly hide the closed changelog dialog; reliable close, Escape, focus handling and mobile layout.
- **Misc:** Plugin and snippet generated from the shared engine; all new text and differences documented in German and English.

### 0.1.0 – October 2, 2026

- **New:** Standalone plugin with targeted admin and V3 editor cleanup.
- **New:** Optional locked V3 panel offers, AI and dashboard news controls.
- **New:** German and English settings page with accessible changelog.
- **New:** Standalone snippet generated from the shared plugin engine.
- **Misc:** Documented plugin/snippet differences and V4, Pro and multisite limits.
- **Misc:** Elementor 4.3.3 source audit, syntax and isolated hook checks; live integration pending.

License: GPL-2.0-or-later. Author: David Decker – DECKERWEB.
