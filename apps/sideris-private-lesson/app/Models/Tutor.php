<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutors';
    protected $primaryKey = 'id_tutor';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'mata_pelajaran',
        'harga_per_jam',
        'jam_tersedia',
        'foto_profil',
        'deskripsi',
    ];

    protected $casts = [
        'jam_tersedia' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_tutor', 'id_tutor');
    }
}
