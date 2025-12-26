<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\{AuditLog, Company};

class AuditLogger
{
    public static function log(
        string $action,
        Model $model,
        ?array $old = null,
        ?array $new = null
    ): void {
        AuditLog::create([
            'company_id' => auth()->user()?->company_id ?? ($model instanceof Company ? $model->id : $model->company_id),
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
