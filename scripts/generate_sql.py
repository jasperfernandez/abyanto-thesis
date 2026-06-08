from __future__ import annotations

from pathlib import Path

import openpyxl


ROOT = Path(__file__).resolve().parents[1]
WORKBOOK = Path("/Users/jasper/Downloads/bislig_me_final.xlsx")
SCHEMA = ROOT / "sql" / "schema.sql"
SEED = ROOT / "sql" / "seed.sql"
DATABASE = ROOT / "sql" / "database.sql"

FIRST_NAMES = [
    "Miguel",
    "Andrea",
    "Rafael",
    "Sofia",
    "Gabriel",
    "Isabella",
    "Nathaniel",
    "Camille",
    "Joshua",
    "Mikaela",
    "Adrian",
    "Patricia",
    "Jerome",
    "Clarisse",
    "Francis",
    "Angelica",
    "Vincent",
    "Janelle",
    "Mark",
    "Alyssa",
    "Christian",
    "Bianca",
    "Paolo",
    "Rochelle",
    "John",
    "Katrina",
]

LAST_NAMES = [
    "Santos",
    "Reyes",
    "Cruz",
    "Bautista",
    "Garcia",
    "Mendoza",
    "Ramos",
    "Dela Cruz",
    "Torres",
    "Flores",
    "Gonzales",
    "Villanueva",
    "Aquino",
    "Castillo",
    "Rivera",
    "Domingo",
    "Navarro",
    "Morales",
    "Mercado",
    "Valdez",
    "Aguilar",
    "Salazar",
    "Pascual",
    "Lim",
    "Soriano",
    "Abad",
]

CITIES = [
    ("Bislig City", "Surigao del Sur", "8311"),
    ("Tandag City", "Surigao del Sur", "8300"),
    ("Hinatuan", "Surigao del Sur", "8310"),
    ("Barobo", "Surigao del Sur", "8309"),
    ("Tagbina", "Surigao del Sur", "8308"),
    ("Lianga", "Surigao del Sur", "8307"),
]

SECONDARY_SCHOOLS = [
    ("Bislig City National High School", "Public"),
    ("Andres Soriano Colleges of Bislig High School", "Private"),
    ("Tabon M. Estrella National High School", "Public"),
    ("De La Salle John Bosco College High School", "Private"),
    ("Mangagoy National High School", "Public"),
    ("Colegio de San Nicolas de Tolentino", "Private"),
]

EDUCATIONAL_ATTAINMENTS = [
    "No Education",
    "Elementary Level",
    "Elementary Graduate",
    "School Level",
    "High School Graduate",
    "College Undergraduate",
    "College Graduate",
    "Post-Graduate",
]

MONTHLY_FAMILY_INCOMES = [
    "Below 10,000",
    "10,000 to 15,000",
    "15,001 to 20,000",
    "20,001 to 30,000",
    "30,001 to 40,000",
    "40,001 to 50,000",
    "50,001 to 100,000 and above",
]


def sql(value):
    if value is None:
        return "NULL"
    if isinstance(value, (int, float)):
        return str(value)
    return "'" + str(value).replace("\\", "\\\\").replace("'", "''") + "'"


def is_major(code):
    return str(code).startswith("ME ") or str(code).startswith("AME ")


def include_course(code):
    code_str = str(code).strip()
    if code_str.startswith("BES "):
        return False
    if code_str.startswith("Math "):
        return False
    if code_str.startswith("Nat Sci ") or code_str.startswith("Nat. Sci "):
        return False
    return True


def grade_value(value):
    if value is None or value == "":
        return None
    return round(float(value), 2)


workbook = openpyxl.load_workbook(WORKBOOK, data_only=True)
sheet = workbook.active
rows = list(sheet.iter_rows(values_only=True))
headers = [str(header).strip() for header in rows[0]]
course_codes = [code for code in headers[4:] if include_course(code)]

lines = [
    "USE licensure_predictor;",
    "",
    "SET FOREIGN_KEY_CHECKS = 0;",
    "DELETE FROM student_grades;",
    "DELETE FROM courses;",
    "DELETE FROM students;",
    "ALTER TABLE student_grades AUTO_INCREMENT = 1;",
    "ALTER TABLE courses AUTO_INCREMENT = 1;",
    "ALTER TABLE students AUTO_INCREMENT = 1;",
    "SET FOREIGN_KEY_CHECKS = 1;",
    "",
]

lines.append("INSERT INTO courses (id, code, name, is_major, sort_order) VALUES")
course_values = []
for index, code in enumerate(course_codes, start=1):
    course_values.append(
        f"({index}, {sql(code)}, {sql(code)}, {1 if is_major(code) else 0}, {index})"
    )
lines.append(",\n".join(course_values) + ";")
lines.append("")

lines.append(
    "INSERT INTO students (id, student_id, full_name, gwa, licensure_result, major_average, program, city_municipality, province, postal_code, age, sex, secondary_school_name, secondary_school_type, father_educational_attainment, mother_educational_attainment, monthly_family_income) VALUES"
)
student_values = []
grade_values = []

for student_index, row in enumerate(rows[1:], start=1):
    original_student_id, gwa, _result, program = row[:4]
    grades = [grade_value(value) for value in row[4:]]
    major_grades = [
        grade for code, grade in zip(course_codes, grades) if is_major(code) and grade is not None
    ]
    major_average = round(sum(major_grades) / len(major_grades), 2) if major_grades else None
    predicted_result = "FAIL" if major_average is not None and major_average >= 2.49 else "PASS"
    full_name = f"{FIRST_NAMES[(student_index - 1) % len(FIRST_NAMES)]} {LAST_NAMES[(student_index - 1) % len(LAST_NAMES)]}"
    city, province, postal_code = CITIES[(student_index - 1) % len(CITIES)]
    secondary_school_name, secondary_school_type = SECONDARY_SCHOOLS[
        (student_index - 1) % len(SECONDARY_SCHOOLS)
    ]

    student_values.append(
        "("
        f"{student_index}, "
        f"{sql(original_student_id)}, "
        f"{sql(full_name)}, "
        f"{sql(round(float(gwa), 2))}, "
        f"{sql(predicted_result)}, "
        f"{sql(major_average)}, "
        f"{sql(program)}, "
        f"{sql(city)}, "
        f"{sql(province)}, "
        f"{sql(postal_code)}, "
        f"{sql(21 + ((student_index - 1) % 5))}, "
        f"{sql('Male' if student_index % 2 else 'Female')}, "
        f"{sql(secondary_school_name)}, "
        f"{sql(secondary_school_type)}, "
        f"{sql(EDUCATIONAL_ATTAINMENTS[(student_index + 2) % len(EDUCATIONAL_ATTAINMENTS)])}, "
        f"{sql(EDUCATIONAL_ATTAINMENTS[(student_index + 4) % len(EDUCATIONAL_ATTAINMENTS)])}, "
        f"{sql(MONTHLY_FAMILY_INCOMES[(student_index - 1) % len(MONTHLY_FAMILY_INCOMES)])}"
        ")"
    )

    for course_index, grade in enumerate(grades, start=1):
        grade_values.append(f"({student_index}, {course_index}, {sql(grade)})")

lines.append(",\n".join(student_values) + ";")
lines.append("")

chunk_size = 500
for start in range(0, len(grade_values), chunk_size):
    chunk = grade_values[start : start + chunk_size]
    lines.append("INSERT INTO student_grades (student_id, course_id, grade) VALUES")
    lines.append(",\n".join(chunk) + ";")
    lines.append("")

seed_sql = "\n".join(lines).rstrip() + "\n"
SEED.write_text(seed_sql)
DATABASE.write_text(SCHEMA.read_text() + "\n" + seed_sql)

print(f"Wrote {SEED}")
print(f"Wrote {DATABASE}")
