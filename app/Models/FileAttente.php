<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string $statut
 * @property int $patient_id
 * @property int $staff_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\StaffMedical|null $staff
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileAttente whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class FileAttente extends Model
{
    protected $table = 'file_attentes';

    protected $fillable = [
        'date',
        'statut',
        'patient_id',
        'staff_id',
    ];

    protected $casts = [
        'date' => 'datetime',
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
