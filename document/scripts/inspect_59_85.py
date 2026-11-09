import os
import re

print("=== CHECKING 59 TO 68 ===")
for num in range(59, 69):
    fp = rf"c:\Users\vijay\Downloads\vj\59 to 68\{num}.js"
    if os.path.exists(fp):
        txt = open(fp, encoding='utf-8', errors='ignore').read()
        print(f"Ex {num}: {txt[:80].replace(chr(10), ' ')}")
    else:
        print(f"Ex {num}: MISSING FILE")

print("\n=== CHECKING 69 TO 75 ===")
for num in range(69, 76):
    fp = rf"c:\Users\vijay\Downloads\vj\69to 73\{num}.js"
    if os.path.exists(fp):
        txt = open(fp, encoding='utf-8', errors='ignore').read()
        print(f"Ex {num}: {txt[:80].replace(chr(10), ' ')}")
    else:
        print(f"Ex {num}: MISSING FILE in 69to 73")

print("\n=== CHECKING 76 TO 85 ===")
for num in range(76, 86):
    # let's see what was used in collect_programs.py
    pass

