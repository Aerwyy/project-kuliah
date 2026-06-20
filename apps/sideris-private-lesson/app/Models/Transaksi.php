<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    protected $fillable = [
        'id_user_murid',
        'id_tutor',
        'total_harga',
        'tanggal_les',
        'jam_pilihan_murid',
    ];

    public function murid()
    {
        return $this->belongsTo(User::class, 'id_user_murid', 'id_user');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id_tutor');
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalBelajar::class, 'id_transaksi', 'id_transaksi');
    }
}
