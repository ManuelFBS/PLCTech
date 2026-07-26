<?php

namespace PLCTech\Helpers;

class PathHelper
{
        // * Obtiene la ruta base del proyecto...
        public static function getBasePath(): string
        {
                return dirname(__DIR__, 2);  // > Sube desde src/Helpers hasta la raíz...
        }

        // * Obtiene la ruta de las vistas...
        public static function getViewsPath(): string
        {
                return self::getBasePath() . '/src/Presentation/Views';
        }

        // * Obtiene la URL base de la aplicación...
        public static function getBaseUrl(): string
        {
                // > Usar APP_URL del .env o detectar automáticamente...
                if (isset($_ENV['APP_URL']) && !empty($_ENV['APP_URL'])) {
                        return rtrim($_ENV['APP_URL'], '/');
                }

                // > Detectar automáticamente...
                $protocol =
                        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                                ? 'https'
                                : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $scriptName = $_SERVER['SCRIPT_NAME'];
                $scriptDir = dirname($scriptName);
                $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;

                return $protocol . '://' . $host . $basePath;
        }
}

?>