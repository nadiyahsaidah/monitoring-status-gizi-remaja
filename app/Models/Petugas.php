<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $fillable = [
         'jenis_kelamin', 'nik', 'nip', 'tempat_lahir', 'tanggal_lahir', 'jabatan', 'alamat', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
