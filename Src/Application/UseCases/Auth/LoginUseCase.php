<?php

namespace PLCTech\Application\UseCases\Auth;

use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;
use PLCTech\Domain\Repositories\UserRepositoryInterface;
use PLCTech\Infrastructure\Auth\JWTHandler;

class LoginUseCase
{
        private UserRepositoryInterface $userRepository;
        private EmployeeRepositoryInterface $employeeRepository;
        private CustomerRepositoryInterface $customerRepository;
        private JWTHandler $jwtHandler;

        public function __construct(
                UserRepositoryInterface $userRepository,
                EmployeeRepositoryInterface $employeeRepository,
                CustomerRepositoryInterface $customerRepository,
                JWTHandler $jwtHandler
        ) {
                $this->userRepository = $userRepository;
                $this->employeeRepository = $employeeRepository;
                $this->customerRepository = $customerRepository;
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

                // ?
                // > Obtener el nombre completo según el rol...
                $fullName = '';
                if ($user->isAdmin()) {
                        // Para Admin, buscar en employees...
                        if ($user->getEmployeeId()) {
                                $employee = $this->employeeRepository->find($user->getEmployeeId());
                                if ($employee) {
                                        $fullName = $employee->getFullName();
                                }
                        }
                } elseif ($user->isEmployee()) {
                        // Para Employee, buscar en employees...
                        if ($user->getEmployeeId()) {
                                $employee = $this->employeeRepository->find($user->getEmployeeId());
                                if ($employee) {
                                        $fullName = $employee->getFullName();
                                }
                        }
                } elseif ($user->isCustomer()) {
                        // Para Customer, buscar en customers...
                        if ($user->getCustomerId()) {
                                $customer = $this->customerRepository->find($user->getCustomerId());
                                if ($customer) {
                                        $fullName = $customer->getFullName();
                                }
                        }
                }

                // > Si no se encontró nombre completo, usar el username...
                if (empty($fullName)) {
                        $fullName = $user->getUser();
                }
                // ?

                // > Actualizar último login...
                $this->userRepository->updateLastLogin($user->getId(), date('Y-m-d H:i:s'));

                // > Generar token JWT...
                $token = $this->jwtHandler->generate([
                        'user_id' => $user->getId(),
                        'username' => $user->getUser(),
                        'full_name' => $fullName,
                        'role' => $user->getRole(),
                        'email' => $user->getEmail()
                ]);

                // > Guardar en sesión...
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUser();
                $_SESSION['full_name'] = $fullName;
                $_SESSION['role'] = $user->getRole();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['token'] = $token;

                return [
                        'user' => [
                                'id' => $user->getId(),
                                'username' => $user->getUser(),
                                'full_name' => $fullName,
                                'email' => $user->getEmail(),
                                'role' => $user->getRole()
                        ],
                        'token' => $token
                ];
        }
}
