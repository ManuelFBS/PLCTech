<?php

namespace PLCTech\Helpers;

use PLCTech\Domain\Entities\ActivityLog;
use PLCTech\Infrastructure\Database\Repositories\MySQLActivityLogRepository;

class ActivityHelper
{
        public static function log(string $action, ?string $description = null): void
        {
                try {
                        $repo = new MySQLActivityLogRepository();

                        $log = new ActivityLog(
                                null,
                                $_SESSION['user_id'] ?? null,
                                $_SESSION['username'] ?? null,
                                $action,
                                $description,
                                $_SERVER['REMOTE_ADDR'] ?? null,
                                $_SERVER['HTTP_USER_AGENT'] ?? null
                        );

                        $repo->save($log);
                } catch (\Exception $e) {
                        // ? No interrumpir la ejecución si falla el log...
                        error_log('Error al registrar actividad: ' . $e->getMessage());
                }
        }
}
