<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = ['libelle', 'amount', 'date', 'justification', 'categorie', 'user_id', 'project_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
