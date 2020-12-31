<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['file'];
    public function requestPayments(){
        return $this->hasMany(RequestPayment::class);
    }
}
