import os
import json
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn
from PIL import Image

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(f'''
        <w:tcMar {nsdecls("w")}>
            <w:top w:w="{top}" w:type="dxa"/>
            <w:bottom w:w="{bottom}" w:type="dxa"/>
            <w:left w:w="{left}" w:type="dxa"/>
            <w:right w:w="{right}" w:type="dxa"/>
        </w:tcMar>
    ''')
    tcPr.append(tcMar)

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    tcPr.append(shd)

def generate_optimized_docx(page_offset=0):
    print("=" * 64)
    print("STARTING LAB RECORD DOCX GENERATION WITH EMBEDDED INDEX")
    print("=" * 64)

    dataset_path = 'document/scripts/all_85_dataset.json'
    with open(dataset_path, 'r', encoding='utf-8') as f:
        programs = json.load(f)

    # Load calculated experiment page numbers if available
    exp_pages_map = {}
    if os.path.exists('document/scripts/exp_page_numbers.json'):
        with open('document/scripts/exp_page_numbers.json', 'r') as f:
            raw_map = json.load(f)
            exp_pages_map = {int(k): v for k, v in raw_map.items()}

    doc = Document()

    # Configure A4 Portrait layout
    sec = doc.sections[0]
    sec.page_width = Inches(8.27)
    sec.page_height = Inches(11.69)
    sec.top_margin = Inches(0.65)
    sec.bottom_margin = Inches(0.65)
    sec.left_margin = Inches(0.65)
    sec.right_margin = Inches(0.65)

    # Outer page border framing every page
    pg_borders_xml = f'''
    <w:pgBorders {nsdecls("w")} w:offsetFrom="page">
        <w:top w:val="single" w:sz="8" w:space="24" w:color="000000"/>
        <w:left w:val="single" w:sz="8" w:space="24" w:color="000000"/>
        <w:bottom w:val="single" w:sz="8" w:space="24" w:color="000000"/>
        <w:right w:val="single" w:sz="8" w:space="24" w:color="000000"/>
    </w:pgBorders>
    '''
    sec._sectPr.append(parse_xml(pg_borders_xml))

    # Base typography: Times New Roman
    style = doc.styles['Normal']
    font = style.font
    font.name = 'Times New Roman'
    font.size = Pt(11)
    font.color.rgb = RGBColor(0, 0, 0)

    # =========================================================================
    # PART 1: CENTERED INDEX HEADING & INDEX TABLE (S. No., DATE, TITLE, Page No.)
    # =========================================================================
    p_idx_title = doc.add_paragraph()
    p_idx_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_idx_title.paragraph_format.space_before = Pt(6)
    p_idx_title.paragraph_format.space_after = Pt(8)
    r_idx_title = p_idx_title.add_run("INDEX")
    r_idx_title.font.name = "Times New Roman"
    r_idx_title.font.size = Pt(16)
    r_idx_title.font.bold = True

    idx_tbl = doc.add_table(rows=len(programs) + 1, cols=4)
    idx_tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    idx_tbl.autofit = False

    idx_borders_xml = f'''
    <w:tblBorders {nsdecls("w")}>
        <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
        <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
        <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
        <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
        <w:insideH w:val="single" w:sz="4" w:space="0" w:color="B0B0B0"/>
        <w:insideV w:val="single" w:sz="6" w:space="0" w:color="000000"/>
    </w:tblBorders>
    '''
    idx_tbl._tbl.tblPr.append(parse_xml(idx_borders_xml))

    col_widths = [Inches(0.70), Inches(1.10), Inches(4.30), Inches(0.87)]
    headers = ["S. No.", "DATE", "TITLE", "Page No."]

    # Header Row
    hdr_row = idx_tbl.rows[0]
    hdr_trPr = hdr_row._tr.get_or_add_trPr()
    hdr_trPr.append(parse_xml(f'<w:tblHeader {nsdecls("w")}/>'))
    hdr_trPr.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

    for col_idx, col_name in enumerate(headers):
        c = hdr_row.cells[col_idx]
        c.width = col_widths[col_idx]
        set_cell_background(c, "F0F0F0")
        p = c.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.paragraph_format.space_before = Pt(3)
        p.paragraph_format.space_after = Pt(3)
        r = p.add_run(col_name)
        r.font.name = "Times New Roman"
        r.font.size = Pt(10)
        r.font.bold = True

    # Data Rows
    for p_idx, prog in enumerate(programs):
        row = idx_tbl.rows[p_idx + 1]
        r_trPr = row._tr.get_or_add_trPr()
        r_trPr.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

        c_sno, c_date, c_title, c_pno = row.cells[0], row.cells[1], row.cells[2], row.cells[3]
        c_sno.width = col_widths[0]
        c_date.width = col_widths[1]
        c_title.width = col_widths[2]
        c_pno.width = col_widths[3]

        # S. No.
        p_s = c_sno.paragraphs[0]
        p_s.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_s.paragraph_format.space_before = Pt(1.2)
        p_s.paragraph_format.space_after = Pt(1.2)
        p_s.paragraph_format.line_spacing = 1.05
        r_s = p_s.add_run(str(prog['ex_no']))
        r_s.font.name = "Times New Roman"
        r_s.font.size = Pt(9.5)

        # DATE
        p_d = c_date.paragraphs[0]
        p_d.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_d.paragraph_format.space_before = Pt(1.2)
        p_d.paragraph_format.space_after = Pt(1.2)
        p_d.paragraph_format.line_spacing = 1.05
        r_d = p_d.add_run(prog['date'])
        r_d.font.name = "Times New Roman"
        r_d.font.size = Pt(9.5)

        # TITLE
        p_t = c_title.paragraphs[0]
        p_t.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p_t.paragraph_format.space_before = Pt(1.2)
        p_t.paragraph_format.space_after = Pt(1.2)
        p_t.paragraph_format.line_spacing = 1.05
        r_t = p_t.add_run(prog['title'])
        r_t.font.name = "Times New Roman"
        r_t.font.size = Pt(9.5)

        # Page No.
        p_p = c_pno.paragraphs[0]
        p_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_p.paragraph_format.space_before = Pt(1.2)
        p_p.paragraph_format.space_after = Pt(1.2)
        p_p.paragraph_format.line_spacing = 1.05
        pno_val = ""
        if prog['ex_no'] in exp_pages_map:
            pno_val = str(exp_pages_map[prog['ex_no']])
        r_p = p_p.add_run(pno_val)
        r_p.font.name = "Times New Roman"
        r_p.font.size = Pt(9.5)

    # =========================================================================
    # PART 2: ALL 85 EXPERIMENTS
    # =========================================================================
    total_programs = len(programs)

    for idx, prog in enumerate(programs):
        ex_no = prog['ex_no']
        date_str = prog['date']
        title = prog['title']
        aim_text = prog['aim']
        code = prog['code'].strip()
        output_img = prog.get('output_img')
        output_text = prog.get('output_text', '').strip()
        result_text = prog['result']

        code_lines = [l for l in code.splitlines()]
        code_line_count = len(code_lines)

        # -----------------------------------------------------------------
        # PAGE BREAK CONTROL WITHOUT ACCIDENTAL BLANK PAGES
        # Starts Program 1 on a fresh page after INDEX, and every program on a new page.
        # -----------------------------------------------------------------
        p_lead = doc.add_paragraph()
        p_lead.paragraph_format.page_break_before = True
        p_lead.paragraph_format.space_before = Pt(0)
        p_lead.paragraph_format.space_after = Pt(0)
        p_lead.paragraph_format.line_spacing = Pt(1)

        # -----------------------------------------------------------------
        # 1. HEADER TABLE (Reference Image Structure)
        # Left: EXACTLY TWO LINES ("EX.NO:XX\nDATE: DD/MM/YY")
        # Right: Centered bold experiment title
        # -----------------------------------------------------------------
        tbl = doc.add_table(rows=1, cols=2)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        tbl.autofit = False

        trPr = tbl.rows[0]._tr.get_or_add_trPr()
        trPr.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

        tbl_borders_xml = f'''
        <w:tblBorders {nsdecls("w")}>
            <w:top w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            <w:bottom w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            <w:insideV w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            <w:insideH w:val="none"/>
        </w:tblBorders>
        '''
        tbl._tbl.tblPr.append(parse_xml(tbl_borders_xml))

        c_left = tbl.cell(0, 0)
        c_right = tbl.cell(0, 1)

        c_left.width = Inches(1.85)
        c_right.width = Inches(5.12)

        # Left Cell Content: EXACTLY TWO LINES
        p_left = c_left.paragraphs[0]
        p_left.paragraph_format.space_before = Pt(3)
        p_left.paragraph_format.space_after = Pt(3)
        p_left.paragraph_format.line_spacing = 1.15
        
        r_ex = p_left.add_run(f"EX.NO:{ex_no:02d}\nDATE: {date_str}")
        r_ex.font.name = "Times New Roman"
        r_ex.font.size = Pt(11)
        r_ex.font.bold = True

        # Right Cell Content: Centered Bold Title
        p_right = c_right.paragraphs[0]
        p_right.alignment = WD_ALIGN_PARAGRAPH.CENTER
        c_right.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
        p_right.paragraph_format.space_before = Pt(4)
        p_right.paragraph_format.space_after = Pt(4)
        
        r_title = p_right.add_run(title)
        r_title.font.name = "Times New Roman"
        r_title.font.size = Pt(12)
        r_title.font.bold = True

        # -----------------------------------------------------------------
        # 2. AIM SECTION
        # -----------------------------------------------------------------
        p_aim_h = doc.add_paragraph()
        p_aim_h.paragraph_format.keep_with_next = True
        p_aim_h.paragraph_format.space_before = Pt(7)
        p_aim_h.paragraph_format.space_after = Pt(1)
        r_aim_h = p_aim_h.add_run("AIM:")
        r_aim_h.font.name = "Times New Roman"
        r_aim_h.font.size = Pt(11.5)
        r_aim_h.font.bold = True

        p_aim_body = doc.add_paragraph()
        p_aim_body.paragraph_format.keep_with_next = True
        p_aim_body.paragraph_format.left_indent = Inches(0.4)
        p_aim_body.paragraph_format.space_before = Pt(0)
        p_aim_body.paragraph_format.space_after = Pt(6)
        r_aim_body = p_aim_body.add_run(aim_text)
        r_aim_body.font.name = "Times New Roman"
        r_aim_body.font.size = Pt(11)

        # -----------------------------------------------------------------
        # 3. PROGRAM SECTION
        # -----------------------------------------------------------------
        p_prog_h = doc.add_paragraph()
        p_prog_h.paragraph_format.keep_with_next = True
        p_prog_h.paragraph_format.space_before = Pt(4)
        p_prog_h.paragraph_format.space_after = Pt(2)
        r_prog_h = p_prog_h.add_run("PROGRAM:")
        r_prog_h.font.name = "Times New Roman"
        r_prog_h.font.size = Pt(11.5)
        r_prog_h.font.bold = True

        if code_line_count <= 22:
            code_pt = Pt(8.5)
            line_sp = Pt(10.2)
        elif code_line_count <= 50:
            code_pt = Pt(8.0)
            line_sp = Pt(9.4)
        else:
            code_pt = Pt(7.5)
            line_sp = Pt(8.6)

        for line_idx, line in enumerate(code_lines):
            p_code = doc.add_paragraph()
            p_code.paragraph_format.left_indent = Inches(0.2)
            p_code.paragraph_format.space_before = Pt(0)
            p_code.paragraph_format.space_after = Pt(0)
            p_code.paragraph_format.line_spacing = line_sp
            
            if line_idx < 3:
                p_code.paragraph_format.keep_with_next = True
                
            r_code = p_code.add_run(line if line else " ")
            r_code.font.name = 'Consolas'
            r_code.font.size = code_pt
            r_code.font.color.rgb = RGBColor(30, 30, 30)

        # -----------------------------------------------------------------
        # 4. OUTPUT SECTION
        # -----------------------------------------------------------------
        p_out_h = doc.add_paragraph()
        p_out_h.paragraph_format.keep_with_next = True
        p_out_h.paragraph_format.space_before = Pt(8)
        p_out_h.paragraph_format.space_after = Pt(4)
        r_out_h = p_out_h.add_run("OUTPUT :")
        r_out_h.font.name = "Times New Roman"
        r_out_h.font.size = Pt(11.5)
        r_out_h.font.bold = True

        if output_img and os.path.exists(output_img):
            p_img = doc.add_paragraph()
            p_img.paragraph_format.keep_with_next = True
            p_img.paragraph_format.left_indent = Inches(0.2)
            p_img.paragraph_format.space_before = Pt(2)
            p_img.paragraph_format.space_after = Pt(4)

            try:
                with Image.open(output_img) as img:
                    orig_w, orig_h = img.size
                    aspect = orig_w / float(orig_h)

                if code_line_count <= 22:
                    max_h = 2.0
                elif code_line_count <= 45:
                    max_h = 2.8
                else:
                    max_h = 2.4

                max_w = 5.6
                calc_w = max_h * aspect
                if calc_w > max_w:
                    calc_w = max_w
                    calc_h = calc_w / aspect
                else:
                    calc_h = max_h

                p_img.add_run().add_picture(output_img, width=Inches(calc_w), height=Inches(calc_h))
            except Exception as e:
                print(f"Warning inserting image for Ex {ex_no}: {e}")
                r_err = p_img.add_run(f"[Visual Output Image: {os.path.basename(output_img)}]")
                r_err.font.name = "Consolas"
                r_err.font.size = Pt(9)

        if output_text:
            tbl_out = doc.add_table(rows=1, cols=1)
            tbl_out.alignment = WD_TABLE_ALIGNMENT.CENTER
            tbl_out.autofit = False

            tr_out = tbl_out.rows[0]._tr.get_or_add_trPr()
            tr_out.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

            c_box = tbl_out.cell(0, 0)
            c_box.width = Inches(6.8)
            set_cell_background(c_box, "F8F9FA")
            set_cell_margins(c_box, top=80, bottom=80, left=120, right=120)

            box_border_xml = f'''
            <w:tcBorders {nsdecls("w")}>
                <w:top w:val="single" w:sz="6" w:space="0" w:color="CCCCCC"/>
                <w:left w:val="single" w:sz="18" w:space="0" w:color="0078D4"/>
                <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CCCCCC"/>
                <w:right w:val="single" w:sz="6" w:space="0" w:color="CCCCCC"/>
            </w:tcBorders>
            '''
            c_box._tc.get_or_add_tcPr().append(parse_xml(box_border_xml))

            p_box = c_box.paragraphs[0]
            p_box.paragraph_format.space_before = Pt(2)
            p_box.paragraph_format.space_after = Pt(2)
            p_box.paragraph_format.line_spacing = 1.05

            for t_line in output_text.splitlines():
                p_l = c_box.add_paragraph() if p_box.text else p_box
                p_l.paragraph_format.space_before = Pt(0)
                p_l.paragraph_format.space_after = Pt(0)
                p_l.paragraph_format.line_spacing = 1.05
                r_t = p_l.add_run(t_line)
                r_t.font.name = "Consolas"
                r_t.font.size = Pt(8.5)
                r_t.font.color.rgb = RGBColor(20, 20, 20)

        # -----------------------------------------------------------------
        # 5. RESULT SECTION
        # -----------------------------------------------------------------
        p_res_h = doc.add_paragraph()
        p_res_h.paragraph_format.keep_with_next = True
        p_res_h.paragraph_format.space_before = Pt(7)
        p_res_h.paragraph_format.space_after = Pt(2)
        r_res_h = p_res_h.add_run("RESULT :")
        r_res_h.font.name = "Times New Roman"
        r_res_h.font.size = Pt(11.5)
        r_res_h.font.bold = True

        p_res_body = doc.add_paragraph()
        p_res_body.paragraph_format.left_indent = Inches(0.4)
        p_res_body.paragraph_format.space_before = Pt(0)
        p_res_body.paragraph_format.space_after = Pt(4)
        r_res_body = p_res_body.add_run(result_text)
        r_res_body.font.name = "Times New Roman"
        r_res_body.font.size = Pt(11)

        if (idx + 1) % 10 == 0 or (idx + 1) == total_programs:
            print(f"Processed program {idx+1}/{total_programs} -> EX.NO:{ex_no:02d} : {title}")

    out_docx_path = r"c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx"
    doc.save(out_docx_path)
    file_size_mb = os.path.getsize(out_docx_path) / (1024 * 1024)
    print("\n" + "=" * 56)
    print(f"✅ FULLY OPTIMIZED DOCUMENT SAVED SUCCESSFULLY!")
    print(f"Destination: {out_docx_path}")
    print(f"File Size  : {file_size_mb:.2f} MB")
    print("=" * 56)

if __name__ == '__main__':
    generate_optimized_docx(page_offset=3)
