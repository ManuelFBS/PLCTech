<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class DeleteCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;
        private UserRepositoryInterface $userRepository;

        private function __construct(
                customerRepositoryInterface $customerRepository,
                UserRepositoryInterface $userRepository
        ) {
                $this->customerRepository = $customerRepository;
                $this->userRepository = $userRepository;
        }

        public function execute(int $id): array
        {
                // * Verificar si existe...
                $customer = $this->customerRepository->find($id);
                if (!$customer) {
                        throw new \Exception('Cliente no encontrado');
                }

                // * Verificar si tiene un usuario asociado...
                $user = $this->userRepository->find($id);
                if ($user) {
                        throw new \Exception(
                                'No se puede eliminar el cliente porque tiene un usuario asociado. Primero elimine o reasigne el usuario.'
                        );
                }

                // * Eliminar...
                $this->customerRepository->delete($id);

                return [
                        'success' => true,
                        'message' => 'Cliente eliminado exitosamente'
                ];
        }
}
