<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Entities\Customer;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;

class CreateCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(
                CustomerRepositoryInterface $customerRepository
        ) {
                $this->customerRepository = $customerRepository;
        }

        public function execute(CustomerDTO $customerDTO): array
        {
                // * Validaciones...
                if ($this->customerRepository->findByDni($customerDTO->dni)) {
                        throw new \Exception('Ya existe un cliente con ese DNI');
                }

                if ($this->customerRepository->findByEmail($customerDTO->email)) {
                        throw new \Exception('Ya existe un cliente con ese email');
                }

                // * Validar edad (mínimo 18 años)...
                $birthdate = new \DateTime($customerDTO->birthdate);
                $today = new \DateTime();
                $age = $today->diff($birthdate)->y;

                if ($age < 18) {
                        throw new \Exception('El cliente debe ser mayor de 18 años');
                }

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
                        'employee_id' => $customerId
                ];
        }
}
