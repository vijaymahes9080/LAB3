import os
import json
import re

with open('document/scripts/all_85_dataset.json', 'r', encoding='utf-8') as f:
    dataset = json.load(f)

print(f"Total programs in dataset: {len(dataset)}")

# Let's inspect each program's title, code snippet, and output
for p in dataset:
    ex = p['ex_no']
    title = p['title']
    date = p['date']
    code = p['code']
    out_img = p.get('output_img')
    out_txt = p.get('output_text')
    
    # summarize code
    code_first_line = ""
    for l in code.splitlines():
        l_s = l.strip()
        if l_s and not l_s.startswith('/*') and not l_s.startswith('//'):
            code_first_line = l_s[:60]
            break
            
    img_name = os.path.basename(out_img) if out_img else "NO_IMG"
    print(f"Ex {ex:02d} | Title: {title:<35} | Img: {img_name:<20} | Code1st: {code_first_line}")

