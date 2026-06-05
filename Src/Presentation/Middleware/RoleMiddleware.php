<?php

namespace PLCTech\Presentation\Middleware;

class RoleMiddleware
{
        /**
         * * Verifica si el usuario tiene el rol requerido...
         * @param string $requiredRoles Ejemplo: "Admin" o "Admin|Employee"
         */
        public static function hasRole(string $requiredRoles): bool
        {
                $userRole = AuthMiddleware::getRole();

                if (!$userRole) {
                        return false;
                }

                $roles = explode('|', $requiredRoles);

                return in_array($userRole, $roles);
        }

        // * Verifica acceso basado en el rol...
        public static function checkAccess(string $requiredRoles, ?callable $onDenied = null): bool
        {
                if (!self::hasRole($requiredRoles)) {
                        if ($onDenied) {
                                $onDenied();
                        } else {
                                http_response_code(403);
                                echo 'Acceso denegado: No tienes permisos suficientes.';
                        }
                        return false;
                }

                return true;
        }

        // * Obtiene permisos según el rol...
        public static function getPermissions(string $role): array
        {
                $permissions = [
                        'Admin' => [
                                'users' => ['create', 'read', 'update', 'delete'],
                                'employees' => ['create', 'read', 'update', 'delete'],
                                'customers' => ['create', 'read', 'update', 'delete'],
                                'products' => ['create', 'read', 'update', 'delete'],
                                'purchases' => ['create', 'read', 'update', 'delete'],
                                'reports' => ['read']
                        ],
                        'Employee' => [
                                'customers' => ['create', 'read'],
                                'products' => ['create', 'read'],
                                'purchases' => ['create', 'read']
                        ],
                        'Customer' => [
                                'products' => ['read'],
                                'purchases' => ['created', 'read']
                        ]
                ];

                return $permissions[$role] ?? [];
        }

        // * Verifica un permiso específico...
        public static function can(string $permission, string $resource): bool
        {
                $role = AuthMiddleware::getRole();
                if (!$role) {
                        return false;
                }

                $permissions = self::getPermissions($role);

                return isset($permissions[$resource]) && in_array($permission, $permissions[$resource]);
        }
}
