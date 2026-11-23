<?php
declare(strict_types=1);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#103462">
    <meta name="description" content="Welcome to the NMC College student portal.">
    <title>NMC College | Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <nav class="navbar navbar-expand-lg container" aria-label="Main navigation">
        <a class="brand" href="index.php" aria-label="NMC College home">
            <span class="brand-mark" aria-hidden="true">N</span>
            <span><strong>NMC College</strong><small>Learn · Lead · Flourish</small></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <a class="nav-link active" href="index.php">Home</a>
                <a class="nav-link" href="register.php">Registration</a>
                <a class="btn btn-primary btn-sm ms-lg-2 px-4" href="login.php">Student Login</a>
            </div>
        </div>
    </nav>
</header>

<main>
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <p class="eyebrow"><span class="eyebrow-dot"></span> NMC COLLEGE STUDENT PORTAL</p>
                    <h1>Your ambition.<br><span>Your next chapter.</span></h1>
                    <p class="hero-copy">A welcoming place to stay connected with your studies. Sign in to view your student profile, or create an account to get started.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a class="btn btn-primary btn-lg" href="register.php">Create student account <span aria-hidden="true">→</span></a>
                        <a class="btn btn-outline-light btn-lg" href="login.php">Sign in</a>
                    </div>
                    <div class="hero-note"><span class="note-check" aria-hidden="true">✓</span> Your student information, all in one place</div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-art" aria-label="Illustration of books and a graduation cap" role="img">
                        <div class="art-orbit orbit-one"></div>
                        <div class="art-orbit orbit-two"></div>
                        <div class="art-sun"></div>
                        <div class="art-book book-back"></div>
                        <div class="art-book book-front"><span>NMC</span></div>
                        <div class="art-cap"><span></span></div>
                        <div class="art-spark spark-one">✦</div>
                        <div class="art-spark spark-two">✦</div>
                        <div class="art-label"><strong>Grow with purpose</strong><small>Make every semester count.</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave" aria-hidden="true"></div>
    </section>

    <section class="portal-section container">
        <div class="section-heading text-center">
            <p class="eyebrow">YOUR PORTAL, YOUR PROGRESS</p>
            <h2>Everything starts with a step forward.</h2>
            <p>Access your essential student details through a simple, secure portal.</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <article class="feature-card">
                    <span class="feature-icon" aria-hidden="true">01</span>
                    <h3>Your profile</h3>
                    <p>Keep your enrollment, contact and course details together in one convenient place.</p>
                </article>
            </div>
            <div class="col-md-4">
                <article class="feature-card">
                    <span class="feature-icon feature-icon--gold" aria-hidden="true">02</span>
                    <h3>Simple access</h3>
                    <p>Sign in securely with your email address or enrollment number whenever you need to.</p>
                </article>
            </div>
            <div class="col-md-4">
                <article class="feature-card">
                    <span class="feature-icon feature-icon--green" aria-hidden="true">03</span>
                    <h3>Ready for what’s next</h3>
                    <p>Get started with your NMC College student account in just a few steps.</p>
                </article>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
        <span><strong>NMC College</strong> · Learn · Lead · Flourish</span>
        <span>© <?= date('Y') ?> NMC College. All rights reserved.</span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
