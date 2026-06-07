<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Application\UseCases\User\CreateUserUseCase;
use PLCTech\Application\UseCases\User\DeleteUserUseCase;
use PLCTech\Application\UseCases\User\GetUserUseCase;
use PLCTech\Application\UseCases\User\ListUsersUseCase;
use PLCTech\Application\UseCases\User\UpdateUserUseCase;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLEmployeeRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class UserController
{
        private CreateUserUseCase $createUserUseCase;
        private ListUsersUseCase $listUsersUseCase;
        private GetUserUseCase $getUserUseCase;
        private UpdateUserUseCase $updateUserUseCase;
        private DeleteUserUseCase $deleteUserUseCase;
        private MySQLEmployeeRepository $employeeRepository;
        private MySQLCustomerRepository $customerRepository;

        public function __construct()
        {
                $userRepository = new MySQLUserRepository();

                $this->createUserUseCase = new CreateUserUseCase($userRepository);
                $this->listUsersUseCase = new ListUsersUseCase($userRepository);
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
                        require_once __DIR__ . '/../Views/layouts/navbar.php';
                        require_once __DIR__ . '/../Views/users/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL']);
                }
        }

        public function create(): void
        {
                $employees = $this->employeeRepository->findAll();
                $customers = $this->customerRepository->findAll();

                require_once __DIR__ . '/../Views/layouts/navbar.php';
                require_once __DIR__ . '/../Views/users/create.php';
        }

        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/users');
                        exit;
                }

                try {
                        $userDTO = new UserDTO([
                                'dni' => $_POST['dni'],
                                'user' => $_POST['user'],
                                'email' => $_POST['email'],
                                'role' => $_POST['role'],
                                'password' => $_POST['password'],
                                'is_active' => isset($_POST['is_active'])
                                        ? (bool) $_POST['is_active']
                                        : true,
                                'employee_id' => !empty($_POST['employee_id'])
                                        ? (int) $_POST['employee_id']
                                        : null,
                                'customer_id' => !empty($_POST['customer_id'])
                                        ? (int) $_POST['customer_id']
                                        : null
                        ]);

                        $result = $this->createUserUseCase->execute($userDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/users');
                exit;
        }

        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $user = $this->getUserUseCase->execute((int) $id);

                        if (!$user) {
                                throw new \Exception('Usuario no encontrado');
                        }

                        $employees = $this->employeeRepository->findAll();
                        $customers = $this->customerRepository->findAll();

                        require_once __DIR__ . '/../Views/layouts/navbar.php';
                        require_once __DIR__ . '/../Views/users/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL'] . '/users');
                }
        }

        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/users');
                        exit;
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $userDTO = new UserDTO([
                                'dni' => $_POST['dni'],
                                'user' => $_POST['user'],
                                'email' => $_POST['email'],
                                'role' => $_POST['role'],
                                'password' => $_POST['password'] ?? '',
                                'is_active' => isset($_POST['is_active']) ? (bool) $_POST['is_active'] : true,
                                'employee_id' => !empty($_POST['employee_id']) ? (int) $_POST['employee_id'] : null,
                                'customer_id' => !empty($_POST['customer_id']) ? (int) $_POST['customer_id'] : null
                        ]);

                        $result = $this->updateUserUseCase->execute((int) $id, $userDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/users');
                exit;
        }

        public function delete(): void
        {
                $id = $_POST['id'] ?? $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteUserUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/users');
                exit;
        }
}
