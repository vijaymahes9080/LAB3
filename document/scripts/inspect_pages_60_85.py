import pypdf

reader = pypdf.PdfReader(r'c:\Users\vijay\Downloads\vj\Lab Document.pdf')

for pno in range(60, 85):
    txt = reader.pages[pno - 1].extract_text() or ''
    lines = [l.strip() for l in txt.splitlines() if l.strip()]
    print(f"=== PAGE {pno} ===")
    for l in lines[:4]:
        print("  ", l)

