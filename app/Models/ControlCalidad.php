<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlCalidad extends Model
{
    use HasFactory;

    protected $table = 'control_calidad'; // Especifica el nombre de la tabla

    protected $fillable = [
        'lote_id',
        'supervisado_por_user_id',
        'fecha_control',
        'resultado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_control' => 'date',
    ];

    /**
     * Get the lot that was subject to quality control.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }

    /**
     * Get the user who supervised the quality control.
     */
    public function supervisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisado_por_user_id');
    }
}
