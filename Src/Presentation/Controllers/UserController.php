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
}
