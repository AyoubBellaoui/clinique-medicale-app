<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\StaffMedical|null $staff
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation query()
 * @mixin \Eloquent
 */

class Consultation extends Model
{
    protected $table = 'consultations';

    protected $fillable = [
        'date_consultation',
        'diagnostic_path',
        'traitement_path',
        'ordonnance_path',
        'scanner_path',
        'analyse_path',
        'patient_id',
        'staff_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(StaffMedical::class, 'staff_id', 'id');
    }
}
