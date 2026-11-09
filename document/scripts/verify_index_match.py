import docx
import json

doc = docx.Document(r'c:\Users\vijay\Downloads\vj\document\index.docx')
t = doc.tables[0]

with open(r'c:\Users\vijay\Downloads\vj\document\scripts\user_prog_pages.json') as f:
    pages = json.load(f)

errors = []
for i in range(1, 86):
    row = t.rows[i]
    sno = row.cells[0].text.strip()
    date = row.cells[1].text.strip()
    title = row.cells[2].text.strip()
    pno = row.cells[3].text.strip()
    expected = str(pages[str(i)])
    if pno != expected:
        errors.append((i, pno, expected))
    if i in [1, 2, 3, 10, 20, 50, 80, 85]:
        print(f"Row {i:02d}: S.No={sno:<3} | Date={date:<8} | Title={title:<35} | Page No={pno}")

if errors:
    print("Errors found:", errors)
else:
    print("\n✅ 100% VERIFIED: All 85 rows in index.docx perfectly match the starting page numbers of Lab_Record_1_to_85.docx!")

