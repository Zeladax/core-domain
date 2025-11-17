<?php

namespace IncadevUns\CoreDomain\Models;

use IncadevUns\CoreDomain\Enums\SecurityEventSeverity;
use IncadevUns\CoreDomain\Enums\SecurityEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityEvent extends Model
{
    protected $table = 'security_events';

    protected $fillable = [
        'user_id',
        'event_type',
        'severity',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_type' => SecurityEventType::class,
        'severity' => SecurityEventSeverity::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con usuario
     */
    public function user(): BelongsTo
    {
        $userModelClass = config('auth.providers.users.model', 'App\Models\User');

        return $this->belongsTo($userModelClass);
    }

    /**
     * Scope: Filtrar por tipo de evento
     */
    public function scopeOfType($query, SecurityEventType $type)
    {
        return $query->where('event_type', $type->value);
    }

    /**
     * Scope: Filtrar por severidad
     */
    public function scopeWithSeverity($query, SecurityEventSeverity $severity)
    {
        return $query->where('severity', $severity->value);
    }

    /**
     * Scope: Eventos críticos
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', SecurityEventSeverity::CRITICAL->value);
    }

    /**
     * Scope: Eventos recientes
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Por usuario
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Por IP
     */
    public function scopeFromIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }
}
