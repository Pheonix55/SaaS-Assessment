<?php

namespace App\Traits;

use App\Services\AuditLogger;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditLogger::log('created', $model, null, $model->toArray());
        });

        static::updated(function ($model) {
            AuditLogger::log(
                'updated',
                $model,
                $model->getOriginal(),
                $model->getChanges()
            );
        });

        static::deleted(function ($model) {
            AuditLogger::log('deleted', $model, $model->toArray(), null);
        });
    }
}
