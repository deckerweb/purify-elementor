# Validation / Prüfung – 0.2.0

Audited source: https://downloads.wordpress.org/plugin/elementor.4.3.3.zip
Downloaded via the latest-stable alias on October 2, 2026; plugin header and ELEMENTOR_VERSION both identify 4.3.3.
ZIP SHA256: e3e1a4eb6bb458da5efcb67d3aa70e5b8a8eb8c5177c51b88876136e8a098871

PHP 8.4.5 syntax: plugin bootstrap, engine, uninstall and generated snippet.
14 isolated checks using WordPress's real WP_Hook/plugin.php implementation: defaults, strict sanitization, rating removal, conversion removal, technical callback preservation, scoped plugin links, third-party dashboard preservation, AI default, runtime AI override, locked panel selector, canvas preservation, cleanup-off behavior, duplicate guard and German UI.

Generation check: embedded snippet engine matches includes/core.php byte for byte (excluding opening PHP tag). Separate standalone file matches the included .txt file.

Source locations: core/admin/admin.php; modules/promotions/module.php; modules/promotions/conversion-banner.php; modules/promotions/pointers/{birthday,black-friday}.php; modules/ai/preferences.php; includes/user.php; includes/editor-templates/panel-elements.php; assets/css/editor.css; assets/js/editor.js; core/editor/loader/editor-loader.php.

Not verified / Nicht geprüft: live admin rendering, V3/V4 editor behavior, save/preview, Pro, role differences, multisite, PHP 7.4 execution and alternate snippet-manager timing. Run staging integration before production use / Vor Produktiveinsatz Staging-Integration durchführen.

## 0.2.0 additional validation / zusätzliche Prüfung

24 isolated checks passed (14 baseline, 1 standalone snippet startup, 9 menu/default/integration checks). New checks cover red Upgrade and Home removal, Account/MCP retention and toggles, plugin/snippet dashboard differences, Library registration and updater inactivity without repository. All bundled PHP files pass syntax checking.

Local browser fixture generated from the actual settings-page method, including a deliberately conflicting dialog{display:block} rule: closed dialog hidden on load; opens correctly; Close and Escape hide it; focus returns to trigger. German and English views checked. Mobile 390×844: dialog width 350px, scrollable within 80vh, opens at scrollTop 0 with visible sticky title and Close button. This validates the isolated settings UI, not a live WordPress/Elementor installation.

Artwork: SVG rendered to PNG, all dimensions checked; three variants with German/English icons and banners. PNG rendering uses explicit SVG colors (no CSS-variable dependency). No artwork chosen for updater yet.

Library 0.3.0 and GitHub Updater V2 copied from existing deckerweb implementation. Runtime registration and inactive-update path tested; catalog installation, live update transport and new repository releases remain untested.

Artwork selection: concept 02 Focus approved by the user, bundled under assets/artwork and mapped to localized updater icon/banner sizes. Repository-dependent updater activation remains off.

## 0.2.1 regression validation

31 isolated checks passed. New fixture reproduces Elementor Home as admin.php?page=elementor-home and a later-registered admin_menu callback at PHP_INT_MAX. It confirms the timing collision, removal before menu output, the Elementor One upgrade_available filter, preserved behavior when cleanup is off, Home re-enable and HTML-escaped query normalization. This is a source-derived regression fixture, not a live check of the user's site.

0.2.2: Six additional isolated editor checks passed: Free Pro category, Global Widgets tab, preservation of global colors/fonts, translated dynamic Theme Builder matcher, cleanup-off behavior and Pro-loaded safeguards. Live editor DOM matching remains unverified.

## 0.3.0

45 isolated PHP checks passed (baseline, snippet, menu and editor suites), plus two JavaScript fixture checks for actual generated sidebar/topbar scripts. Pro sidebar group and children removed, saved templates and Help retained, dynamic topbar rerender handled. Shortcut targets use existing admin.php page URLs to avoid replacing original page registration. All bundled PHP syntax and snippet engine parity verified. Live Elementor 4.3.3 admin navigation and shortcut destination tests remain pending.

## 1.0.0 release

Owner-reported verification / Nutzerprüfung: the owner confirmed the desired behavior on their installation before authorizing public publication. No independent full Pro or multisite integration claim. Repository and Update URI are now configured for the public GitHub release. An actual upgrade to a later release still needs a future-version test.
