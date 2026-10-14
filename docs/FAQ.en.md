# FAQ by topic

[Deutsch](FAQ.de.md)

## Setup and everyday use

**Where are the preferences?**

Settings → Purify Elementor, or the Settings link in the plugin list.

**Can I keep Home, Account and MCP?**

Yes. Home is initially hidden; Account and MCP are initially shown. Change their independent preferences without disconnecting services.

**Does this disable Elementor Pro?**

No. Pro widgets, content and license data are not removed. Audited Pro-only entry points are hidden only in Free.

**What happens after deactivation or uninstall?**

Purify preferences and Elementor data remain. Only temporary host/shared component data is cleaned according to ownership.

**Does it support every Elementor version and Multisite?**

Cleanup is limited to audited Elementor 4.3.3, 4.3.4 and 4.3.5. Preferences are per site; isolated Multisite checks do not establish full live Pro/network compatibility.

## Data, services and integration

The eight Purify preferences belong to each website, including in Multisite. Deactivation and uninstall retain them. Uninstall removes the host update cache and invokes the shared Library cleanup contract; another installed host, even inactive, protects shared data. Library settings remain unless its separate deletion option is enabled. Elementor data and installed catalog plugins are retained.

Release metadata and chosen update ZIPs are requested from GitHub. Optional Library online discovery requests the deckerweb GitHub raw catalog; installation requires release and package validation. Endpoints see the requesting IP. No site URL, user ID or plugin inventory is submitted by the Library. Public use requires no token.

The explicitly authorized standalone snippet uses the same cleanup engine. Run it everywhere in a PHP snippet manager and activate only one variant. Home and the entire dashboard widget are hidden by default. It has code configuration instead of a settings page, and includes neither Library nor updater. Its own German fallback works without plugin language files; load timing depends on the manager.
