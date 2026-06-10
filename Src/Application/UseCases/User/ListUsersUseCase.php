<?php

namespace PLCTech\Application\UseCases\User;

use PLCTech\Application\DTOs\UserDTO;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class ListUsersUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        public function execute(): array
        {
                $users = $this->userRepository->findAll();
                $usersDTO = [];

                foreach ($users as $user) {
                        $usersDTO[] = new UserDTO([
                                'id' => $user->getId(),
                                'dni' => $user->getDni(),
                                'user' => $user->getUser(),
                                'email' => $user->getEmail(),
                                'role' => $user->getRole(),
                                'is_active' => $user->isActive(),
                                'employee_id' => $user->getEmployeeId(),
                                'customer_id' => $user->getCustomerId(),
                                'last_login' => $user->getLastLogin(),
                                'created_at' => $user->getCreatedAt()
                        ]);
                }

                return $usersDTO;
        }
}
