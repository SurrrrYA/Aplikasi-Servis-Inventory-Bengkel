<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public static function log(
        $userId,
        string $action,
        string $module,
        string $description,
        $oldData = null,
        $newData = null
    ): void {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }
}