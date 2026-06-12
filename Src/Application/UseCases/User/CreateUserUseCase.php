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
                // > Validaciones básicas...
                if ($this->userRepository->findByUsername($userDTO->user)) {
                        throw new \Exception('Ya existe un usuario con ese nombre de usuario');
                }

                // > Validar según el rol...
                if ($userDTO->role === 'Admin' || $userDTO->role === 'Employee') {
                        if (!$userDTO->employee_id) {
                                throw new \Exception('No se encontró un empleado válido con los datos proporcionados');
                        }
                } elseif ($userDTO->role === 'Customer') {
                        if (!$userDTO->customer_id) {
                                throw new \Exception('No se encontró un cliente válido con los datos proporcionados');
                        }
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

                $this->userRepository->save($user);

                return [
                        'success' => true,
                        'message' => 'Usuario creado exitosamente',
                        'user_id' => $user->getId()
                ];
        }
}
