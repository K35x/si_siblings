<?php

class AuthController extends Controller
{
    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . url($_SESSION['user']['role'] === Model::ROLE_OWNER ? '/owner' : '/kasir'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify_unified()) {
                http_response_code(403);
                echo '403 — CSRF token tidak valid';
                return;
            }

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $error = 'Username atau password salah.';

            if ($username !== '' && $password !== '') {
                $user = (new UserModel())->findByUsername($username);

                if ($user !== null && password_verify($password, $user['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'user_id' => (int) $user['user_id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                    ];

                    header('Location: ' . url($user['role'] === Model::ROLE_OWNER ? '/owner' : '/kasir'));
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
