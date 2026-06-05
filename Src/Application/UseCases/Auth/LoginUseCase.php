<?php

namespace PLCTech\Application\UseCases\Auth;

use PLCTech\Domain\Repositories\UserRepositoryInterface;
use PLCTech\Infrastructure\Auth\JWTHandler;

class LoginUseCase
{
        private UserRepositoryInterface $userRepository;
        private JWTHandler $jwtHandler;

        public function __construct(
                UserRepositoryInterface $userRepository,
                JWTHandler $jwtHandler
        ) {
                $this->userRepository = $userRepository;
                $this->jwtHandler = $jwtHandler;
        }

        public function execute(string $username, string $password): ?array
        {
                // > Buscar por username o email...
                $user = $this->userRepository->findByUsername($username);
                if (!$user) {
                        $user = $this->userRepository->findByEmail($username);
                }

                if (!$user) {
                        return null;
                }

                // > Verificar si está activo...
                if (!$user->isActive()) {
                        throw new \Exception('Usuario bloqueado. Contacte al administrador.');
                }

                // > Verificar contraseña...
                if (!$user->verifyPassword($password)) {
                        return null;
                }

                // > Actualizar último login...
                $this->userRepository->updateLastLogin($user->getId(), date('Y-m-d H:i:s'));

                // > Generar token JWT...
                $token = $this->jwtHandler->generate([
                        'user_id' => $user->getId(),
                        'username' => $user->getUser(),
                        'role' => $user->getRole(),
                        'email' => $user->getEmail()
                ]);

                // > Guardar en sesión...
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUser();
                $_SESSION['role'] = $user->getRole();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['token'] = $token;

                return [
                        'user' => [
                                'id' => $user->getId(),
                                'username' => $user->getUser(),
                                'email' => $user->getEmail(),
                                'role' => $user->getRole()
                        ],
                        'token' => $token
                ];
        }
}
