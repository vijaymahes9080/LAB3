import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls
import json

with open('document/scripts/all_85_dataset.json', 'r', encoding='utf-8') as f:
    programs = json.load(f)

doc = Document()
sec = doc.sections[0]
sec.page_width = Inches(8.27)
sec.page_height = Inches(11.69)
sec.top_margin = Inches(0.65)
sec.bottom_margin = Inches(0.65)
sec.left_margin = Inches(0.65)
sec.right_margin = Inches(0.65)

# Page border
pg_borders_xml = f'''
<w:pgBorders {nsdecls("w")} w:offsetFrom="page">
    <w:top w:val="single" w:sz="8" w:space="24" w:color="000000"/>
    <w:left w:val="single" w:sz="8" w:space="24" w:color="000000"/>
    <w:bottom w:val="single" w:sz="8" w:space="24" w:color="000000"/>
    <w:right w:val="single" w:sz="8" w:space="24" w:color="000000"/>
</w:pgBorders>
'''
sec._sectPr.append(parse_xml(pg_borders_xml))

p_title = doc.add_paragraph()
p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_title.paragraph_format.space_before = Pt(4)
p_title.paragraph_format.space_after = Pt(8)
r_title = p_title.add_run('INDEX')
r_title.font.name = 'Times New Roman'
r_title.font.size = Pt(16)
r_title.font.bold = True

tbl = doc.add_table(rows=len(programs) + 1, cols=4)
tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
tbl.autofit = False

tbl_borders_xml = f'''
<w:tblBorders {nsdecls("w")}>
    <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
    <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
    <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
    <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
    <w:insideH w:val="single" w:sz="4" w:space="0" w:color="CCCCCC"/>
    <w:insideV w:val="single" w:sz="6" w:space="0" w:color="000000"/>
</w:tblBorders>
'''
tbl._tbl.tblPr.append(parse_xml(tbl_borders_xml))

col_widths = [Inches(0.7), Inches(1.1), Inches(4.3), Inches(0.85)]
headers = ['S. No.', 'DATE', 'TITLE', 'Page No.']

# Header row
hdr_cells = tbl.rows[0].cells
for i, h in enumerate(headers):
    hdr_cells[i].width = col_widths[i]
    p = hdr_cells[i].paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.paragraph_format.space_before = Pt(3)
    p.paragraph_format.space_after = Pt(3)
    r = p.add_run(h)
    r.font.name = 'Times New Roman'
    r.font.size = Pt(10.5)
    r.font.bold = True

# Repeat header row on each page in Word:
trPr = tbl.rows[0]._tr.get_or_add_trPr()
trPr.append(parse_xml(f'<w:tblHeader {nsdecls("w")}/>'))
trPr.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

for idx, prog in enumerate(programs):
    row = tbl.rows[idx + 1]
    r_trPr = row._tr.get_or_add_trPr()
    r_trPr.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))
    row_cells = row.cells
    for i in range(4):
        row_cells[i].width = col_widths[i]
    
    # S. No.
    p0 = row_cells[0].paragraphs[0]
    p0.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p0.paragraph_format.space_before = Pt(1.5)
    p0.paragraph_format.space_after = Pt(1.5)
    p0.paragraph_format.line_spacing = 1.05
    r0 = p0.add_run(str(prog['ex_no']))
    r0.font.name = 'Times New Roman'
    r0.font.size = Pt(9.5)
    
    # DATE
    p1 = row_cells[1].paragraphs[0]
    p1.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p1.paragraph_format.space_before = Pt(1.5)
    p1.paragraph_format.space_after = Pt(1.5)
    p1.paragraph_format.line_spacing = 1.05
    r1 = p1.add_run(prog['date'])
    r1.font.name = 'Times New Roman'
    r1.font.size = Pt(9.5)
    
    # TITLE
    p2 = row_cells[2].paragraphs[0]
    p2.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p2.paragraph_format.space_before = Pt(1.5)
    p2.paragraph_format.space_after = Pt(1.5)
    p2.paragraph_format.line_spacing = 1.05
    r2 = p2.add_run(prog['title'])
    r2.font.name = 'Times New Roman'
    r2.font.size = Pt(9.5)
    
    # Page No.
    p3 = row_cells[3].paragraphs[0]
    p3.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p3.paragraph_format.space_before = Pt(1.5)
    p3.paragraph_format.space_after = Pt(1.5)
    p3.paragraph_format.line_spacing = 1.05
    r3 = p3.add_run('')
    r3.font.name = 'Times New Roman'
    r3.font.size = Pt(9.5)

test_path = r'c:\Users\vijay\Downloads\vj\document\test_index_only.docx'
doc.save(test_path)
print('Saved test_index_only.docx successfully')

