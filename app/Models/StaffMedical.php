<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffMedical extends Model
{
    use HasFactory, SoftDeletes;

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
        'license_number',
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
        'color',

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
        return $this->hasMany(FileAttente::class, 'staff_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'staff_id', 'id');
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
