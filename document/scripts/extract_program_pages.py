import pypdf
import re
import json

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\document\user_rendered.pdf')
print(f"Total pages in user_rendered.pdf: {len(reader.pages)}")

prog_start_pages = {}

for pno, page in enumerate(reader.pages, start=1):
    txt = page.extract_text() or ''
    # Look for EX.NO: or EX. NO:
    matches = re.findall(r'EX\.?\s*NO\s*:\s*(\d+)', txt, re.IGNORECASE)
    for m in matches:
        num = int(m)
        if num not in prog_start_pages:
            prog_start_pages[num] = pno

print(f"Total programs mapped: {len(prog_start_pages)} / 85")
missing = [i for i in range(1, 86) if i not in prog_start_pages]
if missing:
    print(f"Warning: Missing programs {missing}")

for i in range(1, 86):
    print(f"Ex {i:02d} -> Starts on Page {prog_start_pages.get(i)}")

with open('document/scripts/user_prog_pages.json', 'w') as f:
    json.dump(prog_start_pages, f, indent=2)

