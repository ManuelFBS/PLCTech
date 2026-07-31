<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Application\UseCases\User\CreateUserUseCase;
use PLCTech\Application\UseCases\User\DeleteUserUseCase;
use PLCTech\Application\UseCases\User\GetUserUseCase;
use PLCTech\Application\UseCases\User\ListUsersUseCase;
use PLCTech\Application\UseCases\User\UpdateUserUseCase;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLEmployeeRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class UserController
{
        private ListUsersUseCase $listUsersUseCase;
        private CreateUserUseCase $createUserUseCase;
        private GetUserUseCase $getUserUseCase;
        private UpdateUserUseCase $updateUserUseCase;
        private DeleteUserUseCase $deleteUserUseCase;
        private MySQLUserRepository $userRepository;
        private MySQLEmployeeRepository $employeeRepository;
        private MySQLCustomerRepository $customerRepository;

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();
                $employeeRepository = new MySQLEmployeeRepository();
                $customerRepository = new MySQLCustomerRepository();

                // > Asignar a propiedades...
                $this->userRepository = $userRepository;
                $this->employeeRepository = $employeeRepository;
                $this->customerRepository = $customerRepository;

                $this->listUsersUseCase = new ListUsersUseCase($userRepository);
                $this->createUserUseCase =
                        new CreateUserUseCase(
                                $userRepository,
                                $employeeRepository,
                                $customerRepository
                        );
                $this->getUserUseCase = new GetUserUseCase($userRepository);
                $this->updateUserUseCase = new UpdateUserUseCase($userRepository);
                $this->deleteUserUseCase = new DeleteUserUseCase($userRepository);
        }

        public function index(): void
        {
                try {
                        $users = $this->listUsersUseCase->execute();
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/users/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl());
                }
        }

        public function create(): void
        {
                try {
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/users/create.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/users');
                        exit;
                }
        }

        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/users');
                        exit;
                }

                try {
                        $userDTO = new UserDTO([
                                'dni' => $_POST['dni'] ?? '',
                                'user' => $_POST['user'] ?? '',
                                'email' => $_POST['email'] ?? '',
                                'role' => $_POST['role'] ?? '',
                                'password' => $_POST['password'] ?? '',
                                'is_active' => isset($_POST['is_active']) ? true : false,
                                'employee_id' => null,
                                'customer_id' => null
                        ]);

                        $result = $this->createUserUseCase->execute($userDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/users');
                exit;
        }

        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $userDTO = $this->getUserUseCase->execute((int) $id);

                        if (!$userDTO) {
                                throw new \Exception('Usuario no encontrado');
                        }

                        $user = $userDTO;
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/users/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/users');
                        exit;
                }
        }

        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/users');
                        exit;
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $userDTO = new UserDTO([
                                'dni' => $_POST['dni'] ?? '',
                                'user' => $_POST['user'] ?? '',
                                'email' => $_POST['email'] ?? '',
                                'role' => $_POST['role'] ?? '',
                                'password' => $_POST['password'] ?? '',
                                'is_active' => isset($_POST['is_active']) ? true : false,
                                'employee_id' => null,
                                'customer_id' => null
                        ]);

                        $result = $this->updateUserUseCase->execute((int) $id, $userDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/users');
                exit;
        }

        public function delete(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteUserUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/users');
                exit;
        }

        // * ============================================================
        // * MI CUENTA - Ver perfil
        // * ============================================================
        public function profile(): void
        {
                try {
                        $viewsPath = PathHelper::getViewsPath();
                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/users/profile.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl());
                }
        }

        // * ============================================================
        // * CAMBIAR NOMBRE DE USUARIO
        // * ============================================================
        public function updateUsername(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/profile');
                        exit;
                }

                try {
                        $userId = $_SESSION['user_id'] ?? 0;
                        $newUsername = trim($_POST['new_username'] ?? '');
                        $currentPassword = $_POST['current_password'] ?? '';

                        if (empty($newUsername)) {
                                throw new \Exception('El nuevo nombre de usuario no puede estar vacío');
                        }

                        if (strlen($newUsername) < 3) {
                                throw new \Exception('El nombre de usuario debe tener al menos 3 caracteres');
                        }

                        // > Verificar que el usuario existe...
                        $user = $this->userRepository->find($userId);
                        if (!$user) {
                                throw new \Exception('Usuario no encontrado');
                        }

                        // > Verificar contraseña actual...
                        $passwordVerified = $user->verifyPassword($currentPassword);

                        if (!$passwordVerified) {
                                throw new \Exception('Contraseña actual incorrecta');
                        }

                        // > Verificar que el nuevo username no esté en uso...
                        $existingUser = $this->userRepository->findByUsername($newUsername);
                        if ($existingUser && $existingUser->getId() !== $userId) {
                                throw new \Exception('El nombre de usuario ya está en uso');
                        }

                        // > Actualizar username...
                        $userDTO = new UserDTO([
                                'dni' => $user->getDni(),
                                'user' => $newUsername,
                                'email' => $user->getEmail(),
                                'role' => $user->getRole(),
                                'password' => $user->getPassword(),  // ? Mantener la misma contraseña
                                'is_active' => $user->isActive(),
                                'employee_id' => $user->getEmployeeId(),
                                'customer_id' => $user->getCustomerId()
                        ]);

                        $this->updateUserUseCase->execute($userId, $userDTO);

                        // > Actualizar sesión...
                        $_SESSION['username'] = $newUsername;

                        $_SESSION['success_message'] = 'Nombre de usuario actualizado exitosamente';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/profile');
                exit;
        }

        // * ============================================================
        // * CAMBIAR CONTRASEÑA
        // * ============================================================
        public function updatePassword(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/profile');
                        exit;
                }

                try {
                        $userId = $_SESSION['user_id'] ?? 0;
                        $currentPassword = $_POST['current_password'] ?? '';
                        $newPassword = $_POST['new_password'] ?? '';
                        $confirmPassword = $_POST['confirm_password'] ?? '';

                        if (empty($newPassword)) {
                                throw new \Exception('La nueva contraseña no puede estar vacía');
                        }

                        if (strlen($newPassword) < 8) {
                                throw new \Exception('La contraseña debe tener al menos 8 caracteres');
                        }

                        if ($newPassword !== $confirmPassword) {
                                throw new \Exception('Las contraseñas no coinciden');
                        }

                        // > Verificar que el usuario existe...
                        $user = $this->userRepository->find($userId);
                        if (!$user) {
                                throw new \Exception('Usuario no encontrado');
                        }

                        // > Verificar contraseña actual...
                        $passwordVerified = $user->verifyPassword($currentPassword);

                        if (!$passwordVerified) {
                                throw new \Exception('Contraseña actual incorrecta');
                        }

                        // > Actualizar contraseña...
                        $userDTO = new UserDTO([
                                'dni' => $user->getDni(),
                                'user' => $user->getUser(),
                                'email' => $user->getEmail(),
                                'role' => $user->getRole(),
                                'password' => $newPassword,  // ? Se hasheará en el UseCase
                                'is_active' => $user->isActive(),
                                'employee_id' => $user->getEmployeeId(),
                                'customer_id' => $user->getCustomerId()
                        ]);

                        $this->updateUserUseCase->execute($userId, $userDTO);

                        $_SESSION['success_message'] = 'Contraseña actualizada exitosamente';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/profile');
                exit;
        }
}
