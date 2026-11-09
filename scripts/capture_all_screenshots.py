import os
import subprocess
import time

os.makedirs("pdf_assets/screenshots", exist_ok=True)
abs_dir = os.path.abspath("pdf_assets/screenshots")
chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"

print("Starting screenshot capture for Programs 42 to 58...")

for num in range(42, 59):
    html_file = os.path.abspath(f"{num}.html")
    png_path = os.path.join(abs_dir, f"{num}.png")
    
    cmd = [
        chrome_path,
        "--headless=new",
        "--disable-gpu",
        "--no-sandbox",
        "--window-size=1240,780",
        "--hide-scrollbars",
        f"--screenshot={png_path}",
        f"file:///{html_file.replace(os.sep, '/')}"
    ]
    
    subprocess.run(cmd, capture_output=True)
    if os.path.exists(png_path):
        size_kb = os.path.getsize(png_path) / 1024
        print(f"[{num}/58] Captured: {png_path} ({size_kb:.1f} KB)")
    else:
        print(f"[{num}/58] FAILED to capture: {png_path}")

print("All screenshots capture complete!")
