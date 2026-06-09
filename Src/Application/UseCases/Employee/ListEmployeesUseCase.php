<?php

namespace PLCTech\Application\UseCases\Employee;

use PLCTech\Application\DTOs\EmployeeDTO;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;

class ListEmployeesUseCase
{
        private EmployeeRepositoryInterface $employeeRepository;

        public function __construct(EmployeeRepositoryInterface $employeeRepository)
        {
                $this->employeeRepository = $employeeRepository;
        }

        public function execute(): array
        {
                $employees = $this->employeeRepository->findAll();
                $employeesDTO = [];

                foreach ($employees as $employee) {
                        // > Crear un array con los datos en el orden correcto...
                        $data = [
                                'id' => $employee->getId(),
                                'dni' => $employee->getDni(),
                                'names' => $employee->getNames(),
                                'surnames' => $employee->getSurnames(),
                                'birthdate' => $employee->getBirthdate(),
                                'email' => $employee->getEmail(),
                                'address' => $employee->getAddress(),
                                'phone_number' => $employee->getPhoneNumber(),
                                'created_at' => $employee->getCreatedAt()
                        ];

                        $employeesDTO[] = new EmployeeDTO($data);
                }

                return $employeesDTO;
        }
}
