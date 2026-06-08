<?php

namespace PLCTech\Config\DB;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Database
{
        private static ?PDO $connection = null;

        public static function getConnection(): PDO
        {
                if (self::$connection === null) {
                        // > Cargar variables de entorno si no están cargadas...
                        if (!isset($_ENV['DB_HOST'])) {
                                $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
                                $dotenv->load();
                        }

                        try {
                                $dsn = sprintf(
                                        'mysql:host=%s;dbname=%s;charset=%s',
                                        $_ENV['DB_HOST'],
                                        $_ENV['DB_NAME'],
                                        $_ENV['DB_CHARSET'] ?? 'utf8mb4'
                                );

                                self::$connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                                die('Connection failed: ' . $e->getMessage());
                        }
                }

                return self::$connection;
        }
}
