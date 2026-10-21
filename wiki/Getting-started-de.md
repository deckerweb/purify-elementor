# Erste Schritte

[English](Getting-started-en)

Plugin-ZIP unter Plugins → Neues Plugin hinzufügen → Plugin hochladen installieren. Aktivieren und Einstellungen → Purify Elementor öffnen. Änderungen erfordern Administratorrechte (manage_options). Bei fehlendem oder ungeprüftem Elementor pausiert die Bereinigung; Einstellungen bleiben sichtbar; abhängige Änderungen sind während der Pause nicht möglich.

Gebündelte deckerweb Library 1.1.0 und deckerweb Updater 2.2.1 nutzen die vorhandene Host-Textdomain und den nativen WordPress-Updateablauf. Der optionale Online-Katalog ist standardmäßig ausgeschaltet; Library-Einstellungen werden zwischen Hosts geteilt. Mindestens PHP 8.1 ist erforderlich. Das eigenständige Snippet enthält diese Komponenten nicht.

Das ausdrücklich freigegebene eigenständige Snippet nutzt dieselbe Bereinigungsengine. Im PHP-Snippet-Manager überall ausführen und nur eine Variante aktivieren. Home und das gesamte Dashboard-Widget sind standardmäßig verborgen. Konfiguration erfolgt im Code statt auf einer Einstellungsseite; Library und Updater fehlen. Sein deutscher Fallback funktioniert ohne Plugin-Sprachdateien; die Ladezeit hängt vom Manager ab.
