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
         INNER JOIN students s ON s.id = sg.student_id
         WHERE sg.student_id = :student_id
           AND c.is_major = 1
           AND c.program = s.program
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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAuth(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function getLoggedInUser(?PDO $pdo = null): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    if ($pdo !== null) {
        $statement = $pdo->prepare('SELECT id, email, account_type, program FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => (int) $_SESSION['user_id']]);
        $user = $statement->fetch();

        if (!$user) {
            unset($_SESSION['user_id'], $_SESSION['user']);
            return null;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'account_type' => $user['account_type'],
            'program' => $user['program'] ?? null,
        ];
    }

    return $_SESSION['user'] ?? null;
}

function getProgramMajorPrefixes(string $program): array
{
    $program = strtolower($program);
    if (str_contains($program, 'mechanical')) {
        return ['ME', 'AME'];
    }
    if (str_contains($program, 'civil')) {
        return ['CE', 'Chem', 'ES', 'Geol', 'Math', 'Physics'];
    }
    if (str_contains($program, 'electrical')) {
        return ['AEE', 'BES', 'ECE', 'EE', 'MATH', 'Math', 'NAT SCI'];
    }
    if (str_contains($program, 'filipino')) {
        return ['FIL', 'AFIL'];
    }
    if (str_contains($program, 'english') && !str_contains($program, 'elementary')) {
        return ['ENG', 'AENG'];
    }
    if (str_contains($program, 'mathematics') && !str_contains($program, 'elementary')) {
        return ['MATH', 'AMATH'];
    }
    if (str_contains($program, 'science') && !str_contains($program, 'elementary')) {
        return ['SCI', 'ASCI'];
    }
    if (str_contains($program, 'physical education')) {
        return ['PE', 'APE'];
    }
    if (str_contains($program, 'early childhood')) {
        return ['ECE', 'AECE'];
    }
    if (str_contains($program, 'elementary')) {
        return ['ELEM', 'AELEM'];
    }
    return [];
}
