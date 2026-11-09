import os
import json
import shutil
import docx
from docx import Document
from docx.shared import Pt
from docx.enum.text import WD_ALIGN_PARAGRAPH

index_path = r'c:\Users\vijay\Downloads\vj\document\index.docx'
backup_path = r'c:\Users\vijay\Downloads\vj\document\index_backup.docx'
pages_json = r'c:\Users\vijay\Downloads\vj\document\scripts\user_prog_pages.json'

# Backup
shutil.copy2(index_path, backup_path)
print("Backup created at:", backup_path)

# Load exact start pages
with open(pages_json, 'r') as f:
    prog_pages = json.load(f)

doc = Document(index_path)
table = doc.tables[0]

print(f"Total rows in index.docx: {len(table.rows)}")

updated_count = 0
for row_idx in range(1, len(table.rows)):
    row = table.rows[row_idx]
    sno_txt = row.cells[0].text.strip()
    try:
        ex_no = int(sno_txt)
    except ValueError:
        continue
    
    if str(ex_no) in prog_pages:
        actual_page = prog_pages[str(ex_no)]
        cell_pno = row.cells[3]
        
        # Update paragraph in cell 3
        p = cell_pno.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.text = ""
        r = p.add_run(str(actual_page))
        r.font.name = "Times New Roman"
        r.font.size = Pt(9.5)
        updated_count += 1

doc.save(index_path)
print(f"Successfully updated {updated_count} rows in {index_path} with matching start page numbers!")

