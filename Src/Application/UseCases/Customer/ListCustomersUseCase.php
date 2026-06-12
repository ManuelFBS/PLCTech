<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;

class ListCustomersUseCase
{
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(
                CustomerRepositoryInterface $customerRepository
        ) {
                $this->customerRepository = $customerRepository;
        }

        public function execute(): array
        {
                $customers = $this->customerRepository->findAll();
                $customersDTO = [];

                foreach ($customers as $customer) {
                        $data = [
                                'id' => $customer->getId(),
                                'dni' => $customer->getDni(),
                                'full_name' => $customer->getFullName(),
                                'birthdate' => $customer->getBirthdate(),
                                'email' => $customer->getEmail(),
                                'phone_number' => $customer->getPhoneNumber(),
                                'created_at' => $customer->getCreatedAt()
                        ];

                        $customersDTO[] = new CustomerDTO($data);
                }

                return $customersDTO;
        }
}
