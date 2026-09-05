<?php

namespace PLCTech\Helpers;

class MailHelper
{
        // * Guarda un email de recuperación en el archivo de logs...
        public static function sendResetPasswordEmail(string $toEmail, string $toName, string $resetLink): bool
        {
                $logDir = __DIR__ . '/../../logs/';
                if (!is_dir($logDir)) {
                        mkdir($logDir, 0777, true);
                }

                $logFile = $logDir . 'emails.log';

                $logEntry = "========================================\n";
                $logEntry .= 'FECHA: ' . date('Y-m-d H:i:s') . "\n";
                $logEntry .= "TIPO: Recuperación de contraseña\n";
                $logEntry .= "PARA: $toName <$toEmail>\n";
                $logEntry .= "ENLACE: $resetLink\n";
                $logEntry .= "========================================\n\n";

                file_put_contents($logFile, $logEntry, FILE_APPEND);
                return true;
        }

        // * Guarda un email de bienvenida en el archivo de logs...
        public static function sendWelcomeEmail(string $toEmail, string $toName, string $username, string $password): bool
        {
                $logDir = __DIR__ . '/../../logs/';
                if (!is_dir($logDir)) {
                        mkdir($logDir, 0777, true);
                }

                $logFile = $logDir . 'emails.log';

                $logEntry = "========================================\n";
                $logEntry .= 'FECHA: ' . date('Y-m-d H:i:s') . "\n";
                $logEntry .= "TIPO: Bienvenida\n";
                $logEntry .= "PARA: $toName <$toEmail>\n";
                $logEntry .= "USUARIO: $username\n";
                $logEntry .= "CONTRASEÑA: $password\n";
                $logEntry .= "========================================\n\n";

                file_put_contents($logFile, $logEntry, FILE_APPEND);
                return true;
        }
}
