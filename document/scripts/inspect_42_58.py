import os
import re

for num in range(42, 59):
    html_p = rf"c:\Users\vijay\Downloads\vj\{num}.html"
    scr_p = rf"c:\Users\vijay\Downloads\vj\document\assets\{num}.png"
    
    html_title = ""
    h1_text = ""
    if os.path.exists(html_p):
        txt = open(html_p, encoding='utf-8', errors='ignore').read()
        m_t = re.search(r'<title>(.*?)</title>', txt, re.IGNORECASE | re.DOTALL)
        if m_t:
            html_title = m_t.group(1).strip()
        m_h = re.search(r'<h[12][^>]*>(.*?)</h[12]>', txt, re.IGNORECASE | re.DOTALL)
        if m_h:
            h1_text = re.sub(r'<[^>]+>', '', m_h.group(1)).strip()
            
    scr_exists = os.path.exists(scr_p)
    print(f"Ex {num:02d} | HTML Title: {html_title:<30} | H1/H2: {h1_text:<30} | Screenshot: {scr_exists}")

