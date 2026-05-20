<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Siblings.co</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/auth.css') ?>">
</head>
<body>

    <div class="container-fluid p-0 h-100">
        <div class="row g-0 main-wrapper">
    
            <div class="col-md-6 brand-section h-100">
                <img src="<?= asset('img/logo.png') ?>" alt="Siblings.co Logo">
            </div>

            <div class="col-md-6 login-section h-100">
                <div class="login-card">
                    <h1>Login</h1>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= url('/login') ?>">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="username" value="<?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="mb-1">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="password" required>
                                <span class="input-group-text password-toggle">
                                    <i class="fas fa-eye toggle-password"></i>
                                </span>
                            </div>
                            <a href="#" class="forgot-password">forgot password?</a>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-login shadow-sm">Login</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
       
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>