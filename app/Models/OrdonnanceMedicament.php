<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdonnanceMedicament extends Model
{
    protected $fillable = [
        'ordonnance_id',
        'nom',
        'posologie',
        'duree',
    ];

    public function ordonnance()
    {
        return $this->belongsTo(Ordonnance::class);
    }
}
