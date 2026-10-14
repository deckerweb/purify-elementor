# Fragen nach Themen

[English](FAQ.en.md)

## Einrichtung und Alltag

**Wo finde ich die Einstellungen?**

Unter Einstellungen → Purify Elementor oder über Einstellungen in der Pluginliste.

**Kann ich Home, Konto und MCP behalten?**

Ja. Home ist zunächst verborgen; Konto und MCP werden zunächst angezeigt. Die unabhängigen Schalter ändern keine Verbindung.

**Deaktiviert das Elementor Pro?**

Nein. Pro-Widgets, Inhalte und Lizenzdaten bleiben erhalten. Geprüfte Pro-Einstiege werden nur in Free verborgen.

**Was passiert bei Deaktivierung oder Deinstallation?**

Purify-Einstellungen und Elementor-Daten bleiben erhalten. Nur temporäre Host-/Komponentendaten werden entsprechend ihrer Zuständigkeit bereinigt.

**Werden alle Elementor-Versionen und Multisite unterstützt?**

Die Bereinigung ist auf die geprüften Versionen 4.3.3, 4.3.4 und 4.3.5 begrenzt. Einstellungen gelten je Website; isolierte Multisite-Prüfungen belegen keine vollständige Live-Pro-/Netzwerk-Kompatibilität.

## Daten, Dienste und Integration

Die acht Purify-Einstellungen gelten je Website, auch in Multisite. Deaktivierung und Deinstallation erhalten sie. Deinstallation entfernt den Host-Updatecache und ruft die gemeinsame Library-Bereinigung auf; andere installierte Hosts schützen gemeinsame Daten auch bei Deaktivierung. Library-Einstellungen bleiben ohne ihre separate Löschoption erhalten. Elementor-Daten und über den Katalog installierte Plugins bleiben erhalten.

Release-Metadaten und ausgewählte Update-ZIPs werden bei GitHub abgefragt. Die optionale Library-Onlineabfrage nutzt den deckerweb-GitHub-Raw-Katalog; Installationen prüfen Release und Paket. Die Endpunkte sehen die anfragende IP-Adresse. Die Library überträgt keine Website-URL, Nutzer-ID oder Pluginliste. Öffentliche Nutzung benötigt keinen Token.

Das ausdrücklich freigegebene eigenständige Snippet nutzt dieselbe Bereinigungsengine. Im PHP-Snippet-Manager überall ausführen und nur eine Variante aktivieren. Home und das gesamte Dashboard-Widget sind standardmäßig verborgen. Konfiguration erfolgt im Code statt auf einer Einstellungsseite; Library und Updater fehlen. Sein deutscher Fallback funktioniert ohne Plugin-Sprachdateien; die Ladezeit hängt vom Manager ab.
