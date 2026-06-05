<?php

namespace PLCTech\Application\UseCases\Employee;

use PLCTech\Application\DTOs\EmployeeDTO;
use PLCTech\Domain\Entities\Employee;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;

class UpdateEmployeesUseCase
{
        private EmployeeRepositoryInterface $employeeRepository;

        public function __construct(
                EmployeeRepositoryInterface $employeeRepository
        ) {
                $this->employeeRepository = $employeeRepository;
        }

        public function execute(int $id, EmployeeDTO $employeeDTO): array
        {
                $employee = $this->employeeRepository->find($id);

                if (!$employee) {
                        throw new \Exception('Empleado no encontrado');
                }

                // > Actualizar propiedades...
                $updatedEmployee = new Employee(
                        $employee->getId(),
                        $employeeDTO->dni,
                        $employeeDTO->names,
                        $employeeDTO->surnames,
                        $employeeDTO->birthdate,
                        $employeeDTO->email,
                        $employeeDTO->address,
                        $employeeDTO->phone_number,
                        $employee->getCreatedAt()
                );

                $this->employeeRepository->update($updatedEmployee);

                return [
                        'success' => true,
                        'message' => 'Empleado actualizado exitosamente'
                ];
        }
}
