#!/usr/bin/env python3
"""Render bilingual public documentation from one maintained content source."""
from pathlib import Path
import json
root=Path(__file__).resolve().parents[1]
source=json.loads((root/'docs/content.json').read_text())
version=source['version']
requires_php=source['requires_php']
requires_wp=source['requires_wp']
tested_wp=source['tested_wp']
months_de=['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember']
months_en=['January','February','March','April','May','June','July','August','September','October','November','December']
def release_date(entry, de):
 year,month,day=map(int,entry.get('date','2026-10-02').split('-'))
 return f'{day}. {months_de[month-1]} {year}' if de else f'{months_en[month-1]} {day}, {year}'
for lang,c in source['languages'].items():
 de=lang=='de';titles=['Über das Plugin','Auf einen Blick','Installation','Funktionen','Grenzen','Komponenten','Externe Verbindungen','Daten und Multisite','Snippet','FAQ','Änderungsverlauf','Autor und Unterstützung'] if de else ['About','At a glance','Installation','Features','Limits','Components','External connections','Data and Multisite','Snippet','FAQ','Changelog','Author and support']
 sections=[(titles[0],c['about']), (titles[1],'\n'.join('- '+x for x in c['glance']))]+[(titles[i],c[k]) for i,k in enumerate(['install','features','limits','components','privacy','data','snippet'],2)]
 faq='\n\n'.join('**'+q+'**\n\n'+a for q,a in c['faq']);faq+='\n\n['+('Fragen nach Themen' if de else 'FAQ by topic')+'](docs/FAQ.'+lang+'.md)'
 sections.append((titles[9],faq));hist=[];plain=[]
 for release_version,entry in list(source['history'].items())[:7]:
  date=release_date(entry,de);rows=entry[lang]
  hist.append('### '+release_version+' · '+date+'\n\n'+'\n'.join('- **'+cat+':** '+text for cat,text in rows))
  plain.append('= '+release_version+' · '+date+' =\n'+'\n'.join('* '+cat+': '+text for cat,text in rows))
 sections.append((titles[10],'\n\n'.join(hist)))
 sections.append((titles[11],('Entwickler und Herausgeber: David Decker – DECKERWEB. GitHub-Direktvertrieb; keine WordPress.org-Fassung. ' if de else 'Developer and publisher: David Decker – DECKERWEB. GitHub direct distribution; no WordPress.org build. ')+ '[Issues](https://github.com/deckerweb/purify-elementor/issues) · [Security](SECURITY.md) · [Ko-fi](https://ko-fi.com/deckerweb) · [Buy Me a Coffee](https://buymeacoffee.com/daveshine) · [PayPal](https://paypal.me/deckerweb)\n\nCopyright © 2026 David Decker – DECKERWEB. GPL-2.0-or-later. '+('Eingebettete Library und Updater stammen von deckerweb und stehen unter derselben Lizenz. Fokus-Grafiken: native SVG-Vektorformen und Schriftkonturen aus IBM Plex Sans (SIL OFL 1.1), ohne eingebettete Rasterbilder oder externe Ressourcen. [Schriftlizenz](assets/artwork/licenses/OFL-IBM-Plex-Sans.txt).' if de else 'Embedded Library and updater are deckerweb components under the same license. Focus artwork: native SVG vector shapes and IBM Plex Sans glyph outlines (SIL OFL 1.1), without embedded raster images or external resources. [Font license](assets/artwork/licenses/OFL-IBM-Plex-Sans.txt).')))
 def anchor(x):return x.lower().replace(' ','-')
 other='[English](README.md)' if de else '[Deutsch](README.de.md)'
 text='# Purify Elementor\n\n![Purify Elementor](assets-github/banner-'+lang+'.png)\n\n'+c['about']+'\n\n'+other+' · Version '+version+' · WordPress '+requires_wp+'+ · PHP '+requires_php+'+\n\n[Download](https://github.com/deckerweb/purify-elementor/releases/latest) · [FAQ](docs/FAQ.'+lang+'.md)\n\n'+('## Inhalt' if de else '## Contents')+'\n\n'+'\n'.join('- ['+h+'](#'+anchor(h)+')' for h,b in sections[1:])+'\n\n'+'\n\n'.join('## '+h+'\n\n'+b for h,b in sections[1:])+'\n'
 (root/('README.de.md' if de else 'README.md')).write_text(text)
 (root/f'CHANGELOG.{lang}.txt').write_text('\n'.join(plain)+'\n')
 (root/f'docs/FAQ.{lang}.md').write_text('# '+('Fragen nach Themen' if de else 'FAQ by topic')+'\n\n'+('[English](FAQ.en.md)' if de else '[Deutsch](FAQ.de.md)')+'\n\n'+('## Einrichtung und Alltag' if de else '## Setup and everyday use')+'\n\n'+faq.split('\n\n[')[0]+'\n\n'+('## Daten, Dienste und Integration' if de else '## Data, services and integration')+'\n\n'+c['data']+'\n\n'+c['privacy']+'\n\n'+c['snippet']+'\n')
wiki=root/'wiki';wiki.mkdir(exist_ok=True)
# Wiki targets are formatted pages, while repository document links retain their suffix.
for lang in ['en','de']:
    de=lang=='de';c=source['languages'][lang];other='en' if de else 'de'
    switch='[English](Getting-started-en)' if de else '[Deutsch](Getting-started-de)'
    (wiki/f'Getting-started-{lang}.md').write_text('# '+('Erste Schritte' if de else 'Getting started')+'\n\n'+switch+'\n\n'+c['install']+'\n\n'+c['components']+'\n\n'+c['snippet']+'\n')
    (wiki/f'User-guide-{lang}.md').write_text('# '+('Anleitung' if de else 'User guide')+'\n\n'+('[English](User-guide-en)' if de else '[Deutsch](User-guide-de)')+'\n\n'+c['features']+'\n\n## '+('Grenzen' if de else 'Limits')+'\n\n'+c['limits']+'\n')
    (wiki/f'FAQ-{lang}.md').write_text((root/f'docs/FAQ.{lang}.md').read_text().replace('(FAQ.en.md)','(FAQ-en)').replace('(FAQ.de.md)','(FAQ-de)'))
    repo='https://github.com/deckerweb/purify-elementor'
    helptext=('[Fehler und Fragen]('+repo+'/issues) · [Austausch]('+repo+'/discussions) · [Sicherheitsrichtlinie]('+repo+'/security/policy)\n\nSicherheitsprobleme privat über Security → Advisories → Report a vulnerability melden. Keine Zugangsdaten oder Schwachstellendetails in öffentlichen Issues posten.' if de else '[Issues and questions]('+repo+'/issues) · [Discussions]('+repo+'/discussions) · [Security policy]('+repo+'/security/policy)\n\nReport security issues privately through Security → Advisories → Report a vulnerability. Do not post credentials or vulnerability details in public issues.')
    (wiki/f'Help-security-{lang}.md').write_text('# '+('Hilfe und Sicherheit' if de else 'Help and security')+'\n\n'+('[English](Help-security-en)' if de else '[Deutsch](Help-security-de)')+'\n\n'+helptext+'\n')
(wiki/'Home.md').write_text('# Purify Elementor\n\n![Purify Elementor](https://raw.githubusercontent.com/deckerweb/purify-elementor/main/assets-github/banner-en.png)\n\nA calmer Elementor workspace. / Eine ruhigere Elementor-Oberfläche.\n\nHide audited Elementor advertising and keep useful controls accessible. / Geprüfte Elementor-Werbung ausblenden und nützliche Funktionen zugänglich halten.\n\n## English\n\n- [Getting started](Getting-started-en)\n- [User guide](User-guide-en)\n- [FAQ by topic](FAQ-en)\n\n## Deutsch\n\n- [Erste Schritte](Getting-started-de)\n- [Anleitung](User-guide-de)\n- [Fragen nach Themen](FAQ-de)\n')
(wiki/'_Sidebar.md').write_text('## English\n\n- [Home](Home)\n- [Getting started](Getting-started-en)\n- [User guide](User-guide-en)\n- [FAQ by topic](FAQ-en)\n- [Changelog](Changelog-en)\n- [Help and security](Help-security-en)\n\n## Deutsch\n\n- [Startseite](Home)\n- [Erste Schritte](Getting-started-de)\n- [Anleitung](User-guide-de)\n- [Fragen nach Themen](FAQ-de)\n- [Änderungsverlauf](Changelog-de)\n- [Hilfe und Sicherheit](Help-security-de)\n')
(wiki/'_Footer.md').write_text('**Purify Elementor · Documentation / Dokumentation**\n\n[Wiki home / Wiki-Startseite](Home) · [Repository](https://github.com/deckerweb/purify-elementor) · [Downloads & Releases](https://github.com/deckerweb/purify-elementor/releases)\n\n[Help & bugs / Hilfe & Fehler](https://github.com/deckerweb/purify-elementor/issues) · [Discussions / Austausch](https://github.com/deckerweb/purify-elementor/discussions) · [Security / Sicherheit](https://github.com/deckerweb/purify-elementor/security/policy)\n\n© 2026 David Decker – DECKERWEB.  \nPlugin: [GPL v2 or later / GPL v2 oder höher](https://www.gnu.org/licenses/gpl-2.0.html).\n')

# WordPress-format documentation is an export format, not a distribution channel.
c=source['languages']['en']
readme='=== Purify Elementor ===\nContributors: deckerweb\nRequires at least: '+requires_wp+'\nRequires PHP: '+requires_php+'\nTested up to: '+tested_wp+'\nStable tag: '+version+'\nLicense: GPLv2 or later\nLicense URI: https://www.gnu.org/licenses/gpl-2.0.html\n\nA calmer Elementor workspace.\n\n== Description ==\n'+c['about']+'\n\n== Installation ==\n'+c['install']+'\n\n== Frequently Asked Questions ==\n'
for q,a in c['faq']:readme+='\n= '+q+' =\n'+a+'\n'
(root/'readme.txt').write_text(readme+'\n== Changelog ==\n'+(root/'CHANGELOG.en.txt').read_text())
for lang in ['en','de']:
 heading='Änderungsverlauf' if lang=='de' else 'Changelog'
 text='# '+heading+'\n\n'
 for release_version,release in source['history'].items():
  text+='## '+release_version+' · '+release_date(release,lang=='de')+'\n\n'+'\n'.join('- **'+cat+':** '+entry for cat,entry in release[lang])+'\n\n'
 (wiki/f'Changelog-{lang}.md').write_text(text)
