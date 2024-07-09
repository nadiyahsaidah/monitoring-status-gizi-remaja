<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultasiMessage extends Model
{
    use HasFactory;

    protected $table = 'konsultasi_messages';
    protected $fillable = ['konsultasi_id', 'user_id', 'message'];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
