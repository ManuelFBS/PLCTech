<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Entities\Customer;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;

class UpdateCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(CustomerRepositoryInterface $customerRepository)
        {
                $this->customerRepository = $customerRepository;
        }

        public function execute(int $id, CustomerDTO $customerDTO): array
        {
                $existingCustomer = $this->customerRepository->find($id);

                if (!$existingCustomer) {
                        throw new \Exception('Cliente no encontrado');
                }

                // * Validar DNI único (excluyendo el actual)...
                $customerByDni = $this->customerRepository->findByDni($customerDTO->dni);
                if ($customerByDni && $customerByDni->getId() !== $id) {
                        throw new \Exception('Ya existe un cliente con ese DNI');
                }

                // * Validar email único (excluyendo el actual)...
                $customerByEmail = $this->customerRepository->findByEmail($customerDTO->email);
                if ($customerByEmail && $customerByEmail->getId() !== $id) {
                        throw new \Exception('Ya existe un cliente con ese email');
                }

                // * La validación de fecha de nacimiento la hace el TRIGGER de la BD...

                // * Crear cliente actualizado...
                $updatedCustomer = new Customer(
                        $id,
                        $customerDTO->dni,
                        $customerDTO->full_name,
                        $customerDTO->birthdate,
                        $customerDTO->email,
                        $customerDTO->phone_number,
                        $existingCustomer->getCreatedAt()
                );

                $this->customerRepository->update($updatedCustomer);

                return [
                        'success' => true,
                        'message' => 'Cliente actualizado exitosamente'
                ];
        }
}
