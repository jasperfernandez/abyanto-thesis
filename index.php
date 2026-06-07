<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

$students = $pdo->query(
    'SELECT id, student_id, full_name, gwa, licensure_result
     FROM students
     ORDER BY CAST(student_id AS UNSIGNED), student_id'
)->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Licensure Predictor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Licensure Predictor</h1>
                <p class="mt-1 text-sm text-slate-600">Student records</p>
            </div>
            <div class="rounded-md border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500">Students</p>
                <p class="text-2xl font-semibold"><?= count($students) ?></p>
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
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="assets/app.js"></script>
</body>
</html>
