import pypdf
import re
import json

reader = pypdf.PdfReader('Lab Document.pdf')
ex_map = {}
current_ex = None

for p_num in range(1, len(reader.pages) + 1):
    text = reader.pages[p_num - 1].extract_text() or ''
    m = re.search(r'EX\.?\s*NO\s*:\s*(\d+)', text, re.IGNORECASE)
    if m:
        current_ex = int(m.group(1))
        if current_ex not in ex_map:
            ex_map[current_ex] = {'pages': [], 'images': []}
    if current_ex is not None:
        ex_map[current_ex]['pages'].append(p_num)
        page_imgs = reader.pages[p_num - 1].images
        if len(page_imgs) > 0:
            for img in page_imgs:
                ex_map[current_ex]['images'].append({
                    'page': p_num,
                    'name': img.name,
                    'file': f'document/assets/page_{p_num}_{img.name}'
                })

with open('document/scripts/pdf_image_map.json', 'w', encoding='utf-8') as f:
    json.dump(ex_map, f, indent=2)

print(f"Mapped {len(ex_map)} experiments in Lab Document.pdf")
for ex in sorted(ex_map.keys())[:15]:
    print(f"Ex {ex:02d}: {len(ex_map[ex]['images'])} images, pages: {ex_map[ex]['pages']}")

