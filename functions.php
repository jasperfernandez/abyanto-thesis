<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function selectedOption(?string $currentValue, string $optionValue): string
{
    return $currentValue === $optionValue ? ' selected' : '';
}

function recalculateMajorAverage(PDO $pdo, int $studentId): array
{
    $statement = $pdo->prepare(
        'SELECT AVG(sg.grade) AS major_average, COUNT(*) AS major_count
         FROM student_grades sg
         INNER JOIN courses c ON c.id = sg.course_id
         WHERE sg.student_id = :student_id
           AND c.is_major = 1
           AND sg.grade IS NOT NULL'
    );
    $statement->execute(['student_id' => $studentId]);
    $summary = $statement->fetch();

    $majorAverage = $summary['major_average'] !== null ? round((float) $summary['major_average'], 2) : null;
    $majorCount = (int) ($summary['major_count'] ?? 0);

    $update = $pdo->prepare(
        'UPDATE students
         SET major_average = :major_average,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :student_id'
    );
    $update->execute([
        'major_average' => $majorAverage,
        'student_id' => $studentId,
    ]);

    return [
        'major_average' => $majorAverage,
        'major_count' => $majorCount,
    ];
}

function calculatePrediction(PDO $pdo, int $studentId): array
{
    $summary = recalculateMajorAverage($pdo, $studentId);
    $majorAverage = $summary['major_average'];
    $result = $majorAverage !== null && $majorAverage >= 2.49 ? 'FAIL' : 'PASS';

    $update = $pdo->prepare(
        'UPDATE students
         SET licensure_result = :result,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :student_id'
    );
    $update->execute([
        'result' => $result,
        'student_id' => $studentId,
    ]);

    return [
        'result' => $result,
        'major_average' => $majorAverage,
        'major_count' => $summary['major_count'],
    ];
}

function fetchStudent(PDO $pdo, int $studentId): ?array
{
    $statement = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $statement->execute(['id' => $studentId]);

    $student = $statement->fetch();

    return $student ?: null;
}
