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

        // * Guardar nuevo cliente...
        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/customers');
                }

                try {
                        $customerDTO = new CustomerDTO([
                                'dni' => $_POST['dni'],
                                'full_name' => $_POST['full_name'],
                                'birthdate' => $_POST['birthdate'],
                                'email' => $_POST['email'],
                                'phone_number' => $_POST['phone_number'] ?? null
                        ]);

                        $result = $this->createCustomerUseCase->execute($customerDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/customers');
                exit;
        }

        // * Mostrar formulario de edición...
        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $customer = $this->getCustomerUseCase->execute((int) $id);

                        if (!$customer) {
                                throw new \Exception('Cliente no encontrado');
                        }

                        require_once __DIR__ . '/../Views/layouts/navbar.php';
                        require_once __DIR__ . '/../Views/customers/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL'] . '/customers');
                }
        }

        // * Actualizar cliente...
        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/customers');
                        exit;
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $customerDTO = new CustomerDTO([
                                'dni' => $_POST['dni'],
                                'full_name' => $_POST['full_name'],
                                'birthdate' => $_POST['birthdate'],
                                'email' => $_POST['email'],
                                'phone_number' => $_POST['phone_number'] ?? null
                        ]);

                        $result = $this->updateCustomerUseCase->execute((int) $id, $customerDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/customers');
                exit;
        }

        // * Eliminar cliente...
        public function delete(): void
        {
                $id = $_POST['id'] ?? $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteCustomerUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/customers');
                exit;
        }
}
