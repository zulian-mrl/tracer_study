<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_id',
        'actor_nama',
        'target_id',
        'target_nama',
        'jenis',
        'keterangan',
        'device',
        'ip_address',
    ];

    public static function catat(string $jenis, ?User $actor = null, ?User $target = null, ?string $keterangan = null): void
    {
        self::create([
            'jenis' => $jenis,
            'actor_id' => $actor?->id,
            'actor_nama' => $actor?->name,
            'target_id' => $target?->id,
            'target_nama' => $target?->name,
            'keterangan' => $keterangan,
            'device' => request()->userAgent(),
            'ip_address' => request()->ip(),
        ]);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
