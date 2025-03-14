<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'offre_id',
        'cv_path',
        'date_candidature',
    ];

    // Définir la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Définir la relation avec le modèle Offre
    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}