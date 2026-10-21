# Getting started

[Deutsch](Getting-started-de)

Upload the plugin ZIP under Plugins → Add New → Upload Plugin. Activate it and open Settings → Purify Elementor. Administrator permissions (manage_options) are required to change preferences. Missing or unaudited Elementor versions pause cleanup; settings remain accessible.

Bundled deckerweb Library 1.1.0 and deckerweb Updater 2.2.1 use the existing host textdomain and native WordPress update flow. The optional online catalog is off by default; Library preferences are shared between hosts. PHP 8.1 or newer is required. These components are absent from the standalone snippet.

The explicitly authorized standalone snippet uses the same cleanup engine. Run it everywhere in a PHP snippet manager and activate only one variant. Home and the entire dashboard widget are hidden by default. It has code configuration instead of a settings page, and includes neither Library nor updater. Its own German fallback works without plugin language files; load timing depends on the manager.
