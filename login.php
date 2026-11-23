<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit;
}

$identifier = '';
$error = '';
$flash = get_flash();
$showForgotMessage = isset($_GET['forgot']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = isset($_POST['identifier']) && is_string($_POST['identifier']) ? trim($_POST['identifier']) : '';
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';

    if (!verify_csrf()) {
        $error = 'Your form session expired. Please try again.';
    } elseif ($identifier === '' || strlen($identifier) > 254 || $password === '') {
        $error = 'Enter your email or enrollment number and password.';
    } else {
        $statement = $pdo->prepare(
            'SELECT id, full_name, password_hash
             FROM students
             WHERE email = :email OR enrollment_number = :enrollment_number
             LIMIT 1'
        );
        $statement->execute([
            'email' => strtolower($identifier),
            'enrollment_number' => $identifier,
        ]);
        $student = $statement->fetch();

        if ($student && password_verify($password, $student['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['student_id'] = (int) $student['id'];
            unset($_SESSION['csrf_token']);
            set_flash('success', 'Welcome back, ' . $student['full_name'] . '.');
            header('Location: dashboard.php');
            exit;
        }

        $error = 'The email/enrollment number or password is incorrect.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#103462">
    <title>Student Login | NMC College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="auth-page">
<header class="site-header">
    <nav class="navbar container" aria-label="Main navigation">
        <a class="brand" href="index.php"><span class="brand-mark" aria-hidden="true">N</span><span><strong>NMC College</strong><small>Learn · Lead · Flourish</small></span></a>
        <a class="nav-link" href="index.php">← Back to home</a>
    </nav>
</header>
<main class="auth-main container">
    <section class="auth-card auth-card--login">
        <div class="auth-heading">
            <span class="auth-kicker">STUDENT PORTAL</span>
            <h1>Welcome back</h1>
            <p>Sign in to continue to your NMC College account.</p>
        </div>
        <?php if ($flash): ?>
            <div class="alert alert-<?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></div>
        <?php endif; ?>
        <?php if ($showForgotMessage): ?>
            <div class="alert alert-info" role="status">For a password reset, please contact your college administrator. Self-service reset is not configured for this portal.</div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger" role="alert"><?= h($error) ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <div class="mb-3">
                <label class="form-label" for="identifier">Email or enrollment number</label>
                <input class="form-control" id="identifier" name="identifier" type="text" autocomplete="username" maxlength="254" value="<?= h($identifier) ?>" required autofocus>
            </div>
            <div class="mb-2">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <div class="text-end mb-4"><a class="subtle-link" href="login.php?forgot=1">Forgot password?</a></div>
            <button class="btn btn-primary btn-lg w-100" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-switch">New to NMC College? <a href="register.php">Create a student account</a></p>
    </section>
</main>
<footer class="site-footer"><div class="container text-center">© <?= date('Y') ?> NMC College. All rights reserved.</div></footer>
</body>
</html>
