<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\UseCases\Auth\LoginUseCase;
use PLCTech\Application\UseCases\Auth\LogoutUseCase;
use PLCTech\Infrastructure\Auth\JWTHandler;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class AuthController
{
        private LoginUseCase $loginUseCase;
        private LogoutUseCase $logoutUseCase;

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();
                $jwtHandler = new JWTHandler();
                $this->loginUseCase = new LoginUseCase($userRepository, $jwtHandler);
                $this->logoutUseCase = new LogoutUseCase();
        }

        public function showLogin(): void
        {
                // Si ya está logueado, redirigir al home
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . $_ENV['APP_URL']);
                        exit;
                }

                require_once __DIR__ . '/../Views/auth/login.php';
        }

        public function login(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/login');
                        exit;
                }

                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                try {
                        $result = $this->loginUseCase->execute($username, $password);

                        if ($result) {
                                $_SESSION['success_message'] = '¡Bienvenido ' . $result['user']['username'] . '!';
                                header('Location: ' . $_ENV['APP_URL']);
                        } else {
                                $_SESSION['error_message'] = 'Credenciales inválidas';
                                header('Location: ' . $_ENV['APP_URL'] . '/login');
                        }
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL'] . '/login');
                }
                exit;
        }

        public function logout(): void
        {
                $this->logoutUseCase->execute();
                $_SESSION['success_message'] = 'Has cerrado sesión correctamente';
                header('Location: ' . $_ENV['APP_URL'] . '/login');
                exit;
        }
}
