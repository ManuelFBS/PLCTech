<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Application\UseCases\Customer\CreateCustomerUseCase;
use PLCTech\Application\UseCases\Customer\DeleteCustomerUseCase;
use PLCTech\Application\UseCases\Customer\GetCustomerUseCase;
use PLCTech\Application\UseCases\Customer\ListCustomersUseCase;
use PLCTech\Application\UseCases\Customer\UpdateCustomerUseCase;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class CustomerController
{
        private CreateCustomerUseCase $createCustomerUseCase;
        private ListCustomersUseCase $listCustomerUseCase;
        private GetCustomerUseCase $getCustomerUseCase;
        private UpdateCustomerUseCase $updateCustomerUseCase;
        private DeleteCustomerUseCase $deleteCustomerUseCase;

        public function __construct()
        {
                $customerRepository = new MySQLCustomerRepository();
                $userRepository = new MySQLUserRepository();

                $this->createCustomerUseCase =
                        new CreateCustomerUseCase($customerRepository);
                $this->listCustomerUseCase =
                        new ListCustomersUseCase($customerRepository);
                $this->getCustomerUseCase =
                        new GetCustomerUseCase($customerRepository);
                $this->updateCustomerUseCase =
                        new UpdateCustomerUseCase($customerRepository);
                $this->deleteCustomerUseCase =
                        new DeleteCustomerUseCase($customerRepository, $userRepository);
        }

        // * Listar todos los clientes...
        public function index(): void
        {
                try {
                        $users = $this->listCustomerUseCase->execute();
                        require_once __DIR__ . '/../Views/layouts/navbar.php';
                        require_once __DIR__ . '/../Views/customers/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL']);
                }
        }

        // * Mostrar formulario de creación...
        public function create(): void
        {
                require_once __DIR__ . '/../Views/layouts/navbar.php';
                require_once __DIR__ . '/../Views/customers/index.php';
        }
}
