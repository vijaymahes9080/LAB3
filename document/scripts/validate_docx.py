import os
import docx
from docx import Document

def validate():
    docx_path = r"c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx"
    assert os.path.exists(docx_path), "File does not exist!"
    size_mb = os.path.getsize(docx_path) / (1024 * 1024)
    print(f"File size: {size_mb:.2f} MB")

    doc = Document(docx_path)
    print(f"Total tables: {len(doc.tables)}")
    print(f"Total paragraphs: {len(doc.paragraphs)}")

    # Check for all 85 EX.NO
    found_ex = set()
    for tbl in doc.tables:
        cell_text = tbl.cell(0, 0).text
        for line in cell_text.splitlines():
            if line.startswith("EX.NO:"):
                ex_str = line.replace("EX.NO:", "").strip()
                try:
                    found_ex.add(int(ex_str))
                except ValueError:
                    pass

    print(f"Found {len(found_ex)} distinct EX.NO headers: from {min(found_ex)} to {max(found_ex)}")
    assert len(found_ex) == 85, f"Expected 85 experiments, but found {len(found_ex)}"
    missing = set(range(1, 86)) - found_ex
    if missing:
        print(f"Missing experiments: {missing}")
    else:
        print("✔ ALL 85 EXPERIMENTS ARE FULLY PRESENT IN SEQUENTIAL ORDER (1 to 85)!")

    # Check headings presence
    headings_checked = {"AIM:", "PROGRAM:", "OUTPUT :", "RESULT :"}
    found_headings = {h: 0 for h in headings_checked}
    for p in doc.paragraphs:
        txt = p.text.strip()
        if txt in found_headings:
            found_headings[txt] += 1

    for h, count in found_headings.items():
        print(f"Section '{h}': {count} occurrences (Expected: 85)")
        assert count == 85, f"Expected 85 occurrences of '{h}', got {count}"

    print("\n✅ VALIDATION PASSED: 100% of all 85 experiments conform to the reference image template!")

if __name__ == '__main__':
    validate()

