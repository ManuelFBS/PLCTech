<?php

namespace PLCTech\Application\UseCases\User;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Domain\Entities\User;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class UpdateUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        public function execute(int $id, UserDTO $userDTO): array
        {
                $existingUser = $this->userRepository->find($id);

                if (!$existingUser) {
                        throw new \Exception('Usuario no encontrado');
                }

                // * Validar DNI único (excluyendo el actual)...
                $userByDni = $this->userRepository->findByDni($userDTO->dni);
                if ($userByDni && $userByDni->getId() !== $id) {
                        throw new \Exception('Ya existe un usuario con ese DNI');
                }

                // * Validar username único...
                $userByUsername = $this->userRepository->findByUsername($userDTO->user);
                if ($userByUsername && $userByUsername->getId() !== $id) {
                        throw new \Exception(
                                'Ya existe un usuario con ese nombre de usuario'
                        );
                }

                // * Validar email único...
                $userByEmail = $this->userRepository->findByEmail($userDTO->email);
                if ($userByEmail && $userByEmail->getId() !== $id) {
                        throw new \Exception('Ya existe un usuario con ese email');
                }

                // * Validar reglas de negocio...
                if (
                        (
                                $userDTO->role === 'Employee' ||
                                $userDTO->role === 'Admin'
                        ) &&
                        !$userDTO->employee_id
                ) {
                        throw new \Exception(
                                'Los usuarios con rol '
                                . $userDTO->role
                                . ' deben tener un empleado asociado'
                        );
                }

                if (
                        $userDTO->role === 'Customer' &&
                        !$userDTO->customer_id
                ) {
                        throw new \Exception(
                                'Los usuarios con rol Customer deben tener un cliente asociado'
                        );
                }

                if (
                        $userDTO->role === 'Admin' &&
                        $userDTO->customer_id
                ) {
                        throw new \Exception(
                                'Los usuarios Admin no deben tener cliente asociado'
                        );
                }

                if (
                        $userDTO->role === 'Employee' &&
                        $userDTO->customer_id
                ) {
                        throw new \Exception(
                                'Los usuarios Employee no deben tener cliente asociado'
                        );
                }

                if (
                        $userDTO->role === 'Customer' &&
                        $userDTO->employee_id
                ) {
                        throw new \Exception(
                                'Los usuarios Customer no deben tener empleado asociado'
                        );
                }

                // * Determinar la contraseña a usar...
                $passwordToUse = $existingUser->getPassword();  // > Mantener la existente por defecto...

                // * Si se proporcionó una nueva contraseña (no vacía), hashearla...
                if (!empty($userDTO->password)) {
                        $passwordToUse =
                                password_hash(
                                        $userDTO->password, PASSWORD_DEFAULT
                                );
                }

                // * Crear usuario actualizado...
                $updatedUser = new User(
                        $id,
                        $userDTO->dni,
                        $userDTO->user,
                        $userDTO->email,
                        $userDTO->role,
                        $passwordToUse,  // > ← Usar la variable determinada...
                        $userDTO->is_active,
                        $userDTO->employee_id,
                        $userDTO->customer_id,
                        $existingUser->getLastLogin(),
                        $existingUser->getCreatedAt()
                );

                $this->userRepository->update($updatedUser);

                return [
                        'success' => true,
                        'message' => 'Usuario actualizado exitosamente'
                ];
        }
}
