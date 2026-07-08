<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    protected $table = 'rendez_vous';

    protected $fillable = [
        'date',
        'heure',
        'statut',
        'motif',
        'type_consultation',
        'duree',
        'priorite',
        'notes',
        'patient_id',
        'staff_id',
    ];

    protected $casts = [
        'date' => 'date',
        'heure' => 'datetime:H:i',
        'duree' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class);
    }

    public function fileAttente()
    {
        return $this->hasOne(FileAttente::class, 'rendez_vous_id');
    }
}
