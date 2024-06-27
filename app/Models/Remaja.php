<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remaja extends Model
{
    use HasFactory;

    protected $table = 'remaja';

    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hitungUsia()
    {
        $tanggal_lahir = $this->tanggal_lahir;

        if ($tanggal_lahir) {
            $usia = Carbon::parse($tanggal_lahir)->age;
            return $usia;
        }

        return null; 
    }
}
