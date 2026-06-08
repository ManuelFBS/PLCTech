<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\EmployeeDTO;
use PLCTech\Application\UseCases\Employee\CreateEmployeeUseCase;
use PLCTech\Application\UseCases\Employee\DeleteEmployeeUseCase;
use PLCTech\Application\UseCases\Employee\GetEmployeeUseCase;
use PLCTech\Application\UseCases\Employee\ListEmployeesUseCase;
use PLCTech\Application\UseCases\Employee\UpdateEmployeesUseCase;
use PLCTech\Infrastructure\Database\Repositories\MySQLEmployeeRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class EmployeeController
{
        private CreateEmployeeUseCase $createEmployeeUseCase;
        private ListEmployeesUseCase $listEmployeesUseCase;
        private GetEmployeeUseCase $getEmployeeUseCase;
        private UpdateEmployeesUseCase $updateEmployeeUseCase;
        private DeleteEmployeeUseCase $deleteEmployeeUseCase;

        public function __construct()
        {
                $employeeRepository = new MySQLEmployeeRepository();
                $userRepository = new MySQLUserRepository();

                $this->createEmployeeUseCase = new CreateEmployeeUseCase($employeeRepository);
                $this->listEmployeesUseCase = new ListEmployeesUseCase($employeeRepository);
                $this->getEmployeeUseCase = new GetEmployeeUseCase($employeeRepository);
                $this->updateEmployeeUseCase = new UpdateEmployeesUseCase($employeeRepository);
                $this->deleteEmployeeUseCase = new DeleteEmployeeUseCase($employeeRepository, $userRepository);
        }

        // * Listar todos los empleados...

        public function index(): void
        {
                try {
                        $employees = $this->listEmployeesUseCase->execute();
                        require_once __DIR__ . '/../Views/layouts/navbar.php';
                        require_once __DIR__ . '/../Views/employees/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL']);
                }
        }

        // * Mostrar formulario de creación...
        public function create(): void
        {
                require_once __DIR__ . '/../Views/layouts/navbar.php';
                require_once __DIR__ . '/../Views/employees/create.php';
        }

        // * Guardar nuevo empleado...
        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/employees');
                }

                try {
                        $employeeDTO = new EmployeeDTO([
                                'dni' => $_POST['dni'],
                                'names' => $_POST['names'],
                                'surnames' => $_POST['surnames'],
                                'birthdate' => $_POST['birthdate'],
                                'email' => $_POST['email'],
                                'address' => $_POST['address'],
                                'phone_number' => $_POST['phone_number'] ?? null
                        ]);

                        $result = $this->createEmployeeUseCase->execute($employeeDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/employees');
                exit;
        }

        // * Mostrar formulario de edición...
        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                if ($id <= 0) {
                        $_SESSION['error_message'] = 'ID de empleado inválido';
                        header('Location: ' . $_ENV['APP_URL'] . '/employees');
                        exit;
                }

                try {
                        $employee = $this->getEmployeeUseCase->execute((int) $id);

                        if (!$employee) {
                                throw new \Exception('Empleado no encontrado');
                        }

                        // ? Debug: Verificar que los datos existen
                        // ? error_log(print_r($employee, true)); // Descomentar para debug

                        require_once __DIR__ . '/../Views/layouts/navbar.php';
                        require_once __DIR__ . '/../Views/employees/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . $_ENV['APP_URL'] . '/employees');
                        exit;
                }
        }

        // * Actualizar empleado...
        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . $_ENV['APP_URL'] . '/employees');
                        exit;
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $employeeDTO = new EmployeeDTO([
                                'dni' => $_POST['dni'],
                                'names' => $_POST['names'],
                                'surnames' => $_POST['surnames'],
                                'birthdate' => $_POST['birthdate'],
                                'email' => $_POST['email'],
                                'address' => $_POST['address'],
                                'phone_number' => $_POST['phone_number'] ?? null
                        ]);

                        $result = $this->updateEmployeeUseCase->execute((int) $id, $employeeDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/employees');
                exit;
        }

        // * Eliminar empleado...
        public function delete(): void
        {
                $id = $_POST['id'] ?? $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteEmployeeUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . $_ENV['APP_URL'] . '/employees');
                exit;
        }
}
