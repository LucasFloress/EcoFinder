<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'tipo_material',
        'cantidad',
        'unidad_medida',
        'puntos_verdes_ids',
        'fecha_vencimiento',
        'estado',
        'imagenes',
    ];

    protected $casts = [
        'puntos_verdes_ids' => 'array',
        'imagenes' => 'array',
        'fecha_vencimiento' => 'date',
        'cantidad' => 'decimal:2',
    ];

    /**
     * Emprendedor que creó la solicitud
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Transacciones (vecinos que aceptaron)
     */
    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'solicitud_id');
    }

    /**
     * Obtener puntos verdes asociados
     */
    public function puntosVerdes()
    {
        if (empty($this->puntos_verdes_ids)) {
            return collect();
        }
        return PuntoVerde::whereIn('id', $this->puntos_verdes_ids)->get();
    }

    /**
     * Scope para solicitudes activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope para solicitudes por tipo de material
     */
    public function scopePorMaterial($query, $tipo)
    {
        return $query->where('tipo_material', $tipo);
    }

    /**
     * Verificar si está vencida
     */
    public function estaVencida()
    {
        return $this->fecha_vencimiento && $this->fecha_vencimiento->isPast();
    }

    /**
     * Contar suscripciones
     */
    public function getCantidadTransaccionesAttribute()
    {
        return $this->transacciones()->count();
    }

    /**
     * Obtener nombre del material formateado
     */
    public function getMaterialFormateadoAttribute()
    {
        $materiales = [
            'plastico' => '🔵 Plástico',
            'papel' => '📄 Papel/Cartón',
            'vidrio' => '🟢 Vidrio',
            'metal' => '⚙️ Metal',
            'electronico' => '💻 Electrónico',
            'organico' => '🌱 Orgánico',
        ];

        return $materiales[$this->tipo_material] ?? $this->tipo_material;
    }
}