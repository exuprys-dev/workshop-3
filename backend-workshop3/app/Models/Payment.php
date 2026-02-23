<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['amount', 'date', 'method', 'reference', 'status', 'facture_id'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }
}
