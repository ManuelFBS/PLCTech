<?php

namespace PLCTech\Application\UseCases\Employee;

use PLCTech\Application\DTOs\EmployeeDTO;
use PLCTech\Domain\Entities\Employee;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;

class CreateEmployeeUseCase
{
        private EmployeeRepositoryInterface $employeeRepository;

        public function __construct(
                EmployeeRepositoryInterface $employeeRepository
        ) {
                $this->employeeRepository = $employeeRepository;
        }

        public function execute(EmployeeDTO $employeeDTO): array
        {
                // > Validaciones...
                if ($this->employeeRepository->findByDni($employeeDTO->dni)) {
                        throw new \Exception('Ya existe un empleado con ese DNI');
                }

                if ($this->employeeRepository->findByEmail($employeeDTO->email)) {
                        throw new \Exception('Ya existe un empleado con ese email');
                }

                // > Validar edad (mínimo 18 años)...
                $birthdate = new \DateTime($employeeDTO->birthdate);
                $today = new \DateTime();
                $age = $today->diff($birthdate)->y;

                if ($age < 18) {
                        throw new \Exception('El empleado debe ser mayor de 18 años');
                }

                // > Crear entidad...
                $employee = new Employee(
                        null,
                        $employeeDTO->dni,
                        $employeeDTO->names,
                        $employeeDTO->surnames,
                        $employeeDTO->birthdate,
                        $employeeDTO->email,
                        $employeeDTO->address,
                        $employeeDTO->phone_number,
                );

                // > Guardar...
                $employeeId = $this->employeeRepository->save($employee);

                return [
                        'success' => true,
                        'message' => 'Empleado creado exitosamente',
                        'employee_id' => $employeeId
                ];
        }
}
