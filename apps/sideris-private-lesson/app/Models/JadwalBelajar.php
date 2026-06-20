<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalBelajar extends Model
{
    protected $table = 'jadwal_belajar';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi',
        'judul_pertemuan',
        'status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
