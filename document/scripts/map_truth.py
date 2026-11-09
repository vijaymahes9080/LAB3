import json
import os

with open('document/scripts/pdf_all_pages_summary.json') as f:
    pages = json.load(f)

# Let's see the extracted images in document/assets/
assets = os.listdir('document/assets')
page_assets = {}
for a in assets:
    if a.startswith('page_'):
        parts = a.split('_')
        pnum = int(parts[1])
        page_assets.setdefault(pnum, []).append(a)

print("=== PDF EXPERIMENTS WITH OUTPUT PAGES AND ASSET IMAGES ===")
for p in pages:
    pnum = p['page']
    imgs = page_assets.get(pnum, [])
    if p['ex_no'] or p['has_output'] or imgs:
        print(f"P.{pnum:03d} | {p['ex_no']:<10} | Out:{str(p['has_output']):<5} | Imgs:{imgs} | Aim:{p['aim'][:50]}")

