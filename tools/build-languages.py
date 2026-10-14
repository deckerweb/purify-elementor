#!/usr/bin/env python3
from pathlib import Path
import json,struct
r=Path(__file__).resolve().parents[1]
langs=r/"languages"
messages=json.loads((langs/"messages.json").read_text())
def quote(x):return json.dumps(x,ensure_ascii=False)
def mo(entries):
 keys=sorted(entries);ids=b'';strs=b'';ki=[];vi=[]
 for k in keys:
  x=k.encode();y=entries[k].encode();ki.append((len(x),len(ids)));vi.append((len(y),len(strs)));ids+=x+b'\0';strs+=y+b'\0'
 start=28+16*len(keys);out=struct.pack('<7I',0x950412de,0,len(keys),28,28+8*len(keys),0,0)
 out+=b''.join(struct.pack('<2I',n,start+o) for n,o in ki);out+=b''.join(struct.pack('<2I',n,start+len(ids)+o) for n,o in vi)
 return out+ids+strs
for locale in ['de_DE','de_DE_formal']:
 entries={en:values.get(locale,values.get('de_DE',en)) for en,values in messages.items()};entries['']='Content-Type: text/plain; charset=UTF-8\nLanguage: '+locale+'\nPlural-Forms: nplurals=2; plural=(n != 1);\n'
 (langs/f'purify-elementor-{locale}.po').write_text('\n\n'.join('msgid '+quote(en)+'\nmsgstr '+quote(de) for en,de in sorted(entries.items()))+'\n')
 (langs/f'purify-elementor-{locale}.mo').write_bytes(mo(entries))
(langs/'purify-elementor.pot').write_text('msgid ""\nmsgstr "Content-Type: text/plain; charset=UTF-8\\n"\n\n'+'\n\n'.join('msgid '+quote(en)+'\nmsgstr ""' for en in sorted(messages))+'\n')
