<?php

namespace PLCTech\Application\UseCases\User;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Domain\Entities\User;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class CreateUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        public function execute(UserDTO $userDTO): array
        {
                // > Validaciones...
                if ($this->userRepository->findByDni($userDTO->dni)) {
                        throw new \Exception('Ya existe un usuario con ese DNI');
                }

                if ($this->userRepository->findByUsername($userDTO->user)) {
                        throw new \Exception('Ya existe un usuario con ese nombre de usuario');
                }

                if ($this->userRepository->findByEmail($userDTO->email)) {
                        throw new \Exception('Ya existe un usuario con ese email');
                }

                // > Validar reglas de negocio según rol...
                if ($userDTO->role === 'Employee' && !$userDTO->employee_id) {
                        throw new \Exception('Los usuarios con rol Employee deben tener un empleado asociado');
                }

                if ($userDTO->role === 'Customer' && !$userDTO->customer_id) {
                        throw new \Exception('Los usuarios con rol Customer deben tener un cliente asociado');
                }

                if ($userDTO->role === 'Admin' && ($userDTO->employee_id || $userDTO->customer_id)) {
                        throw new \Exception('Los usuarios Admin no deben tener empleado o cliente asociado');
                }

                // > Encriptar contraseña...
                $hashedPassword = password_hash($userDTO->password, PASSWORD_DEFAULT);

                // > Crear entidad...
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

                // > Guardar...
                $this->userRepository->save($user);

                return [
                        'success' => true,
                        'message' => 'Usuario creado exitosamente',
                        'user_id' => $user->getId()
                ];
        }
}
