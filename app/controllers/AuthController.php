<?php

class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $error = 'Username atau password salah.';

            if ($username !== '' && $password !== '') {
                $user = (new UserModel())->findByUsername($username);

                if ($user !== null && password_verify($password, $user['password'])) {
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }

                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'user_id' => (int) $user['user_id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                    ];

                    header('Location: ' . url($user['role'] === 'owner' ? '/owner' : '/kasir'));
                    exit;
                }
            }

            $this->view('layouts.auth', [
                'error' => $error,
                'username' => $username,
            ]);
            return;
        }

        $this->view('layouts.auth');
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header('Location: ' . url('/login'));
        exit;
    }
}
