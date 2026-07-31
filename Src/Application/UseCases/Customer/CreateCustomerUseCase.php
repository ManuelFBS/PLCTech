<?php

namespace PLCTech\Application\UseCases\Customer;

use PLCTech\Application\DTOs\CustomerDTO;
use PLCTech\Domain\Entities\Customer;
use PLCTech\Domain\Entities\User;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PLCTech\Domain\Repositories\UserRepositoryInterface;

class CreateCustomerUseCase
{
        private CustomerRepositoryInterface $customerRepository;
        private UserRepositoryInterface $userRepository;

        public function __construct(
                CustomerRepositoryInterface $customerRepository,
                UserRepositoryInterface $userRepository
        ) {
                $this->customerRepository = $customerRepository;
                $this->userRepository = $userRepository;
        }

        public function execute(
                CustomerDTO $customerDTO,
                ?string $plainPassword = null
        ): array {
                // > Validar DNI único...
                if ($this->customerRepository->findByDni($customerDTO->dni)) {
                        throw new \Exception('Ya existe un cliente con ese DNI');
                }

                // > Validar email único...
                if ($this->customerRepository->findByEmail($customerDTO->email)) {
                        throw new \Exception('Ya existe un cliente con ese email');
                }

                // > Validar que el email no esté en uso por otro usuario...
                if ($this->userRepository->findByEmail($customerDTO->email)) {
                        throw new \Exception('El email ya está registrado en el sistema');
                }

                // > Crear cliente...
                $customer = new Customer(
                        null,
                        $customerDTO->dni,
                        $customerDTO->full_name,
                        $customerDTO->birthdate,
                        $customerDTO->email,
                        $customerDTO->phone_number
                );

                // > Guardar...
                $customerId = $this->customerRepository->save($customer);

                // > ============================================================
                // > GENERAR USUARIO Y CONTRASEÑA (SIN CARACTERES ESPECIALES)
                // > ============================================================

                // > Función para limpiar caracteres especiales...
                $cleanSpecialChars = function ($string) {
                        $unwanted_array = array(
                                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                                'ñ' => 'n', 'Ñ' => 'N',
                                'ü' => 'u', 'Ü' => 'U',
                                'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
                                'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U'
                        );

                        return strtr($string, $unwanted_array);
                };

                // > Generar nombre de usuario...
                $username = explode('@', $customerDTO->email)[0];
                $username = $cleanSpecialChars($username);
                $username = preg_replace('/[^a-zA-Z0-9_]/', '', $username);

                // > Verificar si el username ya existe...
                $existingUser = $this->userRepository->findByUsername($username);
                if ($existingUser) {
                        $username = $username . rand(100, 999);
                }

                // >s ============================================================
                // > USAR LA CONTRASEÑA DEL FORMULARIO O GENERAR UNA TEMPORAL
                // > ============================================================
                $tempPassword = null;
                if (!empty($plainPassword)) {
                        // ? Usar la contraseña proporcionada por el usuario...
                        $passwordToHash = $plainPassword;
                } else {
                        // > Generar contraseña temporal (solo caracteres alfanuméricos)...
                        $cleanName = $cleanSpecialChars(str_replace(' ', '', $customerDTO->full_name));
                        $tempPassword = $customerDTO->dni . substr($cleanName, 0, 4);
                        $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', $tempPassword);

                        // > Asegurar longitud mínima...
                        if (strlen($tempPassword) < 6) {
                                $tempPassword = $tempPassword . 'Abc123';
                        }
                        $passwordToHash = $tempPassword;
                }

                $hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

                // > Crear usuario con rol Customer...
                $user = new User(
                        null,
                        $customerDTO->dni,
                        $username,
                        $customerDTO->email,
                        'Customer',
                        $hashedPassword,
                        true,
                        null,
                        $customerId
                );

                $this->userRepository->save($user);

                if (empty($tempPassword)) {
                        $tempPassword = '************';
                }

                return [
                        'success' => true,
                        'message' => 'Cliente creado exitosamente. Usuario creado: ' . $username,
                        'customer_id' => $customerId,
                        'username' => $username,
                        'password' => $tempPassword,
                ];
        }
}
