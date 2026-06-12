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

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();
                $employeeRepository = new MySQLEmployeeRepository();
                $customerRepository = new MySQLCustomerRepository();

                $this->listUsersUseCase = new ListUsersUseCase($userRepository);
                $this->createUserUseCase = new CreateUserUseCase($userRepository, $employeeRepository, $customerRepository);
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
}
