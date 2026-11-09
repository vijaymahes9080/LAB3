import docx

doc = docx.Document(r'c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx')
print('Sections count:', len(doc.sections))
for i, s in enumerate(doc.sections):
    print(f'Section {i}: start_type={s.start_type}')
    footer = s.footer
    print(f'Section {i} footer paragraphs:', len(footer.paragraphs))
    for p in footer.paragraphs:
        print(f'  Footer text: "{p.text}"')

print('Total paragraphs:', len(doc.paragraphs))
print('Total tables:', len(doc.tables))

# Check first few paragraphs and tables
for p in doc.paragraphs[:5]:
    if p.text.strip():
        print('P:', p.text.strip())

for tbl in doc.tables[:3]:
    if tbl.rows:
        row0 = [c.text.strip().replace('\n', ' ') for c in tbl.rows[0].cells]
        print('Tbl row 0:', row0[:4])

