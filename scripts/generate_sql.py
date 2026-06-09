from __future__ import annotations

import json
import random
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
SCHEMA = ROOT / "sql" / "schema.sql"
SEED = ROOT / "sql" / "seed.sql"
DATABASE = ROOT / "sql" / "database.sql"

PASSWORD_HASH = "$2y$10$3eWeLSCbPGAhvkxAwWsecOFcABYdHdTZWZATNrV2vF6v2Uti8eF8m"

COLLEGE_CTE = "College of Teacher Education"
COLLEGE_CET = "College of Engineering"

CAMPUS_PROGRAMS = {
    "NEMSU Cantilan": {
        COLLEGE_CTE: [
            "Bachelor of Science in Secondary Education major in English",
            "Bachelor of Science in Secondary Education major in Filipino",
            "Bachelor of Science in Secondary Education major in Mathematics",
            "Bachelor of Science in Secondary Education major in Science",
            "Bachelor of Technical and Livelihood Education major in Home Economics",
            "Bachelor of Technical Vocational Education major in Electrical Technology",
            "Bachelor of Technical Vocational Education major in Food and Service Management",
            "Bachelor of Technical Vocational Education major in Garments and Fashion Design",
        ],
    },
    "NEMSU Tandag": {
        COLLEGE_CTE: [
            "Bachelor of Early Childhood Education",
            "Bachelor of Physical Education",
            "Bachelor of Elementary Education",
            "Bachelor of Science in Secondary Education major in English",
            "Bachelor of Science in Secondary Education major in Filipino",
            "Bachelor of Science in Secondary Education major in Mathematics",
            "Bachelor of Science in Secondary Education major in Science",
        ],
        COLLEGE_CET: [
            "Bachelor of Science in Civil Engineering",
        ],
    },
    "NEMSU San Miguel": {
        COLLEGE_CTE: [
            "Bachelor of Technical and Livelihood Education major in Home Economics",
        ],
    },
    "NEMSU Lianga": {
        COLLEGE_CTE: [
            "Bachelor of Science in Elementary Education",
            "Bachelor of Science in Secondary Education major in English",
        ],
    },
    "NEMSU Tagbina": {
        COLLEGE_CTE: [
            "Bachelor of Science in Secondary Education major in English",
        ],
    },
    "NEMSU Bislig": {
        COLLEGE_CTE: [
            "Bachelor of Science in Secondary Education major in English",
            "Bachelor of Technical Vocational Education major in Electrical Technology",
            "Bachelor of Technical Vocational Education major in Automotive Technology",
        ],
        COLLEGE_CET: [
            "Bachelor of Science in Civil Engineering",
            "Bachelor of Science in Electrical Engineering",
            "Bachelor of Science in Mechanical Engineering",
        ],
    },
}

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

CAMPUS_LOCATIONS = {
    "NEMSU Cantilan": ("Cantilan", "Surigao del Sur", "8317"),
    "NEMSU Tandag": ("Tandag City", "Surigao del Sur", "8300"),
    "NEMSU San Miguel": ("San Miguel", "Surigao del Sur", "8301"),
    "NEMSU Lianga": ("Lianga", "Surigao del Sur", "8307"),
    "NEMSU Tagbina": ("Tagbina", "Surigao del Sur", "8308"),
    "NEMSU Bislig": ("Bislig City", "Surigao del Sur", "8311"),
}

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


def slug(value: str) -> str:
    return (
        value.lower()
        .replace("nemsu ", "")
        .replace(" and ", " ")
        .replace("/", " ")
        .replace("-", " ")
        .replace(",", "")
        .replace(".", "")
        .replace(" ", "_")
    )


def program_prefixes(program: str) -> list[str]:
    lowered = program.lower()

    if "mechanical" in lowered:
        return ["ME", "AME"]
    if "civil" in lowered:
        return ["CE", "CEM"]
    if "electrical engineering" in lowered:
        return ["EE", "AEE"]
    if "automotive" in lowered:
        return ["AT", "AAT"]
    if "electrical technology" in lowered:
        return ["ET", "AET"]
    if "food and service" in lowered:
        return ["FSM", "AFSM"]
    if "garments" in lowered:
        return ["GFD", "AGFD"]
    if "home economics" in lowered:
        return ["HE", "AHE"]
    if "filipino" in lowered:
        return ["FIL", "AFIL"]
    if "english" in lowered and "elementary" not in lowered:
        return ["ENG", "AENG"]
    if "mathematics" in lowered and "elementary" not in lowered:
        return ["MATH", "AMATH"]
    if "science" in lowered and "elementary" not in lowered:
        return ["SCI", "ASCI"]
    if "physical education" in lowered:
        return ["PE", "APE"]
    if "early childhood" in lowered:
        return ["ECE", "AECE"]
    if "elementary" in lowered:
        return ["ELEM", "AELEM"]

    return ["MAJ", "AMAJ"]


def program_courses(program: str) -> list[tuple[str, str, int]]:
    prefixes = program_prefixes(program)
    major_codes = [
        f"{prefixes[0]} {level}{number}"
        for level in range(1, 5)
        for number in (1, 2)
    ]
    advanced_codes = [f"{prefixes[1]} {number}" for number in range(1, 5)]
    minor_codes = ["GEN ED 101", "GEN ED 102", "PROF ED 201", "RESEARCH 401"]
    courses = []

    for code in major_codes + advanced_codes:
        courses.append((code, code, 1))

    for code in minor_codes:
        courses.append((code, code, 0))

    return courses


def college_dean_users() -> list[tuple[str, str, str, str | None, str | None, str | None, str | None]]:
    cte_campuses = [
        campus
        for campus, colleges in CAMPUS_PROGRAMS.items()
        if COLLEGE_CTE in colleges
    ]
    cet_campuses = [
        campus
        for campus, colleges in CAMPUS_PROGRAMS.items()
        if COLLEGE_CET in colleges
    ]

    return [
        (
            "college_dean_cte@abyanto.freedev.app",
            PASSWORD_HASH,
            "college dean",
            None,
            COLLEGE_CTE,
            None,
            json.dumps(cte_campuses),
        ),
        (
            "college_dean_cet@abyanto.freedev.app",
            PASSWORD_HASH,
            "college dean",
            None,
            COLLEGE_CET,
            None,
            json.dumps(cet_campuses),
        ),
    ]


def users() -> list[tuple[str, str, str, str | None, str | None, str | None, str | None]]:
    rows = [
        (
            "administrator@abyanto.freedev.app",
            PASSWORD_HASH,
            "administrator",
            None,
            None,
            None,
            None,
        ),
        (
            "program_coor@abyanto.freedev.app",
            PASSWORD_HASH,
            "program coor",
            "Bachelor of Science in Mechanical Engineering",
            None,
            "NEMSU Bislig",
            None,
        ),
    ]

    rows.extend(college_dean_users())

    for campus in CAMPUS_PROGRAMS:
        rows.append(
            (
                f"administrator_{slug(campus)}@abyanto.freedev.app",
                PASSWORD_HASH,
                "administrator",
                None,
                None,
                campus,
                None,
            )
        )

    for campus, colleges in CAMPUS_PROGRAMS.items():
        for college in colleges:
            suffix = "cte" if college == COLLEGE_CTE else "cet"
            rows.append(
                (
                    f"department_chair_{slug(campus)}_{suffix}@abyanto.freedev.app",
                    PASSWORD_HASH,
                    "department chair",
                    None,
                    college,
                    campus,
                    None,
                )
            )

    return rows


def all_programs() -> list[tuple[str, str, str]]:
    programs = []

    for campus, colleges in CAMPUS_PROGRAMS.items():
        for college, college_programs in colleges.items():
            for program in college_programs:
                programs.append((campus, college, program))

    return programs


def build_seed() -> str:
    rng = random.Random(20260609)
    lines = [
        "USE licensure_predictor;",
        "",
        "SET FOREIGN_KEY_CHECKS = 0;",
        "DELETE FROM student_grades;",
        "DELETE FROM courses;",
        "DELETE FROM students;",
        "DELETE FROM users;",
        "ALTER TABLE student_grades AUTO_INCREMENT = 1;",
        "ALTER TABLE courses AUTO_INCREMENT = 1;",
        "ALTER TABLE students AUTO_INCREMENT = 1;",
        "ALTER TABLE users AUTO_INCREMENT = 1;",
        "SET FOREIGN_KEY_CHECKS = 1;",
        "",
    ]

    user_values = [
        "("
        f"{sql(email)}, {sql(password)}, {sql(account_type)}, {sql(program)}, "
        f"{sql(college)}, {sql(campus)}, {sql(campuses)}"
        ")"
        for email, password, account_type, program, college, campus, campuses in users()
    ]
    lines.append("INSERT INTO users (email, password, account_type, program, college, campus, campuses) VALUES")
    lines.append(",\n".join(user_values) + ";")
    lines.append("")

    course_values = []
    course_ids_by_program = {}
    course_id = 1

    for program in sorted({program for _, _, program in all_programs()}):
        for sort_index, (code, name, is_major) in enumerate(program_courses(program), start=1):
            course_ids_by_program.setdefault(program, []).append((course_id, is_major))
            course_values.append(
                "("
                f"{course_id}, {sql(code)}, {sql(name)}, {is_major}, "
                f"{sql(program)}, {sort_index}"
                ")"
            )
            course_id += 1

    lines.append("INSERT INTO courses (id, code, name, is_major, program, sort_order) VALUES")
    lines.append(",\n".join(course_values) + ";")
    lines.append("")

    student_values = []
    grade_values = []
    student_id = 1

    for program_index, (campus, college, program) in enumerate(all_programs(), start=1):
        campus_slug = slug(campus).upper()
        program_slug = "".join(part[0] for part in program.split() if part[0].isalnum())[:6].upper()
        program_code = f"{program_index:02d}{program_slug}"
        city, province, postal_code = CAMPUS_LOCATIONS[campus]

        for offset in range(12):
            name_index = student_id - 1
            full_name = f"{FIRST_NAMES[name_index % len(FIRST_NAMES)]} {LAST_NAMES[(name_index + program_index) % len(LAST_NAMES)]}"
            school_name, school_type = SECONDARY_SCHOOLS[(name_index + program_index) % len(SECONDARY_SCHOOLS)]
            student_number = f"{campus_slug}-{program_code}-{offset + 1:03d}"
            major_grades = []
            all_grades = []

            for course_id, is_major in course_ids_by_program[program]:
                grade = round(rng.uniform(1.00, 3.00), 2)
                all_grades.append((course_id, grade))

                if is_major:
                    major_grades.append(grade)

            major_average = round(sum(major_grades) / len(major_grades), 2)
            predicted_result = "FAIL" if major_average >= 2.49 else "PASS"
            gwa = round(75 + ((3.0 - major_average) * 8) + rng.uniform(-2.0, 2.0), 2)

            student_values.append(
                "("
                f"{student_id}, "
                f"{sql(student_number)}, "
                f"{sql(full_name)}, "
                f"{sql(gwa)}, "
                f"{sql(predicted_result)}, "
                f"{sql(major_average)}, "
                f"{sql(campus)}, "
                f"{sql(program)}, "
                f"{sql(college)}, "
                f"{sql(city)}, "
                f"{sql(province)}, "
                f"{sql(postal_code)}, "
                f"{sql(21 + (name_index % 5))}, "
                f"{sql('Male' if student_id % 2 else 'Female')}, "
                f"{sql(school_name)}, "
                f"{sql(school_type)}, "
                f"{sql(EDUCATIONAL_ATTAINMENTS[(name_index + 2) % len(EDUCATIONAL_ATTAINMENTS)])}, "
                f"{sql(EDUCATIONAL_ATTAINMENTS[(name_index + 4) % len(EDUCATIONAL_ATTAINMENTS)])}, "
                f"{sql(MONTHLY_FAMILY_INCOMES[name_index % len(MONTHLY_FAMILY_INCOMES)])}"
                ")"
            )

            for course_id, grade in all_grades:
                grade_values.append(f"({student_id}, {course_id}, {sql(grade)})")

            student_id += 1

    lines.append(
        "INSERT INTO students (id, student_id, full_name, gwa, licensure_result, major_average, campus, program, college, city_municipality, province, postal_code, age, sex, secondary_school_name, secondary_school_type, father_educational_attainment, mother_educational_attainment, monthly_family_income) VALUES"
    )
    lines.append(",\n".join(student_values) + ";")
    lines.append("")

    chunk_size = 500
    for start in range(0, len(grade_values), chunk_size):
        chunk = grade_values[start : start + chunk_size]
        lines.append("INSERT INTO student_grades (student_id, course_id, grade) VALUES")
        lines.append(",\n".join(chunk) + ";")
        lines.append("")

    return "\n".join(lines).rstrip() + "\n"


def main() -> None:
    seed_sql = build_seed()
    SEED.write_text(seed_sql)
    DATABASE.write_text(SCHEMA.read_text() + "\n" + seed_sql)

    print(f"Wrote {SEED}")
    print(f"Wrote {DATABASE}")


if __name__ == "__main__":
    main()
