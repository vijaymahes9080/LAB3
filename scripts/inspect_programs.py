import re
import json

with open('index.html', 'r', encoding='utf-8') as f:
    text = f.read()

# Extract program definitions
match = re.search(r'\$scope\.programs\s*=\s*\[(.*?)\];', text, re.DOTALL)
if match:
    block = match.group(1)
    # find individual program objects
    prog_blocks = re.findall(r'\{[^{}]*id:\s*(\d+)[^{}]*\}', block, re.DOTALL)
    print(f"Found {len(prog_blocks)} program matches.")

# Let's also parse directly using regex for id, title, category, description, tags
pattern = re.compile(
    r'id:\s*(?P<id>\d+).*?'
    r'title:\s*[\'"](?P<title>.*?)[\'"].*?'
    r'category:\s*[\'"](?P<category>.*?)[\'"].*?'
    r'description:\s*[\'"](?P<desc>.*?)[\'"].*?'
    r'tags:\s*\[(?P<tags>.*?)\]',
    re.DOTALL
)

programs = []
for m in pattern.finditer(text):
    tags = [t.strip().strip("'\"") for t in m.group('tags').split(',') if t.strip()]
    prog = {
        'id': int(m.group('id')),
        'title': m.group('title'),
        'category': m.group('category'),
        'description': m.group('desc'),
        'tags': tags
    }
    programs.append(prog)

print(f"Successfully extracted {len(programs)} programs (IDs: {[p['id'] for p in programs]})")
with open('scripts/programs_metadata.json', 'w', encoding='utf-8') as f:
    json.dump(programs, f, indent=2)
print("Saved to scripts/programs_metadata.json")
