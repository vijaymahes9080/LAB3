import docx
from docx.shared import Pt

doc = docx.Document(r'c:\Users\vijay\Downloads\vj\document\index.docx')
t = doc.tables[0]
c = t.rows[1].cells[3]
p = c.paragraphs[0]
print("Text:", p.text)
print("Alignment:", p.alignment)
if p.runs:
    r = p.runs[0]
    print("Font:", r.font.name, r.font.size)

