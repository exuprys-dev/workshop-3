<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'budget', 'status', 'client_id', 'user_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}
