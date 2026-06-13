<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;

class GetCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;

        public function __construct(CustomerRepositoryInterface $customerRepository)
        {
                $this->customerRepository = $customerRepository;
        }

        public function execute(int $id): ?CustomerDTO
        {
                $customer = $this->customerRepository->find($id);

                if (!$customer) {
                        return null;
                }

                return new CustomerDTO([
                        'id' => $customer->getId(),
                        'dni' => $customer->getDni(),
                        'full_name' => $customer->getFullName(),
                        'birthdate' => $customer->getBirthdate(),
                        'email' => $customer->getEmail(),
                        'phone_number' => $customer->getPhoneNumber(),
                        'created_at' => $customer->getCreatedAt()
                ]);
        }
}
