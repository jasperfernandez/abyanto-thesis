<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

// Check auth
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$student = fetchStudent($pdo, $id);

if ($student === null) {
    http_response_code(404);
    exit('Student not found.');
}

$action = $_POST['action'] ?? 'save';
$grades = $_POST['grades'] ?? [];

$user = getLoggedInUser($pdo);
$isAdministrator = isAdministrator($user);
$isGlobalAdministrator = isGlobalAdministrator($user);

if (!userCanAccessStudent($user, $student)) {
    header('Location: index.php');
    exit;
}

if (!$isAdministrator && $action === 'save') {
    header('Location: student.php?id=' . $id . '&message=' . urlencode('Only administrators can edit student records.'));
    exit;
}

$pdo->beginTransaction();

try {
    if ($isAdministrator) {
        $age = trim((string) ($_POST['age'] ?? ''));
        $sex = trim((string) ($_POST['sex'] ?? ''));
        $secondarySchoolType = trim((string) ($_POST['secondary_school_type'] ?? ''));

        $program = trim((string) ($_POST['program'] ?? ''));
        $campus = $isGlobalAdministrator
            ? trim((string) ($_POST['campus'] ?? ''))
            : (string) $student['campus'];
        $campus = $campus === '' ? (string) $student['campus'] : $campus;
        $college = trim((string) ($_POST['college'] ?? ''));
        $college = $college === '' ? collegeForProgram($program) : $college;

        $updateStudent = $pdo->prepare(
            'UPDATE students
             SET student_id = :student_id,
                 full_name = :full_name,
                 gwa = :gwa,
                 campus = :campus,
                 program = :program,
                 college = :college,
                 city_municipality = :city_municipality,
                 province = :province,
                 postal_code = :postal_code,
                 age = :age,
                 sex = :sex,
                 secondary_school_name = :secondary_school_name,
                 secondary_school_type = :secondary_school_type,
                 father_educational_attainment = :father_educational_attainment,
                 mother_educational_attainment = :mother_educational_attainment,
                 monthly_family_income = :monthly_family_income,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $updateStudent->execute([
            'student_id' => trim((string) ($_POST['student_id'] ?? '')),
            'full_name' => trim((string) ($_POST['full_name'] ?? '')),
            'gwa' => (float) ($_POST['gwa'] ?? 0),
            'campus' => $campus,
            'program' => $program,
            'college' => $college,
            'city_municipality' => trim((string) ($_POST['city_municipality'] ?? '')),
            'province' => trim((string) ($_POST['province'] ?? '')),
            'postal_code' => trim((string) ($_POST['postal_code'] ?? '')),
            'age' => $age === '' ? null : (int) $age,
            'sex' => $sex === '' ? null : $sex,
            'secondary_school_name' => trim((string) ($_POST['secondary_school_name'] ?? '')),
            'secondary_school_type' => $secondarySchoolType === '' ? null : $secondarySchoolType,
            'father_educational_attainment' => trim((string) ($_POST['father_educational_attainment'] ?? '')),
            'mother_educational_attainment' => trim((string) ($_POST['mother_educational_attainment'] ?? '')),
            'monthly_family_income' => trim((string) ($_POST['monthly_family_income'] ?? '')),
            'id' => $id,
        ]);

        $upsertGrade = $pdo->prepare(
            'INSERT INTO student_grades (student_id, course_id, grade)
             VALUES (:student_id, :course_id, :grade)
             ON DUPLICATE KEY UPDATE grade = VALUES(grade)'
        );

        foreach ($grades as $courseId => $grade) {
            $normalizedGrade = trim((string) $grade);
            $upsertGrade->execute([
                'student_id' => $id,
                'course_id' => (int) $courseId,
                'grade' => $normalizedGrade === '' ? null : (float) $normalizedGrade,
            ]);
        }
    }

    $message = 'Student information saved.';
    $predictionResult = null;

    if ($action === 'predict') {
        $prediction = calculatePrediction($pdo, $id);
        $predictionResult = $prediction['result'];
        $message = 'Prediction complete.';
    } else {
        recalculateMajorAverage($pdo, $id);
    }

    $pdo->commit();
} catch (Throwable $exception) {
    $pdo->rollBack();
    http_response_code(500);
    exit('Unable to save student record.');
}

$location = 'student.php?id=' . $id . '&message=' . urlencode($message);

if ($predictionResult !== null) {
    $location .= '&prediction=' . urlencode($predictionResult);
}

header('Location: ' . $location);
exit;
