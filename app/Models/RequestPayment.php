<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPayment extends Model
{
    use HasFactory;
    protected $fillable = ['jmbg', 'email', 'payment_id'];

    public function payment(){
        return $this->belongsTo(Payment::class);
    }
}
