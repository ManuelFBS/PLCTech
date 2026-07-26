<?php

namespace PLCTech\Helpers;

class UrlHelper
{
        /**
         * * Genera una URL completa para la aplicación
         *
         * @param string $path Ruta base
         * @param array $params Parámetros query (opcional)
         * @return string URL completa
         */
        public static function url(string $path = '', array $params = []): string
        {
                $baseUrl = PathHelper::getBaseUrl();
                $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

                // > Si hay parámetros, los agregamos con http_build_query...
                if (!empty($params)) {
                        $url .= '?' . http_build_query($params);
                }

                return $url;
        }
}
