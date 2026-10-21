=== Purify Elementor ===
Contributors: deckerweb
Requires at least: 6.7
Requires PHP: 8.1
Tested up to: 7.1.3
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A calmer Elementor workspace.

== Description ==
A calmer Elementor workspace. Hide audited promotions and simplify navigation while keeping content and stored Elementor preferences intact.

== Installation ==
Upload the plugin ZIP under Plugins → Add New → Upload Plugin. Activate it and open Settings → Purify Elementor. Administrator permissions (manage_options) are required to change preferences. Missing or unaudited Elementor versions pause cleanup; settings remain accessible.

== Frequently Asked Questions ==

= Where are the preferences? =
Settings → Purify Elementor, or the Settings link in the plugin list.

= Can I keep Home, Account and MCP? =
Yes. Home is initially hidden; Account and MCP are initially shown. Change their independent preferences without disconnecting services.

= Does this disable Elementor Pro? =
No. Pro widgets, content and license data are not removed. Audited Pro-only entry points are hidden only in Free.

= What happens after deactivation or uninstall? =
Purify preferences and Elementor data remain. Only temporary host/shared component data is cleaned according to ownership.

= Does it support every Elementor version and Multisite? =
Cleanup is limited to audited Elementor 4.3.3, 4.3.4 and 4.3.5. Preferences are per site; isolated Multisite checks do not establish full live Pro/network compatibility.

== Changelog ==
= 1.1.1 · October 21, 2026 =
* Improved: Includes deckerweb Library 1.1.0 and the pinned deckerweb Updater 2.2.1; an older updater loaded by another plugin no longer selects the wrong engine.
* Fixed: Changelog, documentation links and artwork follow the active WordPress interface language.
* Fixed: Paused cleanup preserves saved preferences and rejects changes until a checked Elementor version is active.
* Misc: Requires PHP 8.1 or newer; WordPress 6.7 remains supported. Wiki navigation and bilingual footer are consistent.
= 1.1.0 · October 14, 2026 =
* New: Compact compatibility status shows detected Elementor versions and whether cleanup is active or paused.
* Improved: Hides audited Angie installation promotions, empty Custom Widget advertising, Structure upsells and MCP upgrade advertising while retaining usable widgets and MCP features.
* Improved: Supports audited Elementor Free 4.3.4 and 4.3.5 alongside 4.3.3 and includes deckerweb Library 0.9.0.
* Improved: Localized Focus artwork uses IBM Plex Sans with self-contained vector outlines.
* Fixed: Admin notices remain below the complete header; saving produces one confirmation.
* Fixed: Scoped form compatibility avoids the Elementor dashboard-tracking error on the Purify settings page.
* Fixed: Editor cleanup pauses for unreviewed Elementor versions.
* Fixed: Footer dialogs remain closed under conflicting global styles and use isolated controls, native closing and keyboard focus return. The plugin changelog retains a direct link if its dialog is unavailable.
= 1.0.1 · October 2, 2026 =
* Improved: Compact deckerweb footer with version, changelog, documentation and author links.
* Fixed: Selected icon displayed in the admin header and localized banners displayed in both READMEs.
= 1.0.0 · October 2, 2026 =
* New: First public stable release with plugin ZIP and optional standalone snippet.
* Improved: GitHub release updates and localized artwork.
= 0.3.0 · October 2, 2026 =
* New: Direct admin links for Templates, Add New Template, Settings and Element Manager.
* Improved: Hide Pro-only navigation in Free; apply Account and MCP preferences to the internal sidebar.
* Fixed: Hide the top Upgrade button.
= 0.2.2 · October 2, 2026 =
* New: Hide Theme Builder and Global Widgets promotions in Free; preserve global colors and fonts.
* Fixed: Hide the empty Pro category with promotional widgets.
= 0.2.1 · October 2, 2026 =
* Fixed: Recognize Home URLs and remove late-registered Upgrade entries.
