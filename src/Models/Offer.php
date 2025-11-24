<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IncadevUns\CoreDomain\Enums\OfferStatus;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property array<array-key, mixed>|null $requirements
 * @property \Illuminate\Support\Carbon|null $closing_date
 * @property int $available_positions
 * @property OfferStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Application> $applications
 * @property-read int|null $applications_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereAvailablePositions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereClosingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Offer extends Model
{
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'closing_date',
        'available_positions',
        'status', // Agregar status a los fillable
    ];

    protected $casts = [
        'requirements' => 'array',
        'closing_date' => 'date',
        'status' => OfferStatus::class, // Agregar el cast del enum
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Scope para ofertas activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', OfferStatus::Active);
    }

    /**
     * Scope para ofertas cerradas
     */
    public function scopeClosed($query)
    {
        return $query->where('status', OfferStatus::Closed);
    }

    /**
     * Verificar si la oferta está activa
     */
    public function isActive(): bool
    {
        return $this->status === OfferStatus::Active;
    }

    /**
     * Verificar si la oferta está cerrada
     */
    public function isClosed(): bool
    {
        return $this->status === OfferStatus::Closed;
    }
}
