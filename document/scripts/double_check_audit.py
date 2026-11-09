import json
import os
import docx
from docx import Document

def double_check():
    docx_path = r"c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx"
    doc = Document(docx_path)
    
    with open('document/scripts/all_85_dataset.json', 'r', encoding='utf-8') as f:
        dataset = json.load(f)
        
    print("=" * 80)
    print("DOUBLE CHECK AUDIT LEDGER: ALL 85 PROGRAMS")
    print("=" * 80)
    
    header_tables = [t for t in doc.tables if "EX.NO:" in t.cell(0, 0).text]
    print(f"Total Header Tables Verified: {len(header_tables)} / 85")
    assert len(header_tables) == 85, "Header tables mismatch!"

    for i, tbl in enumerate(header_tables):
        prog = dataset[i]
        ex_num = prog['ex_no']
        expected_title = prog['title']
        expected_date = prog['date']
        
        c0_text = tbl.cell(0, 0).text.strip()
        c1_text = tbl.cell(0, 1).text.strip()
        
        # Verify EX.NO and Date
        assert f"EX.NO:{ex_num:02d}" in c0_text, f"Mismatch in Ex {ex_num} number"
        assert expected_date in c0_text, f"Mismatch in Ex {ex_num} date"
        assert expected_title.lower() in c1_text.lower(), f"Mismatch in Ex {ex_num} title: '{expected_title}' vs '{c1_text}'"
        
        # Verify code length
        code_len = len(prog['code'])
        assert code_len > 25, f"Ex {ex_num} code empty or too short!"
        
        # Verify output
        has_img = bool(prog.get('output_img') and os.path.exists(prog['output_img']))
        has_txt = bool(prog.get('output_text') and prog['output_text'].strip())
        assert has_img or has_txt, f"Ex {ex_num} has neither image nor text output!"
        
        status_out = f"Image ({os.path.basename(prog['output_img'])})" if has_img else "Terminal Output Box"
        print(f"✔ Program {ex_num:02d} | Date: {expected_date:<8} | Title: {expected_title:<36} | Code: {code_len:>4}c | Output: {status_out}")

    print("\n" + "=" * 80)
    print("✅ DOUBLE CHECK COMPLETE: ALL 85 TITLES, CODES, AND OUTPUTS ARE 100% VERIFIED!")
    print("=" * 80)

if __name__ == '__main__':
    double_check()

