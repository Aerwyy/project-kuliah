<?php

namespace App\Http\Controllers;

use App\Models\JadwalBelajar;
use App\Models\Transaksi;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function home()
    {
        $totalUser    = User::count();
        $totalStudent = User::where('role', 'murid')->count();
        $totalTutor   = User::where('role', 'tutor')->count();

        $transaksiTerbaru = Transaksi::with(['murid', 'tutor.user'])
            ->orderBy('id_transaksi', 'desc')
            ->take(10)
            ->get();

        return view('admin', compact('totalUser', 'totalStudent', 'totalTutor', 'transaksiTerbaru'));
    }

    /* ── USER MANAGEMENT ─────────────────── */

    public function userList()
    {
        $users = User::orderBy('id_user', 'desc')->get();
        return view('admin_user', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'role'        => 'required|in:admin,murid,tutor',
            'foto_profil' => 'nullable|file|max:2048',
            'mata_pelajaran' => 'required_if:role,tutor|in:Matematika,Bahasa Inggris,Bahasa Indonesia,Fisika,Bahasa Latin',
            'harga_per_jam'  => 'required_if:role,tutor|numeric|min:0',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = $file->hashName();
            $file->storeAs('uploads', $filename, 'public');
            $fotoPath = $filename;
        }

        $user = User::create([
            'nama'        => $request->nama,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'foto_profil' => $fotoPath,
        ]);

        if ($request->role === 'tutor') {
            Tutor::create([
                'id_user'       => $user->id_user,
                'mata_pelajaran'=> $request->mata_pelajaran,
                'harga_per_jam' => $request->harga_per_jam ?? 0,
                'jam_tersedia'  => json_encode(['09:00', '16:00', '20:00']),
                'foto_profil'   => $fotoPath,
            ]);
        }

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,'.$id.',id_user',
            'password'    => 'nullable|min:6',
            'foto_profil' => 'nullable|file|max:2048',
        ]);

        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = $file->hashName();
            $file->storeAs('uploads', $filename, 'public');
            $user->foto_profil = $filename;

            if ($user->role === 'tutor') {
                $tutor = Tutor::where('id_user', $id)->first();
                if ($tutor) {
                    $tutor->foto_profil = $filename;
                    $tutor->save();
                }
            }
        }

        $user->save();

        return redirect()->route('admin.user')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // 1. Hapus file foto profil jika ada
        if ($user->foto_profil && Storage::disk('public')->exists('uploads/' . $user->foto_profil)) {
            Storage::disk('public')->delete('uploads/' . $user->foto_profil);
        }

        if ($user->role === 'murid') {
            // 2. Hapus semua transaksi milik murid ini beserta jadwal_belajarnya
            //    (jadwal_belajar akan terhapus otomatis via ON DELETE CASCADE)
            Transaksi::where('id_user_murid', $id)->delete();

        } elseif ($user->role === 'tutor') {
            $tutor = Tutor::where('id_user', $id)->first();
            if ($tutor) {
                // 3. Hapus foto profil tutor jika berbeda
                if ($tutor->foto_profil && $tutor->foto_profil !== $user->foto_profil
                    && Storage::disk('public')->exists('uploads/' . $tutor->foto_profil)) {
                    Storage::disk('public')->delete('uploads/' . $tutor->foto_profil);
                }

                // 4. Hapus semua transaksi yang melibatkan tutor ini beserta jadwal_belajarnya
                //    (jadwal_belajar akan terhapus otomatis via ON DELETE CASCADE)
                Transaksi::where('id_tutor', $tutor->id_tutor)->delete();

                // 5. Hapus entri tutor (juga akan terhapus via ON DELETE CASCADE dari users)
                $tutor->delete();
            }
        }

        // 6. Hapus user — tutors ter-cascade dari DB jika belum dihapus manual
        $user->delete();

        return redirect()->route('admin.user')->with('success', 'User dan semua data terkait berhasil dihapus.');
    }

    /* ── ASSIGNMENT MANAGEMENT ───────────── */

    public function assignmentList()
    {
        $assignments = JadwalBelajar::with('transaksi.murid', 'transaksi.tutor.user')
            ->orderBy('id_jadwal', 'desc')
            ->get();

        $transaksiList = Transaksi::with(['murid', 'tutor.user'])
            ->orderBy('id_transaksi', 'desc')
            ->get();

        return view('admin_assignment', compact('assignments', 'transaksiList'));
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'id_transaksi'    => 'required|exists:transaksi,id_transaksi',
            'judul_pertemuan' => 'required|string|max:100',
        ]);

        JadwalBelajar::create([
            'id_transaksi'    => $request->id_transaksi,
            'judul_pertemuan' => $request->judul_pertemuan,
            'status'          => 'Terjadwal',
        ]);

        return redirect()->route('admin.assignment')->with('success', 'Assignment berhasil ditambahkan.');
    }

    public function deleteAssignment($id)
    {
        JadwalBelajar::findOrFail($id)->delete();
        return redirect()->route('admin.assignment')->with('success', 'Assignment berhasil dihapus.');
    }
}
