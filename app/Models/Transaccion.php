<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $fillable = [
        'solicitud_id',
        'user_id',
        'punto_verde_id',
        'cantidad_ofrecida',
        'notas',
        'fecha_entrega',
        'estado',
        'calificacion',
        'comentario',
    ];

    protected $casts = [
        'cantidad_ofrecida' => 'decimal:2',
        'fecha_entrega' => 'date',
    ];

    /**
     * Solicitud de reciclaje
     */
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    /**
     * Vecino que se suscribió
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Punto verde elegido
     */
    public function puntoVerde()
    {
        return $this->belongsTo(PuntoVerde::class, 'punto_verde_id');
    }

    /**
     * Scope para transacciones pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para transacciones confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    /**
     * Scope para transacciones entregadas
     */
    public function scopeEntregadas($query)
    {
        return $query->where('estado', 'entregada');
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => '<span style="display: inline-block; padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 12px; font-weight: 600;">⏳ Pendiente</span>',
            'confirmada' => '<span style="display: inline-block; padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">✅ Confirmada</span>',
            'entregada' => '<span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 12px; font-weight: 600;">🎉 Entregada</span>',
            'cancelada' => '<span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 12px; font-weight: 600;">❌ Cancelada</span>',
        ];

        return $badges[$this->estado] ?? $this->estado;
    }
}