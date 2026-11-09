import pypdf
import json

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\Lab Document.pdf')

pdf_pages_info = []
for idx, page in enumerate(reader.pages):
    txt = page.extract_text() or ''
    lines = [l.strip() for l in txt.splitlines() if l.strip()]
    ex_no = ""
    aim = ""
    has_output = False
    has_program = False
    for l in lines:
        if 'EX.NO:' in l:
            ex_no = l
        if 'AIM:' in l:
            aim = l
        if 'OUTPUT' in l:
            has_output = True
        if 'PROGRAM:' in l or 'SOURCE CODE:' in l:
            has_program = True
    pdf_pages_info.append({
        'page': idx + 1,
        'ex_no': ex_no,
        'aim': aim[:80],
        'has_output': has_output,
        'has_program': has_program,
        'first_3': lines[:3]
    })

with open('document/scripts/pdf_all_pages_summary.json', 'w', encoding='utf-8') as f:
    json.dump(pdf_pages_info, f, indent=2)

print("Saved pdf_all_pages_summary.json")

