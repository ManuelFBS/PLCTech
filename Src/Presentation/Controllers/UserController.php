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
        private MySQLEmployeeRepository $employeeRepository;
        private MySQLCustomerRepository $customerRepository;

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();

                $this->listUsersUseCase = new ListUsersUseCase($userRepository);
                $this->createUserUseCase = new CreateUserUseCase($userRepository);
                $this->getUserUseCase = new GetUserUseCase($userRepository);
                $this->updateUserUseCase = new UpdateUserUseCase($userRepository);
                $this->deleteUserUseCase = new DeleteUserUseCase($userRepository);
                $this->employeeRepository = new MySQLEmployeeRepository();
                $this->customerRepository = new MySQLCustomerRepository();
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
                        // * Obtener todos los empleados y clientes...
                        $employees = $this->employeeRepository->findAll();
                        $customers = $this->customerRepository->findAll();

                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/users/create.php';
                } catch (\Exception $e) {
                        echo 'ERROR: ' . $e->getMessage() . '<br>';
                        echo 'Archivo: ' . $e->getFile() . '<br>';
                        echo 'Línea: ' . $e->getLine() . '<br>';
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
                                'employee_id' => !empty($_POST['employee_id']) ? (int) $_POST['employee_id'] : null,
                                'customer_id' => !empty($_POST['customer_id']) ? (int) $_POST['customer_id'] : null
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
                        // * Obtener el usuario...
                        $userDTO = $this->getUserUseCase->execute((int) $id);

                        if (!$userDTO) {
                                throw new \Exception('Usuario no encontrado');
                        }

                        // * Obtener empleados y clientes para los selects...
                        $employees = $this->employeeRepository->findAll();
                        $customers = $this->customerRepository->findAll();

                        // * Pasar las variables a la vista...
                        $user = $userDTO;  // > Renombrar para la vista...

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
                                'employee_id' => !empty($_POST['employee_id']) ? (int) $_POST['employee_id'] : null,
                                'customer_id' => !empty($_POST['customer_id']) ? (int) $_POST['customer_id'] : null
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
}
