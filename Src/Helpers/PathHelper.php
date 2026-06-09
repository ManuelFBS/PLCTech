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
                return self::getBasePath() . '/Src/Presentation/Views';
        }

        // * Obtiene la URL base de la aplicación...
        public static function getBaseUrl(): string
        {
                return $_ENV['APP_URL'] ?? 'http://localhost/Projects/PLCTech';
        }
}

?>