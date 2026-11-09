import pypdf
import json
import re

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\Lab Document.pdf')

pdf_exps = []
for idx, page in enumerate(reader.pages):
    txt = page.extract_text() or ''
    if 'EX.NO:' in txt:
        lines = [l.strip() for l in txt.splitlines() if l.strip()]
        # find EX.NO line
        ex_line = ""
        date_line = ""
        aim_line = ""
        for i, l in enumerate(lines):
            if 'EX.NO:' in l:
                ex_line = l
            if 'DATE:' in l and not date_line:
                date_line = l
                if i + 1 < len(lines) and not lines[i+1].startswith('AIM'):
                    date_line += " " + lines[i+1]
            if 'AIM:' in l:
                aim_line = " ".join(lines[i:i+3])
                break
        pdf_exps.append({'page': idx + 1, 'ex_line': ex_line, 'date_line': date_line, 'aim_line': aim_line, 'first_lines': lines[:6]})

print(f"Total EX.NO pages found in PDF: {len(pdf_exps)}")
for e in pdf_exps:
    print(f"P.{e['page']:03d} | {e['ex_line']} | {e['date_line']} | {e['aim_line']}")

