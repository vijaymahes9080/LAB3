import docx

doc = docx.Document(r'c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx')

print(f"Total paragraphs: {len(doc.paragraphs)}")
print(f"Total tables: {len(doc.tables)}")

# Check for consecutive page breaks or empty paragraphs with page breaks
empty_p = 0
for i, p in enumerate(doc.paragraphs):
    if not p.text.strip() and not p.runs:
        empty_p += 1
    # check runs
    for r in p.runs:
        if '<w:br w:type="page"/>' in r._element.xml:
            # check if previous paragraph was empty or had page break
            pass

print(f"Empty paragraphs without runs: {empty_p}")

