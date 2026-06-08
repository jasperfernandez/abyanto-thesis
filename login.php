<?php

declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/functions.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'account_type' => $user['account_type'],
            ];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · Licensure Predictor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="flex min-h-full flex-col justify-center bg-slate-900 text-slate-100 py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-600 shadow-lg shadow-emerald-600/30">
            <span class="text-3xl font-bold text-white">LP</span>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold tracking-tight">Licensure Predictor</h2>
        <p class="mt-2 text-center text-sm text-slate-400">Sign in to manage student records</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-800 py-8 px-4 shadow-xl border border-slate-700/50 rounded-2xl sm:px-10">
            <?php if ($error !== ''): ?>
                <div class="mb-4 rounded-xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm font-medium text-rose-400">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form class="space-y-6" method="POST" action="login.php">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Email address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 text-slate-100 shadow-sm placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 sm:text-sm" 
                            placeholder="registrar@abyanto.freedev.app">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 text-slate-100 shadow-sm placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 sm:text-sm" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="flex w-full justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition duration-150">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-slate-700/60 pt-6">
                <div class="text-xs text-slate-400 leading-relaxed">
                    <p class="font-medium text-slate-300 mb-1">Demo Accounts:</p>
                    <p>• Registrar: <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">registrar@abyanto.freedev.app</code> / <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">password</code></p>
                    <p class="mt-1">• Program Chair: <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">program_chair@abyanto.freedev.app</code> / <code class="bg-slate-900 px-1 py-0.5 rounded text-emerald-400">password</code></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
