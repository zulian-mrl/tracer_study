<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\MasterAlumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterAlumniController extends Controller
{
    private function cekSuper(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_super, 403);
    }

    // Kembali ke daftar dengan filter/pagination yang sedang aktif (parameter kosong dibuang)
    private function kembaliKeDaftar(Request $request): array
    {
        return array_filter([
            'q' => $request->input('q'),
            'tahun_lulus' => $request->input('filter_tahun'),
            'kode_prodi' => $request->input('filter_prodi'),
            'page' => $request->input('page'),
        ], fn ($nilai) => $nilai !== null && $nilai !== '');
    }

    public function index(Request $request)
    {
        $this->cekSuper();

        $cari = trim((string) $request->input('q'));
        $tahunTerpilih = trim((string) $request->input('tahun_lulus'));
        $prodiTerpilih = trim((string) $request->input('kode_prodi'));

        $alumni = MasterAlumni::query()
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($sub) use ($cari) {
                    $sub->where('no_mahasiswa', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%")
                        ->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('kode_prodi', 'like', "%{$cari}%")
                        ->orWhere('tahun_lulus', 'like', "%{$cari}%");
                });
            })
            ->when($tahunTerpilih !== '', function ($query) use ($tahunTerpilih) {
                $query->where('tahun_lulus', $tahunTerpilih);
            })
            ->when($prodiTerpilih !== '', function ($query) use ($prodiTerpilih) {
                $query->where('kode_prodi', $prodiTerpilih);
            })
            ->orderByDesc('tahun_lulus')
            ->orderBy('kode_prodi')
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        $listTahun = MasterAlumni::query()
            ->select('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        $listProdi = MasterAlumni::query()
            ->select('kode_prodi')
            ->distinct()
            ->orderBy('kode_prodi')
            ->pluck('kode_prodi');

        return view('master_alumni', [
            'alumni' => $alumni,
            'cari' => $cari,
            'listTahun' => $listTahun,
            'tahunTerpilih' => $tahunTerpilih,
            'listProdi' => $listProdi,
            'prodiTerpilih' => $prodiTerpilih,
        ]);
    }

    public function update(Request $request, MasterAlumni $master_alumni)
    {
        $this->cekSuper();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:60'],
            'nik' => ['required', 'string', 'digits:16', 'unique:master_alumnis,nik,'.$master_alumni->no_mahasiswa.',no_mahasiswa'],
            'kode_prodi' => ['nullable', 'string', 'max:6'],
            'tahun_lulus' => ['required', 'string', 'max:4'],
        ], [
            'nama.required' => 'Nama alumni wajib diisi.',
            'nama.max' => 'Nama alumni maksimal 60 karakter.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK tersebut sudah dipakai alumni lain.',
            'kode_prodi.max' => 'Kode prodi maksimal 6 karakter.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'tahun_lulus.max' => 'Tahun lulus maksimal 4 karakter.',
        ]);

        $lama = "NIM {$master_alumni->no_mahasiswa} ({$master_alumni->nama})";

        $master_alumni->update($data);

        AuditLog::catat(
            'edit_master_alumni',
            Auth::user(),
            null,
            "Mengubah data master {$lama} menjadi nama {$data['nama']}, NIK {$data['nik']}, prodi {$data['kode_prodi']}, tahun {$data['tahun_lulus']}"
        );

        return redirect()->route('master.index', $this->kembaliKeDaftar($request))
            ->with('success', "Data master {$master_alumni->no_mahasiswa} berhasil diperbarui.");
    }

    public function destroy(Request $request, MasterAlumni $master_alumni)
    {
        $this->cekSuper();

        $info = "NIM {$master_alumni->no_mahasiswa} ({$master_alumni->nama})";

        $master_alumni->delete();

        AuditLog::catat('hapus_master_alumni', Auth::user(), null, "Menghapus data master {$info}");

        return redirect()->route('master.index', $this->kembaliKeDaftar($request))
            ->with('success', "Data master {$info} berhasil dihapus.");
    }
}
