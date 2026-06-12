<?php

namespace PLCTech\Application\UseCases\User;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Domain\Entities\User;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class CreateUserUseCase
{
        private UserRepositoryInterface $userRepository;
        private EmployeeRepositoryInterface $employeeRepository;
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(
                UserRepositoryInterface $userRepository,
                EmployeeRepositoryInterface $employeeRepository,
                CustomerRepositoryInterface $customerRepository
        ) {
                $this->userRepository = $userRepository;
                $this->employeeRepository = $employeeRepository;
                $this->customerRepository = $customerRepository;
        }

        public function execute(UserDTO $userDTO): array
        {
                // 1. Validar que el nombre de usuario no exista
                if ($this->userRepository->findByUsername($userDTO->user)) {
                        throw new \Exception('El nombre de usuario ya está en uso');
                }

                // 2. Buscar por DNI según el rol
                if ($userDTO->role === 'Admin' || $userDTO->role === 'Employee') {
                        $employee = $this->employeeRepository->findByDni($userDTO->dni);
                        if (!$employee) {
                                throw new \Exception('No existe un EMPLEADO con el DNI: ' . $userDTO->dni);
                        }

                        // Verificar que el email coincida
                        if ($employee->getEmail() !== $userDTO->email) {
                                throw new \Exception('El email no coincide con el registrado para el empleado. Email correcto: ' . $employee->getEmail());
                        }

                        $userDTO->employee_id = $employee->getId();
                } elseif ($userDTO->role === 'Customer') {
                        $customer = $this->customerRepository->findByDni($userDTO->dni);
                        if (!$customer) {
                                throw new \Exception('No existe un CLIENTE con el DNI: ' . $userDTO->dni);
                        }

                        // Verificar que el email coincida
                        if ($customer->getEmail() !== $userDTO->email) {
                                throw new \Exception('El email no coincide con el registrado para el cliente. Email correcto: ' . $customer->getEmail());
                        }

                        $userDTO->customer_id = $customer->getId();
                }

                // 3. Verificar que el email no exista ya en users
                if ($this->userRepository->findByEmail($userDTO->email)) {
                        throw new \Exception('El email ya está registrado en el sistema');
                }

                // 4. Verificar que el DNI no exista ya en users
                if ($this->userRepository->findByDni($userDTO->dni)) {
                        throw new \Exception('El DNI ya está registrado en el sistema');
                }

                // 5. Crear el usuario
                $hashedPassword = password_hash($userDTO->password, PASSWORD_DEFAULT);

                $user = new User(
                        null,
                        $userDTO->dni,
                        $userDTO->user,
                        $userDTO->email,
                        $userDTO->role,
                        $hashedPassword,
                        $userDTO->is_active,
                        $userDTO->employee_id,
                        $userDTO->customer_id
                );

                $this->userRepository->save($user);

                return [
                        'success' => true,
                        'message' => 'Usuario creado exitosamente',
                        'user_id' => $user->getId()
                ];
        }
}
