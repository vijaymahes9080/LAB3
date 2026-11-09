import docx
from docx import Document
from docx.shared import Inches, Pt
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

def create_sample():
    doc = Document()
    sec = doc.sections[0]
    sec.page_width = Inches(8.27)
    sec.page_height = Inches(11.69)
    sec.top_margin = Inches(0.7)
    sec.bottom_margin = Inches(0.7)
    sec.left_margin = Inches(0.7)
    sec.right_margin = Inches(0.7)

    # In WordprocessingML, w:pgBorders defines page border
    pg_borders_xml = f'''
    <w:pgBorders {nsdecls("w")} w:offsetFrom="page">
        <w:top w:val="single" w:sz="6" w:space="20" w:color="000000"/>
        <w:left w:val="single" w:sz="6" w:space="20" w:color="000000"/>
        <w:bottom w:val="single" w:sz="6" w:space="20" w:color="000000"/>
        <w:right w:val="single" w:sz="6" w:space="20" w:color="000000"/>
    </w:pgBorders>
    '''
    sec._sectPr.append(parse_xml(pg_borders_xml))

    tbl = doc.add_table(rows=1, cols=2)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl_borders_xml = f'''
    <w:tblBorders {nsdecls("w")}>
        <w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/>
        <w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/>
        <w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/>
        <w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>
        <w:insideV w:val="single" w:sz="6" w:space="0" w:color="000000"/>
        <w:insideH w:val="none"/>
    </w:tblBorders>
    '''
    tbl._tbl.tblPr.append(parse_xml(tbl_borders_xml))

    c0 = tbl.cell(0, 0)
    c1 = tbl.cell(0, 1)
    c0.width = Inches(1.8)
    c1.width = Inches(5.0)

    p0 = c0.paragraphs[0]
    p0.paragraph_format.space_before = Pt(2)
    p0.paragraph_format.space_after = Pt(2)
    p0.paragraph_format.line_spacing = 1.15
    r0 = p0.add_run("EX.NO:04\nDATE:\n28.7.2025")
    r0.font.name = "Times New Roman"
    r0.font.size = Pt(11)
    r0.font.bold = True

    p1 = c1.paragraphs[0]
    p1.alignment = WD_ALIGN_PARAGRAPH.CENTER
    c1.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
    p1.paragraph_format.space_before = Pt(4)
    p1.paragraph_format.space_after = Pt(4)
    r1 = p1.add_run("Design any web page using Frames")
    r1.font.name = "Times New Roman"
    r1.font.size = Pt(13)
    r1.font.bold = True

    doc.save("test_border.docx")
    print("test_border.docx generated successfully!")

if __name__ == "__main__":
    create_sample()

