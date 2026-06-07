<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Application\UseCases\Customer\CreateCustomerUseCase;
use PLCTech\Application\UseCases\Customer\DeleteCustomerUseCase;
use PLCTech\Application\UseCases\Customer\GetCustomerUseCase;
use PLCTech\Application\UseCases\Customer\ListCustomersUseCase;
use PLCTech\Application\UseCases\Customer\UpdateCustomerUseCase;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLUserRepository;

class CustomerController
{
        private CreateCustomerUseCase $createCustomerUseCase;
        private ListCustomersUseCase $listCustomerUseCase;
        private GetCustomerUseCase $getCustomerUseCase;
        private UpdateCustomerUseCase $updateCustomerUseCase;
        private DeleteCustomerUseCase $deleteCustomerUseCase;

        public function __construct()
        {
                $customerRepository = new MySQLCustomerRepository();
                $userRepository = new MySQLUserRepository();
        }
}
