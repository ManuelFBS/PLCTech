<?php

namespace PLCTech\Infrastructure\Auth;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class JWTHandler
{
        private string $secret;
        private int $expiry;

        public function __construct()
        {
                $this->secret = $_ENV['JWT_SECRET'] ?? 'default_secret_key_change_me';
                $this->expiry = (int) ($_ENV['SESSION_LIFETIME'] ?? 7200);
        }

        // * Genera un token JWT...
        public function generate(array $payload): string
        {
                $issueAt = time();
                $expire = $issueAt + $this->expiry;

                $tokenPayload = [
                        'iat' => $issueAt,
                        'exp' => $expire,
                        'data' => $payload
                ];

                return JWT::encode($tokenPayload, $this->secret, 'HS256');
        }

        // * Valida y decodifica un token JWT...
        public function validate(string $token): ?array
        {
                try {
                        $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
                        return (array) $decoded->data;
                } catch (ExpiredException $e) {
                        return null;  // > Token expirado...
                } catch (SignatureInvalidException $e) {
                        return null;  // > Firma inválida...
                } catch (\Exception $e) {
                        return null;  // > Otro error...
                }
        }

        // * Obtiene el token de la sesión...
        public function getTokenFromSession(): ?string
        {
                return $_SESSION['token'] ?? null;
        }

        // * Obtiene el usuario del token actual...
        public function getCurrentUser(): ?array
        {
                $token = $this->getTokenFromSession();
                if (!$token) {
                        return null;
                }

                return $this->validate($token);
        }

        // * Verifica si el token es válido...
        public function isValid(): bool
        {
                $token = $this->getTokenFromSession();
                if (!$token) {
                        return false;
                }

                return $this->validate($token) !== null;
        }

        // * Renueva el token (si está cerca de expirar)...
        public function renewIfNeeded(): ?string
        {
                $token = $this->getTokenFromSession();
                if (!$token) {
                        return null;
                }

                try {
                        $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
                        $currentTime = time();
                        $expiryTime = $decoded->exp;

                        // > Si queda menos del 20% del tiempo, renovar...
                        $timeLeft = $expiryTime - $currentTime;
                        $totalLifetime = $this->expiry;

                        if ($timeLeft < ($totalLifetime * 0.2)) {
                                $newToken = $this->generate((array) $decoded->data);
                                $_SESSION['token'] = $newToken;
                                return $newToken;
                        }
                } catch (\Exception $e) {
                        return null;
                }

                return null;
        }

        // * Invalida el token actual...
        public function invalidate(): void
        {
                unset($_SESSION['token']);
        }
}
