<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    public static function log(string $action, ?int $userId = null): void
    {
        try {
            AuditLog::create([
                'user_id'    => $userId ?? auth()->id(),
                'action'     => $action,
                'ip'         => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
