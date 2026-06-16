import os

file_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/php_views/Views/student/survey.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace container
content = content.replace('<div class="d-flex flex-column gap-3">', '<div class="row g-3">')

# Replace label classes
content = content.replace(
    '<label class="survey-option rounded-4 p-4 text-start d-flex align-items-center gap-3">',
    '<label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">'
)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated CSS classes for grid layout.")
