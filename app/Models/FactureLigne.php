<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactureLigne extends Model
{
    protected $fillable = [
        'facture_id',
        'designation',
        'quantite',
        'prix_unitaire',
    ];

    protected $casts = [
        'quantite'      => 'integer',
        'prix_unitaire' => 'decimal:2',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }
}
