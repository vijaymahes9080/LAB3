import pypdf
import os

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\document\rendered_test.pdf')
print(f"Total Pages in rendered_test.pdf: {len(reader.pages)}")

pages_info = []
for i, page in enumerate(reader.pages):
    txt = (page.extract_text() or '').strip()
    img_count = len(page.images)
    lines = [l.strip() for l in txt.splitlines() if l.strip()]
    has_header = any('EX.NO:' in l for l in lines)
    pages_info.append({
        'page': i + 1,
        'chars': len(txt),
        'imgs': img_count,
        'lines_count': len(lines),
        'has_header': has_header,
        'first_lines': lines[:3]
    })

print("\n=== BLANK OR VERY SHORT PAGES (chars < 120 and imgs == 0) ===")
blank_count = 0
for p in pages_info:
    if p['chars'] < 120 and p['imgs'] == 0:
        blank_count += 1
        print(f"Page {p['page']:03d}: chars={p['chars']}, imgs={p['imgs']}, header={p['has_header']}, text={p['first_lines']}")

print(f"\nTotal blank / very short pages: {blank_count}")

print("\n=== EXPERIMENT PAGE SPANS ===")
current_exp = None
for p in pages_info:
    if p['has_header']:
        for l in p['first_lines']:
            if 'EX.NO:' in l:
                print(f"Page {p['page']:03d} -> {l} (Chars: {p['chars']}, Imgs: {p['imgs']})")

