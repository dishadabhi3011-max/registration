<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit;
}

$courses = [
    'BCA' => 'BCA',
    'BBA' => 'BBA',
    'B.Com' => 'B.Com',
    'B.A.' => 'B.A.',
    'B.Sc.' => 'B.Sc.',
    'Other' => 'Other',
];
$values = [
    'full_name' => '',
    'email' => '',
    'mobile' => '',
    'enrollment_number' => '',
    'course' => '',
    'semester' => '',
];
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $unused) {
        $values[$key] = isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
    }
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) && is_string($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (!verify_csrf()) {
        $message = 'Your form session expired. Please try again.';
    } else {
        if (strlen($values['full_name']) < 2 || strlen($values['full_name']) > 120 || preg_match('/[\x00-\x1F\x7F]/', $values['full_name'])) {
            $errors['full_name'] = 'Enter a name between 2 and 120 characters.';
        }
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || strlen($values['email']) > 254) {
            $errors['email'] = 'Enter a valid email address.';
        }
        $mobileDigits = preg_replace('/\D/', '', $values['mobile']);
        if (!preg_match('/^\+?[0-9\s().-]{8,20}$/', $values['mobile']) || strlen($mobileDigits) < 8 || strlen($mobileDigits) > 15) {
            $errors['mobile'] = 'Enter a valid mobile number with 8 to 15 digits.';
        }
        if (strlen($values['enrollment_number']) < 3 || strlen($values['enrollment_number']) > 50 || !preg_match('/^[A-Za-z0-9-]+$/', $values['enrollment_number'])) {
            $errors['enrollment_number'] = 'Use 3 to 50 letters, numbers or hyphens.';
        }
        if (!array_key_exists($values['course'], $courses)) {
            $errors['course'] = 'Select a course from the list.';
        }
        if (!preg_match('/^[1-8]$/', $values['semester'])) {
            $errors['semester'] = 'Select a semester from 1 to 8.';
        }
        if (strlen($password) < 8 || strlen($password) > 72) {
            $errors['password'] = 'Use a password between 8 and 72 characters.';
        }
        if ($confirmPassword !== $password) {
            $errors['confirm_password'] = 'The passwords do not match.';
        }

        if (!$errors) {
            try {
                $statement = $pdo->prepare(
                    'INSERT INTO students (full_name, email, mobile, enrollment_number, course, semester, password_hash)
                     VALUES (:full_name, :email, :mobile, :enrollment_number, :course, :semester, :password_hash)'
                );
                $statement->execute([
                    'full_name' => $values['full_name'],
                    'email' => strtolower($values['email']),
                    'mobile' => $values['mobile'],
                    'enrollment_number' => $values['enrollment_number'],
                    'course' => $values['course'],
                    'semester' => (int) $values['semester'],
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);
                set_flash('success', 'Registration complete. You can now sign in.');
                header('Location: login.php');
                exit;
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $errors['account'] = 'That email address or enrollment number is already registered.';
                } else {
                    error_log('NMC student registration failed: ' . $exception->getMessage());
                    $message = 'We could not complete your registration right now. Please try again.';
                }
            }
        }
    }
}

$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#103462">
    <title>Student Registration | NMC College</title>
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
    <section class="auth-card auth-card--wide">
        <div class="auth-heading">
            <span class="auth-kicker">JOIN OUR COMMUNITY</span>
            <h1>Create your student account</h1>
            <p>Enter your details below to access the NMC College student portal.</p>
        </div>
        <?php if ($flash): ?>
            <div class="alert alert-<?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></div>
        <?php endif; ?>
        <?php if ($message !== ''): ?>
            <div class="alert alert-danger" role="alert"><?= h($message) ?></div>
        <?php endif; ?>
        <?php if (isset($errors['account'])): ?>
            <div class="alert alert-danger" role="alert"><?= h($errors['account']) ?></div>
        <?php endif; ?>
        <form method="post" action="register.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="full_name">Student full name</label>
                    <input class="form-control<?= isset($errors['full_name']) ? ' is-invalid' : '' ?>" id="full_name" name="full_name" type="text" autocomplete="name" maxlength="120" value="<?= h($values['full_name']) ?>" required>
                    <?php if (isset($errors['full_name'])): ?><div class="invalid-feedback"><?= h($errors['full_name']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email">Email address</label>
                    <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= h($values['email']) ?>" required>
                    <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= h($errors['email']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="mobile">Mobile number</label>
                    <input class="form-control<?= isset($errors['mobile']) ? ' is-invalid' : '' ?>" id="mobile" name="mobile" type="tel" autocomplete="tel" maxlength="20" value="<?= h($values['mobile']) ?>" required>
                    <?php if (isset($errors['mobile'])): ?><div class="invalid-feedback"><?= h($errors['mobile']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="enrollment_number">Enrollment number</label>
                    <input class="form-control<?= isset($errors['enrollment_number']) ? ' is-invalid' : '' ?>" id="enrollment_number" name="enrollment_number" type="text" autocomplete="off" maxlength="50" value="<?= h($values['enrollment_number']) ?>" required>
                    <?php if (isset($errors['enrollment_number'])): ?><div class="invalid-feedback"><?= h($errors['enrollment_number']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="course">Course</label>
                    <select class="form-select<?= isset($errors['course']) ? ' is-invalid' : '' ?>" id="course" name="course" required>
                        <option value="">Select course</option>
                        <?php foreach ($courses as $courseValue => $courseLabel): ?>
                            <option value="<?= h($courseValue) ?>"<?= $values['course'] === $courseValue ? ' selected' : '' ?>><?= h($courseLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['course'])): ?><div class="invalid-feedback"><?= h($errors['course']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="semester">Semester</label>
                    <select class="form-select<?= isset($errors['semester']) ? ' is-invalid' : '' ?>" id="semester" name="semester" required>
                        <option value="">Select semester</option>
                        <?php for ($semester = 1; $semester <= 8; $semester++): ?>
                            <option value="<?= $semester ?>"<?= $values['semester'] === (string) $semester ? ' selected' : '' ?>>Semester <?= $semester ?></option>
                        <?php endfor; ?>
                    </select>
                    <?php if (isset($errors['semester'])): ?><div class="invalid-feedback"><?= h($errors['semester']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" id="password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" required>
                    <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= h($errors['password']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="confirm_password">Confirm password</label>
                    <input class="form-control<?= isset($errors['confirm_password']) ? ' is-invalid' : '' ?>" id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" minlength="8" maxlength="72" required>
                    <?php if (isset($errors['confirm_password'])): ?><div class="invalid-feedback"><?= h($errors['confirm_password']) ?></div><?php endif; ?>
                </div>
            </div>
            <button class="btn btn-primary btn-lg w-100 mt-4" type="submit">Create account <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-switch">Already registered? <a href="login.php">Sign in to your account</a></p>
    </section>
</main>
<footer class="site-footer"><div class="container text-center">© <?= date('Y') ?> NMC College. All rights reserved.</div></footer>
</body>
</html>
