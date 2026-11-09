import os
import json

with open('document/scripts/pdf_image_map.json', encoding='utf-8') as f:
    pdf_imgs = json.load(f)

for k in range(1, 34):
    k_str = str(k)
    imgs = pdf_imgs.get(k_str, {}).get('images', [])
    img_files = [x['file'] for x in imgs if os.path.exists(x['file'])]
    print(f"Ex {k:02d}: {len(img_files)} existing images -> {img_files}")

