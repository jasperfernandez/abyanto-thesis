<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

// Check auth
requireAuth();

// Query program summary statistics
$user = getLoggedInUser($pdo);
$isAdministrator = isAdministrator($user);
$isCollegeDean = isCollegeDean($user);

if ($isAdministrator) {
    $programs = $pdo->query(
        'SELECT 
            program, 
            college,
            COUNT(*) as total_students,
            SUM(CASE WHEN licensure_result = "PASS" THEN 1 ELSE 0 END) as passed_students,
            SUM(CASE WHEN licensure_result = "FAIL" THEN 1 ELSE 0 END) as failed_students
         FROM students
         GROUP BY program, college
         ORDER BY program'
    )->fetchAll();
} elseif ($isCollegeDean) {
    $assignedCollege = $user['college'] ?? '';
    $statement = $pdo->prepare(
        'SELECT
            program,
            college,
            COUNT(*) as total_students,
            SUM(CASE WHEN licensure_result = "PASS" THEN 1 ELSE 0 END) as passed_students,
            SUM(CASE WHEN licensure_result = "FAIL" THEN 1 ELSE 0 END) as failed_students
         FROM students
         WHERE college = :college
         GROUP BY program, college
         ORDER BY program'
    );
    $statement->execute(['college' => $assignedCollege]);
    $programs = $statement->fetchAll();
} else {
    $assignedProgram = $user['program'] ?? '';
    $statement = $pdo->prepare(
        'SELECT 
            program, 
            college,
            COUNT(*) as total_students,
            SUM(CASE WHEN licensure_result = "PASS" THEN 1 ELSE 0 END) as passed_students,
            SUM(CASE WHEN licensure_result = "FAIL" THEN 1 ELSE 0 END) as failed_students
         FROM students
         WHERE program = :program
         GROUP BY program, college
         ORDER BY program'
    );
    $statement->execute(['program' => $assignedProgram]);
    $programs = $statement->fetchAll();
}

// Calculate overall summary metrics
$totalStudents = 0;
$totalPassed = 0;
$totalFailed = 0;

foreach ($programs as $p) {
    $totalStudents += (int) $p['total_students'];
    $totalPassed += (int) $p['passed_students'];
    $totalFailed += (int) $p['failed_students'];
}

$roleBadgeClass = roleBadgeClass($user);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Programs · Licensure Predictor</title>
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
                <?php if ($isAdministrator): ?>
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
        <!-- Dashboard Header & Stat Blocks -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Programs Directory</h1>
                <p class="mt-1 text-sm text-slate-600">Overview of student enrollment and licensure prediction results</p>
            </div>
        </div>

        <div class="mb-8 grid gap-4 grid-cols-1 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Students Evaluated</p>
                <p class="mt-2 text-3xl font-bold text-slate-950"><?= $totalStudents ?></p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Cleared (Safe)</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700"><?= $totalPassed ?></p>
            </div>
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Total At-Risk Students</p>
                <p class="mt-2 text-3xl font-bold text-rose-700"><?= $totalFailed ?></p>
            </div>
        </div>

        <!-- Programs Table -->
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Program Name</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">College</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Students</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Passed</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Failed</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 font-bold">Passing Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($programs as $program): ?>
                            <?php 
                                $total = (int) $program['total_students'];
                                $passed = (int) $program['passed_students'];
                                $failed = (int) $program['failed_students'];
                                $passingRate = $total > 0 ? ($passed / $total) * 100 : 0;
                            ?>
                            <tr class="cursor-pointer transition hover:bg-emerald-50" data-href="students.php?program=<?= urlencode($program['program']) ?>" tabindex="0">
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900"><?= e($program['program']) ?></td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-700"><?= e($program['college']) ?></td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-700"><?= $total ?></td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-700">
                                    <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">
                                        <?= $passed ?>
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-700">
                                    <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700">
                                        <?= $failed ?>
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-2">
                                        <span><?= number_format($passingRate, 1) ?>%</span>
                                        <div class="hidden md:block w-24 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: <?= $passingRate ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="assets/app.js"></script>
</body>
</html>
