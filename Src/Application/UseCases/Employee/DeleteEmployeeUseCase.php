<?php

namespace PLCTech\Application\UseCases\Employee;

use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class DeleteEmployeeUseCase
{
        private EmployeeRepositoryInterface $employeeRepository;
        private UserRepositoryInterface $userRepository;

        private function __construct(
                EmployeeRepositoryInterface $employeeRepository,
                UserRepositoryInterface $userRepository
        ) {
                $this->employeeRepository = $employeeRepository;
                $this->userRepository = $userRepository;
        }

        public function execute(int $id): array
        {
                // * Verificar si existe...
                $employee = $this->employeeRepository->find($id);
                if (!$employee) {
                        throw new \Exception('Empleado no encontrado');
                }

                // * Verificar si tiene un usuario asociado...
                $user = $this->userRepository->find($id);
                if ($user) {
                        throw new \Exception(
                                'No se puede eliminar el empleado porque tiene un usuario asociado. Primero elimine o reasigne el usuario.'
                        );
                }

                // * Eliminar...
                $this->employeeRepository->delete($id);

                return [
                        'success' => true,
                        'message' => 'Empleado eliminado exitosamente'
                ];
        }
}
