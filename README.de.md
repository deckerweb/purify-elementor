# Purify Elementor

[English](README.md) · Version 1.0.0 · 2. Oktober 2026

Ein kleines Plugin von deckerweb für eine ruhigere Elementor-Oberfläche. Die erste Version bereinigt konkret geprüfte Werbeflächen. Elementor-Inhalte, registrierte Widgets und gespeicherte Elementor-Einstellungen werden nicht verändert.

## Installation als Plugin

ZIP über **Plugins → Neues Plugin hinzufügen → Plugin hochladen** installieren und aktivieren. Unter **Einstellungen → Purify Elementor** stehen acht Schalter zur Verfügung. Das Plugin bleibt bei inaktivem Elementor wirkungslos; seine Einstellungen sind weiterhin erreichbar. Voraussetzung: WordPress 6.7+, PHP 7.4+. Die Bereinigungsregeln wurden am veröffentlichten Elementor-Paket 4.3.3 geprüft. Ältere oder spätere Versionen sind nicht als unterstützt getestet.

## Was wird bereinigt?

| Regel | Standard | Umsetzung und Grenze |
| --- | --- | --- |
| Go-Pro-Link in der Pluginliste | An | Nur den benannten `go_pro`-Eintrag entfernen. |
| Upgrade-Menüs | An | Nur `go_elementor_pro`, `elementor-one-upgrade` und `admin_menu_promo`; keine Funktionsmenüs. Direkte URLs bleiben erreichbar. |
| Dashboard-Upgrade- und AI-Werbelinks | An | Benannte Footeraktionen entfernen; Hilfe und zuletzt bearbeitete Seiten bleiben. |
| Bewertungsaufforderung im Admin-Footer | An | Ausschließlich den bekannten Elementor-Callback entfernen. |
| Conversion-Banner, Birthday-/Black-Friday-Pointer | An | Bekannte Klassen/Methoden gezielt von ihren Hooks entfernen; statischer Banner-Selektor als Rückfall. |
| V3-Panel: Upgrade-Boxen, Sticky-Werbung, werbliche Überschriften | An | Gezielt ausgeblendete CSS-Selektoren. Die Angebotsdaten können weiter geladen werden. |
| Gesperrte Werbe-Widgets im V3-Panel | Aus | Nur `.elementor-element--promotion` ausblenden; keine Widgetregistrierung entfernen. |
| Elementor AI | Aus | `get_user_option_elementor_enable_ai` zur Laufzeit auf `0` setzen. Der gespeicherte Nutzerwert bleibt erhalten; die Option deaktiviert die von dieser Präferenz abhängigen AI-Funktionen, statt nur Buttons zu verstecken. |
| Konto-Untermenü | Sichtbar | `show_account` ist an: die bereitgestellte Template Library nutzt auch mit Free ein kostenloses Konto. Ausblenden trennt das Konto nicht. |
| Elementor MCP | Sichtbar | `show_mcp` ist an: kostenlose Grundfunktionen sind verfügbar. Ausblenden deaktiviert keinen MCP-Zugang. |
| Home-Untermenü | Ausgeblendet | `show_home` ist aus; in den Einstellungen wieder aktivieren. Die direkte Home-Seite und das Hauptmenü bleiben erhalten. |
| Gesamtes Dashboard-Widget | Plugin: sichtbar; Snippet: ausgeblendet | `hide_dashboard_widget` entfernt die Metabox nach Registrierung. |
| Dashboard-News | Aus | Nur den Feed im Elementor-Dashboard-Widget ausblenden; Feedabrufe werden dadurch nicht unterbunden. |

Keine globale Notice-Unterdrückung: technische Hinweise, Fehler, Update- und Lizenzmeldungen bleiben unangetastet. Keine Manipulation von Lizenzstatus, Seiteninhalten oder Pro-Berechtigungen.

## Installation als Snippet

`purify-elementor-snippet.php.txt` ist die aus dem Plugin-Kern generierte Fassung. Alternativ liegt die gleiche Fassung als separate PHP-Datei neben dem Pluginpaket. In einen PHP-Snippet-Manager kopieren, bei Bedarf das anfängliche `<?php` weglassen und **überall ausführen** wählen. Die optionale Konfiguration befindet sich am Ende: `cleanup`, `hide_locked_widgets`, `hide_ai`, `hide_news`, `show_home`, `hide_dashboard_widget`, `show_account`, `show_mcp` jeweils auf `true` oder `false` setzen.

| Plugin | Snippet |
| --- | --- |
| Einstellungen mit Formular und Änderungsverlauf | Konfiguration im Code; keine Einstellungsseite |
| Eigene Einstellungen in einer WordPress-Option | Keine eigenen gespeicherten Optionen |
| Einzeldateien, reguläre Pluginverwaltung | Eigenständig, keine Zusatzdateien erforderlich |
| Direkter Ladezeitpunkt als Plugin | Ladezeitpunkt abhängig vom Snippet-Manager |
| Gemeinsamer Bereinigungskern | Identischer eingebetteter Kern |

**Nur eine Variante aktivieren.** Ein Instanzschutz verhindert doppelte Registrierung, ersetzt aber keine saubere Auswahl der Installationsart. Manche Manager laden zu spät oder nur im Admin: dann können frühe Hooks oder AI-Anfragen außerhalb des Admins nicht vollständig erfasst werden. Integrierte Snippet-Fassung mit `python3 tools/build-snippet.py` aus dem gemeinsamen Kern neu erzeugen.

## deckerweb Library und Updater

Die Pluginfassung enthält jetzt die eingebettete **deckerweb Library 0.3.0**. Sie ergänzt den freigegebenen deckerweb-Katalog unter Plugins → Installieren; bei mehreren einbettenden Plugins wird eine gemeinsame Laufzeit gewählt. Die Library bleibt eine eigenständige Funktion mit eigener Einstellung und wird nicht durch den Purify-Bereinigungsschalter abgeschaltet. Der gebündelte Katalog funktioniert lokal; Online-Katalogaktualisierung ist optional. Das Snippet enthält die Library nicht.

Der gebündelte **deckerweb GitHub Release Updater V2** nutzt das öffentliche [Repository deckerweb/purify-elementor](https://github.com/deckerweb/purify-elementor) und dessen stabile Releases. Er fragt Release-Metadaten bei GitHub ab und lädt beim von dir gestarteten Update das Plugin-ZIP. Das Plugin aktiviert keine automatischen Updates. Repository und Grafiken lassen sich über `deckerweb/purify_elementor/update_repository` und `deckerweb/purify_elementor/update_artwork` filtern; das Repository muss zum `Update URI`-Header passen. Das Snippet enthält keinen Updater. Ein vollständiges Update auf eine spätere Version ist noch nicht getestet.

Grafikvariante 2 **Fokus** ist als Standard ausgewählt und unter `assets/artwork/` gebündelt. Der vorbereitete Updater verwendet die passenden DE/EN-Dateien, für die Release-Informationen. Die übrigen Entwürfe bleiben separate Vorschauen. Alle Banner und Icons liegen in DE/EN als PNG/SVG vor; Banner zusätzlich in 772×250 und 1544×500, Icons in 128×128 und 256×256.

## Grenzen und Teststand

V4-/Atomic-Werbeflächen, React-Angebote, gesperrte Pro-Funktionsmenüs, Serviceempfehlungen und Drittanbieterangebote werden nicht vollständig abgedeckt. Die V1 entfernt keine Atomic-Schemas und keine React-Assets: Sie können für andere Oberflächen gebraucht werden. Vorhandene gesperrte Widgets auf der Bearbeitungsfläche bleiben erkennbar; nur deren Panelangebote können ausgeblendet werden. Aktive Pro-Widgets sollen erhalten bleiben; ein Live-Test mit Pro steht aus.

PHP-Syntax, 45 isolierte PHP-Prüfungen mit dem echten WordPress-Hooksystem und zwei JavaScript-Navigationsprüfungen bestanden. Die isolierte Einstellungsseite wurde auf Deutsch und Englisch einschließlich Mobilansicht, Schließen, Escape und Fokusrückgabe geprüft. Der Betreiber hat den Wunschzustand auf seiner Installation vor dem Release 1.0.0 bestätigt. Dies ist eine Nutzerprüfung, kein unabhängiger vollständiger Integrationstest. Pro, Multisite, andere Elementor-Versionen und alternative Snippet-Manager bleiben ungeprüft. Kein zugesicherter Performancegewinn oder vollständiger Stopp von Hintergrunddiensten.

Multisite: Einstellungen gelten je Website; keine Netzwerk-Einstellungsseite. Netzwerkaktivierung und Netzwerk-Deinstallation sind nicht getestet. Deinstallation entfernt die Option nur im aktuellen Website-Kontext; in anderen Websites können eigene Purify-Optionen verbleiben.

## Konto und MCP

Ein kostenloses Konto ist auch für Elementor Free sinnvoll: für die bereitgestellten Vorlagen der [Template Library](https://elementor.com/help/connect-library/). Eigene gespeicherte Templates bleiben laut [FAQ](https://elementor.com/help/build-with-elementor-ai-faq/) ohne Verbindung nutzbar. [Elementor MCP](https://elementor.com/mcp/) bietet kostenlose Verbindung und Grundfunktionen; Pro-Funktionen benötigen Pro, externe AI-Tools können eigene Kosten verursachen. Die [MCP-Hilfe](https://elementor.com/help/how-to-connect-elementor-to-an-ai-tool-using-mcp/) nennt Core und Pro 4.3.0+, während Produktseite und Core-Quellcode auch kostenlose Grundfunktionen zeigen. Deshalb keine pauschale Pro-Pflicht annehmen; konkretes Free-Setup noch live prüfen. Beide Menüs standardmäßig behalten. Schalter gelten für WordPress-Admin-Untermenüs und ab 0.3.0 für die interne Elementor-Sidebar. Konto-/MCP-Dienste werden dadurch nicht deaktiviert.


In Free blendet die allgemeine Bereinigung jetzt den Theme-Builder-Menüeintrag und ausschließlich den Globals-Widget-Tab aus. Globale Farben, Schriften und Website-Einstellungen bleiben nutzbar. Bei ausgeblendeten Pro-Widgets wird auch die komplette Pro-Kategorie verborgen. Diese zusätzlichen Regeln greifen nicht bei geladenem Elementor Pro. Die Theme-Builder-Regel nutzt einen dynamischen DOM-Abgleich mit dem übersetzten Namen; der Betreiber hat den Wunschzustand auf seiner Installation bestätigt.

## Navigation ab 0.3.0

Unter Elementor im WP-Admin: **Templates** → gespeicherte Templates; **Neues Template hinzufügen** → native Template-Auswahl über die Template-Liste; **Einstellungen** → bestehende Elementor-Einstellungen; **Element-Manager** → bestehende Elementverwaltung. Die ersten beiden Links benötigen `edit_posts`, die letzten beiden `manage_options`; Elementor prüft die Zielberechtigungen zusätzlich selbst. Die zusätzlichen Links sind unabhängig vom allgemeinen Bereinigungsschalter aktiv.

Bei aktivierter Bereinigung ohne geladenes Pro werden Theme-Builder, Übermittlungen, Custom Elements inklusive Fonts/Icons/Code sowie Popups aus der Navigation entfernt. Die interne Seitenleiste wird über öffentliche Ausschlussfilter und ihre lokalisierten Konfigurationsdaten bereinigt; der obere Upgrade-Button über einen festen DOM-Selektor. Diese Eingriffe ändern keine Lizenz und löschen keine Daten. Interne Elementor-Seiten bleiben direkt erreichbar. Die Konto-/MCP-Schalter gelten nun zusätzlich für diese Seitenleiste; ihr Ausblenden deaktiviert keine Verbindung.

Diese Regeln sind an der Struktur von Elementor 4.3.3 ausgerichtet. Andere Versionen können abweichen. Der Betreiber hat die gewünschte Navigation auf seiner Installation bestätigt.

## FAQ

**Ändert die Deaktivierung Elementor-Daten?** Nein. Sie beendet die Laufzeitregeln. Die Pluginoption bleibt bis zur Deinstallation erhalten.

**Funktioniert Elementor Pro weiterhin?** V1 entfernt keine Pro-Klassen, Widgets oder Lizenzinformationen. Vor Einsatz die konkret genutzten Pro-Funktionen auf Staging testen.

**Kann ich AI und News behalten?** Ja, beide zusätzlichen Optionen sind standardmäßig aus. Der allgemeine Bereinigungsschalter entfernt den werblichen Dashboard-AI-Link unabhängig davon.

**Ist alles werbefrei?** Nein. Die Tabelle beschreibt die geprüfte Abdeckung. Neue Werbeflächen benötigen neue Prüfungen.

**Kann ich die Optionen per Code steuern?** Ja, über `deckerweb/purify_elementor/options`; dieselben acht booleschen Schlüssel verwenden.

## Änderungsverlauf

### 1.0.0 – 2. Oktober 2026

- **Neu:** Erste öffentliche stabile GitHub-Version mit installierbarem Plugin-ZIP und erzeugtem eigenständigem Snippet.
- **Verbessert:** Gebündelten GitHub Release Updater V2 für deckerweb/purify-elementor aktivieren; ausgewählte Fokus-Grafiken in Deutsch und Englisch enthalten.
- **Sonstiges:** Zweisprachige Dokumentation und Release-Paket fertigstellen. Der Betreiber hat den Wunschzustand auf seiner Installation bestätigt; Pro und Multisite bleiben ungeprüft.


### 0.3.0 – 2. Oktober 2026

- **Neu:** Vier direkte Elementor-Untermenüs: Templates, Neues Template hinzufügen, Einstellungen und Element-Manager mit passenden WordPress-Rechten.
- **Verbessert:** Pro-Funktionseinstiege in Free aus WP-Admin und interner Elementor-Seitenleiste entfernen: Theme-Builder, Übermittlungen, Custom Elements samt Unterpunkten und Popups.
- **Behoben:** Oberen Upgrade-Button anhand seines eindeutigen Elementor-Selektors ausblenden.
- **Verbessert:** Konto- und MCP-Schalter auch in der internen Elementor-Seitenleiste berücksichtigen.
- **Sonstiges:** Snippet aus gemeinsamem Kern aktualisiert; zusätzliche Regressionstests für Navigation und Direktlinks.

### 0.2.2 – 2. Oktober 2026

- **Behoben:** Leere Pro-Widget-Kategorie in Free zusammen mit ausgeblendeten Pro-Angeboten entfernen.
- **Neu:** Theme-Builder-Menüeintrag und Globals-Widget-Tab in Free bei aktivierter Bereinigung ausblenden; globale Farben und Schriften erhalten.
- **Sonstiges:** Editor-Regeln erhalten diese Einstiege bei geladenem Elementor Pro. Dynamische Theme-Builder-Menüs werden über den übersetzten Namen erkannt.

### 0.2.1 – 2. Oktober 2026

- **Behoben:** Home- und weitere Untermenüs auch in der URL-Form admin.php?page=… erkennen.
- **Behoben:** Elementor-One-Upgrade über eigenen Filter unterbinden und spät registrierte Einträge vor der Menüausgabe erneut entfernen.
- **Sonstiges:** Regressionstests für tatsächliche Home-URL und Upgrade-Registrierung mit maximaler Priorität ergänzt.

### 0.2.0 – 2. Oktober 2026

- **Verbessert:** Grafikvariante 2 Fokus als Standard gebündelt und für lokalisierte Updater-Icons und -Banner vorbereitet.

- **Neu:** Konto- und MCP-Untermenüs bleiben wegen kostenloser Funktionen sichtbar; separat über Einstellungen ausblendbar.

- **Neu:** Home-Untermenü standardmäßig ausgeblendet; über Einstellungen wieder aktivierbar.
- **Neu:** Dashboard-Widget-Schalter; im Snippet ist das gesamte Elementor-Widget standardmäßig ausgeschaltet.
- **Neu:** deckerweb Library 0.3.0 eingebunden; GitHub-Updater V2 für ein zukünftiges Repository vorbereitet und derzeit inaktiv.
- **Neu:** Drei Grafikentwürfe mit Icons und Bannern auf Deutsch und Englisch als PNG und SVG separat bereitgestellt.
- **Behoben:** Zusätzlichen roten Upgrade-Menüeintrag admin_menu_promo entfernt.
- **Behoben:** Geschlossenes Changelog-Modal ausdrücklich verborgen; Schließen, Escape, Fokus und mobile Darstellung abgesichert.
- **Sonstiges:** Plugin und Snippet aus gemeinsamem Kern erzeugt; alle neuen Texte und Unterschiede auf Deutsch und Englisch dokumentiert.

### 0.1.0 – 2. Oktober 2026

- **Neu:** Eigenständiges Plugin mit gezielten Admin- und V3-Editor-Regeln.
- **Neu:** Optionale Schalter für gesperrte V3-Panelangebote, AI und Dashboard-News.
- **Neu:** Einstellungsseite auf Deutsch und Englisch mit zugänglichem Änderungsverlauf.
- **Neu:** Eigenständige Snippet-Fassung aus dem gemeinsamen Plugin-Kern erzeugt.
- **Sonstiges:** Unterschiede von Plugin und Snippet sowie V4-, Pro- und Multisite-Grenzen dokumentiert.
- **Sonstiges:** Quellcodeprüfung an Elementor 4.3.3, Syntax- und isolierte Hookprüfungen; Live-Integration noch offen.

Lizenz: GPL-2.0-or-later. Autor: David Decker – DECKERWEB.
