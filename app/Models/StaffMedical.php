<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffMedical extends Model
{
    protected $table = 'staff_medicals';

    protected $fillable = [
        // Identity
        'nom',
        'prenom',
        'cin',
        'gender',
        'date_naissance',

        // Contact
        'email',
        'telephone',
        'adresse',

        // Professional
        'specialite',
        'degree',
        'school',
        'grad_year',
        'languages',

        // Contract
        'date_embauche',
        'salaire',
        'contract_type',
        'schedule',
        'status',

        // System
        'role',

        // Notes
        'notes',
    ];

    /* ===================== RELATIONS ===================== */

    public function patients()
    {
        return $this->hasMany(Patient::class, 'medecin_id', 'id');
    }

    public function fileAttentes()
    {
        return $this->hasMany(FileAttente::class, 'id_staff', 'id');
    }

    /* ===================== ACCESSORS ===================== */

    public function getFullNameAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    /* ===================== HELPERS (IMPORTANT) ===================== */

    public function isDoctor(): bool
    {
        return $this->role === 'medecin';
    }

    public function isSecretary(): bool
    {
        return $this->role === 'secretariat';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

}
