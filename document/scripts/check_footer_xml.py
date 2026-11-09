import docx

doc = docx.Document(r'c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx')
for s in doc.sections:
    f = s.footer
    print("Footer paragraphs count:", len(f.paragraphs))
    print("Footer XML:", f._element.xml)

