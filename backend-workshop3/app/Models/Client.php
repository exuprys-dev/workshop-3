<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address', 'status'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }
}
