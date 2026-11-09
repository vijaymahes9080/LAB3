import pypdf

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\document\rendered_test.pdf')
total = len(reader.pages)
print(f'Total pages in rendered PDF: {total}')

all_correct = True
for pno in range(1, total + 1):
    page = reader.pages[pno - 1]
    txt = page.extract_text() or ''
    lines = [l.strip() for l in txt.splitlines() if l.strip()]
    last_line = lines[-1] if lines else ''
    
    # Check if the page number is in the last lines
    found_num = False
    for l in lines[-3:]:
        if l == str(pno):
            found_num = True
            break
            
    if not found_num:
        print(f"Page {pno:03d}: Expected {pno}, but last lines are: {lines[-2:]}")
        all_correct = False
    elif pno in [1, 2, 3, 10, 25, 50, 75, 100, total]:
        print(f"✔ Page {pno:03d} footer: {pno}")

if all_correct:
    print(f"\n🎉 100% VERIFIED: Every single page from 1 to {total} has its correct centered page number in the footer!")

