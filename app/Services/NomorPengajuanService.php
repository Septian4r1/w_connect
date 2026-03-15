<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Exception;

class NomorPengajuanService
{
    protected static array $allowedPrefix = [
        'PGJ',
        'SUR',
        'ADM',
        'BNT'
    ];

    public static function generate(
        string $prefix,
        string $instansi = 'CSR',
        string $lokasi = 'RW_000'
    ): string {

        if (!in_array($prefix, self::$allowedPrefix)) {
            throw new Exception('Prefix tidak valid');
        }

        return DB::transaction(function () use ($prefix, $instansi, $lokasi) {

            $tahun = now()->format('Y');
            $bulan = self::bulanRomawi(now()->format('n'));

            // 🔥 KEY COUNTER
            $counterKey = "{$prefix}/{$instansi}/{$lokasi}/{$bulan}/{$tahun}";

            $row = DB::table('nomor_pengajuans')
                ->where('prefix', $counterKey)
                ->lockForUpdate()
                ->first();

            if (!$row) {

                $nomor = 1;

                DB::table('nomor_pengajuans')->insert([
                    'prefix' => $counterKey,
                    'tanggal' => now()->format('Ymd'), // hanya metadata
                    'nomor_terakhir' => $nomor,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {

                $nomor = $row->nomor_terakhir + 1;

                DB::table('nomor_pengajuans')
                    ->where('id', $row->id)
                    ->update([
                        'nomor_terakhir' => $nomor,
                        'updated_at' => now()
                    ]);
            }

            $sequence = str_pad($nomor, 6, '0', STR_PAD_LEFT);

            return "{$counterKey}/{$sequence}";
        });
    }

    protected static function bulanRomawi(int $bulan): string
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $map[$bulan] ?? '';
    }
}
