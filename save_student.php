<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

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

$pdo->beginTransaction();

try {
    $updateStudent = $pdo->prepare(
        'UPDATE students
         SET student_id = :student_id,
             full_name = :full_name,
             gwa = :gwa,
             program = :program,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :id'
    );
    $updateStudent->execute([
        'student_id' => trim((string) ($_POST['student_id'] ?? '')),
        'full_name' => trim((string) ($_POST['full_name'] ?? '')),
        'gwa' => (float) ($_POST['gwa'] ?? 0),
        'program' => trim((string) ($_POST['program'] ?? '')),
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
