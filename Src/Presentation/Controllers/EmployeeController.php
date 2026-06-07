<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\EmployeeDTO;
use PLCTech\Application\UseCases\Employee\CreateEmployeeUseCase;
use PLCTech\Application\UseCases\Employee\DeleteEmployeeUseCase;
use PLCTech\Application\UseCases\Employee\GetEmployeeUseCase;
use PLCTech\Application\UseCases\Employee\ListEmployeesUseCase;
use PLCTech\Application\UseCases\Employee\UpdateEmployeesUseCase;
use PLCTech\Infrastructure\Database\Repositories\MySQLEmployeeRepository;
use PLCTech\Infrastruscture\Database\Repositories\MySQLUserRepository;

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
}
