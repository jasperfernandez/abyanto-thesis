<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

requireAuth();

$user = getLoggedInUser($pdo);
$isRegistrar = $user['account_type'] === 'registrar';

if (!$isRegistrar) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int) ($_POST['user_id'] ?? 0);
    $program = trim((string) ($_POST['program'] ?? ''));
    $program = $program === '' ? null : $program;

    $update = $pdo->prepare('UPDATE users SET program = :program WHERE id = :id');
    $update->execute(['program' => $program, 'id' => $userId]);
    $message = 'User updated.';
}

$users = $pdo->query(
    'SELECT id, email, account_type, program FROM users ORDER BY account_type, email'
)->fetchAll();

$programs = $pdo->query(
    'SELECT DISTINCT program FROM students WHERE program IS NOT NULL ORDER BY program'
)->fetchAll();

$roleBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Users · Licensure Predictor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <header class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl font-bold tracking-tight text-slate-900">Licensure Predictor</span>
            </div>
            <div class="flex items-center gap-4">
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
            <h1 class="mt-4 text-3xl font-bold tracking-tight">Manage Users</h1>
            <p class="mt-1 text-sm text-slate-600">Assign programs to program chair accounts</p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Email</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Account Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Program</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-medium"><?= e($u['email']) ?></td>
                                <td class="whitespace-nowrap px-5 py-4 capitalize"><?= e($u['account_type']) ?></td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <?php if ($u['account_type'] === 'program chair'): ?>
                                        <form method="post" class="flex items-center gap-2">
                                            <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                            <select name="program" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                                <option value="">-- None --</option>
                                                <?php foreach ($programs as $p): ?>
                                                    <option value="<?= e($p['program']) ?>"<?= selectedOption($u['program'], $p['program']) ?>><?= e($p['program']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition">Save</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-sm text-slate-500">All programs</span>
                                    <?php endif; ?>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <?php if ($u['account_type'] === 'program chair' && $u['program'] === null): ?>
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Needs setup</span>
                                    <?php endif; ?>
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
