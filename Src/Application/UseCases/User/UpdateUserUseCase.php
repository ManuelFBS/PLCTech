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
                $user = $this->userRepository->find($id);

                if (!$user) {
                        throw new \Exception('Usuario no encontrado');
                }

                // > Actualizar propiedades...
                $updatedUser = new User(
                        $user->getId(),
                        $userDTO->dni,
                        $userDTO->user,
                        $userDTO->email,
                        $userDTO->role,
                        !empty($userDTO->password) ? password_hash($userDTO->password, PASSWORD_DEFAULT) : $user->getPassword(),
                        $userDTO->is_active,
                        $userDTO->employee_id,
                        $userDTO->customer_id,
                        $user->getLastLogin(),
                        $user->getCreatedAt()
                );

                $this->userRepository->update($updatedUser);

                return [
                        'success' => true,
                        'message' => 'Usuario actualizado exitosamente'
                ];
        }
}
