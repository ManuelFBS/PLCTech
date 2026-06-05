<?php

namespace PLCTech\Application\UseCases\User;

use PLCTech\Domain\Repositories\UserRepositoryInterface;

class DeleteUserUseCase
{
        private UserRepositoryInterface $userRepository;

        public function __construct(UserRepositoryInterface $userRepository)
        {
                $this->userRepository = $userRepository;
        }

        public function execute(int $id): array
        {
                // * Verificar si existe...
                $user = $this->userRepository->find($id);
                if (!$user) {
                        throw new \Exception('Usuario no encontrado');
                }

                // * Prevenir eliminar el último Admin (regla de negocio)...
                if ($user->isAdmin()) {
                        $allUsers = $this->userRepository->findAll();
                        $adminCount = 0;
                        foreach ($allUsers as $u) {
                                if ($u->isAdmin() && $u->isActive()) {
                                        $adminCount++;
                                }
                        }

                        if ($adminCount <= 1) {
                                throw new \Exception('No se puede eliminar el único administrador activo del sistema');
                        }
                }

                // * Eliminar...
                $this->userRepository->delete($id);

                return [
                        'success' => true,
                        'message' => 'Usuario eliminado exitosamente'
                ];
        }
}
