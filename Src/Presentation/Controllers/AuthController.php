<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\UseCases\Auth\LoginUseCase;
use PLCTech\Application\UseCases\Auth\LogoutUseCase;
use PLCTech\Infrastructure\Auth\JWTHandler;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLEmployeeRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;
use \PLCTech\Helpers\PathHelper;

class AuthController
{
        private LoginUseCase $loginUseCase;
        private LogoutUseCase $logoutUseCase;

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();
                $employeeRepository = new MySQLEmployeeRepository();
                $customerRepository = new MySQLCustomerRepository();
                $jwtHandler = new JWTHandler();

                $this->loginUseCase = new LoginUseCase(
                        $userRepository,
                        $employeeRepository,
                        $customerRepository,
                        $jwtHandler
                );

                $this->logoutUseCase = new LogoutUseCase();
        }

        public function showLogin(): void
        {
                // > Si ya está logueado, redirigir al home...
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

                require_once __DIR__ . '/../Views/auth/login.php';
        }

        public function login(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/login');
                        exit;
                }

                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                try {
                        $result = $this->loginUseCase->execute($username, $password);

                        if ($result) {
                                $_SESSION['success_message'] =
                                        '¡Bienvenido '
                                        . $result['user']['full_name']
                                        . '!';
                                header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        } else {
                                $_SESSION['error_message'] = 'Credenciales inválidas';
                                header('Location: ' . PathHelper::getBaseUrl() . '/login');
                        }
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/login');
                }
                exit;
        }

        public function logout(): void
        {
                $this->logoutUseCase->execute();
                $_SESSION['success_message'] = 'Has cerrado sesión correctamente';

                // > ============================================================
                // > REDIRIGIR A LANDING PAGE (/) EN LUGAR DE /login
                // > ============================================================
                header('Location: ' . PathHelper::getBaseUrl() . '/');
                exit;
        }
}
