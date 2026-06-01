<?php
$pageTitle = 'Login - Siblings.co';
$pageStyles = ['auth.css'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
    <a href="#login-form" class="skip-to-content">Lewati ke form login</a>

    <div class="auth-wrapper">
        <div class="auth-brand" aria-hidden="true">
            <img src="<?= asset('img/logo.png') ?>" alt="Logo Siblings.co">
        </div>

        <section class="auth-form-section" aria-labelledby="auth-title">
            <div class="auth-card">
                <h1 class="auth-card__title" id="auth-title">Login</h1>

                <?php if (!empty($error)): ?>
                    <div class="auth-error" role="alert">
                        <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form id="login-form" class="auth-form" method="post" action="<?= url('/login') ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="form-field">
                        <label for="username" class="form-field__label">Username</label>
                        <div class="auth-input-group">
                            <span class="auth-input-group__icon" aria-hidden="true">
                                <i class="fas fa-user"></i>
                            </span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-control"
                                placeholder="Masukkan username"
                                value="<?= e($username ?? '') ?>"
                                autocomplete="username"
                                spellcheck="false"
                                autocapitalize="off"
                                required
                                aria-required="true">
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="password" class="form-field__label">Password</label>
                        <div class="auth-input-group">
                            <span class="auth-input-group__icon" aria-hidden="true">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                spellcheck="false"
                                required
                                aria-required="true">
                            <button
                                type="button"
                                class="auth-input-group__toggle"
                                id="togglePassword"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                aria-controls="password">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="auth-actions">
                        <button type="submit" class="btn auth-submit" data-loading-label="Masuk…">Masuk</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('togglePassword');
            const input = document.getElementById('password');
            const icon = toggle.querySelector('i');

            toggle.addEventListener('click', function () {
                const isVisible = input.getAttribute('type') === 'text';
                input.setAttribute('type', isVisible ? 'password' : 'text');
                toggle.setAttribute('aria-pressed', String(!isVisible));
                toggle.setAttribute('aria-label', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        })();
    </script>
</body>
</html>
