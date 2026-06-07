<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

$studentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$student = fetchStudent($pdo, $studentId);

if ($student === null) {
    http_response_code(404);
    exit('Student not found.');
}

$courseStatement = $pdo->prepare(
    'SELECT c.id, c.code, c.name, c.is_major, sg.grade
     FROM courses c
     LEFT JOIN student_grades sg
       ON sg.course_id = c.id AND sg.student_id = :student_id
     ORDER BY c.sort_order, c.code'
);
$courseStatement->execute(['student_id' => $studentId]);
$courses = $courseStatement->fetchAll();

$majorCourses = array_values(array_filter($courses, fn ($course) => (int) $course['is_major'] === 1));
$minorCourses = array_values(array_filter($courses, fn ($course) => (int) $course['is_major'] === 0));
$message = $_GET['message'] ?? '';
$prediction = $_GET['prediction'] ?? '';
$hasPrediction = in_array($prediction, ['PASS', 'FAIL'], true);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($student['full_name']) ?> · Licensure Predictor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="index.php" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">&larr; Back to students</a>
                <h1 class="mt-2 text-3xl font-bold tracking-tight"><?= e($student['full_name']) ?></h1>
                <p class="mt-1 text-sm text-slate-600">Student ID <?= e($student['student_id']) ?></p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white px-5 py-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500">Current Result</p>
                <p class="mt-1 text-2xl font-bold <?= $student['licensure_result'] === 'PASS' ? 'text-emerald-700' : 'text-rose-700' ?>"><?= e($student['licensure_result']) ?></p>
            </div>
        </div>

        <?php if ($message !== '' && ! $hasPrediction): ?>
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="save_student.php" class="space-y-6">
            <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Student Information</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Full Name</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="full_name" value="<?= e($student['full_name']) ?>" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Student ID</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="student_id" value="<?= e($student['student_id']) ?>" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">GWA</span>
                        <input type="number" step="0.01" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="gwa" value="<?= e((string) $student['gwa']) ?>" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Program</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="program" value="<?= e($student['program']) ?>" required>
                    </label>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Major Course Grades</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <?php foreach ($majorCourses as $course): ?>
                        <label class="block rounded-md border border-emerald-100 bg-emerald-50/60 p-3">
                            <span class="block text-sm font-semibold text-slate-800"><?= e($course['code']) ?></span>
                            <input type="number" min="1" max="5" step="0.01" name="grades[<?= (int) $course['id'] ?>]" value="<?= e((string) $course['grade']) ?>" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        </label>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Minor Course Grades</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <?php foreach ($minorCourses as $course): ?>
                        <label class="block rounded-md border border-slate-200 bg-slate-50 p-3">
                            <span class="block text-sm font-semibold text-slate-800"><?= e($course['code']) ?></span>
                            <input type="number" min="1" max="5" step="0.01" name="grades[<?= (int) $course['id'] ?>]" value="<?= e((string) $course['grade']) ?>" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        </label>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="sticky bottom-0 flex justify-end gap-3 border-t border-slate-200 bg-slate-50/95 py-4 backdrop-blur">
                <a href="index.php" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Cancel</a>
                <button type="submit" name="action" value="save" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Save</button>
                <button type="submit" name="action" value="predict" data-loading-text="Running..." class="inline-flex min-w-36 items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <span class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" data-spinner></span>
                    <span data-label>Run Prediction</span>
                </button>
            </div>
        </form>
    </main>

    <?php if ($hasPrediction): ?>
        <dialog id="predictionDialog" class="w-[min(92vw,28rem)] rounded-xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-slate-950/50">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full <?= $prediction === 'PASS' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                        <span class="text-xl font-bold"><?= $prediction === 'PASS' ? '✓' : '!' ?></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">Prediction Result</p>
                        <h2 class="mt-1 text-3xl font-bold <?= $prediction === 'PASS' ? 'text-emerald-700' : 'text-rose-700' ?>"><?= e($prediction) ?></h2>
                        <p class="mt-2 text-sm text-slate-600"><?= e($student['full_name']) ?>'s record has been updated.</p>
                    </div>
                </div>
                <form method="dialog" class="mt-6 flex justify-end">
                    <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Close</button>
                </form>
            </div>
        </dialog>
    <?php endif; ?>

    <script src="assets/app.js"></script>
</body>
</html>
