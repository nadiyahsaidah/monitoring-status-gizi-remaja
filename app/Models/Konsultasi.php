<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Konsultasi extends Model
{
    use HasFactory,Notifiable;
    

    protected $table = 'konsultasi'; 

    protected $fillable = [
        'remaja_id', 'perihal', 'deskripsi', 'status', 'balasan'
    ];

    public function remaja()
    {
        return $this->belongsTo(Remaja::class, 'remaja_id');
    }
}
