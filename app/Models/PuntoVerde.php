<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoVerde extends Model
{
    use HasFactory;

    protected $table = 'puntos_verdes';

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'province',
        'latitude',
        'longitude',
        'phone',
        'email',
        'schedule',
        'accepted_materials',
        'municipality_id',
        'is_active',
        'status',
        'image',
    ];

    protected $casts = [
        'schedule' => 'array',
        'accepted_materials' => 'array',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relación: Un punto verde pertenece a una municipalidad
     */
    public function municipality()
    {
        return $this->belongsTo(User::class, 'municipality_id');
    }

    /**
     * Scope para filtrar por municipalidad
     */
    public function scopeByMunicipality($query, $municipalityId)
    {
        return $query->where('municipality_id', $municipalityId);
    }

    /**
     * Scope para puntos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para puntos operativos
     */
    public function scopeOperational($query)
    {
        return $query->where('status', 'operativo');
    }

    /**
     * Obtener materiales como string
     */
    public function getMaterialsStringAttribute()
    {
        return $this->accepted_materials 
            ? implode(', ', $this->accepted_materials) 
            : 'No especificado';
    }
}