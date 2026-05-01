<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nom
 * @property string $prenom
 * @property string $specialite
 * @property string $cin
 * @property string $email
 * @property string $telephone
 * @property string|null $adresse
 * @property string $date_embauche
 * @property numeric|null $salaire
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Consultation> $consultations
 * @property-read int|null $consultations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileAttente> $fileAttentes
 * @property-read int|null $file_attentes_count
 * @property-read string $full_name
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereCin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereDateEmbauche($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereSalaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereSpecialite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffMedical whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffMedical extends Model
{
    protected $table      = 'staff_medicals';
    protected $primaryKey = 'id_staff';

    protected $fillable = [
        'nom',
        'prenom',
        'specialite',
        'cin',
        'email',
        'telephone',
        'adresse',
        'date_embauche',
        'salaire',
        'role',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class, 'medecin_id', 'id');
    }

    public function fileAttentes()
    {
        return $this->hasMany(FileAttente::class, 'id_staff', 'id_staff');
    }

}
