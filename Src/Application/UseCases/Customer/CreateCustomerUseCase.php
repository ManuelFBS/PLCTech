<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Entities\Customer;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;

class CreateCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(CustomerRepositoryInterface $customerRepository)
        {
                $this->customerRepository = $customerRepository;
        }

        public function execute(CustomerDTO $customerDTO): array
        {
                // * Validar DNI único...
                if ($this->customerRepository->findByDni($customerDTO->dni)) {
                        throw new \Exception('Ya existe un cliente con ese DNI');
                }

                // * Validar email único...
                if ($this->customerRepository->findByEmail($customerDTO->email)) {
                        throw new \Exception('Ya existe un cliente con ese email');
                }

                // * La validación de fecha de nacimiento la hace el TRIGGER de la BD...

                // * Crear entidad...
                $customer = new Customer(
                        null,
                        $customerDTO->dni,
                        $customerDTO->full_name,
                        $customerDTO->birthdate,
                        $customerDTO->email,
                        $customerDTO->phone_number
                );

                // * Guardar...
                $customerId = $this->customerRepository->save($customer);

                return [
                        'success' => true,
                        'message' => 'Cliente creado exitosamente',
                        'customer_id' => $customerId
                ];
        }
}
