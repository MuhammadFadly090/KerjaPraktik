<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Http\Controllers\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $absensi = Absen::with('karyawan')
            ->when($request->filled('tanggal_dari') && $request->filled('tanggal_sampai'), function ($query) use ($request) {
                $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
            })
            ->when($request->filled('nama_karyawan'), function ($query) use ($request) {
                $query->whereHas('karyawan', function ($subQuery) use ($request) {
                    $subQuery->where('nama', 'like', '%' . $request->nama_karyawan . '%');
                });
            })
            ->orderBy('tanggal', 'desc')
            ->join('karyawan', 'absen.id_karyawan', '=', 'karyawan.id_karyawan')
            ->orderBy('karyawan.nama', 'asc')
            ->get();

        return view('laporan', ['absensi' => $absensi]);
    }

    public function filter(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_dari' => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
        ]);
    
        $tanggalDari = $request->tanggal_dari;
        $tanggalSampai = $request->tanggal_sampai;
    
        // Ambil nama karyawan dari request
        $namaKaryawan = $request->nama_karyawan;
    
        // Filter data absensi berdasarkan rentang tanggal dan nama karyawan
        $absensi = Absen::with('karyawan')
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->when($namaKaryawan, function ($query) use ($namaKaryawan) {
                $query->whereHas('karyawan', function ($subQuery) use ($namaKaryawan) {
                    $subQuery->where('nama', 'like', '%' . $namaKaryawan . '%');
                });
            })
            ->get();
        
        // Mengirim data absen yang sudah difilter ke view laporan
        return view('laporan', ['absensi' => $absensi]);
    }
}