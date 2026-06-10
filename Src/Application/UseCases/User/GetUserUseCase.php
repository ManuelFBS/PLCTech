<?php

namespace PLCTech\Application\UseCases\User;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class GetUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        public function execute(int $id): ?UserDTO
        {
                $user = $this->userRepository->find($id);

                if (!$user) {
                        return null;
                }

                return new UserDTO([
                        'id' => $user->getId(),
                        'dni' => $user->getDni(),
                        'user' => $user->getUser(),
                        'email' => $user->getEmail(),
                        'role' => $user->getRole(),
                        'password' => '',  // No devolver la contraseña por seguridad
                        'is_active' => $user->isActive(),
                        'employee_id' => $user->getEmployeeId(),
                        'customer_id' => $user->getCustomerId(),
                        'last_login' => $user->getLastLogin(),
                        'created_at' => $user->getCreatedAt()
                ]);
        }
}
