<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date_consultation',
        'motif',
        'type_consultation',
        'tension_systolique',
        'tension_diastolique',
        'frequence_cardiaque',
        'temperature',
        'spo2',
        'poids',
        'taille',
        'diagnostic',
        'traitement',
        'notes',
        'ordonnance_demandee',
        'scanner_demande',
        'analyse_demandee',
        'kine_demandee',
        'prochain_rdv_date',
        'patient_id',
        'staff_id',
        'file_attente_id',
    ];

    protected $casts = [
        'date_consultation'    => 'datetime',
        'prochain_rdv_date'    => 'date',
        'ordonnance_demandee'  => 'boolean',
        'scanner_demande'      => 'boolean',
        'analyse_demandee'     => 'boolean',
        'kine_demandee'        => 'boolean',
        'temperature'          => 'float',
        'poids'                => 'float',
    ];

    public function patient()
    {
        // withTrashed(): a soft-deleted (archived) patient must still resolve here,
        // otherwise past consultations would silently lose their patient reference.
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class)->withTrashed();
    }

    public function ordonnance()
    {
        return $this->hasOne(Ordonnance::class);
    }

    public function fileAttente()
    {
        return $this->belongsTo(FileAttente::class);
    }
}
