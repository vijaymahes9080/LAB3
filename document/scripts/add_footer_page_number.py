import docx
from docx import Document
from docx.shared import Pt, Inches
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

def add_footer_page_number(doc_path):
    doc = Document(doc_path)
    
    for s_idx, section in enumerate(doc.sections):
        # Configure footer margin so page number is neatly placed
        section.footer_distance = Inches(0.4)
        
        footer = section.footer
        p = footer.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        p.text = "" # clear any text
        
        # XML for centered page number field formatted in Times New Roman 10pt
        fld_xml = f'''
        <w:fldSimple {nsdecls("w")} w:instr="PAGE">
            <w:r>
                <w:rPr>
                    <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/>
                    <w:sz w:val="20"/>
                    <w:szCs w:val="20"/>
                </w:rPr>
                <w:t>1</w:t>
            </w:r>
        </w:fldSimple>
        '''
        p._p.append(parse_xml(fld_xml))
        
    doc.save(doc_path)
    print("Page numbers added to footers successfully!")

if __name__ == '__main__':
    add_footer_page_number(r'c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx')

