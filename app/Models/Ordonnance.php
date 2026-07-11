<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'staff_id',
        'date_prescription',
        'duree_validite',
        'diagnostic_associe',
        'instructions',
        'renouvelable',
        'substitution_autorisee',
        'notes_privees',
        'contenu',
    ];

    protected $casts = [
        'date_prescription'      => 'date',
        'renouvelable'           => 'integer',
        'substitution_autorisee' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class)->withTrashed();
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function medicaments()
    {
        return $this->hasMany(OrdonnanceMedicament::class);
    }
}
