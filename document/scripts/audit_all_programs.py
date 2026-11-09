import json
import os

with open('document/scripts/all_85_dataset.json', 'r', encoding='utf-8') as f:
    programs = json.load(f)

print(f"Total programs in dataset: {len(programs)}\n")

issues = []
for p in programs:
    num = p['ex_no']
    date = p['date']
    title = p['title']
    code = p['code']
    img = p.get('output_img')
    out_txt = p.get('output_text')
    
    code_lines = len(code.splitlines()) if code else 0
    code_len = len(code) if code else 0
    
    has_img = bool(img and os.path.exists(img))
    has_txt = bool(out_txt and out_txt.strip())

    if code_len < 30:
        issues.append(f"Ex {num:02d}: Code too short ({code_len} chars)")
    
    if not has_img and not has_txt:
        issues.append(f"Ex {num:02d}: NO output image AND NO output text!")
        
    print(f"Ex {num:02d} | Date: {date:<8} | Title: {title:<38} | Code: {code_lines:>3} lines ({code_len:>5} chars) | Img: {'YES' if has_img else ' NO'} | Txt: {'YES' if has_txt else ' NO'}")

print(f"\nTotal potential issues detected: {len(issues)}")
for iss in issues:
    print(" - " + iss)

