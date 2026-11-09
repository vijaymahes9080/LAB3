import docx

doc = docx.Document(r'c:\Users\vijay\Downloads\vj\document\index.docx')
t = doc.tables[0]
print(f"Total rows in index.docx: {len(t.rows)}")
for r in t.rows[:10]:
    print([c.text.strip() for c in r.cells])

