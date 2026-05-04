<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileAttente extends Model
{
    protected $table = 'file_attentes';

    protected $fillable = [
        'arrived_at',
        'position',
        'statut',
        'rendez_vous_id',
        'patient_id',
        'staff_id',
    ];

    protected $casts = [
        'arrived_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class);
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class);
    }
}
