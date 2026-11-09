import pypdf
import json
import os

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\Lab Document.pdf')

# Let's map each experiment in Lab Document.pdf
# Experiments 1 to 50 are in Lab Document.pdf
exps_pdf = {}

current_exp = None
for page_num in range(1, len(reader.pages) + 1):
    txt = reader.pages[page_num - 1].extract_text() or ''
    lines = [l.strip() for l in txt.splitlines() if l.strip()]
    
    # check for EX.NO
    found_ex = None
    for l in lines:
        if 'EX.NO:' in l:
            # extract number
            parts = l.split('EX.NO:')
            if len(parts) > 1:
                digits = ''.join(c for c in parts[1] if c.isdigit())
                if digits:
                    found_ex = int(digits)
                    break
    
    if found_ex is not None:
        current_exp = found_ex
        exps_pdf[current_exp] = {
            'pdf_ex_no': current_exp,
            'start_page': page_num,
            'pages': [page_num],
            'first_lines': lines[:8]
        }
    elif current_exp is not None:
        exps_pdf[current_exp]['pages'].append(page_num)

for exp_id in sorted(exps_pdf.keys()):
    e = exps_pdf[exp_id]
    print(f"PDF Ex {exp_id:02d} | Pages {e['pages']} | Lines: {' // '.join(e['first_lines'][:4])}")

