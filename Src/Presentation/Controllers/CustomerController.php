<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Application\UseCases\Customer\CreateCustomerUseCase;
use PLCTech\Application\UseCases\Customer\DeleteCustomerUseCase;
use PLCTech\Application\UseCases\Customer\GetCustomerUseCase;
use PLCTech\Application\UseCases\Customer\ListCustomersUseCase;
use PLCTech\Application\UseCases\Customer\UpdateCustomerUseCase;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class CustomerController
{
        private ListCustomersUseCase $listCustomersUseCase;
        private CreateCustomerUseCase $createCustomerUseCase;
        private GetCustomerUseCase $getCustomerUseCase;
        private UpdateCustomerUseCase $updateCustomerUseCase;
        private DeleteCustomerUseCase $deleteCustomerUseCase;
        private MySQLCustomerRepository $customerRepository;

        public function __construct()
        {
                $customerRepository = new MySQLCustomerRepository();
                $userRepository = new MySQLUserRepository();

                // > Asignar repositorio a propiedad...
                $this->customerRepository = $customerRepository;

                $this->listCustomersUseCase = new ListCustomersUseCase($customerRepository);
                $this->createCustomerUseCase = new CreateCustomerUseCase($customerRepository);
                $this->getCustomerUseCase = new GetCustomerUseCase($customerRepository);
                $this->updateCustomerUseCase = new UpdateCustomerUseCase($customerRepository);
                $this->deleteCustomerUseCase = new DeleteCustomerUseCase($customerRepository, $userRepository);
        }

        public function index(): void
        {
                try {
                        $customers = $this->listCustomersUseCase->execute();
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/customers/index.php';
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
                        require_once $viewsPath . '/customers/create.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                        exit;
                }
        }

        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                        exit;
                }

                try {
                        $customerDTO = new CustomerDTO([
                                'dni' => $_POST['dni'] ?? '',
                                'full_name' => $_POST['full_name'] ?? '',
                                'birthdate' => $_POST['birthdate'] ?? '',
                                'email' => $_POST['email'] ?? '',
                                'phone_number' => $_POST['phone_number'] ?? null
                        ]);

                        $result = $this->createCustomerUseCase->execute($customerDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                exit;
        }

        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $customerDTO = $this->getCustomerUseCase->execute((int) $id);

                        if (!$customerDTO) {
                                throw new \Exception('Cliente no encontrado');
                        }

                        $customer = $customerDTO;
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/customers/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                        exit;
                }
        }

        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                        exit;
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $customerDTO = new CustomerDTO([
                                'dni' => $_POST['dni'] ?? '',
                                'full_name' => $_POST['full_name'] ?? '',
                                'birthdate' => $_POST['birthdate'] ?? '',
                                'email' => $_POST['email'] ?? '',
                                'phone_number' => $_POST['phone_number'] ?? null
                        ]);

                        $result = $this->updateCustomerUseCase->execute((int) $id, $customerDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                exit;
        }

        public function delete(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteCustomerUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/customers');
                exit;
        }

        public function searchByDni(): void
        {
                header('Content-Type: application/json');

                try {
                        $dni = $_GET['dni'] ?? '';

                        // ! DEPURACIÓN...
                        error_log('🔍 Buscando cliente con DNI: ' . $dni);

                        if (empty($dni)) {
                                echo json_encode(['found' => false, 'message' => 'Ingrese un DNI']);
                                return;
                        }

                        $customer = $this->customerRepository->findByDni($dni);

                        // ! DEPURACIÓN...
                        error_log('📦 Cliente encontrado: ' . ($customer ? 'Sí (ID: ' . $customer->getId() . ')' : 'No'));

                        if ($customer) {
                                echo json_encode([
                                        'found' => true,
                                        'id' => $customer->getId(),
                                        'dni' => $customer->getDni(),
                                        'full_name' => $customer->getFullName(),
                                        'email' => $customer->getEmail(),
                                        'phone_number' => $customer->getPhoneNumber()
                                ]);
                        } else {
                                echo json_encode([
                                        'found' => false,
                                        'message' => 'Cliente no encontrado. ¿Desea registrarlo?',
                                        'dni' => $dni
                                ]);
                        }
                } catch (\Exception $e) {
                        error_log('❌ Error en searchByDni: ' . $e->getMessage());
                        echo json_encode(['found' => false, 'message' => 'Error: ' . $e->getMessage()]);
                }
        }
}
