<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

// Check auth
requireAuth();

$program = $_GET['program'] ?? '';
$campus = $_GET['campus'] ?? '';

if ($program === '') {
    header('Location: index.php');
    exit;
}

$user = getLoggedInUser($pdo);
$canManageUsers = isGlobalAdministrator($user);

$programStatement = $pdo->prepare(
    'SELECT campus, program, college
     FROM students
     WHERE program = :program
       AND (:campus_filter = "" OR campus = :campus_match)
     ORDER BY campus
     LIMIT 1'
);
$programStatement->execute([
    'program' => $program,
    'campus_filter' => $campus,
    'campus_match' => $campus,
]);
$programRecord = $programStatement->fetch();

if (!$programRecord) {
    header('Location: index.php');
    exit;
}

if (!userCanAccessProgram($user, $programRecord)) {
    header('Location: index.php');
    exit;
}

$campus = $programRecord['campus'];

$sql = 'SELECT id, student_id, full_name, gwa, licensure_result, campus, college
     FROM students
     WHERE program = :program
       AND campus = :campus
';
$params = ['program' => $program, 'campus' => $campus];

$sql .= ' ORDER BY CAST(student_id AS UNSIGNED), student_id';

$statement = $pdo->prepare($sql);
$statement->execute($params);
$students = $statement->fetchAll();

$roleBadgeClass = roleBadgeClass($user);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($program) ?> · Licensure Predictor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <!-- Top Navbar -->
    <header class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl font-bold tracking-tight text-slate-900">Licensure Predictor</span>
            </div>
            <div class="flex items-center gap-4">
                <?php if ($canManageUsers): ?>
                    <a href="users.php" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">Manage Users</a>
                <?php endif; ?>
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-slate-900"><?= e($user['email']) ?></p>
                    <p class="text-xs text-slate-500 capitalize"><?= e($user['account_type']) ?></p>
                </div>
                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider <?= $roleBadgeClass ?>">
                    <?= e($user['account_type']) ?>
                </span>
                <a href="logout.php" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-100 transition">Sign Out</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6">
            <a href="index.php" class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 hover:text-emerald-800 transition">
                &larr; Back to Programs
            </a>
            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight"><?= e($program) ?></h1>
                    <p class="mt-1 text-sm text-slate-600"><?= e($programRecord['campus']) ?> · <?= e($programRecord['college']) ?> student records</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-white px-4 py-3 shadow-sm min-w-32">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Students</p>
                    <p class="text-2xl font-semibold"><?= count($students) ?></p>
                </div>
            </div>
        </div>

        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Student ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">GWA</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Result</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (count($students) === 0): ?>
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-500">
                                    No students found for this program.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr class="cursor-pointer transition hover:bg-emerald-50" data-href="student.php?id=<?= (int) $student['id'] ?>" tabindex="0">
                                    <td class="whitespace-nowrap px-5 py-4 font-medium"><?= e($student['full_name']) ?></td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-700"><?= e($student['student_id']) ?></td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-700"><?= number_format((float) $student['gwa'], 2) ?></td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $student['licensure_result'] === 'PASS' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                                            <?= e($student['licensure_result']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="assets/app.js"></script>
</body>
</html>
