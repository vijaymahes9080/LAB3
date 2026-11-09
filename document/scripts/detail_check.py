import json
import os

with open('document/scripts/all_85_dataset.json', 'r', encoding='utf-8') as f:
    programs = json.load(f)

print("--- AUDITING ALL 85 PROGRAMS DETAIL ---")
for p in programs:
    num = p['ex_no']
    title = p['title']
    code = p['code']
    img = p.get('output_img')
    txt = p.get('output_text')

    # check first 50 chars of code
    code_preview = code.replace('\n', ' ')[:50]
    
    # check output
    out_status = "IMG: " + os.path.basename(img) if img else f"TXT: {txt[:30]}..."
    
    print(f"[{num:02d}] {title[:32]:<32} | Code: {len(code):>4} chars | {out_status}")

