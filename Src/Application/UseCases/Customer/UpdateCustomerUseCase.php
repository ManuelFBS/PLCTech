<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Entities\Customer;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;

class UpdateCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(
                CustomerRepositoryInterface $customerRepository
        ) {
                $this->customerRepository = $customerRepository;
        }

        public function execute(int $id, CustomerDTO $customerDTO): array
        {
                // * Verificar si existe...
                $existingCustomer = $this->customerRepository->find($id);
                if (!$existingCustomer) {
                        throw new \Exception('Cliente no encontrado');
                }

                // * Validar DNI único (excluyendo el actual)...
                $customerDni = $this->customerRepository->findByDni($customerDTO->dni);
                if ($customerDni && $customerDni->getId() !== $id) {
                        throw new \Exception('Ya existe un cliente con ese DNI');
                }

                // * Validar email único (excluyendo el actual)...
                $customerEmail = $this->customerRepository->findByEmail($customerDTO->email);
                if ($customerEmail && $customerEmail->getEmail() !== $id) {
                        throw new \Exception('Ya existe un cliente con ese email');
                }

                // * Validar edad (mínimo 18 años)...
                $birthdate = new \DateTime($customerDTO->birthdate);
                $today = new \DateTime();
                $age = $today->diff($birthdate)->y;

                if ($age < 18) {
                        throw new \Exception('El cliente debe ser mayor de 18 años');
                }

                // * Crear entidad actualizada...
                $customer = new Customer(
                        $id,
                        $customerDTO->dni,
                        $customerDTO->full_name,
                        $customerDTO->birthdate,
                        $customerDTO->email,
                        $customerDTO->phone_number,
                        $existingCustomer->getCreatedAt()
                );

                // * Actualizar...
                $this->customerRepository->update($customer);

                return [
                        'success' => true,
                        'message' => 'Cliente actualizado exitosamente'
                ];
        }
}
