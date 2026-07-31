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

        // * ============================================================
        // * REGISTRO DE NUEVO CLIENTE (GET y POST)
        // * ============================================================
        public function register(): void
        {
                // > Si ya está logueado, redirigir al dashboard...
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

                // > ============================================================
                // > MÉTODO GET: Mostrar el formulario
                // > ============================================================
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        require_once __DIR__ . '/../Views/auth/register.php';
                        return;
                }

                // > ============================================================
                // > MÉTODO POST: Procesar el registro
                // > ============================================================
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        try {
                                // ? Validar campos...
                                $dni = trim($_POST['dni'] ?? '');
                                $fullName = trim($_POST['full_name'] ?? '');
                                $birthdate = $_POST['birthdate'] ?? '';
                                $email = trim($_POST['email'] ?? '');
                                $phoneNumber = trim($_POST['phone_number'] ?? '');
                                $password = $_POST['password'] ?? '';
                                $confirmPassword = $_POST['confirm_password'] ?? '';

                                // ? Validaciones...
                                if (empty($dni))
                                        throw new \Exception('El DNI es obligatorio');
                                if (empty($fullName))
                                        throw new \Exception('El nombre completo es obligatorio');
                                if (empty($birthdate))
                                        throw new \Exception('La fecha de nacimiento es obligatoria');
                                if (empty($email))
                                        throw new \Exception('El email es obligatorio');
                                if (empty($password))
                                        throw new \Exception('La contraseña es obligatoria');
                                if ($password !== $confirmPassword)
                                        throw new \Exception('Las contraseñas no coinciden');
                                if (strlen($password) < 8)
                                        throw new \Exception('La contraseña debe tener al menos 8 caracteres');

                                // ? Validar fecha de nacimiento (no futura)...
                                if (strtotime($birthdate) > time()) {
                                        throw new \Exception('La fecha de nacimiento no puede ser futura');
                                }

                                // ? Usar el CreateCustomerUseCase para registrar el cliente...
                                $customerDTO = new \PLCTech\Application\DTOs\CustomerDTO([
                                        'dni' => $dni,
                                        'full_name' => $fullName,
                                        'birthdate' => $birthdate,
                                        'email' => $email,
                                        'phone_number' => $phoneNumber
                                ]);

                                $customerRepository = new \PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository();
                                $userRepository = new \PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository();

                                $createCustomerUseCase = new \PLCTech\Application\UseCases\Customer\CreateCustomerUseCase(
                                        $customerRepository,
                                        $userRepository
                                );

                                // ? ============================================================
                                // ? PASAR LA CONTRASEÑA DEL FORMULARIO
                                // ? ============================================================
                                $result = $createCustomerUseCase->execute($customerDTO, $password);

                                $label_password = '';
                                if ($result['password'] === '************') {
                                        $label_password = 'Contraseña: <strong>';
                                } else {
                                        $label_password = 'Contraseña temporal: <strong>';
                                }

                                $_SESSION['success_message'] =
                                        '¡Registro exitoso!<br>'
                                        . 'Usuario: <strong>' . $result['username'] . '</strong><br>'
                                        . $label_password . $result['password'] . '</strong><br><br>'
                                        . 'Por favor, inicia sesión con estas credenciales y cambia tu contraseña.';

                                header('Location: ' . PathHelper::getBaseUrl() . '/login');
                                exit;
                        } catch (\Exception $e) {
                                $_SESSION['error_message'] = $e->getMessage();
                                header('Location: ' . PathHelper::getBaseUrl() . '/register');
                                exit;
                        }
                }
        }
}
