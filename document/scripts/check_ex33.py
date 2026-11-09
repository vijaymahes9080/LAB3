import json

with open('document/scripts/pdf_dump.json', encoding='utf-8') as f:
    data = json.load(f)

for item in data[59:66]:
    print(f"=== PAGE {item['page']} ===")
    print(item['text'][:400])

