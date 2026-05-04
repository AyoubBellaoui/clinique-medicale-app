<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $table = 'ordonnances';

    protected $fillable = [
        'consultation_id',
        'contenu',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
