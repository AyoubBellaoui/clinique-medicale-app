<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Consultation> $consultations
 * @property-read int|null $consultations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileAttente> $fileAttentes
 * @property-read int|null $file_attentes_count
 * @property-read string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @mixin \Eloquent
 */
class Patient extends Model
{
    protected $table      = 'patients';
    protected $primaryKey = 'id_patient';

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'genre',
        'statut_marital',
        'cin',
        'telephone',
        'adresse',

        'email',
        'groupe_sanguin',
        'allergies',
        'antecedents',
        'medecin_id',
        'statut_dossier',
        'assurance_type',
        'assurance_numero',
        'contact_urgence_nom',
        'contact_urgence_tel',
        'lien_urgence',
    ];

    public function fileAttentes()
    {
        return $this->hasMany(FileAttente::class, 'id_patient', 'id_patient');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'id_patient', 'id_patient');
    }

    public function getFullNameAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
