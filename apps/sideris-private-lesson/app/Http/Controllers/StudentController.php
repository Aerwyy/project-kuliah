<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Tutor;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function home()
    {
        $userId = session('user')['id'];

        // Ambil jadwal mendatang (transaksi terbaru)
        $jadwal = Transaksi::with('tutor.user')
            ->where('id_user_murid', $userId)
            ->orderBy('tanggal_les', 'asc')
            ->where('tanggal_les', '>=', now()->toDateString())
            ->first();

        return view('student', compact('jadwal'));
    }

    public function tutorList(Request $request)
    {
        $query = Tutor::with('user');

        // Filter by mata_pelajaran
        if ($request->filled('subjects')) {
            $query->whereIn('mata_pelajaran', $request->subjects);
        }

        $tutors = $query->get();
        $selectedSubjects = $request->subjects ?? [];

        return view('tutor', compact('tutors', 'selectedSubjects'));
    }

    public function order(Request $request)
    {
        $request->validate([
            'id_tutor'         => 'required|exists:tutors,id_tutor',
            'tanggal_les'      => 'required|date|after_or_equal:today',
            'jam_pilihan_murid'=> 'required|in:09:00,16:00,20:00',
            'durasi_jam'       => 'required|integer|min:1|max:8',
        ]);

        $tutor = Tutor::findOrFail($request->id_tutor);
        $total = $tutor->harga_per_jam * $request->durasi_jam;

        Transaksi::create([
            'id_user_murid'     => session('user')['id'],
            'id_tutor'          => $request->id_tutor,
            'total_harga'       => $total,
            'tanggal_les'       => $request->tanggal_les,
            'jam_pilihan_murid' => $request->jam_pilihan_murid,
        ]);

        return redirect()->route('purchase')->with('success', 'Pemesanan berhasil! Cek riwayat pesananmu.');
    }

    public function purchase()
    {
        $userId = session('user')['id'];

        $transaksi = Transaksi::with('tutor.user')
            ->where('id_user_murid', $userId)
            ->orderBy('tanggal_les', 'desc')
            ->get();

        return view('purchase', compact('transaksi'));
    }
}
