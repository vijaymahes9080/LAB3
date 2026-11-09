import pypdf
import json
import re

reader = pypdf.PdfReader('Lab Document.pdf')
pages_data = []

for idx, page in enumerate(reader.pages):
    text = page.extract_text() or ''
    pages_data.append({
        'page': idx + 1,
        'text': text,
        'image_count': len(page.images)
    })

with open('document/scripts/pdf_dump.json', 'w', encoding='utf-8') as f:
    json.dump(pages_data, f, indent=2)

print(f"Dumped {len(pages_data)} pages from Lab Document.pdf")

