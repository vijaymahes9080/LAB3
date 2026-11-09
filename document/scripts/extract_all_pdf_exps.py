import pypdf
import re
import json

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\Lab Document.pdf')

experiments = []

for pno in range(1, len(reader.pages) + 1):
    txt = reader.pages[pno - 1].extract_text() or ''
    lines = [l.strip() for l in txt.splitlines() if l.strip()]
    
    # Check if page has EX.NO or EX. NO:
    for i, line in enumerate(lines):
        m = re.search(r'EX\.?\s*NO\s*:\s*(\d+)', line, re.IGNORECASE)
        if m:
            exp_num = int(m.group(1))
            # look for date
            date = ""
            for j in range(i, min(len(lines), i + 6)):
                if 'DATE:' in lines[j] or 'DATE' in lines[j]:
                    if j + 1 < len(lines) and re.search(r'\d+[./]\d+[./]\d+', lines[j+1]):
                        date = lines[j+1]
                    elif re.search(r'\d+[./]\d+[./]\d+', lines[j]):
                        dm = re.search(r'\d+[./]\d+[./]\d+', lines[j])
                        date = dm.group(0)
            # look for title: lines between date and AIM
            title_parts = []
            aim_idx = None
            for j in range(i, min(len(lines), i + 8)):
                if lines[j].startswith('AIM:'):
                    aim_idx = j
                    break
            
            # title is in header
            for j in range(i, aim_idx if aim_idx else min(len(lines), i + 6)):
                l = lines[j]
                if not re.search(r'EX\.?\s*NO', l, re.IGNORECASE) and not l.startswith('DATE'):
                    title_parts.append(l)
            
            title = " ".join(title_parts)
            experiments.append({
                'pdf_exp_num': exp_num,
                'page': pno,
                'date': date,
                'title': title
            })
            break

print(f"Total experiments found with EX. NO: {len(experiments)}")
for e in experiments:
    print(f"Ex {e['pdf_exp_num']:02d} | Page {e['page']:03d} | Date: {e['date']} | Title: {e['title']}")

