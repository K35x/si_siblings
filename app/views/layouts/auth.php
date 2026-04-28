<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Siblings.co</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f7e9d5; /* Warna krem sesuai desain */
            overflow: hidden;
        }

        .main-wrapper {
            height: 100vh;
            display: flex;
        }

        /* --- PERUBAHAN DI SINI: MENGGUNAKAN FOTO SEBAGAI BACKGROUND --- */
        .brand-section {
            /* Mengambil foto tekstur cokelat tua yang kamu kirim */
            background-image: url('/si_siblings/public/assets/img/background.jpeg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
        }

        /* Opsional: Menambah lapisan gelap tipis agar logo lebih menonjol */
        .brand-section::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.2); 
        }

        .brand-section img {
            width: 70%;
            max-width: 350px;
            height: auto;
            position: relative; /* Agar logo berada di atas lapisan overlay */
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.5));
        }

        /* --- SISI KANAN TETAP SAMA --- */
        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px;
        }

        .login-card {
            background-color: #3C2317; /* Kotak cokelat form */
            color: white;
            padding: 40px;
            border-radius: 40px;
            width: 100%;
            max-width: 450px;
            text-align: left; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .login-card h1 {
            font-size: 3rem;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .input-group {
            background-color: white;
            border-radius: 25px;
            overflow: hidden;
        }

        .input-group-text {
            background-color: white;
            border: none;
            color: #888;
            padding-left: 20px;
        }

        .form-control {
            border: none;
            padding: 12px;
            color: #333;
        }

        .btn-login {
            background-color: white;
            color: #3C2317;
            font-weight: bold;
            border-radius: 30px;
            padding: 10px 60px;
            border: none;
            margin-top: 20px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #e2e2e2;
            transform: scale(1.05);
        }

        .forgot-password {
            font-size: 0.8rem;
            color: white;
            text-decoration: underline;
            margin-top: 5px;
            margin-left: 15px;
            display: inline-block;
        }

        @media (max-width: 767px) {
            .brand-section { display: none; }
            .login-section { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0 h-100">
        <div class="row g-0 main-wrapper">
    
            <div class="col-md-6 brand-section h-100">
                <img src="/si_siblings/public/assets/img/logo.png" alt="Siblings.co Logo">
            </div>

            <div class="col-md-6 login-section h-100">
                <div class="login-card">
                    <h1>Login</h1>
                    <form>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="email" class="form-control" placeholder="email@gmail.com" required>
                        </div>

                        <div class="mb-1">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" placeholder="password" required>
                                <span class="input-group-text" style="cursor: pointer; border-radius: 0 25px 25px 0 !important;">
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
        // Script untuk toggle buka/tutup mata pada password
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