<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * KpiGoal model.
 *
 * Propósito:
 * Gestiona los indicadores clave de rendimiento (KPI) institucionales, almacenando
 * sus metas, valores actuales, tendencias y estado de cumplimiento. Permite el
 * seguimiento del progreso de objetivos estratégicos a lo largo del tiempo.
 *
 * Reglas de negocio:
 * - El campo 'name' es único y se usa para identificación programática (ej: 'satisfaccion_estudiantil')
 * - Los valores 'goal_value', 'current_value' y 'previous_value' representan porcentajes (0-100)
 * - El campo 'trend' indica el cambio porcentual respecto al período anterior
 * - Los estados válidos son: 'Requiere atención' (<80%), 'En camino' (80-99%), 'Cumplido' (≥100%)
 * - Los estados se calculan automáticamente basándose en el progreso actual vs la meta
 * - La tendencia se calcula comparando el valor actual con el valor previo
 *
 * @property int $id
 * @property string $name Identificador único programático del KPI
 * @property string $display_name Nombre legible para mostrar en la interfaz
 * @property float $goal_value Meta a alcanzar (porcentaje)
 * @property float $current_value Valor actual del KPI (porcentaje)
 * @property float $previous_value Valor del período anterior para calcular tendencia
 * @property float $trend Cambio porcentual respecto al período anterior
 * @property string $status Estado actual: 'Requiere atención', 'En camino', 'Cumplido'
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class KpiGoal extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name',
        'goal_value',
        'current_value',
        'previous_value',
        'trend',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'goal_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'previous_value' => 'decimal:2',
        'trend' => 'decimal:2',
    ];

    /**
     * Valid status values for KPIs.
     */
    public const STATUS_NEEDS_ATTENTION = 'Requiere atención';

    public const STATUS_ON_TRACK = 'En camino';

    public const STATUS_ACHIEVED = 'Cumplido';

    /**
     * Get all valid status values.
     *
     * @return array<string>
     */
    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_NEEDS_ATTENTION,
            self::STATUS_ON_TRACK,
            self::STATUS_ACHIEVED,
        ];
    }

    /**
     * Update KPI status based on current progress.
     *
     * Calcula el porcentaje de progreso (current_value / goal_value * 100) y
     * actualiza el estado según los umbrales definidos:
     * - ≥100%: Cumplido
     * - 80-99%: En camino
     * - <80%: Requiere atención
     */
    public function updateStatus(): void
    {
        $progress = $this->goal_value > 0
            ? ($this->current_value / $this->goal_value) * 100
            : 0;

        $this->status = match (true) {
            $progress >= 100 => self::STATUS_ACHIEVED,
            $progress >= 80 => self::STATUS_ON_TRACK,
            default => self::STATUS_NEEDS_ATTENTION,
        };

        $this->save();
    }

    /**
     * Calculate and update trend based on previous value.
     *
     * La tendencia se calcula restando el valor previo del valor actual.
     * Un valor positivo indica mejora, negativo indica deterioro.
     * Si no hay valor previo (previous_value = 0), la tendencia es 0.
     */
    public function calculateTrend(): void
    {
        $this->trend = $this->previous_value > 0
            ? $this->current_value - $this->previous_value
            : 0;

        $this->save();
    }

    /**
     * Get the progress percentage towards the goal.
     */
    public function getProgressPercentage(): float
    {
        return $this->goal_value > 0
            ? ($this->current_value / $this->goal_value) * 100
            : 0;
    }

    /**
     * Check if the KPI goal has been achieved.
     */
    public function isAchieved(): bool
    {
        return $this->status === self::STATUS_ACHIEVED;
    }

    /**
     * Check if the KPI is on track to meet its goal.
     */
    public function isOnTrack(): bool
    {
        return $this->status === self::STATUS_ON_TRACK;
    }

    /**
     * Check if the KPI requires attention.
     */
    public function needsAttention(): bool
    {
        return $this->status === self::STATUS_NEEDS_ATTENTION;
    }
}
