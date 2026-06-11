<?php

namespace PLCTech\Presentation\Middleware;

use PLCTech\Infrastructure\Auth\JWTHandler;

class AuthMiddleware
{
        private static JWTHandler $jwtHandler;

        public static function init(): void
        {
                if (!isset(self::$jwtHandler)) {
                        self::$jwtHandler = new JWTHandler();
                }
        }

        public static function check(): bool
        {
                self::init();

                if (!isset($_SESSION['user_id'])) {
                        return false;
                }

                return self::$jwtHandler->isValid();
        }

        public static function getUser(): ?array
        {
                self::init();
                return self::$jwtHandler->getCurrentUser();
        }

        public static function getUserId(): ?int
        {
                $user = self::getUser();
                return $user['user_id'] ?? null;
        }

        public static function getRole(): ?string
        {
                $user = self::getUser();
                return $user['role'] ?? null;
        }

        public static function getFullName(): ?string
        {
                $user = self::getUser();
                return $user['full_name'] ?? ($_SESSION['full_name'] ?? null);
        }
}
