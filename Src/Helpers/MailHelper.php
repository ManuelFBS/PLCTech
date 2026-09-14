<?php

namespace PLCTech\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
        private static ?PHPMailer $mailer = null;

        private static function getMailer(): PHPMailer
        {
                if (self::$mailer === null) {
                        try {
                                self::$mailer = new PHPMailer(true);

                                self::$mailer->isSMTP();
                                self::$mailer->Host = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
                                self::$mailer->SMTPAuth = true;
                                self::$mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
                                self::$mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
                                self::$mailer->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
                                self::$mailer->Port = (int) ($_ENV['MAIL_PORT'] ?? 587);

                                // > ============================================================
                                // > DESACTIVAR DEBUG (producción)
                                // > ============================================================
                                self::$mailer->SMTPDebug = SMTP::DEBUG_OFF;

                                // > Configuración del remitente...
                                self::$mailer->setFrom(
                                        $_ENV['MAIL_FROM_EMAIL'] ??
                                                ($_ENV['MAIL_USERNAME'] ??
                                                        'noreply@plctechpulse.com'),
                                        $_ENV['MAIL_FROM_NAME'] ?? 'PLC Tech Pulse',
                                );

                                self::$mailer->isHTML(true);
                                self::$mailer->CharSet = 'UTF-8';
                        } catch (Exception $e) {
                                error_log('Error al inicializar PHPMailer: ' . $e->getMessage());
                                throw $e;
                        }
                }

                return self::$mailer;
        }

        // * Envía un correo de recuperación de contraseña REAL...
        public static function sendResetPasswordEmail(
                string $toEmail,
                string $toName,
                string $resetLink,
        ): bool {
                try {
                        $mailer = self::getMailer();

                        $mailer->clearAddresses();
                        $mailer->addAddress($toEmail, $toName);

                        $mailer->Subject = '🔐 Recuperación de contraseña - PLC Tech Pulse';
                        $mailer->Body = self::getResetPasswordHtml($toName, $resetLink);
                        $mailer->AltBody = self::getResetPasswordPlainText($toName, $resetLink);

                        $result = $mailer->send();

                        error_log('✅ Email enviado correctamente a: ' . $toEmail);
                        return $result;
                } catch (Exception $e) {
                        error_log('❌ Error al enviar email: ' . $e->getMessage());
                        return false;
                }
        }

        // * Envía un correo de bienvenida REAL...
        public static function sendWelcomeEmail(
                string $toEmail,
                string $toName,
                string $username,
                string $password,
        ): bool {
                try {
                        $mailer = self::getMailer();

                        $mailer->clearAddresses();
                        $mailer->addAddress($toEmail, $toName);

                        $mailer->Subject = '🎉 Bienvenido a PLC Tech Pulse';

                        $mailer->Body = self::getWelcomeHtml($toName, $username, $password);
                        $mailer->AltBody = self::getWelcomePlainText($toName, $username, $password);

                        return $mailer->send();
                } catch (Exception $e) {
                        error_log('Error al enviar email de bienvenida: ' . $e->getMessage());
                        return false;
                }
        }

        // * Guarda en el log de emails (para tener registro de envíos)...
        private static function saveToLog(
                string $toEmail,
                string $toName,
                string $resetLink,
                bool $success,
                string $error = '',
        ): void {
                $logDir = 'C:/xampp/htdocs/Projects/PLCTech/logs/';
                if (!is_dir($logDir)) {
                        mkdir($logDir, 0777, true);
                }

                $logFile = $logDir . 'emails.log';

                $logEntry = "========================================\n";
                $logEntry .= 'FECHA: ' . date('Y-m-d H:i:s') . "\n";
                $logEntry .= "TIPO: Recuperación de contraseña\n";
                $logEntry .= "DESTINO: $toName <$toEmail>\n";
                $logEntry .= "ENLACE: $resetLink\n";
                $logEntry .= 'ESTADO: ' . ($success ? '✅ ENVIADO' : '❌ ERROR: ' . $error) . "\n";
                $logEntry .= "========================================\n\n";

                file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        }

        // * ============================================================
        // * PLANTILLAS HTML Y TEXTO PLANO (igual que antes)
        // * ============================================================

        private static function getResetPasswordHtml(string $name, string $resetLink): string
        {
                $appName = $_ENV['APP_NAME'] ?? 'PLC Tech Pulse';
                $appUrl = PathHelper::getBaseUrl();

                return <<<HTML
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                        .btn { display: inline-block; background: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
                        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>🔐 {$appName}</h1>
                            <p>Recuperación de contraseña</p>
                        </div>
                        <div class="content">
                            <h2>Hola {$name},</h2>
                            <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                            <p>Para crear una nueva contraseña, haz clic en el siguiente botón:</p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{$resetLink}" class="btn">Restablecer contraseña</a>
                            </p>
                            <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                            <p><strong>Este enlace expirará en 1 hora.</strong></p>
                            <hr>
                            <p><small>Si el botón no funciona, copia y pega este enlace en tu navegador:</small></p>
                            <p><small><a href="{$resetLink}">{$resetLink}</a></small></p>
                        </div>
                        <div class="footer">
                            <p>&copy; {$appName} - Todos los derechos reservados</p>
                            <p><a href="{$appUrl}">{$appUrl}</a></p>
                        </div>
                    </div>
                </body>
                </html>
                HTML;
        }

        private static function getResetPasswordPlainText(string $name, string $resetLink): string
        {
                $appName = $_ENV['APP_NAME'] ?? 'PLC Tech Pulse';

                return <<<TEXT
                Hola {$name},

                Hemos recibido una solicitud para restablecer tu contraseña en {$appName}.

                Para crear una nueva contraseña, copia y pega este enlace en tu navegador:
                {$resetLink}

                Este enlace expirará en 1 hora.

                Si no solicitaste este cambio, puedes ignorar este correo.

                ---
                {$appName}
                TEXT;
        }

        private static function getWelcomeHtml(
                string $name,
                string $username,
                string $password,
        ): string {
                $appName = $_ENV['APP_NAME'] ?? 'PLC Tech Pulse';
                $appUrl = PathHelper::getBaseUrl();

                return <<<HTML
                <!DOCTYPE html>
                <html>
                        <head>
                                <meta charset="UTF-8">
                                <style>
                                        body { font-family: Arial, sans-serif; color: #333; }
                                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                        .header { background: linear-gradient(135deg, #00d1b2 0%, #00b894 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                                        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                                        .credentials { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; }
                                        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 20px; }
                                </style>
                        </head>
                        <body>
                                <div class="container">
                                        <div class="header">
                                                <h1>🎉 {$appName}</h1>
                                                <p>¡Bienvenido!</p>
                                        </div>
                                        <div class="content">
                                                <h2>Hola {$name},</h2>
                                                <p>Tu cuenta ha sido creada exitosamente en {$appName}.</p>
                                        <div class="credentials">
                                                <h3>Tus credenciales de acceso:</h3>
                                                <p><strong>Usuario:</strong> {$username}</p>
                                                <p><strong>Contraseña:</strong> {$password}</p>
                                        </div>

                                        <p style="text-align: center; margin: 30px 0;">
                                                <a href="{$appUrl}/login" class="btn" style="display: inline-block; background: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px;">
                                                Iniciar sesión
                                                </a>
                                        </p>
                                        <p><strong>Recomendación:</strong> Te sugerimos cambiar tu contraseña después de tu primer inicio de sesión.</p>
                                        </div>
                                        <div class="footer">
                                                <p>&copy; {$appName} - Todos los derechos reservados</p>
                                                <p><a href="{$appUrl}">{$appUrl}</a></p>
                                        </div>
                                </div>
                        </body>
                </html>
                HTML;
        }

        private static function getWelcomePlainText(
                string $name,
                string $username,
                string $password,
        ): string {
                $appName = $_ENV['APP_NAME'] ?? 'PLC Tech Pulse';
                $appUrl = PathHelper::getBaseUrl();

                return <<<TEXT
                Hola {$name},

                Tu cuenta ha sido creada exitosamente en {$appName}.

                Tus credenciales de acceso:
                - Usuario: {$username}
                - Contraseña: {$password}

                Inicia sesión aquí: {$appUrl}/login

                Te recomendamos cambiar tu contraseña después de tu primer inicio de sesión.

                ---
                {$appName}
                TEXT;
        }
}
