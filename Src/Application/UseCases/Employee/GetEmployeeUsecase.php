<?php

namespace PLCTech\Application\UseCases\Employee;

use PLCTech\Application\DTOs\EmployeeDTO;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;

class GetEmployeeUseCase
{
        private EmployeeRepositoryInterface $employeeRepository;

        public function __construct(EmployeeRepositoryInterface $employeeRepository)
        {
                $this->employeeRepository = $employeeRepository;
        }

        public function execute(int $id): ?EmployeeDTO
        {
                $employee = $this->employeeRepository->find($id);

                if (!$employee) {
                        return null;
                }

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

                return new EmployeeDTO($data);
        }
}
