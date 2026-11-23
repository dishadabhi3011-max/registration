<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['student_id'])) {
    set_flash('info', 'Please sign in to view your dashboard.');
    header('Location: login.php');
    exit;
}

$statement = $pdo->prepare(
    'SELECT full_name, email, mobile, enrollment_number, course, semester, created_at
     FROM students
     WHERE id = :id
     LIMIT 1'
);
$statement->execute(['id' => (int) $_SESSION['student_id']]);
$student = $statement->fetch();

if (!$student) {
    unset($_SESSION['student_id']);
    set_flash('info', 'Your account could not be found. Please sign in again.');
    header('Location: login.php');
    exit;
}

$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#103462">
    <title>Student Dashboard | NMC College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="dashboard-page">
<header class="site-header">
    <nav class="navbar container" aria-label="Main navigation">
        <a class="brand" href="index.php"><span class="brand-mark" aria-hidden="true">N</span><span><strong>NMC College</strong><small>Learn · Lead · Flourish</small></span></a>
        <div class="d-flex align-items-center gap-3">
            <a class="nav-link d-none d-sm-inline" href="index.php">Home</a>
            <form method="post" action="logout.php">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <button class="btn btn-outline-primary btn-sm px-3" type="submit">Log out</button>
            </form>
        </div>
    </nav>
</header>
<main class="container dashboard-main">
    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></div>
    <?php endif; ?>
    <section class="dashboard-welcome">
        <div>
            <p class="eyebrow">STUDENT DASHBOARD</p>
            <h1>Hello, <?= h($student['full_name']) ?> <span aria-hidden="true">✦</span></h1>
            <p>Your NMC College profile and enrollment details.</p>
        </div>
        <div class="student-avatar" aria-hidden="true"><?= h(strtoupper(substr($student['full_name'], 0, 1))) ?></div>
    </section>
    <section class="profile-card" aria-labelledby="profile-title">
        <div class="profile-card-heading">
            <div><span class="auth-kicker">YOUR INFORMATION</span><h2 id="profile-title">Student profile</h2></div>
            <span class="status-pill"><span></span> Active student</span>
        </div>
        <div class="profile-grid">
            <div class="profile-item"><span>Full name</span><strong><?= h($student['full_name']) ?></strong></div>
            <div class="profile-item"><span>Enrollment number</span><strong><?= h($student['enrollment_number']) ?></strong></div>
            <div class="profile-item"><span>Email address</span><strong><?= h($student['email']) ?></strong></div>
            <div class="profile-item"><span>Mobile number</span><strong><?= h($student['mobile']) ?></strong></div>
            <div class="profile-item"><span>Course</span><strong><?= h($student['course']) ?></strong></div>
            <div class="profile-item"><span>Semester</span><strong><?= h((string) $student['semester']) ?></strong></div>
        </div>
    </section>
    <div class="dashboard-tip"><span aria-hidden="true">✦</span><p><strong>Keep moving forward.</strong> Your student profile is ready whenever you need it.</p></div>
</main>
<footer class="site-footer"><div class="container text-center">© <?= date('Y') ?> NMC College. All rights reserved.</div></footer>
</body>
</html>
