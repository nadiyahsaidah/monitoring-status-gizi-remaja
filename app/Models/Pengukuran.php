<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengukuran extends Model
{
    use HasFactory;

    protected $table = 'pengukuran';

    protected $fillable = [
        'remaja_id',
        'tanggal_pengukuran',
        'bb',
        'tb',
        'lila',
        'tensi',
        'status_gizi',
    ];

    public function remaja()
    {
        return $this->belongsTo(Remaja::class);
    }

    
    
    
}
