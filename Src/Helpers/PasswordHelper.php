<?php

namespace PLCTech\Helpers;

class PasswordHelper
{
        /**
         * * Valida la fortaleza de una contraseña
         * * Retorna un array con el resultado y los mensajes
         */
        public static function validateStrength(string $password): array
        {
                $score = 0;
                $message = [];

                // > Longitud mínima...
                if (strlen($password) < 8) {
                        $message[] = 'Debe tener al menos 8 caracteres';
                } else {
                        $score += 25;
                }

                // > Contiene mayúsculas...
                if (preg_match('/[A-Z]/', $password)) {
                        $score += 20;
                } else {
                        $messages[] = 'Debe incluir al menos una letra mayúscula';
                }

                // > Contiene números...
                if (preg_match('/[0-9]/', $password)) {
                        $score += 20;
                } else {
                        $messages[] = 'Debe incluir al menos un número';
                }

                // > Contiene caracteres especiales...
                if (preg_match('/[^a-zA-Z0-9]/', $password)) {
                        $score += 20;
                } else {
                        $messages[] = 'Debe incluir al menos un carácter especial (!@#$%^&*)';
                }

                // > Determinar nivel...
                if ($score < 40) {
                        $level = 'weak';
                        $label = '🔴 Débil';
                        $color = 'red';
                } elseif ($score < 70) {
                        $level = 'medium';
                        $label = '🟡 Media';
                        $color = 'orange';
                } else {
                        $level = 'strong';
                        $label = '🟢 Fuerte';
                        $color = 'green';
                }

                return [
                        'valid' => $score >= 40,  // ? Se aceptan contraseñas media o fuerte...
                        'score' => $score,
                        'level' => $level,
                        'label' => $label,
                        'color' => $color,
                        'messages' => $messages
                ];
        }

        // * Genera una contraseña aleatoria segura...
        public static function generateSecure(int $length = 12): string
        {
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=';
                $password = '';

                for ($i = 0; $i < $length; $i++) {
                        $password .= $characters[random_int(0, strlen($characters) - 1)];
                }

                return $password;
        }
}
