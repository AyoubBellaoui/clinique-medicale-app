<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileAttente extends Model
{
    use HasFactory;

    protected $table = 'file_attentes';

    protected $fillable = [
        'arrived_at',
        'position',
        'statut',
        'priorite',
        'type_visite',
        'motif',
        'rendez_vous_id',
        'patient_id',
        'staff_id',
    ];

    protected $casts = [
        'arrived_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class)->withTrashed();
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    /**
     * Move this queue entry to "termine", unless it's already terminal (termine/annule).
     */
    public function terminer(): void
    {
        if (!in_array($this->statut, ['termine', 'annule'], true)) {
            $this->update(['statut' => 'termine']);
        }
    }
}
