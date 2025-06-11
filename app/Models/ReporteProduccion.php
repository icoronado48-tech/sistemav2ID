<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteProduccion extends Model
{
    use HasFactory;

    protected $table = 'reportes_produccion'; // Especifica el nombre de la tabla

    protected $fillable = [
        'fecha_reporte',
        'tipo_reporte',
        'contenido_reporte',
        'generado_por_user_id',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
    ];

    /**
     * Get the user who generated the report.
     */
    public function generadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generado_por_user_id');
    }
}
