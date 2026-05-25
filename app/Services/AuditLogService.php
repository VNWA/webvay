<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public function log(
        ?User $user,
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $properties = [],
        ?string $ip = null,
        ?string $userAgent = null,
    ): void {
        AuditLog::query()->create([
            'user_id' => $user?->id,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'ip_address' => $ip ?? Request::ip(),
            'user_agent' => $userAgent ?? Request::userAgent(),
            'properties' => $properties ?: null,
        ]);
    }
}
