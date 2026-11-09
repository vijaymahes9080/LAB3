import pypdf
import json

reader = pypdf.PdfReader('AngularJS_Lab_Programs_42_to_58.pdf')
pages_data = []

for idx, page in enumerate(reader.pages):
    text = page.extract_text() or ''
    pages_data.append({
        'page': idx + 1,
        'text': text
    })

with open('document/scripts/angular_pdf_dump.json', 'w', encoding='utf-8') as f:
    json.dump(pages_data, f, indent=2)

print(f"Dumped {len(pages_data)} pages from AngularJS_Lab_Programs_42_to_58.pdf")

