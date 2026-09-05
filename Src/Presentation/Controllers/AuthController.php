<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Application\UseCases\Auth\LoginUseCase;
use PLCTech\Application\UseCases\Auth\LogoutUseCase;
use PLCTech\Application\UseCases\User\UpdateUserUseCase;
use PLCTech\Helpers\ActivityHelper;
use PLCTech\Helpers\PasswordHelper;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Auth\JWTHandler;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLEmployeeRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class AuthController
{
        private LoginUseCase $loginUseCase;
        private LogoutUseCase $logoutUseCase;
        private MySQLUserRepository $userRepository;
        private UpdateUserUseCase $updateUserUseCase;

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();
                $employeeRepository = new MySQLEmployeeRepository();
                $customerRepository = new MySQLCustomerRepository();
                $jwtHandler = new JWTHandler();

                // > ============================================================
                // > ASIGNAR A PROPIEDADES
                // > ============================================================
                $this->userRepository = $userRepository;  // ← AGREGAR ESTO
                $this->updateUserUseCase = new UpdateUserUseCase($userRepository);  // ← AGREGAR ESTO

                $this->loginUseCase = new LoginUseCase(
                        $userRepository,
                        $employeeRepository,
                        $customerRepository,
                        $jwtHandler
                );

                $this->logoutUseCase = new LogoutUseCase();
        }

        // * ============================================================
        // * MOSTRAR LOGIN
        // * ============================================================
        public function showLogin(): void
        {
                // > Si ya está logueado, redirigir al home...
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

                require_once __DIR__ . '/../Views/auth/login.php';
        }

        // * ============================================================
        // * PROCESAR LOGIN
        // * ============================================================
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
                                // > Registrar actividad...
                                ActivityHelper::log(
                                        'login',
                                        'Inicio de sesión exitoso desde IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown')
                                );

                                $_SESSION['success_message'] = '¡Bienvenido ' . $result['user']['full_name'] . '!';
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

        // * ============================================================
        // * CERRAR SESIÓN
        // * ============================================================
        public function logout(): void
        {
                // > Registrar actividad...
                ActivityHelper::log(
                        'logout',
                        'Cierre de sesión'
                );

                $this->logoutUseCase->execute();
                $_SESSION['success_message'] = 'Has cerrado sesión correctamente';
                header('Location: ' . PathHelper::getBaseUrl() . '/');
                exit;
        }

        // * ============================================================
        // * REGISTRO DE NUEVO CLIENTE (GET y POST)
        // * ============================================================
        public function register(): void
        {
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

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

                                // > Registrar actividad...
                                ActivityHelper::log(
                                        'register',
                                        'Nuevo cliente registrado: ' . $fullName . ' (Usuario: ' . $result['username'] . ')'
                                );

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

        // * ============================================================
        // * MOSTRAR FORMULARIO DE RECUPERACIÓN DE CONTRASEÑA
        // * ============================================================
        public function showForgotPassword(): void
        {
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

                require_once __DIR__ . '/../Views/auth/forgot-password.php';
        }

        // * ============================================================
        // * PROCESAR SOLICITUD DE RECUPERACIÓN
        // * ============================================================
        public function forgotPassword(): void
        {
                // ============================================================
                // MÉTODO GET: Mostrar el formulario
                // ============================================================
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        // Si ya está logueado, redirigir al dashboard
                        if (isset($_SESSION['user_id'])) {
                                header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                                exit;
                        }
                        require_once __DIR__ . '/../Views/auth/forgot-password.php';
                        return;
                }

                // ============================================================
                // MÉTODO POST: Procesar la solicitud
                // ============================================================
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        try {
                                $email = trim($_POST['email'] ?? '');

                                if (empty($email)) {
                                        throw new \Exception('El email es obligatorio');
                                }

                                // Verificar que userRepository existe
                                if (!isset($this->userRepository)) {
                                        throw new \Exception('Error interno: repositorio no disponible');
                                }

                                $user = $this->userRepository->findByEmail($email);

                                if (!$user) {
                                        $_SESSION['success_message'] = 'Si el email existe en nuestro sistema, recibirás un enlace de recuperación.';
                                        header('Location: ' . PathHelper::getBaseUrl() . '/login');
                                        exit;
                                }

                                // ============================================================
                                // GENERAR ENLACE
                                // ============================================================
                                $token = bin2hex(random_bytes(16));
                                $resetLink = PathHelper::getBaseUrl() . '/reset-password?token=' . $token . '&user_id=' . $user->getId();

                                // Guardar en log
                                $logDir = 'C:/xampp/htdocs/Projects/PLCTech/logs/';
                                if (!is_dir($logDir))
                                        mkdir($logDir, 0777, true);

                                $logFile = $logDir . 'emails.log';
                                $logEntry = "========================================\n";
                                $logEntry .= 'FECHA: ' . date('Y-m-d H:i:s') . "\n";
                                $logEntry .= "TIPO: Recuperación de contraseña\n";
                                $logEntry .= 'EMAIL: ' . $email . "\n";
                                $logEntry .= 'USUARIO: ' . $user->getUser() . "\n";
                                $logEntry .= "ENLACE: $resetLink\n";
                                $logEntry .= "========================================\n\n";
                                file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

                                // ============================================================
                                // MOSTRAR ENLACE (NO REDIRIGIR)
                                // ============================================================
                                echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Enlace de recuperación</title>
                <link rel='stylesheet' href='" . PathHelper::getBaseUrl() . "/CSS/bulma.min.css'>
                <style>
                    body { background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
                    .box { max-width: 600px; padding: 30px; }
                </style>
            </head>
            <body>
                <div class='box'>
                    <h3 class='title is-4 has-text-success'>📧 Enlace generado</h3>
                    <p>El enlace de recuperación se ha generado correctamente para: <strong>" . htmlspecialchars($email) . "</strong></p>
                    <div class='notification is-info is-light'>
                        <strong>🔗 Enlace:</strong><br>
                        <a href='$resetLink' target='_blank'>$resetLink</a>
                    </div>
                    <p class='is-size-7 has-text-grey'>
                        <i class='fas fa-info-circle'></i>
                        Este enlace se ha guardado en: <code>logs/emails.log</code>
                    </p>
                    <hr>
                    <a href='" . PathHelper::getBaseUrl() . "/login' class='button is-primary'>
                        <i class='fas fa-arrow-left'></i> Volver al login
                    </a>
                </div>
            </body>
            </html>";
                                exit;
                        } catch (\Exception $e) {
                                $_SESSION['error_message'] = $e->getMessage();
                                header('Location: ' . PathHelper::getBaseUrl() . '/forgot-password');
                                exit;
                        }
                }
        }

        // * ============================================================
        // * MOSTRAR FORMULARIO DE RESTABLECER CONTRASEÑA
        // * ============================================================
        public function showResetPassword(): void
        {
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

                $token = $_GET['token'] ?? '';
                $userId = $_GET['user_id'] ?? '';

                if (empty($token) || empty($userId)) {
                        $_SESSION['error_message'] = 'Enlace inválido o expirado';
                        header('Location: ' . PathHelper::getBaseUrl() . '/login');
                        exit;
                }

                require_once __DIR__ . '/../Views/auth/reset-password.php';
        }

        // * ============================================================
        // * PROCESAR RESTABLECER CONTRASEÑA
        // * ============================================================
        public function resetPassword(): void
        {
                // ============================================================
                // MÉTODO GET: Mostrar el formulario de restablecimiento
                // ============================================================
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        // Si ya está logueado, redirigir al dashboard
                        if (isset($_SESSION['user_id'])) {
                                header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                                exit;
                        }

                        $token = $_GET['token'] ?? '';
                        $userId = $_GET['user_id'] ?? '';

                        if (empty($token) || empty($userId)) {
                                $_SESSION['error_message'] = 'Enlace inválido o expirado';
                                header('Location: ' . PathHelper::getBaseUrl() . '/login');
                                exit;
                        }

                        // Mostrar el formulario
                        require_once __DIR__ . '/../Views/auth/reset-password.php';
                        return;
                }

                // ============================================================
                // MÉTODO POST: Procesar el restablecimiento
                // ============================================================
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        try {
                                $token = $_POST['token'] ?? '';
                                $userId = (int) ($_POST['user_id'] ?? 0);
                                $newPassword = $_POST['new_password'] ?? '';
                                $confirmPassword = $_POST['confirm_password'] ?? '';

                                if (empty($token) || empty($userId)) {
                                        throw new \Exception('Enlace inválido');
                                }

                                if (empty($newPassword)) {
                                        throw new \Exception('La nueva contraseña es obligatoria');
                                }

                                if (strlen($newPassword) < 8) {
                                        throw new \Exception('La contraseña debe tener al menos 8 caracteres');
                                }

                                if ($newPassword !== $confirmPassword) {
                                        throw new \Exception('Las contraseñas no coinciden');
                                }

                                // Verificar fortaleza
                                $strength = \PLCTech\Helpers\PasswordHelper::validateStrength($newPassword);
                                if (!$strength['valid']) {
                                        throw new \Exception('Contraseña débil: ' . implode(', ', $strength['messages']));
                                }

                                // Verificar que el usuario existe
                                if (!isset($this->userRepository)) {
                                        throw new \Exception('Error interno: repositorio no disponible');
                                }

                                $user = $this->userRepository->find($userId);
                                if (!$user) {
                                        throw new \Exception('Usuario no encontrado');
                                }

                                // Actualizar contraseña
                                $userDTO = new UserDTO([
                                        'dni' => $user->getDni(),
                                        'user' => $user->getUser(),
                                        'email' => $user->getEmail(),
                                        'role' => $user->getRole(),
                                        'password' => $newPassword,
                                        'is_active' => $user->isActive(),
                                        'employee_id' => $user->getEmployeeId(),
                                        'customer_id' => $user->getCustomerId()
                                ]);

                                $this->updateUserUseCase->execute($userId, $userDTO);

                                // Registrar actividad
                                ActivityHelper::log(
                                        'reset_password',
                                        'Contraseña restablecida para el usuario: ' . $user->getUser()
                                );

                                $_SESSION['success_message'] = 'Contraseña restablecida exitosamente. Ahora puedes iniciar sesión.';
                                header('Location: ' . PathHelper::getBaseUrl() . '/login');
                                exit;
                        } catch (\Exception $e) {
                                $_SESSION['error_message'] = $e->getMessage();
                                header('Location: ' . PathHelper::getBaseUrl() . '/reset-password?token=' . ($_POST['token'] ?? '') . '&user_id=' . ($_POST['user_id'] ?? ''));
                                exit;
                        }
                }
        }
}
