<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'numero',
        'patient_id',
        'staff_id',
        'consultation_id',
        'date_facturation',
        'date_echeance',
        'mode_paiement',
        'statut',
        'paid_at',
        'assurance',
        'taux_remboursement',
        'remise',
        'tva',
        'sous_total',
        'total_ttc',
        'notes',
    ];

    protected $casts = [
        'date_facturation'    => 'date',
        'date_echeance'       => 'date',
        'paid_at'             => 'datetime',
        'taux_remboursement'  => 'integer',
        'remise'              => 'integer',
        'tva'                 => 'integer',
        'sous_total'          => 'decimal:2',
        'total_ttc'           => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function lignes()
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function isOverdue(): bool
    {
        return $this->statut === 'en_attente'
            && $this->date_echeance
            && $this->date_echeance->isPast();
    }
}
