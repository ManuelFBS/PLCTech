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
                // * Verificar si existe...
                $existingEmployee = $this->employeeRepository->find($id);

                if (!$existingEmployee) {
                        throw new \Exception('Empleado no encontrado');
                }
                // * Validar DNI único (excluyendo el actual)...
                $employeeDni = $this->employeeRepository->findByDni($employeeDTO->dni);
                if ($employeeDni && $employeeDni->getId() !== $id) {
                        throw new \Exception('Ya existe un empleado con ese DNI');
                }

                // * Validar email único (excluyendo el actual)...
                $employeeEmail = $this->employeeRepository->findByEmail($employeeDTO->email);
                if ($employeeEmail && $employeeEmail->getEmail() !== $id) {
                        throw new \Exception('Ya existe un empleado con ese email');
                }

                // * Validar edad (mínimo 18 años)...
                $birthdate = new \DateTime($employeeDTO->birthdate);
                $today = new \DateTime();
                $age = $today->diff($birthdate)->y;

                if ($age < 18) {
                        throw new \Exception('El empleado debe ser mayor de 18 años');
                }

                // * Crear entidad actualizada...
                $employee = new Employee(
                        $id,
                        $employeeDTO->dni,
                        $employeeDTO->names,
                        $employeeDTO->surnames,
                        $employeeDTO->birthdate,
                        $employeeDTO->email,
                        $employeeDTO->address,
                        $employeeDTO->phone_number,
                        $existingEmployee->getCreatedAt()
                );

                // * Actualizar...
                $this->employeeRepository->update($employee);

                return [
                        'success' => true,
                        'message' => 'Empleado actualizado exitosamente'
                ];
        }
}
