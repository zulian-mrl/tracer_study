<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = ['kode_provinsi', 'nama_provinsi', 'kode_kab_kota', 'nama_kab_kota'];

    protected static $cache = null;

    protected static function allCached()
    {
        if (static::$cache === null) {
            static::$cache = static::orderBy('nama_provinsi')
                ->orderBy('nama_kab_kota')
                ->get();
        }
        return static::$cache;
    }

    public static function provinsiList(): array
    {
        $out = [];
        foreach (static::allCached() as $row) {
            if ($row->kode_kab_kota === null) {
                $out[$row->nama_provinsi] = $row->kode_provinsi;
            }
        }
        if ($out === []) {
            $out = config('wilayah.provinsi', []);
        }
        return $out;
    }

    public static function provinsiKode(string $nama): ?string
    {
        $row = static::allCached()->first(fn ($r) => $r->kode_kab_kota === null && $r->nama_provinsi === $nama);
        if ($row) {
            return $row->kode_provinsi;
        }
        return config('wilayah.provinsi')[$nama] ?? null;
    }

    public static function provinsiName(string $kode): ?string
    {
        $row = static::allCached()->first(fn ($r) => $r->kode_kab_kota === null && $r->kode_provinsi === $kode);
        if ($row) {
            return $row->nama_provinsi;
        }
        $nama = config('wilayah.provinsi') ? array_search($kode, config('wilayah.provinsi'), true) : false;
        return $nama ?: null;
    }

    public static function kabKotaKode(string $nama): ?string
    {
        $row = static::allCached()->first(fn ($r) => $r->nama_kab_kota !== null && $r->nama_kab_kota === $nama);
        if ($row) {
            return $row->kode_kab_kota;
        }
        return config('wilayah.kab_kota')[$nama] ?? null;
    }

    public static function kabKotaName(string $kode): ?string
    {
        $row = static::allCached()->first(fn ($r) => $r->kode_kab_kota !== null && $r->kode_kab_kota === $kode);
        if ($row) {
            return $row->nama_kab_kota;
        }
        $nama = config('wilayah.kab_kota') ? array_search($kode, config('wilayah.kab_kota'), true) : false;
        return $nama ?: null;
    }

    public static function kabKotaByProvinsi(string $namaProvinsi): array
    {
        $out = [];
        foreach (static::allCached() as $row) {
            if ($row->nama_provinsi === $namaProvinsi && $row->nama_kab_kota !== null) {
                $out[$row->nama_kab_kota] = $row->kode_kab_kota;
            }
        }
        if ($out === [] && isset(config('wilayah.provinsi_list')[$namaProvinsi])) {
            foreach (config('wilayah.provinsi_list')[$namaProvinsi] as $nama) {
                $out[$nama] = config('wilayah.kab_kota')[$nama] ?? $nama;
            }
        }
        return $out;
    }

    public static function provinsiKabKotaList(): array
    {
        $out = [];
        foreach (static::allCached() as $row) {
            if (!isset($out[$row->nama_provinsi])) {
                $out[$row->nama_provinsi] = [];
            }
            if ($row->nama_kab_kota !== null) {
                $out[$row->nama_provinsi][] = $row->nama_kab_kota;
            }
        }
        if ($out === []) {
            return config('wilayah.provinsi_list', []);
        }
        return $out;
    }
}
