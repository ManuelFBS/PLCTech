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
                // > Verificar si existe...
                $existingUser = $this->userRepository->find($id);
                if (!$existingUser) {
                        throw new \Exception('Usuario no encontrado');
                }

                // > Validar DNI único...
                $userByDni = $this->userRepository->findByDni($userDTO->dni);
                if ($userByDni && $userByDni->getId() !== $id) {
                        throw new \Exception('Ya existe un usuario con ese DNI');
                }

                // > Validar email único...
                $userByEmail = $this->userRepository->findByEmail($userDTO->email);
                if ($userByEmail && $userByEmail->getId() !== $id) {
                        throw new \Exception('Ya existe un usuario con ese email');
                }

                // > Actualizar propiedades...
                $user = new User(
                        $id,
                        $userDTO->dni,
                        $userDTO->user,
                        $userDTO->email,
                        $userDTO->role,
                        !empty($userDTO->password)
                                ? password_hash($userDTO->password, PASSWORD_DEFAULT)
                                : $existingUser->getPassword(),
                        $userDTO->is_active,
                        $userDTO->employee_id,
                        $userDTO->customer_id,
                        $existingUser->getLastLogin(),
                        $existingUser->getUpdatedAt()
                );

                // > Actualizar...
                $this->userRepository->update($user);

                return [
                        'success' => true,
                        'message' => 'Usuario actualizado exitosamente'
                ];
        }
}
