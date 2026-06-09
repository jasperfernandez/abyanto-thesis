<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

// Check auth
requireAuth();

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
     WHERE c.program IS NULL OR c.program = :program
     ORDER BY c.sort_order, c.code'
);
$courseStatement->execute([
    'student_id' => $studentId,
    'program' => $student['program'],
]);
$courses = $courseStatement->fetchAll();

$majorCourses = array_values(array_filter($courses, fn ($course) => (int) $course['is_major'] === 1));
$minorCourses = array_values(array_filter($courses, fn ($course) => (int) $course['is_major'] === 0));

$user = getLoggedInUser($pdo);
$isAdministrator = isAdministrator($user);
$canManageUsers = isGlobalAdministrator($user);
$canEditCampus = isGlobalAdministrator($user);

if (!userCanAccessStudent($user, $student)) {
    header('Location: index.php');
    exit;
}

$programSummaryStatement = $pdo->prepare(
    'SELECT
        COUNT(*) as total_students,
        SUM(CASE WHEN licensure_result = "PASS" THEN 1 ELSE 0 END) as passed_students,
        SUM(CASE WHEN licensure_result = "FAIL" THEN 1 ELSE 0 END) as failed_students
     FROM students
     WHERE program = :program
       AND campus = :campus'
);
$programSummaryStatement->execute([
    'program' => $student['program'],
    'campus' => $student['campus'],
]);
$programSummary = $programSummaryStatement->fetch() ?: [
    'total_students' => 0,
    'passed_students' => 0,
    'failed_students' => 0,
];

$roleBadgeClass = roleBadgeClass($user);
$message = $_GET['message'] ?? '';
$prediction = $_GET['prediction'] ?? '';
$hasPrediction = in_array($prediction, ['PASS', 'FAIL'], true);
$sexOptions = ['Male', 'Female'];
$schoolTypeOptions = ['Private', 'Public'];
$educationalAttainmentOptions = [
    'No Education',
    'Elementary Level',
    'Elementary Graduate',
    'School Level',
    'High School Graduate',
    'College Undergraduate',
    'College Graduate',
    'Post-Graduate',
];
$monthlyFamilyIncomeOptions = [
    'Below 10,000',
    '10,000 to 15,000',
    '15,001 to 20,000',
    '20,001 to 30,000',
    '30,001 to 40,000',
    '40,001 to 50,000',
    '50,001 to 100,000 and above',
];
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
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="students.php?program=<?= urlencode($student['program']) ?>&campus=<?= urlencode($student['campus']) ?>" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">&larr; Back to students</a>
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
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Campus</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="campus" value="<?= e($student['campus']) ?>" required <?= $canEditCampus ? '' : 'readonly' ?>>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">College</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="college" value="<?= e($student['college']) ?>" required>
                    </label>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Student Background</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">City/Municipality</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="city_municipality" value="<?= e($student['city_municipality']) ?>">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Province</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="province" value="<?= e($student['province']) ?>">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Postal Code</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="postal_code" value="<?= e($student['postal_code']) ?>">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Age</span>
                        <input type="number" min="1" max="120" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="age" value="<?= e((string) $student['age']) ?>">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Sex</span>
                        <select class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="sex">
                            <option value="">Select sex</option>
                            <?php foreach ($sexOptions as $option): ?>
                                <option value="<?= e($option) ?>"<?= selectedOption($student['sex'], $option) ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Secondary School Name</span>
                        <input class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="secondary_school_name" value="<?= e($student['secondary_school_name']) ?>">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Secondary School Type</span>
                        <select class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="secondary_school_type">
                            <option value="">Select school type</option>
                            <?php foreach ($schoolTypeOptions as $option): ?>
                                <option value="<?= e($option) ?>"<?= selectedOption($student['secondary_school_type'], $option) ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Educational Attainment (Father)</span>
                        <select class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="father_educational_attainment">
                            <option value="">Select attainment</option>
                            <?php foreach ($educationalAttainmentOptions as $option): ?>
                                <option value="<?= e($option) ?>"<?= selectedOption($student['father_educational_attainment'], $option) ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Educational Attainment (Mother)</span>
                        <select class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="mother_educational_attainment">
                            <option value="">Select attainment</option>
                            <?php foreach ($educationalAttainmentOptions as $option): ?>
                                <option value="<?= e($option) ?>"<?= selectedOption($student['mother_educational_attainment'], $option) ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700">Monthly Family Income</span>
                        <select class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" name="monthly_family_income">
                            <option value="">Select income range</option>
                            <?php foreach ($monthlyFamilyIncomeOptions as $option): ?>
                                <option value="<?= e($option) ?>"<?= selectedOption($student['monthly_family_income'], $option) ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Major Course Grades</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <?php foreach ($majorCourses as $course): ?>
                        <label class="block rounded-md border border-emerald-100 bg-emerald-50/60 p-3">
                            <span class="block text-sm font-semibold text-slate-800"><?= e($course['code']) ?></span>
                            <input type="number" min="1" max="5" step="0.01" name="grades[<?= (int) $course['id'] ?>]" value="<?= e((string) $course['grade']) ?>" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" <?= $isAdministrator ? '' : 'readonly' ?>>
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
                            <input type="number" min="1" max="5" step="0.01" name="grades[<?= (int) $course['id'] ?>]" value="<?= e((string) $course['grade']) ?>" class="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" <?= $isAdministrator ? '' : 'readonly' ?>>
                        </label>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="sticky bottom-0 flex justify-end gap-3 border-t border-slate-200 bg-slate-50/95 py-4 backdrop-blur">
                <a href="students.php?program=<?= urlencode($student['program']) ?>&campus=<?= urlencode($student['campus']) ?>" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Cancel</a>
                <?php if ($isAdministrator): ?>
                    <button type="submit" name="action" value="save" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Save</button>
                <?php endif; ?>
                <button type="submit" name="action" value="predict" data-loading-text="Running..." class="inline-flex min-w-36 items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <span class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" data-spinner></span>
                    <span data-label>Run Prediction</span>
                </button>
            </div>
        </form>
    </main>

    <?php if ($hasPrediction): ?>
        <dialog id="predictionDialog" class="w-[min(92vw,36rem)] rounded-xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-slate-950/50">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full <?= $prediction === 'PASS' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                        <span class="text-xl font-bold"><?= $prediction === 'PASS' ? '✓' : '!' ?></span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">Prediction Result</p>
                        <h2 class="mt-1 text-3xl font-bold <?= $prediction === 'PASS' ? 'text-emerald-700' : 'text-rose-700' ?>"><?= e($prediction) ?></h2>
                        <p class="mt-2 text-sm text-slate-600"><?= e($student['full_name']) ?>'s record has been updated.</p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg border <?= $prediction === 'PASS' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-rose-200 bg-rose-50 text-rose-800' ?> px-4 py-3 text-sm font-bold uppercase tracking-wide">
                    <?= $prediction === 'PASS' ? 'CLASSIFICATION: BASELINE SAFE (PASS TRAJECTORY)' : 'AT-RISK (FAIL TRAJECTORY)' ?>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[0.7rem] font-semibold uppercase tracking-wide text-slate-500">Total Students Evaluated</p>
                        <p class="mt-2 text-2xl font-bold text-slate-950"><?= (int) $programSummary['total_students'] ?></p>
                    </div>
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                        <p class="text-[0.7rem] font-semibold uppercase tracking-wide text-emerald-700">Total Cleared (Safe)</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-700"><?= (int) $programSummary['passed_students'] ?></p>
                    </div>
                    <div class="rounded-lg border border-rose-200 bg-rose-50 p-4">
                        <p class="text-[0.7rem] font-semibold uppercase tracking-wide text-rose-700">Total At-Risk Students</p>
                        <p class="mt-2 text-2xl font-bold text-rose-700"><?= (int) $programSummary['failed_students'] ?></p>
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
